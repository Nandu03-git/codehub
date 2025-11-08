<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Kolkata');

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // default XAMPP blank
$DB_NAME = 'codehub';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) { die("DB Connection failed: " . $conn->connect_error); }

// Flash helper
function set_flash($msg, $type='success'){
    $_SESSION['flash'] = ['msg'=>$msg, 'type'=>$type];
}
function get_flash(){
    if(isset($_SESSION['flash'])){ $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// auth helper
function is_logged(){ return isset($_SESSION['user_id']); }
function require_login(){ if(!is_logged()){ header('Location: /codehub/auth/login.php'); exit; } }
?>
