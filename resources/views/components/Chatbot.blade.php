<div id="chat-circle" class="shadow-lg">
    <i class="fas fa-comments" id="chat-icon"></i>
</div>

<div class="chat-box shadow-lg" id="chat-box-body">
    <div class="chat-box-header">
        <span><i class="fas fa-hospital-user mr-2"></i> Asistente El Buen Pastor</span>
        <span class="chat-box-toggle" style="cursor:pointer;"><i class="fas fa-times"></i></span>
    </div>
    <div id="chat-logs">
        <div class="chat-msg bot">
            <div class="cm-msg-text">
                ¡Hola! 👋 Soy el asistente virtual de <b>Clínica El Buen Pastor</b>. 
                Es un gusto saludarte. ¿En qué puedo ayudarte hoy?
                
                <div class="chat-options mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Horarios')">📅 Horarios de atención</button>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Ubicación')">📍 Nuestra ubicación</button>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Servicios')">🩺 Servicios médicos</button>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Agendar Cita')">📝 Agendar una cita</button>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Escribe tu mensaje..."/>      
        <button type="button" class="chat-submit" id="chat-send-btn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
// 1. Esta función debe estar AFUERA para que el 'onclick' del HTML la encuentre
function enviarOpcion(texto) {
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.value = texto;
        // Disparamos el evento click del botón de enviar
        document.getElementById('chat-send-btn').click();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const chatCircle = document.getElementById('chat-circle');
    const chatBox = document.getElementById('chat-box-body');
    const chatToggle = document.querySelector('.chat-box-toggle');
    const chatInput = document.getElementById('chat-input');
    const chatSendBtn = document.getElementById('chat-send-btn');
    const chatLogs = document.getElementById('chat-logs');

    // 2. Función para Abrir/Cerrar
    function toggleChat() {
        if (chatBox.style.display === 'flex') {
            chatBox.style.display = 'none';
            chatCircle.classList.remove('active');
        } else {
            chatBox.style.display = 'flex';
            chatCircle.classList.add('active');
        }
    }

    chatCircle.addEventListener('click', toggleChat);
    chatToggle.addEventListener('click', toggleChat);

    // 3. Función principal de envío
    function sendMessage() {
        const msg = chatInput.value.trim();
        if (msg === "") return;

        // Renderizar mensaje del usuario
        const userHtml = `<div class="chat-msg user"><div class="cm-msg-text">${msg}</div></div>`;
        chatLogs.insertAdjacentHTML('beforeend', userHtml);
        chatInput.value = "";
        
        // Auto-scroll
        chatLogs.scrollTop = chatLogs.scrollHeight;

        // AJAX a Laravel
        $.ajax({
            url: "{{ route('chatbot.consulta') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                mensaje: msg
            },
success: function(response) {
    let botHtml = `<div class="chat-msg bot"><div class="cm-msg-text">${response.respuesta}`;
    
    if(response.tipo === 'seleccionar_especialidad') {
        botHtml += `<div class="chat-options mt-3">`;
        response.especialidades.forEach(esp => {
            botHtml += `<button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('${esp}')">🩺 ${esp}</button>`;
        });
        botHtml += `</div>`;
    }
    else if(response.tipo === 'seleccionar_doctor') {
        botHtml += `<div class="chat-options mt-3">`;
        response.doctores.forEach(doc => {
            botHtml += `<button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('${doc}')">👨‍⚕️ ${doc}</button>`;
        });
        botHtml += `</div>`;
    }
    else if(response.tipo === 'seleccionar_hijo') {
        botHtml += `<div class="chat-options mt-3">`;
        response.hijos.forEach(hijo => {
            botHtml += `<button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Agendar para ${hijo.nombre}')">👶 ${hijo.nombre}</button>`;
        });
        botHtml += `</div>`;
    }
    // Para el Paso 2: Solo dejamos que el usuario escriba la fecha
// NUEVO: Botones de Hora
    if(response.tipo === 'seleccionar_hora') {
        botHtml += `<div class="chat-options mt-3">`;
        response.horas.forEach(hora => {
            botHtml += `<button type="button" class="btn btn-outline-info btn-sm mb-2" onclick="enviarOpcion('${hora}')">⏰ ${hora}</button>`;
        });
        botHtml += `</div>`;
    }
                // Casos de Error o Inicio
                else if(response.tipo === 'opciones_cita' || response.tipo === 'paciente_no_encontrado') {
                    botHtml += `
                        <div class="chat-options mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Ya soy Paciente')">✅ Ya soy Paciente</button>
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="enviarOpcion('Soy Paciente Nuevo')">🆕 Soy Paciente Nuevo</button>
                        </div>`;
                }
                
                botHtml += `</div></div>`;
                $("#chat-logs").append(botHtml);
                $("#chat-logs").scrollTop($("#chat-logs")[0].scrollHeight);
            },
            error: function() {
                const errorHtml = `<div class="chat-msg bot"><div class="cm-msg-text">Error de conexión con el asistente clínico.</div></div>`;
                chatLogs.insertAdjacentHTML('beforeend', errorHtml);
            }
        });
    }

    // Eventos
    chatSendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => { 
        if(e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });
});
</script>

