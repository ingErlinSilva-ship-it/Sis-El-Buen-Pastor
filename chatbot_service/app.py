from flask import Flask, request, jsonify
from flask_cors import CORS
from google import genai
import unicodedata
import mysql.connector
from mysql.connector import Error
import os
from datetime import datetime, timedelta
import bcrypt


# ==============================
# 1. CONFIGURACIÓN INICIAL
# ==============================

app = Flask(__name__)
CORS(app)

# 🔐 API KEY (luego pásala a variable de entorno)
client = genai.Client(api_key="AIzaSyAvW4AjKXrPBgKYYUED7sKwQQWHqIJqTzQ")

MODEL_NAME = "gemini-2.5-flash"

# ==============================
# 2. BASE DE DATOS
# ==============================

db_config = {
    'host': os.getenv('DB_HOST'),
    'user': os.getenv('DB_USER'),
    'password': os.getenv('DB_PASSWORD'),
    'database': os.getenv('DB_NAME'),
    'port': 3306
}

INFO_CLINICA = {
    "horarios": "Lunes a Viernes (01:30 PM - 06:00 PM) y Domingos (08:00 AM - 12:00 PM)",
    "ubicacion": "Del Laboratorio Divino Niño, 1/2 cuadra al Oeste, Diriá-Granada, Nicaragua.",
    "servicios": "• Electrocardiogramas\n• Consultas Médicas\n• Holter"
}

# ==============================
# 3. SESIONES (Memoria por IP)
# ==============================

sesiones = {}

# ==============================
# 4. FUNCIONES AUXILIARES
# ==============================

def normalizar(texto):
    texto = texto.lower()
    return ''.join(c for c in unicodedata.normalize('NFD', texto)
        if unicodedata.category(c) != 'Mn')

def calcular_edad(fecha_nacimiento):
    hoy = datetime.now().date()
    edad = hoy.year - fecha_nacimiento.year - (
        (hoy.month, hoy.day) < (fecha_nacimiento.month, fecha_nacimiento.day)
    )
    return edad


def normalizar_hora_usuario(hora_texto, fecha):
    """
    Convierte cualquier formato ingresado por el usuario
    a formato 24h HH:MM respetando reglas del día.
    """

    hora_texto = hora_texto.strip().lower().replace(".", "")

    try:
        # 1️⃣ Intentar formatos con AM/PM
        formatos_am_pm = [
            "%I:%M %p",
            "%I %p",
            "%I:%M%p",
            "%I%p"
        ]

        for formato in formatos_am_pm:
            try:
                hora_dt = datetime.strptime(hora_texto, formato)
                return hora_dt.strftime("%H:%M")
            except:
                continue

        # 2️⃣ Formato con :
        if ":" in hora_texto:
            hora_dt = datetime.strptime(hora_texto, "%H:%M")
            hora_24 = hora_dt.strftime("%H:%M")

            hora_int = int(hora_24.split(":")[0])

            # 🔹 Si es lunes-viernes (tarde)
            if fecha.weekday() != 6:
                if 1 <= hora_int <= 6:
                    hora_int += 12
                    return f"{hora_int:02d}:{hora_24.split(':')[1]}"

            return hora_24

        # 3️⃣ Solo número (ej: 5)
        if hora_texto.isdigit():
            hora_int = int(hora_texto)

            if fecha.weekday() == 6:
                # Domingo mañana
                return f"{hora_int:02d}:00"
            else:
                # Lunes a viernes tarde
                if 1 <= hora_int <= 6:
                    hora_int += 12
                return f"{hora_int:02d}:00"

        return None

    except:
        return None


def formatear_hora_12h(hora_24):
    """
    Convierte HH:MM a formato 12 horas bonito
    Ej: 14:00 -> 2:00 pm
    """
    hora_dt = datetime.strptime(hora_24, "%H:%M")
    return hora_dt.strftime("%I:%M %p").lstrip("0").lower()


# ==============================
# 5. FUNCIONES BASE DE DATOS
# ==============================
def consultar_paciente_por_cedula(cedula: str):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)

        query = """
            SELECT 
                p.id as paciente_id, 
                u.nombre, 
                u.apellido,
                p.fecha_nacimiento
            FROM pacientes p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.cedula = %s OR p.tutor_cedula = %s
        """

        cursor.execute(query, (cedula, cedula))
        resultados = cursor.fetchall()
        connection.close()

        if not resultados:
            return "No encontrado"

        return resultados

    except Error as e:
        print("Error BD:", e)
        return "Error"



