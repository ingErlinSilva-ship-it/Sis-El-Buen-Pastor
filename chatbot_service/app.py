from flask import Flask, request, jsonify
from flask_cors import CORS
import unicodedata
import mysql.connector 
from mysql.connector import Error

app = Flask(__name__)
CORS(app)
# --- CONECCION CON LA BASE DE DATOS ---
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'clinicadb'
}

INFO_CLINICA = {
    "horarios": "Lunes a Viernes (01:30 PM - 06:00 PM) y Domingos (08:00 AM - 12:00 PM)",
    "ubicacion": "Del Laboratorio Divino Niño, media cuadra al Oeste, Diriá-Granada, Nicaragua.",
    "servicios": "• Electrocardiogramas\n• Consultas Médicas\n• Holter"
}

sesion_cita = {
    "paso": None,
    "paciente_id": None,
    "paciente_nombre": None,
    "lista_hijos": None,
    "medico": None,
    "fecha_final": None,
    "hora_final": None,
    "motivo": None
}

def normalizar(texto):
    texto = texto.lower()
    return ''.join(c for c in unicodedata.normalize('NFD', texto) if unicodedata.category(c) != 'Mn')

# --- FUNCIONES DE BASE DE DATOS ---

def consultar_paciente_por_cedula(cedula):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)
        query = """
            SELECT p.id as paciente_id, u.nombre, u.apellido 
            FROM pacientes p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.cedula = %s OR p.tutor_cedula = %s
        """
        cursor.execute(query, (cedula, cedula))
        return cursor.fetchall()
    except Error as e:
        print(f"Error: {e}")
        return None
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()

def obtener_especialidades_con_medicos():
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)
        # Filtramos especialidades que tengan al menos un médico vinculado
        query = "SELECT DISTINCT e.nombre FROM especialidades e INNER JOIN medicos m ON e.id = m.especialidad_id"
        cursor.execute(query)
        return [row['nombre'] for row in cursor.fetchall()]
    except: return []
    finally: connection.close()

def consultar_medicos_por_especialidad(nombre_esp):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)
        query = """
            SELECT u.nombre, u.apellido 
            FROM medicos m
            INNER JOIN usuarios u ON m.usuario_id = u.id
            INNER JOIN especialidades e ON m.especialidad_id = e.id
            WHERE e.nombre = %s
        """
        cursor.execute(query, (nombre_esp,))
        return [f"Dr. {row['nombre']} {row['apellido']}" for row in cursor.fetchall()]
    except:
        return []
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()

# --- FUNCIÓN DE GUARDADO FINAL ---
def guardar_cita(paciente_id, medico_id, fecha, hora, motivo):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()
        # Insertamos usando los campos exactos de tu migración
        query = """
            INSERT INTO citas 
            (paciente_id, medico_id, fecha, hora, duracion_minutos, motivo, estado, origen, created_at, updated_at)
            VALUES (%s, %s, %s, %s, 30, %s, 'confirmada', 'chatbot', NOW(), NOW())
        """
        cursor.execute(query, (paciente_id, medico_id, fecha, hora, motivo))
        connection.commit()
        return True
    except Error as e:
        print(f"Error MySQL: {e}")
        return False
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def obtener_horas_ocupadas(medico_nombre, fecha):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)
        query = """
            SELECT hora FROM citas c 
            INNER JOIN medicos m ON c.medico_id = m.id 
            INNER JOIN usuarios u ON m.usuario_id = u.id
            WHERE CONCAT('Dr. ', u.nombre, ' ', u.apellido) = %s AND c.fecha = %s
        """
        cursor.execute(query, (medico_nombre, fecha))
        return [str(row['hora'])[:5] for row in cursor.fetchall()] # Formato HH:MM
    except: return []
    finally: connection.close()         

