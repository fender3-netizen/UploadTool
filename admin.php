<?php
require_once 'functions.php';
checkAdmin();

// Mindestgröße speichern
if(isset($_POST['width']) && isset($_POST['height'])){
    $db->exec("DELETE FROM config");
    $stmt = $db->prepare("INSERT INTO config (width,height) VALUES (?,?)");
    $stmt->execute([$_POST['width'], $_POST['height']]);
    $message="Mindestgröße gespeichert!";
}

// Upload-Link generieren
$link = '';
if(isset($_POST['generate'])){
    $token = generateToken();
    $stmt = $db->prepare("INSERT INTO links (token) VALUES (?)");
    $stmt->execute([$token]);
    $link = "upload.php?token=".$token;
}

// Aktuelle Config aus DB
$stmt = $db->query("SELECT * FROM config ORDER BY id DESC LIMIT 1");
$config = $stmt->fetch();
$width = $config['width'] ?? 210;
$height = $config['height'] ?? 297;
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Admin Panel</title></head>
<body>
<h1>Admin Panel</h1>

<h2>Mindestgröße PDF</h2>
<form method="POST">
    Breite (mm): <input name="width" value="<?=$width?>" required>
    Höhe (mm): <input name="height" value="<?=$height?>" required>
    <button>Speichern</button>
</form>
<?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

<h2>Upload-Link generieren</h2>
<form method="POST">
    <button name="generate">Link generieren</button>
</form>
<?php if($link) echo "<p>Link für Kunden: <a href='$link'>$link</a></p>"; ?>
</body>
</html>