def obtener_especialidades_con_medicos():
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)

        cursor.execute("""
            SELECT DISTINCT e.nombre
            FROM especialidades e
            INNER JOIN medicos m ON e.id = m.especialidad_id
        """)

        resultados = cursor.fetchall()
        connection.close()

        return [row['nombre'] for row in resultados]

    except Error as e:
        print("Error BD:", e)
        return []


def consultar_medico_por_especialidad(nombre_especialidad):
    """Obtiene el médico (nombre e ID) según especialidad"""
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)

        query = """
            SELECT m.id as medico_id, u.nombre, u.apellido
            FROM medicos m
            INNER JOIN usuarios u ON m.usuario_id = u.id
            INNER JOIN especialidades e ON m.especialidad_id = e.id
            WHERE e.nombre = %s
        """

        cursor.execute(query, (nombre_especialidad,))
        resultado = cursor.fetchone()

        if not resultado:
            return None

        return {
            "medico_id": resultado["medico_id"],
            "nombre_completo": f"Dr. {resultado['nombre']} {resultado['apellido']}"
        }

    except Error as e:
        print("Error BD:", e)
        return None
    finally:
        if connection.is_connected():
            connection.close()


def guardar_cita(paciente_id, medico_id, fecha, hora, motivo):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()

        query = """
            INSERT INTO citas 
            (paciente_id, medico_id, fecha, hora, duracion_minutos, motivo, estado, origen, created_at, updated_at)
            VALUES (%s, %s, %s, %s, 30, %s, 'confirmada', 'chatbot', NOW(), NOW())
        """

        cursor.execute(query, (paciente_id, medico_id, fecha, hora, motivo))
        connection.commit()
        connection.close()

        return True

    except Error as e:
        print("Error al guardar cita:", e)
        return False

def validar_fecha(fecha_str):
    formatos = [
        "%Y-%m-%d",  # 2026-02-14
        "%d/%m/%Y",  # 14/02/2026
        "%Y/%m/%d",  # 2026/02/14
        "%d-%m-%Y"   # 14-02-2026
    ]

    fecha = None

    for formato in formatos:
        try:
            fecha = datetime.strptime(fecha_str, formato).date()
            break
        except:
            continue

    if not fecha:
        return False, "❌ Formato inválido. Puedes usar por ejemplo 14/02/2026."

    hoy = datetime.now().date()

    # 🔹 Validar fecha pasada
    if fecha < hoy:
        return False, "❌ No puedes agendar en una fecha pasada."

    # 🔹 Validar sábado
    if fecha.weekday() == 5:
        return False, "❌ El día Seleccionado cae Sábado y NO Atendemos ese Día. Elige Otra Fecha 😊"


    return True, fecha


def generar_horarios_disponibles(medico_id, fecha):
    horarios = []

    # Domingo
    if fecha.weekday() == 6:
        inicio = datetime.combine(fecha, datetime.strptime("08:00", "%H:%M").time())
        fin = datetime.combine(fecha, datetime.strptime("12:00", "%H:%M").time())
    else:
        # Lunes a Viernes
        inicio = datetime.combine(fecha, datetime.strptime("13:30", "%H:%M").time())
        fin = datetime.combine(fecha, datetime.strptime("18:00", "%H:%M").time())

    while inicio < fin:
        horarios.append(inicio.strftime("%H:%M"))
        inicio += timedelta(minutes=30)

    # Obtener horas ocupadas
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()
    cursor.execute(
        "SELECT hora FROM citas WHERE medico_id = %s AND fecha = %s AND estado != 'cancelada'",
        (medico_id, fecha)
    )
    ocupadas = [str(row[0])[:5] for row in cursor.fetchall()]
    connection.close()

    disponibles = [h for h in horarios if h not in ocupadas]

    return disponibles

