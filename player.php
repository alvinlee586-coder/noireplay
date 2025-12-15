<?php
// =======================================
// PLAYER.PHP (FULL FIXED VERSION)
// =======================================

// HEADER CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Origin, Accept, Range");
header("Access-Control-Expose-Headers: Content-Length, Content-Range, Accept-Ranges");

// URL DOMAIN TEMPAT VIDEO DISIMPAN
$BASE_URL = 'https://noireplay.xo.je/';

// GET PARAMETER FOLDER
$folder_name = isset($_GET['file']) ? trim($_GET['file']) : null;

if (!$folder_name) {
    http_response_code(400);
    die("Error: parameter ?file= belum dimasukkan.");
}

// MANIFEST YG DICARI
$HLS_URL = $BASE_URL . $folder_name . '/index.m3u8';

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Streaming: <?php echo htmlspecialchars($folder_name); ?></title>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<style>
    html, body {
        margin: 0;
        padding: 0;
        background: #000;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    #video {
        width: 100%;
        height: 100%;
        background: #000;
    }
</style>

</head>
<body>

<video id="video" controls autoplay playsinline></video>

<script>
const video = document.getElementById("video");
const folderName = "<?php echo htmlspecialchars($folder_name); ?>";
const manifestUrl = "<?php echo $HLS_URL; ?>";

// URL stream.php proxy
const PROXY_URL_BASE = "https://noireplay.xo.je/stream.php?file=" + folderName + "&seg=";

// Ambil manifest lalu rewrite URL segmen
function fetchManifest(url, callback) {
    fetch(url)
        .then(r => {
            if (!r.ok) throw new Error("Manifest HTTP Error " + r.status);
            return r.text();
        })
        .then(text => {
            const fixedManifest = text.replace(
                /(^|\n)([^#\n]+\.ts)/gm,
                (match, nl, seg) => nl + PROXY_URL_BASE + seg
            );
            callback(fixedManifest);
        })
        .catch(err => {
            console.error("Manifest Error:", err);
            document.body.innerHTML =
                "<h3 style='color:red;text-align:center;margin-top:30px;'>❌ Gagal memuat manifest.<br>Pastikan folder <b>" +
                folderName +
                "</b> dan file index.m3u8 ada.</h3>";
        });
}

// Load manifest
if (Hls.isSupported()) {
    const hls = new Hls();

    fetchManifest(manifestUrl, function(modifiedManifest){
        const blobUrl = URL.createObjectURL(
            new Blob([modifiedManifest], { type: "application/x-mpegURL" })
        );
        hls.loadSource(blobUrl);
        hls.attachMedia(video);
    });

    hls.on(Hls.Events.ERROR, function (e, data) {
        if (data.fatal) {
            console.error("HLS Fatal:", data);
            document.body.innerHTML =
                "<h3 style='color:red;text-align:center;margin-top:30px;'>❌ Gagal memuat video segmen.<br>Periksa stream.php.</h3>";
        }
    });

} else if (video.canPlayType("application/vnd.apple.mpegurl")) {
    video.src = manifestUrl;
}
</script>

</body>
</html>
