<?php
require_once 'config.php';
if(isset($_POST['username'])){
    $stmt = $db->prepare("SELECT * FROM admin WHERE username=? AND password=?");
    $stmt->execute([$_POST['username'], $_POST['password']]);
    if($stmt->fetch()){
        $_SESSION['loggedin'] = true;
        header("Location: admin.php");
        exit;
    } else $error="Login fehlgeschlagen";
}
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Admin Login</title></head>
<body>
<h1>Admin Login</h1>
<form method="POST">
    <input name="username" placeholder="Benutzername" required>
    <input name="password" type="password" placeholder="Passwort" required>
    <button>Login</button>
</form>
<?php if(isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
