<?php
session_start();


function cekLogin(){
    if(isset($_SESSION['username'])){
        return true;
    } else {
        return false;
    }
}