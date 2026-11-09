<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chat Realtime via API</title>
    <!-- Pusher JS Library -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        body { font-family: sans-serif; background: #eef2f5; padding: 20px; }
        .chat-card { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .box { height: 320px; border: 1px solid #ddd; overflow-y: auto; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .msg { margin-bottom: 8px; padding: 6px 10px; border-radius: 6px; background: #f1f0f0; }
        .input-box { display: flex; gap: 8px; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="chat-card">
    <h3>Live Chat via API Pusher</h3>
    <input type="text" id="namaUser" placeholder="Nama Anda..." style="width: 95%; margin-bottom: 10px;">
    
    <div class="box" id="chatBox"></div>

    <div class="input-box">
        <input type="text" id="pesanText" placeholder="Ketik pesan...">
        <button onclick="kirimPesan()">Kirim</button>
    </div>
</div>

<script>
    // 1. Inisialisasi Pusher Client
    const PUSHER_KEY = 'be2ed99ed409c7762088'; // Ganti dengan Key Pusher Anda
    const CLUSTER = 'ap1';

    const pusher = new Pusher(PUSHER_KEY, { cluster: CLUSTER });
    const channel = pusher.subscribe('ruang-chat');

    // 2. Listener saat ada pesan masuk via API
    channel.bind('pesan-baru', function(data) {
        const box = document.getElementById('chatBox');
        box.innerHTML += `<div class="msg"><strong>${data.nama}</strong> <small>(${data.waktu})</small><br>${data.pesan}</div>`;
        box.scrollTop = box.scrollHeight;
    });

    // 3. Fungsi Kirim Pesan ke API Backend PHP
    function kirimPesan() {
        const nama = document.getElementById('namaUser').value.trim() || 'Anonim';
        const pesan = document.getElementById('pesanText').value.trim();
        if (!pesan) return;

        fetch('kirim_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nama: nama, pesan: pesan })
        });

        document.getElementById('pesanText').value = '';
    }
</script>

</body>
</html>