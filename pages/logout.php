<?php
require_once('../classes/User.php');
require_once('../config/database.php');
session_start();

$user = new User($pdo);
$user->logout();
header('Location: ../index.php');

?>