<?php

/*
 * Copyright (C) 2016 Seba
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//deklaracja funkcji 

function polacz_do_ftp($serwer) {

    $array = array(
        "ftp_user" => $_POST['js_login'],
        "ftp_haslo" => $_POST['js_password'],
        "ftp_serwer" => $_POST['js_serwer'],
        "is_login" => FALSE,
        "ftp_link" => '',
    );
// nawiązanie połączenia lub zakończenie działania skryptu

    $ftp_link = ftp_connect($serwer) or die("Nie można połączyć się z " . $array["ftp_serwer"]);

// próba logowania
    if (@ftp_login($ftp_link, $array["ftp_user"], $array["ftp_haslo"])) {
        //echo "Połączony jako ".$array["ftp_user"]."@".$serwer."\n";
        $array["is_login"] = true;
        $array["ftp_link"] = $ftp_link;
        return $array;
    } else {
        //echo "Nie można zalogować się jako ".$array["ftp_user"]."\n";
        return NULL;
    }
    exit();
}

function polacz_do_ftp_user_zalogowany($tablica) {

    $ftp_link = ftp_connect($tablica['ftp_serwer']) or die("Nie można połączyć się z " . $tablica['ftp_serwer']);

    // próba logowania
    if (@ftp_login($ftp_link, $tablica["ftp_user"], $tablica["ftp_haslo"])) {

        return $ftp_link;
    } else {
        echo "Nie można zalogować się jako " . $tablica["ftp_user"] . "\n";
        return NULL;
    }
    exit();
}

function create_folder_on_serwer($place, $file_name) {

    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    ftp_chdir($ftp_link, $place);

    if (ftp_mkdir($ftp_link, $file_name)) {
        echo "Pomyślnie stworzono katalog $file_name\n";
    } else {
        echo "Wystąpiły błędy przy tworzeniu katalogu $file_name\n";
    }

    // zamknięcie połączenia
    ftp_close($ftp_link);
}

function remove_folder_on_serwer($place, $file_name) {

    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    ftp_chdir($ftp_link, $place);

    foreach ($file_name as $value) {
        if (ftp_rmdir($ftp_link, $value)) {
            echo "Pomyślnie usunieto katalog $value\n";
        } else {
            echo "Wystąpiły błędy przy usuwaniu katalogu $value\n";
        }
    }

    // zamknięcie połączenia
    ftp_close($ftp_link);
}

function pobierz_plik($file_url, $file_local_name, $file_remote) {
    $pobrano_plik = false;
    //laczenie do ftp i uchwyt
    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    //zmiana folderu
    ftp_chdir($ftp_link, $file_url);

    // try to download $server_file and save to $local_file
    if (ftp_get($ftp_link, $file_local_name, $file_remote, FTP_BINARY)) {
        $pobrano_plik = true;

    } else {
        echo "There was a problem\n";
    }

    // close the connection
    ftp_close($ftp_link);
    return $pobrano_plik;
}

function add_file($place, $file_name) {

    // open some file for w
    $fp = fopen($file_name, 'w');

    // set up basic connection
    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);

    //zmiana folderu
    ftp_chdir($ftp_link, $place);

    // try to upload $file
    if (ftp_fput($ftp_link, $file_name, $fp, FTP_ASCII)) {
        echo "Successfully uploaded $file_name\n";
    } else {
        echo "There was a problem while uploading $file_name\n";
    }

    // close the connection and the file handler
    ftp_close($ftp_link);
    fclose($fp);
}

function upload_file_on_serwer($ftp_path, $local_file, $previus_page, $new_file_name) {

    // set up basic connection
    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    
    if ($new_file_name != '' || $new_file_name != NULL) {
        $local_file['name'] = $new_file_name;
    }

    //przesłanie pliku na serwer
    if (ftp_put($ftp_link, $ftp_path . $local_file['name'], $local_file['tmp_name'], FTP_BINARY)) {
        //echo "successfully uploaded " . $local_file['name'] . "\n";
    } else {
        echo "There was a problem while uploading " . $local_file['name'] . "\n";
    }

    ftp_close($ftp_link);

    $_SESSION['last_file_path'] = $ftp_path;

    //otwarcie poprzedniej strony
    //header('Location: ' . $previus_page . '');
    header('Location: ' . $previus_page . '');
}

function upload_file_on_serwer_after_edit($ftp_path, $file) {
    //znacznik czy udało się przesłać
    $przesylanie = false;

    // set up basic connection
    $ftp_link = polacz_do_ftp_user_zalogowany($_SESSION['tabel']);
    //zmiana folderu na ftp
    ftp_chdir($ftp_link, $ftp_path);

    if (ftp_put($ftp_link, $file, $file, FTP_BINARY)) {
        echo "successfully uploaded " . $file . "\n";
        $przesylanie = true;
    } else {
        echo "There was a problem while uploading " . $file . "\n";
    }

    ftp_close($ftp_link);
    return $przesylanie;
}

function zapisz_plik_po_edycj($ftp_url, $local_file, $remote_file, $tresc) {
    //otwarcie pliku do zapisu
    $plik = fopen($remote_file, 'w') or die('Wystąpił problem z zapisem pliku !');
    //echo $tresc;
    $tresc = stripslashes($tresc);
    //zapis do pliku
    if (fwrite($plik, $tresc)  == FALSE) {
        echo "Nie mogę zapisać do pliku ($remote_file)";
        exit;
    } else {
       echo 'zapis udany !';
    }
    echo 'Debugowanie!';
    
    //zamkniecie pliku
    fclose($plik);

    //przesłanie pliku na serwer i sprawdzenei czy sie udalo
    if (upload_file_on_serwer_after_edit($ftp_url, $remote_file)) {
        //usuniecie pliku po zapisie
        unlink($local_file);
    } else {
        echo 'Nie usuwam pliku bo nie udało się go przesłać';
    }
}
