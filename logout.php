<?php
// logout.php
require_once 'config.php';
require_once 'functions.php';

// Hapus semua session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke login
header("Location: login.php");
exit();
?>