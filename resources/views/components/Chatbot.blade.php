<div id="chat-circle" class="shadow-lg">
    <i class="fas fa-comments" id="chat-icon"></i>
</div>

<div class="chat-box shadow-lg" id="chat-box-body">
    <div class="chat-box-header">
        <span><i class="fas fa-hospital-user mr-2"></i> Asistente El Buen Pastor</span>
        <span class="chat-box-toggle"><i class="fas fa-times"></i></span>
    </div>
    
    <div id="chat-logs">
        <div class="chat-msg bot">
            <div class="cm-msg-text">
                <b>¡Hola! 👋 Soy tu asistente de la Clínica El Buen Pastor.</b>
                ¿Cómo Puedo Ayudar el dia de hoy?
                
                <div class="chat-options">
                    <button type="button" class="btn-chat-opt" onclick="enviarOpcion('Horarios')">📅 Horarios de atención</button>
                    <button type="button" class="btn-chat-opt" onclick="enviarOpcion('Ubicación')">📍 Nuestra ubicación</button>
                    <button type="button" class="btn-chat-opt" onclick="enviarOpcion('Servicios')">🩺 Servicios médicos</button>
                    <button type="button" class="btn-chat-opt highlight" onclick="enviarOpcion('Agendar Cita')">📝 Agendar una cita</button>
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
function enviarOpcion(texto) {
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.value = texto;
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

    function toggleChat() {
        // Usamos getComputedStyle para leer el estado real del display
        const display = window.getComputedStyle(chatBox).display;
        if (display === 'none') {
            chatBox.style.display = 'flex';
            chatCircle.classList.add('active');
        } else {
            chatBox.style.display = 'none';
            chatCircle.classList.remove('active');
        }
    }

    chatCircle.addEventListener('click', toggleChat);
    chatToggle.addEventListener('click', toggleChat);

    // Crear session_id único por navegador
    let chatSessionId = localStorage.getItem("chat_session_id");

    if (!chatSessionId) {
        chatSessionId = crypto.randomUUID();
        localStorage.setItem("chat_session_id", chatSessionId);
    }

    function sendMessage() {
        const msg = chatInput.value.trim();
        if (msg === "") return;

        const userHtml = `<div class="chat-msg user"><div class="cm-msg-text">${msg}</div></div>`;
        chatLogs.insertAdjacentHTML('beforeend', userHtml);
        chatInput.value = "";
        chatLogs.scrollTop = chatLogs.scrollHeight;

        $.ajax({
            url: "{{ route('chatbot.consulta') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                mensaje: msg,
                session_id: chatSessionId
            },
            success: function(response) {
                let textoFormateado = response.respuesta
                    .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

                let botHtml = `<div class="chat-msg bot"><div class="cm-msg-text">${textoFormateado}`;

                
                if(response.tipo && response.tipo !== 'mensaje_ia') {
                    botHtml += `<div class="chat-options">`;
                    if(response.tipo === 'seleccionar_especialidad') {
                        response.especialidades.forEach(esp => {
                            botHtml += `<button type="button" class="btn-chat-opt" onclick="enviarOpcion('${esp}')">🩺 ${esp}</button>`;
                        });
                    }
                    else if(response.tipo === 'seleccionar_doctor') {
                        response.doctores.forEach(doc => {
                            botHtml += `<button type="button" class="btn-chat-opt" onclick="enviarOpcion('${doc}')">👨‍⚕️ ${doc}</button>`;
                        });
                    }
                    else if(response.tipo === 'seleccionar_hijo') {
                        response.hijos.forEach(hijo => {
                            botHtml += `<button type="button" class="btn-chat-opt" onclick="enviarOpcion('Agendar para ${hijo.nombre}')">👶 ${hijo.nombre}</button>`;
                        });
                    }
                    else if(response.tipo === 'seleccionar_hora') {
                        response.horas.forEach(hora => {
                            botHtml += `<button type="button" class="btn-chat-opt highlight" onclick="enviarOpcion('${hora}')">⏰ ${hora}</button>`;
                        });
                    }
                    else if(response.tipo === 'opciones_cita' || response.tipo === 'paciente_no_encontrado') {
                        botHtml += `<button type="button" class="btn-chat-opt" onclick="enviarOpcion('Ya soy Paciente')">✅ Ya soy Paciente</button>`;
                        botHtml += `<button type="button" class="btn-chat-opt" onclick="enviarOpcion('Soy Paciente Nuevo')">🆕 Soy Paciente Nuevo</button>`;
                    }
                    botHtml += `</div>`;
                }
                
                botHtml += `</div></div>`;
                $("#chat-logs").append(botHtml);
                $("#chat-logs").scrollTop($("#chat-logs")[0].scrollHeight);
            },
            error: function() {
                const errorHtml = `<div class="chat-msg bot"><div class="cm-msg-text">Error de conexión.</div></div>`;
                chatLogs.insertAdjacentHTML('beforeend', errorHtml);
            }
        });
    }

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
    #chat-circle {
        position: fixed; bottom: 30px; right: 30px;
        background: #14b2c6; width: 65px; height: 65px;
        border-radius: 50%; color: white; display: flex;
        align-items: center; justify-content: center;
        font-size: 28px; cursor: pointer; z-index: 1000;
        box-shadow: 0px 10px 20px rgba(20, 178, 198, 0.4);
        transition: all 0.3s ease;
    }
    #chat-circle.active { transform: rotate(90deg); background: #0d8a9a; }

    .chat-box {
        display: none; position: fixed; right: 30px; bottom: 110px;
        width: 350px; max-width: 85vw; height: 500px;
        background: #fff; border-radius: 15px; z-index: 1001;
        flex-direction: column; box-shadow: 0px 5px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .chat-box-header {
        background: #14b2c6; color: white; padding: 15px;
        display: flex; justify-content: space-between; align-items: center;
    }
    #chat-logs {
        padding: 15px; flex: 1; overflow-y: auto;
        background: #f7f9fb; display: flex; flex-direction: column;
    }
    .chat-msg { margin-bottom: 15px; max-width: 85%; }
    .chat-msg.user { align-self: flex-end; }
    .chat-msg.bot { align-self: flex-start; }