# --- RUTA PRINCIPAL ---
@app.route('/chat', methods=['POST'])
def chat():
    global sesion_cita
    try:
        data = request.json
        msg_orig = data.get('mensaje', '').strip()
        msg_norm = normalizar(msg_orig)
        nums = ''.join(filter(str.isdigit, msg_orig))

        # --- FLUJO INICIAL ---
        if "agendar" in msg_norm or "cita" in msg_norm:
            sesion_cita.clear() # Limpieza total para empezar de cero
            return jsonify({"respuesta": "¿Eres paciente ya registrado o nuevo?", "tipo": "opciones_cita"})

        if "ya soy paciente" in msg_norm:
            sesion_cita["paso"] = "esperando_cedula"
            return jsonify({"respuesta": "Perfecto, escribe tu número de cédula con guiones."})

        # --- VALIDACIÓN DE CÉDULA (Solo si el paso es correcto y trae números) ---
        if sesion_cita.get("paso") == "esperando_cedula" and len(nums) >= 8:
            pacientes = consultar_paciente_por_cedula(msg_orig)
            if pacientes:
                if len(pacientes) == 1:
                    sesion_cita.update({
                        "paciente_id": pacientes[0]['paciente_id'],
                        "paciente_nombre": f"{pacientes[0]['nombre']} {pacientes[0]['apellido']}",
                        "paso": "esperando_especialidad"
                    })
                    return jsonify({
                        "respuesta": f"¡Hola {pacientes[0]['nombre']}! ¿En qué especialidad agendamos?",
                        "tipo": "seleccionar_especialidad",
                        "especialidades": obtener_especialidades_con_medicos()
                    })
                else:
                    sesion_cita.update({"lista_hijos": pacientes, "paso": "seleccionando_hijo"})
                    return jsonify({
                        "respuesta": "Encontré varios menores. ¿Para quién es la cita?",
                        "tipo": "seleccionar_hijo",
                        "hijos": [{"nombre": p['nombre'] + " " + p['apellido']} for p in pacientes]
                    })
            return jsonify({"respuesta": "No encontré esa cédula. Intenta de nuevo."})

        # --- SELECCIÓN DE HIJO ---
        if sesion_cita.get("paso") == "seleccionando_hijo":
            for h in sesion_cita.get("lista_hijos", []):
                if normalizar(h['nombre']) in msg_norm:
                    sesion_cita.update({
                        "paciente_id": h['paciente_id'],
                        "paciente_nombre": f"{h['nombre']} {h['apellido']}",
                        "paso": "esperando_especialidad"
                    })
                    return jsonify({
                        "respuesta": f"Para {h['nombre']}. ¿Especialidad?",
                        "tipo": "seleccionar_especialidad",
                        "especialidades": obtener_especialidades_con_medicos()
                    })

        # --- ESPECIALIDAD ---
        if sesion_cita.get("paso") == "esperando_especialidad":
            esps = obtener_especialidades_con_medicos()
            for e in esps:
                if normalizar(e) in msg_norm:
                    medicos = consultar_medicos_por_especialidad(e)
                    sesion_cita["paso"] = "esperando_medico"
                    return jsonify({"respuesta": f"Para {e}, elige tu doctor:", "tipo": "seleccionar_doctor", "doctores": medicos})

        # --- MÉDICO Y SIGUIENTES ---
        if "dr." in msg_norm or "dra." in msg_norm:
            sesion_cita.update({"medico": msg_orig, "paso": "esperando_fecha"})
            return jsonify({"respuesta": "¿Para qué fecha deseas tu cita? (Ej: 15/02/2026)"})

        # --- FECHA ---
        if (len(nums) >= 6 and ("-" in msg_orig or "/" in msg_orig)) or "2026" in msg_orig:
            # (Inserta aquí tu bloque de validación de fecha que ya tenías funcionando)
            # Al final de ese bloque, asegúrate de poner:
            # sesion_cita["paso"] = "esperando_hora"
            pass 

        # --- RESUMEN Y GUARDADO (Lo que ya tenías que guardaba bien) ---
        if sesion_cita.get("hora_final") and not sesion_cita.get("motivo"):
             # Aquí procesas el motivo y lanzas el resumen (Paso 9 anterior)
             pass

        if "confirmar" in msg_norm:
             # Aquí procesas el guardado final (Paso 10 anterior)
             pass

        return jsonify({"respuesta": "Lo siento, no entendí. Escribe 'agendar' para iniciar."})

    except Exception as e:
        print(f"Error: {e}")
        return jsonify({"respuesta": "Error interno."})
    
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)