/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//globalne

var scierzka = '/';
var scierzka_poprzednia = '';


(function () {

    window.onload = function () {
                
        console.log('strona wczytana');

        var aktualna_strona = window.location.href ;
        
        if (aktualna_strona.indexOf('cms.php')===-1){
            var guzik = document.getElementById('id_lacz_z_ftp');

            guzik.onclick = function () {
                console.log('you push login button !');
                func_connect_to_ftp(document.getElementById('js_login').value, document.getElementById('js_password').value, document.getElementById('js_serwer').value);
            };

        } else {

            var guzik_dodaj = document.getElementById('id_dodaj_plik');
            var guzik_usun = document.getElementById('id_usun_plik');
            var pole_scierzki = document.getElementById('id_url_line');
            var pole_a_nazwa = document.getElementById('id_old_file_name');
            var pole_n_nazwa = document.getElementById('id_new_file_name');
            var guzik_przeslij_plik = document.getElementById('id_upload_file_submit');
            var guzik_wskaz_plik = document.getElementById('id_new_file_to_upload');
            var guzik_zapisz_plik = document.getElementById('id_zapisz_zmiany');
            var guzik_back_folder = document.getElementById('id_back_button');

            //func_update_url_line('id_url_line', scierzka);

            guzik_dodaj.onclick = function () {
                alert('Dodaj folder lub plik');
                func_dodaj_folder(pole_scierzki.value, func_podaj_nazwe_folder('id_new_file_name'));
                func_dir_files_from_ftp('id_dir_files', scierzka, 'id_url_line', 'id_dane_dir', 'id_load_content_file_from_ftp')

            };

            guzik_usun.onclick = function () {

                alert('Usuń folder');
                console.log('pole_scierzki.value =' + pole_scierzki.value);
                func_usun_folder(pole_scierzki.value, func_folder_do_usuniecia('id_glowne_okno'));
                func_dir_files_from_ftp('id_dir_files', scierzka, 'id_url_line', 'id_dane_dir', 'id_load_content_file_from_ftp')

            };

            guzik_wskaz_plik.onclick = function () {
                //pobranie ścirzki dla wgrywanego pliku
                document.getElementById('id_url_dla_upload_file').value = document.getElementById('id_url_line').value;

                //pobranie aktualnej strony 
                document.getElementById('id_page_adress').value = window.location.href;

                //pobranie nowej nazwy pliku
                document.getElementById('id_new_file_name_to_upload').value = pole_n_nazwa.value;
                //aktualizacja pola aktualnej nazwy pliku
                pole_a_nazwa.value = document.getElementById('id_new_file_to_upload').value;
            };

            guzik_zapisz_plik.onclick = function () {
                console.log('Guzik Zapisz wciśnięty !');

                func_zapisz_plik_na_serwerze(scierzka, pole_a_nazwa.value, pole_n_nazwa.value, document.getElementById('id_text_area').value);
            };

            guzik_back_folder.onclick = function () {
                console.log('Guzik back wciśnięty');
                pole_scierzki.value = scierzka_poprzednia;

                func_dir_files_from_ftp('id_dir_files', scierzka = pole_scierzki.value, 'id_url_line', 'id_dane_dir', 'id_load_content_file_from_ftp');

            };

            pole_scierzki.onkeyup = function (event) {
                if (event.keyCode === 13) {

                    scierzka = pole_scierzki.value;

                    func_dir_files_from_ftp('id_dir_files', scierzka, 'id_url_line', 'id_dane_dir', 'id_load_content_file_from_ftp');

                }
            };

            //  function func_dir_files_from_ftp(id_pola, url, id_line, id_tabeli,id_pola_na_plik) {
            func_dir_files_from_ftp('id_dir_files', scierzka, 'id_url_line', 'id_dane_dir', 'id_load_content_file_from_ftp');


        }
    };
}());

