<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'funkcje_h.php';

if(!isset($_SESSION)){
    session_start();
}
$dzialanie = $_POST['dzialanie'];

//sprawdzenie typu pliku do edycji jeszcze nie działa
$typ_pliku_edycja = 'text/plain';



//koniec deklaracji funkcji
//
//działanie programu

if ($dzialanie == 1) {

    //logowanie

    $zwrocona_tablica = polacz_do_ftp($ftp_serwer = $_POST['js_serwer']);
    $_SESSION['tabel'] = $zwrocona_tablica;

    if (!isset($_SESSION['last_file_path'])) {
        $_SESSION['last_file_path'] = '/';

        //echo 'Brak pliku sesji !';
        exit();
    }


    if ($_SESSION['tabel']['is_login'] == true) {
        echo 'Poprawnie';
        exit();
    } else {
        echo 'Odmowa logowania';
        exit();
    }

    exit();
}

if ($dzialanie == 2) {


    //zmiana folderu ftp i pobranie zawartości z lokalizacji

    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    $ftp_url = $_POST['scierzka'];

    $_SESSION['last_file_path'] = $ftp_url;

    if (isset($_SESSION['tabel']['ftp_link'])) {
        // pobranie zawartości bieżącego katalogu
        // $zawartosc = ftp_nlist($ftp_link, "/usr/apache/httpd/html/html");
        //$zawartosc = ftp_nlist($ftp_link, $ftp_url);
        ftp_chdir($ftp_link, $ftp_url);
        $zawartosc = ftp_nlist($ftp_link, '');

        $tabelka = '<table class="table" id="id_dane_dir"><thead><tr><td><i class="fa fa-thumb-tack"></i></td><td>Folder/Plik</td></t></thead>';
        $tr = '';
        // wyświetlenie zawartości
        foreach ($zawartosc as $value) {
            //echo '<p class="rekordy">'.$value.'</p>' ;
            $tr = $tr . '<tr class="rekordy"><td><input type="checkbox"</td><td class="plik_from_serwer">' . $value . '</td></tr>';
        }

        $tabelka = $tabelka . $tr . '</table>';
        echo $tabelka;
        // close connection
        ftp_close($ftp_link);
    } else {
        echo 'Blad !';
    }
    exit();
}

if ($dzialanie == 3) {

    //przeczytanie pliku i próba jego wyświetlenia - nie działą


    $url_file_to_download = $_POST['scierzka_do_pliku'];
    $file = $_POST['nazwa'];
    $zawartosc_pliku = 'test';

    $_SESSION['last_file_path'] = $url_file_to_download;

    //próba rozpoznwania typu plików
    /*
      $finfo = finfo_open(FILEINFO_MIME_TYPE);

      if(finfo_file($finfo, $file) != $typ_pliku_edycja) {
      echo 'Plik nie edytowalny !';
      exit();
      }
      finfo_close($finfo);
     */
    if (pobierz_plik($url_file_to_download, $file, $file)) {
        $readFile = fopen($file, "r") or die("Unable to open file!");
        // echo '<pre><code>';
        echo htmlspecialchars(fread($readFile, filesize($file)), ENT_COMPAT);
        //echo fread($readFile,filesize($file));
        // echo '</code></pre>';
        fclose($readFile);

        //odczytalem plik i go usunolem z lokalnej lokalizacji
        unlink($file);
    }
}

if ($dzialanie == 4) {

    //zakłdanie folderu/pliku na serwerze

    $url_to_create_folder = $_POST['scierzka_do_pliku'];
    $file_name = $_POST['nazwa_folderu'];

    $_SESSION['last_file_path'] = $url_to_create_folder;

    if (!strpos($file_name, '.')) {
        create_folder_on_serwer($url_to_create_folder, $file_name);
    } else {
        
    }
}

if ($dzialanie == 5) {

    //usuwanie pliku z serwera

    $url_to_remove_folder = $_POST['scierzka_do_pliku'];
    $file_name = $_POST['nazwa_folderow'];

    $_SESSION['last_file_path'] = $url_to_remove_folder;

    remove_folder_on_serwer($url_to_remove_folder, $file_name);

    echo 'usuń folder';
}

if ($dzialanie == 6) {

    //twożenie pliku i wysyłanie na serwer
    $ftp_url = $_POST['scierzka_do_pliku'];
    $file_name = $_POST['nazwa_folderu'];

    $_SESSION['last_file_path'] = $ftp_url;

    add_file($ftp_url, $file_name);
}

if ($dzialanie == 7) {

    //sprawdzaj jaki max file upload ma ustawiony serwer
    //wgrywanie pliku

    $ftp_path = $_POST['scierzka'];
    $local_file = $_FILES["file_to_upload"];
    $previus_page = $_POST['previus_page'];
    $new_file_name = $_POST['new_file_name'];

    $_SESSION['last_file_path'] = $ftp_path;

    upload_file_on_serwer($ftp_path, $local_file, $previus_page,$new_file_name);
}

if ($dzialanie == 8) {


    $ftp_url = $_POST['scierzka_do_pliku'];
    $local_file = $_POST['plik_lokalny'];
    $remote_file = $_POST['plik_zdalny'];
    $tresc = $_POST['zawartosc'];

    zapisz_plik_po_edycj($ftp_url, $local_file, $remote_file, $tresc);
}
?>
