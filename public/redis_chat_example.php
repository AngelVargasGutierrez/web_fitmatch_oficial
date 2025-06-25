<?php
session_start();
// Simulación: usuario 1 o 2 (cambiar para probar)
if (!isset($_SESSION['user_id'])) {
    // Cambia el ID aquí para simular usuario 1 o 2
    $_SESSION['user_id'] = isset($_GET['user']) && $_GET['user'] == 2 ? 2 : 1;
}
$userId = $_SESSION['user_id'];
$otherUserId = $userId == 1 ? 2 : 1;
$matchId = 1; // Suponemos que el match entre 1 y 2 es ID 1
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat de Ejemplo (Usuario <?php echo $userId; ?>)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box { height: 350px; overflow-y: auto; background: #f8f9fa; border-radius: 10px; padding: 15px; }
        .msg-own { text-align: right; color: #fff; background: #667eea; border-radius: 15px 15px 0 15px; display: inline-block; padding: 8px 15px; margin: 5px 0; }
        .msg-other { text-align: left; color: #333; background: #e2e3e5; border-radius: 15px 15px 15px 0; display: inline-block; padding: 8px 15px; margin: 5px 0; }
    </style>
</head>
<body class="container py-4">
    <h3>Chat entre Usuario 1 y 2 (Tú eres el Usuario <?php echo $userId; ?>)</h3>
    <div id="chat-box"></div>
    <form id="chat-form" class="mt-3 d-flex">
        <input type="text" id="message" class="form-control me-2" placeholder="Escribe un mensaje..." autocomplete="off" required>
        <button class="btn btn-primary" type="submit">Enviar</button>
    </form>
    <div class="mt-2">
        <a href="?user=1" class="btn btn-outline-secondary btn-sm">Simular Usuario 1</a>
        <a href="?user=2" class="btn btn-outline-secondary btn-sm">Simular Usuario 2</a>
    </div>
    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const userId = <?php echo $userId; ?>;
        const matchId = <?php echo $matchId; ?>;

        function fetchMessages() {
            fetch('api/chat_api.php?match_id=' + matchId)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        chatBox.innerHTML = '';
                        data.messages.forEach(msg => {
                            if (!msg) return;
                            const isOwn = msg.sender_id == userId;
                            const div = document.createElement('div');
                            div.className = isOwn ? 'msg-own' : 'msg-other';
                            div.textContent = msg.message;
                            chatBox.appendChild(div);
                        });
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;
            fetch('api/chat_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ match_id: matchId, message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    fetchMessages();
                }
            });
        });

        // Actualizar mensajes cada 2 segundos
        setInterval(fetchMessages, 2000);
        fetchMessages();
    </script>
</body>
</html> 