<style>
    /* 1. Burbuja Flotante (Círculo) */
    #chat-circle {
        position: fixed; 
        bottom: 30px; 
        right: 30px;
        background: #14b2c6; 
        width: 65px; 
        height: 65px;
        border-radius: 50%; 
        color: white; 
        display: flex;
        align-items: center; 
        justify-content: center;
        font-size: 28px; 
        cursor: pointer; 
        z-index: 1000;
        box-shadow: 0px 10px 20px rgba(20, 178, 198, 0.4);
        transition: all 0.3s ease;
    }
    
    #chat-circle.active {
        background: #0d8a9a; 
        transform: rotate(90deg);
    }

    /* 2. Ventana de Chat */
    .chat-box {
        display: none; 
        position: fixed; 
        right: 30px; 
        bottom: 110px;
        width: 350px; 
        max-width: 85vw; 
        height: 500px;
        background: #fff; 
        border-radius: 15px; 
        z-index: 1001;
        flex-direction: column;
        box-shadow: 0px 5px 40px rgba(0,0,0,0.15);
    }

    .chat-box-header {
        background: #14b2c6; 
        color: white; 
        padding: 15px;
        border-top-left-radius: 15px; 
        border-top-right-radius: 15px;
        display: flex; 
        justify-content: space-between; 
        align-items: center;
    }

    /* 3. Área de Mensajes y Scroll */
    #chat-logs {
        padding: 15px; 
        flex: 1; 
        overflow-y: auto; 
        max-height: 380px;
        display: flex;
        flex-direction: column;
    }

    .chat-msg { margin-bottom: 15px; }

    .cm-msg-text {
        padding: 10px 15px; 
        border-radius: 15px; 
        background: #f1f1f1;
        display: inline-block; 
        max-width: 80%; 
        font-size: 14px;
    }

    .chat-msg.user { text-align: right; }
    .chat-msg.user .cm-msg-text { background: #14b2c6; color: white; }

    /* 4. Botones de Opciones Rápidas */
    .chat-options {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        margin-top: 10px;
    }

    .chat-options .btn {
        border-radius: 15px;
        font-size: 0.85rem;
        background-color: white;
        border: 1px solid #14b2c6;
        color: #14b2c6;
        transition: all 0.3s;
        text-align: left;
        width: 100%; 
        padding: 8px 12px;
    }

    .chat-options .btn:hover {
        background-color: #14b2c6;
        color: white;
        box-shadow: 0px 4px 8px rgba(20, 178, 198, 0.2);
    }

    /* 5. Área de Entrada de Texto */
    .chat-input-area { 
        border-top: 1px solid #eee; 
        padding: 10px; 
        display: flex; 
    }

    .chat-input-area input { 
        width: 100%; 
        border: none; 
        padding: 10px; 
        outline: none; 
    }

    .chat-submit { 
        background: transparent; 
        border: none; 
        color: #14b2c6; 
        cursor: pointer; 
        font-size: 20px;
    }

    .cm-msg-text {
    white-space: pre-wrap; /* Esto permite que los \n de Python se vean como saltos de línea */
}
</style>
