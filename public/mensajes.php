<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitMatch - Mensajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Poppins', sans-serif; }
        .mensajes-container { display: flex; height: 90vh; margin: 30px auto; max-width: 1100px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border-radius: 20px; overflow: hidden; }
        .matches-list { width: 320px; background: #fff; border-right: 1px solid #eee; overflow-y: auto; }
        .match-item { display: flex; align-items: center; gap: 15px; padding: 18px 20px; cursor: pointer; border-bottom: 1px solid #f2f2f2; transition: background 0.2s; }
        .match-item:hover, .match-item.active { background: #f3f6fa; }
        .match-avatar { width: 48px; height: 48px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #888; }
        .match-info { flex: 1; }
        .match-name { font-weight: 600; font-size: 1.1rem; color: #333; }
        .chat-panel { flex: 1; display: flex; flex-direction: column; background: #fcfcfc; }
        .chat-header { padding: 18px 24px; border-bottom: 1px solid #eee; font-weight: 600; font-size: 1.2rem; color: #444; background: #fff; }
        .chat-messages { flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; }
        .msg-own { align-self: flex-end; background: #667eea; color: #fff; border-radius: 16px 16px 4px 16px; padding: 10px 18px; max-width: 60%; }
        .msg-other { align-self: flex-start; background: #e5e9f2; color: #333; border-radius: 16px 16px 16px 4px; padding: 10px 18px; max-width: 60%; }
        .chat-input-panel { display: flex; gap: 10px; padding: 18px 24px; border-top: 1px solid #eee; background: #fff; }
        .chat-input { flex: 1; border-radius: 12px; border: 1px solid #ddd; padding: 10px 16px; font-size: 1rem; }
        .btn-send { background: linear-gradient(45deg, #667eea, #764ba2); color: #fff; border: none; border-radius: 12px; padding: 10px 24px; font-weight: 600; transition: background 0.2s; }
        .btn-send:hover { background: linear-gradient(45deg, #764ba2, #667eea); }
        @media (max-width: 900px) { .mensajes-container { flex-direction: column; height: auto; } .matches-list { width: 100%; border-right: none; border-bottom: 1px solid #eee; } .chat-panel { min-height: 400px; } }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="mensajes-container">
        <div class="matches-list" id="matchesList">
            <div class="text-center text-muted py-4">Cargando matches...</div>
        </div>
        <div class="chat-panel">
            <div class="chat-header" id="chatHeader">Selecciona un match para chatear</div>
            <div class="chat-messages" id="chatMessages"></div>
            <form class="chat-input-panel" id="chatForm" style="display:none;">
                <input type="text" class="chat-input" id="chatInput" placeholder="Escribe un mensaje..." autocomplete="off" required>
                <button class="btn-send" type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>
<script>
let matches = [];
let currentMatch = null;
let currentUserId = <?php echo (int)$_SESSION['user_id']; ?>;

async function loadMatches() {
    const res = await fetch('api/chat_api.php?action=matches');
    const data = await res.json();
    const list = document.getElementById('matchesList');
    if (!data.success || !data.matches.length) {
        list.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-heart-broken fa-2x mb-2"></i><br>No tienes matches aún</div>';
        document.getElementById('chatHeader').textContent = 'Selecciona un match para chatear';
        document.getElementById('chatMessages').innerHTML = '';
        document.getElementById('chatForm').style.display = 'none';
        return;
    }
    matches = data.matches;
    list.innerHTML = '';
    matches.forEach((m, i) => {
        const item = document.createElement('div');
        item.className = 'match-item';
        item.innerHTML = `<div class='match-avatar'><i class='fas fa-user'></i></div>
            <div class='match-info'><div class='match-name'>${m.first_name || m.username}</div></div>`;
        item.onclick = () => selectMatch(i);
        list.appendChild(item);
    });
}

async function selectMatch(idx) {
    currentMatch = matches[idx];
    document.querySelectorAll('.match-item').forEach((el, i) => el.classList.toggle('active', i === idx));
    document.getElementById('chatHeader').textContent = `Chat con ${currentMatch.first_name || currentMatch.username}`;
    document.getElementById('chatForm').style.display = '';
    loadMessages();
}

async function loadMessages() {
    if (!currentMatch) return;
    const res = await fetch('api/chat_api.php?action=history&match_id=' + getMatchId());
    const data = await res.json();
    const box = document.getElementById('chatMessages');
    box.innerHTML = '';
    if (data.success && data.messages.length) {
        data.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = msg.sender_id == currentUserId ? 'msg-own' : 'msg-other';
            div.textContent = msg.message;
            box.appendChild(div);
        });
        box.scrollTop = box.scrollHeight;
    }
}

function getMatchId() {
    return [currentUserId, currentMatch.id].sort((a,b)=>a-b).join('_');
}

document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;
    await fetch('api/chat_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ match_id: getMatchId(), message: msg })
    });
    input.value = '';
    loadMessages();
});

window.onload = loadMatches;
</script>
</body>
</html> 