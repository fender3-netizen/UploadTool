<?php
require_once 'functions.php';

$token = $_GET['token'] ?? '';
$stmt = $db->prepare("SELECT * FROM links WHERE token=?");
$stmt->execute([$token]);
if(!$stmt->fetch()){ die("Ungültiger Link!"); }

// Konfiguration aus DB
$stmt = $db->query("SELECT * FROM config ORDER BY id DESC LIMIT 1");
$config = $stmt->fetch();
$minW = $config['width'] ?? 210;
$minH = $config['height'] ?? 297;

$uploadMessage = '';

if(isset($_FILES['pdf'])){
    $file = $_FILES['pdf']['tmp_name'];
    require_once __DIR__ . '/vendor/autoload.php';
    if(checkPDFSize($file, $minW, $minH)){
        $target = 'uploads/' . basename($_FILES['pdf']['name']);
        move_uploaded_file($file, $target);
        $uploadMessage = "Upload erfolgreich!";
    } else {
        $uploadMessage = "PDF unterschreitet Mindestgröße!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>PDF Upload</title>
<style>
#canvasContainer { border:1px solid #000; width:<?=$minW?>mm; height:<?=$minH?>mm; position:relative; }
#innerBox { border:2px solid black; width:<?=($minW-50)?>mm; height:<?=($minH-50)?>mm; position:absolute; top:25mm; left:25mm; }
</style>
</head>
<body>
<h1>PDF Upload</h1>
<?php if($uploadMessage) echo "<p>$uploadMessage</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf" accept="application/pdf" required>
    <button>Upload</button>
</form>

<h2>PDF Vorschau / Canvas</h2>
<div id="canvasContainer">
    <div id="innerBox"></div>
    <!-- Hier kann später PDF-Preview via JS/Canvas integriert werden -->
</div>
<script src="public/js/main.js"></script>
</body>
</html>