def crear_usuario(nombre, apellido, email, celular):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()

        if not celular.isdigit() or len(celular) != 8:
            return None

        salt = bcrypt.gensalt(rounds=12)
        hash_bytes = bcrypt.hashpw(celular.encode('utf-8'), salt)

        password_hash = hash_bytes.decode('utf-8')

        # 🔥 Forzar compatibilidad Laravel
        password_hash = password_hash.replace("$2b$", "$2y$")

        query = """
            INSERT INTO usuarios 
            (nombre, apellido, celular, email, password, estado, rol_id, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, 1, 3, NOW(), NOW())
        """

        cursor.execute(query, (nombre, apellido, celular, email, password_hash))
        connection.commit()

        usuario_id = cursor.lastrowid
        connection.close()

        return usuario_id

    except Error as e:
        print("Error creando usuario:", e)
        return None

def crear_paciente(usuario_id):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()

        query = """
            INSERT INTO pacientes
            (usuario_id, created_at, updated_at)
            VALUES (%s, NOW(), NOW())
        """

        cursor.execute(query, (usuario_id,))
        connection.commit()

        paciente_id = cursor.lastrowid
        connection.close()

        return paciente_id

    except Error as e:
        print("Error creando paciente:", e)
        return None

def email_existe(email):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()

        cursor.execute("SELECT id FROM usuarios WHERE email = %s", (email,))
        resultado = cursor.fetchone()
        connection.close()

        return resultado is not None

    except Error as e:
        print("Error verificando email:", e)
        return True


# ==============================
# 6. FUNCIÓN GEMINI (Solo redacción)
# ==============================

instrucciones_asistente = """
Eres el asistente virtual de la Clínica El Buen Pastor.
Responde de forma profesional, amable y clara.
Usa emojis cuando sea apropiado.
"""

def generar_respuesta(mensaje_usuario):
    try:
        response = client.models.generate_content(
            model=MODEL_NAME,
            contents=[
                {
                    "role": "user",
                    "parts": [
                        {"text": instrucciones_asistente + "\n\nUsuario: " + mensaje_usuario}
                    ]
                }
            ]
        )
        return response.text

    except Exception as e:
        print("Error Gemini:", e)
        return "Lo siento, ocurrió un problema al procesar tu solicitud."

