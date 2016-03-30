<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ob_start();
if(!isset($_SESSION)){
    session_start();
}

if ($_SESSION['tabel']['is_login'] == true) {
    
} else {
    header("Location: http://".$_SERVER['HTTP_HOST']."/cms_w_serwis_www/");
}

