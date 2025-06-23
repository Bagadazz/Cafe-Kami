<?php
require 'config.php';
$username = $_POST['username'];
$password = $_POST['password'];

$userdb = 'bagas';
$passdb = '123456';

if(($username == $userdb) AND ($password == $passdb)){
    //mengeset sesi login
    $_SESSION['username'] = $username;
    $_SESSION['level_user'];
    //redirect
    header('location: admin/dashboard.php');
} else {

    header('location:index.php');
}