# ==============================
# 7. RUTA DEL CHAT 
# ==============================
@app.route('/chat', methods=['POST'])
def chat():

    data = request.get_json()

    print("JSON DATA:", data)

    if not data:
        return jsonify({"respuesta": "Error recibiendo datos."})

    session_id = data.get("session_id")
    mensaje = data.get("mensaje", "").strip()
    mensaje_normalizado = normalizar(mensaje)

    if not session_id:
        return jsonify({"respuesta": "Error de sesión."})

    if session_id not in sesiones:
        sesiones[session_id] = {
            "estado": "inicio",
            "paciente_id": None,
            "especialidad": None,
            "medico_id": None,
            "fecha": None
        }

    estado = sesiones[session_id]["estado"]

    # ===== ESTADO INICIO =====
    if estado == "inicio":

        if "cita" in mensaje_normalizado or "agendar" in mensaje_normalizado:
            sesiones[session_id]["estado"] = "esperando_cedula"
            return jsonify({"respuesta": "🩺 Para comenzar, indícame tu número de cédula 😊"})

        if "horario" in mensaje_normalizado:
            return jsonify({"respuesta": f"🕒 Nuestros horarios son:\n{INFO_CLINICA['horarios']}"})

        if "ubicacion" in mensaje_normalizado or "direccion" in mensaje_normalizado:
            return jsonify({"respuesta": f"📍 Nuestra ubicación:\n{INFO_CLINICA['ubicacion']}"})

        if "servicio" in mensaje_normalizado:
            return jsonify({"respuesta": f"🩺 Servicios disponibles:\n{INFO_CLINICA['servicios']}"})

        # 🔒 Respuesta restringida
        return jsonify({
            "respuesta":
            "🤖 Soy el asistente virtual de la Clínica El Buen Pastor.\n\n"
            "Puedo ayudarte con:\n"
            "• Agendar cita\n"
            "• Consultar horarios\n"
            "• Ver ubicación\n"
            "• Ver servicios\n\n"
            "Por favor escribe una de esas opciones 😊"
        })


    # ===== ESPERANDO CÉDULA =====
    if estado == "esperando_cedula":

        resultado = consultar_paciente_por_cedula(mensaje)

        if isinstance(resultado, str):

            sesiones[session_id]["estado"] = "preguntar_preregistro"

            return jsonify({
                "respuesta": "❌ No encontramos un paciente con esa cédula.\n\n"
                            "¿Deseas realizar un preregistro para agendar tu cita? (Si / No)"
            })


        # 🔹 SI SOLO HAY UN PACIENTE (flujo normal actual)
        if len(resultado) == 1:

            paciente = resultado[0]

            sesiones[session_id]["paciente_id"] = paciente["paciente_id"]
            sesiones[session_id]["nombre_paciente"] = f"{paciente['nombre']} {paciente['apellido']}"
            sesiones[session_id]["estado"] = "esperando_especialidad"

            especialidades = obtener_especialidades_con_medicos()
            lista = "\n• ".join(especialidades)

            return jsonify({
                "respuesta": f"😊 Hola {paciente['nombre']} {paciente['apellido']}.\n\n"
                            f"🩺 Estas son nuestras especialidades disponibles:\n"
                            f"• {lista}\n\n"
                            f"Por favor escribe una exactamente como aparece."
            })

        # 🔹 SI HAY VARIOS PACIENTES (menores del tutor)
        sesiones[session_id]["lista_pacientes"] = resultado
        sesiones[session_id]["estado"] = "seleccionando_paciente"

        mensaje_respuesta = "👶 Encontré los siguientes pacientes asociados a esta cédula:\n\n"

        for i, paciente in enumerate(resultado, start=1):
            edad = calcular_edad(paciente["fecha_nacimiento"])
            mensaje_respuesta += f"{i}️⃣ {paciente['nombre']} {paciente['apellido']} ({edad} años)\n"

        mensaje_respuesta += "\nEscribe el número del paciente que deseas agendar."

        return jsonify({"respuesta": mensaje_respuesta})

    # ===== SELECCIONANDO PACIENTE =====
    if estado == "seleccionando_paciente":

        if not mensaje.isdigit():
            return jsonify({"respuesta": "❌ Escribe el número del paciente que deseas seleccionar."})

        indice = int(mensaje) - 1
        lista = sesiones[session_id]["lista_pacientes"]

        if indice < 0 or indice >= len(lista):
            return jsonify({"respuesta": "❌ Número inválido. Intenta nuevamente."})

        paciente = lista[indice]

        sesiones[session_id]["paciente_id"] = paciente["paciente_id"]
        sesiones[session_id]["nombre_paciente"] = f"{paciente['nombre']} {paciente['apellido']}"
        sesiones[session_id]["estado"] = "esperando_especialidad"

        especialidades = obtener_especialidades_con_medicos()
        lista_especialidades = "\n• ".join(especialidades)

        return jsonify({
            "respuesta": f"😊 Perfecto, agendaremos para {paciente['nombre']} {paciente['apellido']}.\n\n"
                f"🩺 Estas son nuestras especialidades disponibles:\n"
                f"• {lista_especialidades}\n\n"
                f"Por favor escribe una exactamente como aparece."
        })

    # ===== PREGUNTAR PREREGISTRO =====
    if estado == "preguntar_preregistro":

        if mensaje_normalizado in ["si", "sí", "s"]:
            sesiones[session_id]["estado"] = "preregistro_nombre"
            return jsonify({"respuesta": "📝 Perfecto. Indícame tu nombre."})

        if mensaje_normalizado in ["no", "n"]:
            sesiones[session_id]["estado"] = "inicio"
            return jsonify({"respuesta": "😊 Está bien. Si cambias de opinión, escribe 'Agendar cita'."})

        return jsonify({"respuesta": "Por favor responde Si o No 😊"})


    # ===== PREREGISTRO NOMBRE =====
    if estado == "preregistro_nombre":

        sesiones[session_id]["preregistro_nombre"] = mensaje
        sesiones[session_id]["estado"] = "preregistro_apellido"

        return jsonify({"respuesta": "Indícame tu apellido."})


    # ===== PREREGISTRO APELLIDO =====
    if estado == "preregistro_apellido":

        sesiones[session_id]["preregistro_apellido"] = mensaje
        sesiones[session_id]["estado"] = "preregistro_email"

        return jsonify({"respuesta": "Indícame tu correo electrónico."})


    # ===== PREREGISTRO EMAIL =====
    if estado == "preregistro_email":

        if email_existe(mensaje):
            return jsonify({
                "respuesta": "❌ Este correo ya está registrado.\n"
                             "Intenta con otro correo o escribe uno diferente."
            })

        sesiones[session_id]["preregistro_email"] = mensaje
        sesiones[session_id]["estado"] = "preregistro_celular"

        return jsonify({"respuesta": "Indícame tu número de celular (8 dígitos)."})


    # ===== PREREGISTRO CELULAR =====
    if estado == "preregistro_celular":

        celular = mensaje.strip()

        # Validar que tenga exactamente 8 dígitos numéricos
        if not celular.isdigit() or len(celular) != 8:
            return jsonify({
                "respuesta": "❌ El número de celular debe tener exactamente 8 dígitos numéricos."
            })

        sesiones[session_id]["preregistro_celular"] = celular

        usuario_id = crear_usuario(
            sesiones[session_id]["preregistro_nombre"],
            sesiones[session_id]["preregistro_apellido"],
            sesiones[session_id]["preregistro_email"],
            celular
        )

        if not usuario_id:
            return jsonify({"respuesta": "❌ Ocurrió un error creando el usuario."})

        paciente_id = crear_paciente(usuario_id)

        if not paciente_id:
            return jsonify({"respuesta": "❌ Ocurrió un error creando el paciente."})

        # 🔐 MARCAR COMO USUARIO NUEVO (ANTES DEL RETURN)
        sesiones[session_id]["usuario_nuevo"] = True
        sesiones[session_id]["email_registrado"] = sesiones[session_id]["preregistro_email"]
        sesiones[session_id]["celular_registrado"] = celular

        sesiones[session_id]["paciente_id"] = paciente_id
        sesiones[session_id]["nombre_paciente"] = f"{sesiones[session_id]['preregistro_nombre']} {sesiones[session_id]['preregistro_apellido']}"
        sesiones[session_id]["estado"] = "esperando_especialidad"

        especialidades = obtener_especialidades_con_medicos()
        lista = "\n• ".join(especialidades)

        return jsonify({
            "respuesta": f"✅ Registro exitoso.\n\n"
                        f"🩺 Estas son nuestras especialidades disponibles:\n"
                        f"• {lista}\n\n"
                        f"Por favor escribe una exactamente como aparece."
        })


    # ===== ESPERANDO ESPECIALIDAD =====
    if estado == "esperando_especialidad":

        especialidades = obtener_especialidades_con_medicos()

        if mensaje not in especialidades:
            lista = "\n• ".join(especialidades)
            return jsonify({
                "respuesta": f"❌ Especialidad no válida.\n\n"
                            f"Disponibles:\n• {lista}\n\n"
                            f"Escribe una exactamente como aparece."
            })

        sesiones[session_id]["especialidad"] = mensaje

        medico = consultar_medico_por_especialidad(mensaje)

        if not medico:
            return jsonify({"respuesta": "❌ No hay médico disponible para esa especialidad."})

        sesiones[session_id]["medico_id"] = medico["medico_id"]
        sesiones[session_id]["nombre_medico"] = medico["nombre_completo"]
        sesiones[session_id]["estado"] = "esperando_fecha"

        return jsonify({
            "respuesta": f"👨‍⚕️ Médico disponible:\n{medico['nombre_completo']}\n\n"
                        f"📅 Indícame la fecha deseada en formato Dia-Mes-Año.\n"
                        f"(No Atendemos Sábados)"
        })


    # ===== ESPERANDO FECHA =====
    if estado == "esperando_fecha":

        valido, resultado = validar_fecha(mensaje)

        if not valido:
            return jsonify({"respuesta": resultado})

        fecha = resultado
        sesiones[session_id]["fecha"] = fecha

        horarios = generar_horarios_disponibles(
            sesiones[session_id]["medico_id"],
            fecha
        )

        if not horarios:
            return jsonify({"respuesta": "❌ No hay horarios disponibles para esa fecha."})
        
        sesiones[session_id]["horarios_disponibles"] = horarios
        sesiones[session_id]["estado"] = "esperando_hora"

        lista = "\n".join([f"• {formatear_hora_12h(h)}" for h in horarios])


        return jsonify({
            "respuesta": f"🕒 Horarios disponibles:\n{lista}\n\nEscribe la hora exacta que deseas."
        })
    
    # ===== ESPERANDO HORA =====
    if estado == "esperando_hora":

        hora_normalizada = normalizar_hora_usuario(
            mensaje,
            sesiones[session_id]["fecha"]
        )


        if not hora_normalizada:
            return jsonify({"respuesta": "❌ Formato de hora inválido."})

        if hora_normalizada not in sesiones[session_id]["horarios_disponibles"]:
            return jsonify({"respuesta": "❌ Hora no disponible. Elige una de la lista."})

        sesiones[session_id]["hora"] = hora_normalizada
        sesiones[session_id]["estado"] = "esperando_motivo"

        return jsonify({"respuesta": "📝 Indícame el motivo de la cita."})

    # ===== ESPERANDO MOTIVO =====
    if estado == "esperando_motivo":

        sesiones[session_id]["motivo"] = mensaje
        sesiones[session_id]["estado"] = "confirmacion"

        fecha_obj = sesiones[session_id]["fecha"]
        fecha_formateada = fecha_obj.strftime("%A %d/%m/%Y")


        dias = {
            "Monday": "Lunes",
            "Tuesday": "Martes",
            "Wednesday": "Miércoles",
            "Thursday": "Jueves",
            "Friday": "Viernes",
            "Saturday": "Sábado",
            "Sunday": "Domingo"
        }

        for eng, esp in dias.items():
            fecha_formateada = fecha_formateada.replace(eng, esp)

        hora_formateada = formatear_hora_12h(sesiones[session_id]["hora"])

        resumen = (
            "📋 **RESUMEN DE TU CITA**\n\n"
            f"**Paciente:** {sesiones[session_id]['nombre_paciente']}\n"
            f"**Doctor:** {sesiones[session_id]['nombre_medico']}\n"
            f"**Fecha:** {fecha_formateada}\n"
            f"**Hora:** {hora_formateada}\n"
            f"**Motivo:** {sesiones[session_id]['motivo']}\n\n"
            "⏰ Por favor presentarte 5 minutos antes de tu cita.\n\n"
            "Escribe **CONFIRMAR** para finalizar."
        )

        return jsonify({"respuesta": resumen})

    # ===== CONFIRMACION =====
    if estado == "confirmacion":

        if mensaje_normalizado != "confirmar":
            return jsonify({
                "respuesta": "Por favor escribe **CONFIRMAR** para finalizar la cita 😊"
            })


        guardar_cita(
            sesiones[session_id]["paciente_id"],
            sesiones[session_id]["medico_id"],
            sesiones[session_id]["fecha"],
            sesiones[session_id]["hora"],
            sesiones[session_id]["motivo"]
        )

        sesiones[session_id]["estado"] = "post_confirmacion"

        mensaje_final = "✅ Tu cita fue guardada exitosamente en el sistema.\n\n"

        # 🔐 Si es usuario nuevo
        if sesiones[session_id].get("usuario_nuevo"):

            mensaje_final += (
                "🔐 **Tu cuenta ha sido creada exitosamente en nuestro sistema.**\n\n"
                f"**Usuario:** {sesiones[session_id]['email_registrado']}\n\n"
                "Se ha generado una contraseña temporal basada en el número de celular registrado.\n"
                "Por seguridad, te recomendamos cambiar tu contraseña al ingresar al sistema.\n\n"
                "Puedes acceder desde la plataforma web de la Clínica El Buen Pastor.\n\n"
            )

            sesiones[session_id]["usuario_nuevo"] = False

        mensaje_final += "¿Puedo ayudarte en algo más? (Si / No)"

        return jsonify({"respuesta": mensaje_final})



    # ===== POST CONFIRMACION =====
    if estado == "post_confirmacion":

        if mensaje_normalizado in ["no", "n"]:
            sesiones[session_id]["estado"] = "inicio"
            return jsonify({
                "respuesta": "😊 Gracias por confiar en la Clínica El Buen Pastor.\n¡Que tengas un excelente día!"
            })

        if mensaje_normalizado in ["si", "sí", "s"]:
            sesiones[session_id]["estado"] = "inicio"
            return jsonify({
                "respuesta": "🩺 ¿Qué deseas hacer?\n\n• Agendar cita\n• Consultar horarios\n• Ver servicios"
            })

        return jsonify({
            "respuesta": "Por favor responde Si o No 😊"
        })
    # ===== RESPUESTA DE SEGURIDAD =====
    return jsonify({
        "respuesta": "⚠️ Ocurrió un problema inesperado. Volvamos al inicio.\n\nEscribe 'Agendar cita' para comenzar."
    })
# ==============================
# 8. RUN
# ==============================

if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port)
