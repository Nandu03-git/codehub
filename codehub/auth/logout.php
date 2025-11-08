<?php
require_once __DIR__ . '/../includes/config.php';
session_unset();
session_destroy();
session_start();
set_flash("Logged out successfully","success");
header("Location: login.php");
exit;
