<?php
require_once 'config.php';

// Prüft, ob Admin eingeloggt ist
function checkAdmin() {
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
        header("Location: index.php");
        exit;
    }
}

// Generiert zufälligen Token
function generateToken($length=8){
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
}

// Prüft PDF Seitenmaße (nur erste Seite)
function checkPDFSize($filePath, $minWidthMM, $minHeightMM){
    require_once('vendor/autoload.php'); // Für FPDI / TCPDI
    $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
    $pageCount = $pdf->setSourceFile($filePath);
    $tpl = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tpl);

    // Maße in mm
    $width = $size['width'];
    $height = $size['height'];

    if($width >= $minWidthMM && $height >= $minHeightMM) return true;
    return false;
}
?>
