<?php
require_once 'functions.php';
$token = $_GET['token'] ?? '';
$stmt = $db->prepare("SELECT * FROM links WHERE token=?");
$stmt->execute([$token]);
if(!$stmt->fetch()){ die("Ungültiger Link"); }

// Upload prüfen
if(isset($_FILES['pdf'])){
    $file = $_FILES['pdf']['tmp_name'];
    $stmt = $db->query("SELECT * FROM config ORDER BY id DESC LIMIT 1");
    $cfg = $stmt->fetch(PDO::FETCH_ASSOC);
    $minW = $cfg['width'] ?? 210;
    $minH = $cfg['height'] ?? 297;

    require_once('vendor/autoload.php'); // FPDI
    $pdfok = checkPDFSize($file, $minW, $minH);
    if($pdfok){
        move_uploaded_file($file, "uploads/".$_FILES['pdf']['name']);
        echo "Upload erfolgreich";
    } else echo "PDF unterschreitet Mindestgröße";
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>PDF Upload</title></head>
<body>
<h1>PDF Upload</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf" accept="application/pdf" required>
    <button>Upload</button>
</form>
<script src="public/js/main.js"></script>
</body>
</html>