.cm-msg-text {
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-wrap: break-word;
    display: block;
    /* Eliminamos cualquier margen o padding interno que desalinee el texto */
    margin: 0;
}

/* Para mantener los saltos de línea de la IA sin tabulaciones locas */
.chat-msg.bot .cm-msg-text {
    background: #f2f2f2;
    color: #333;
    border: 1px solid #e0e0e0;
    text-align: left; /* Alineado a la izquierda para mejor lectura */
}

/* Si quieres que el resumen de la cita se vea un poco más "especial" */
.cm-msg-text b {
    color: #0f1010; /* Color de tu clínica para las negritas */
    font-weight: 700;
}
    .chat-msg.user .cm-msg-text { background: #14b2c6; color: white; }
    .chat-options { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
    .btn-chat-opt { background: white; border: 1px solid #14b2c6; color: #14b2c6; padding: 8px 12px; border-radius: 12px; font-size: 13px; text-align: left; cursor: pointer; transition: 0.2s; }
    .btn-chat-opt:hover { 
        background: #14b2c6 !important; 
        color: white !important; /* Asegura que la letra sea blanca sobre el fondo azul */
    }
    .btn-chat-opt.highlight:hover {
        background: #14b2c6 !important;
        color: white !important;
    }
    .chat-input-area { border-top: 1px solid #eee; padding: 12px; display: flex; background: white; }
    .chat-input-area input { flex: 1; border: none; outline: none; font-size: 14px; }
    .chat-submit { background: none; border: none; color: #14b2c6; font-size: 20px; cursor: pointer; }
</style>
