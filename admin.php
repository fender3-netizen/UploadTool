<?php
require_once 'functions.php';
checkAdmin();

// Mindestgröße speichern
if(isset($_POST['width']) && isset($_POST['height'])){
    $db->exec("DELETE FROM config");
    $stmt = $db->prepare("INSERT INTO config (width,height) VALUES (?,?)");
    $stmt->execute([$_POST['width'], $_POST['height']]);
    $message="Mindestgröße gespeichert";
}

// Link generieren
$link="";
if(isset($_POST['generate'])){
    $token = generateToken();
    $stmt = $db->prepare("INSERT INTO links (token) VALUES (?)");
    $stmt->execute([$token]);
    $link = "upload.php?token=".$token;
}

// Aktuelle Config
$stmt = $db->query("SELECT * FROM config ORDER BY id DESC LIMIT 1");
$config = $stmt->fetch(PDO::FETCH_ASSOC);
$width = $config['width'] ?? 210;
$height = $config['height'] ?? 297;
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Admin Panel</title></head>
<body>
<h1>Admin Panel</h1>
<form method="POST">
    Breite (mm): <input name="width" value="<?=$width?>" required>
    Höhe (mm): <input name="height" value="<?=$height?>" required>
    <button>Speichern</button>
</form>
<?php if(isset($message)) echo "<p>$message</p>"; ?>
<form method="POST">
    <button name="generate">Upload-Link generieren</button>
</form>
<?php if($link) echo "<p>Link für Kunden: <a href='$link'>$link</a></p>"; ?>
</body>
</html>
