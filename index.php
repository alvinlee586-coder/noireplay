<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noireplay Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #121212; color: #fff; text-align: center; padding: 20px; }
        .box { background: #1e1e1e; max-width: 800px; margin: 20px auto; padding: 20px; border-radius: 8px; border: 1px solid #333; }
        input { width: 70%; padding: 10px; border-radius: 4px; border: 1px solid #444; background: #000; color: #fff; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        iframe { width: 100%; height: 400px; border: none; background: #000; margin-top: 15px; }
        h2 { color: #4CAF50; border-bottom: 1px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>

    <div class="box">
        <h2>🎬 Generator Link Embed</h2>
        <p>Masukkan nama folder hasil transcoding di bawah ini:</p>
        
        <input type="text" id="folderName" value="SORE-NOIRE_hls_master" placeholder="Contoh: VIDEO_hls_master">
        <button onclick="generate()">Preview & Generate</button>
        
        <div id="resultArea" style="display:none; margin-top:20px;">
            <p><strong>Preview Player:</strong></p>
            <iframe id="previewFrame" src=""></iframe>
            
            <p style="margin-top:20px;"><strong>Kode Embed untuk Web Lain:</strong></p>
            <textarea id="embedCode" style="width:100%; height:100px; background:#000; color:#0f0; border:1px solid #333;"></textarea>
            <br><br>
            <button onclick="copyCode()">📋 Salin Kode</button>
        </div>
    </div>

    <script>
        function generate() {
            var folder = document.getElementById('folderName').value.trim();
            if(!folder) { alert("Isi nama folder dulu!"); return; }

            // URL Player Unik
            var playerUrl = 'http://noireplay.xo.je/player.php?file=' + folder;

            // Tampilkan Preview
            document.getElementById('previewFrame').src = playerUrl;
            
            // Buat Kode Iframe
            var code = '<iframe src="' + playerUrl + '" width="100%" height="450" frameborder="0" allowfullscreen></iframe>';
            
            document.getElementById('embedCode').value = code;
            document.getElementById('resultArea').style.display = 'block';
        }

        function copyCode() {
            var copyText = document.getElementById("embedCode");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* Untuk mobile */
            try {
                document.execCommand('copy');
                alert("Kode berhasil disalin!");
            } catch (err) {
                alert("Gagal menyalin otomatis. Silakan copy manual.");
            }
        }
    </script>
</body>
</html>