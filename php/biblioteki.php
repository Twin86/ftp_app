<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    
}
*/

function return_serwer_name(){
    echo '<h1>'.$_SESSION['tabel']['ftp_serwer'].'</h1>';
}

function return_folder_path() {
    echo 'value = "'.$_SESSION['last_file_path'].'"' ;
}