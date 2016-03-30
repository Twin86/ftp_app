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

function func_connect_to_ftp(user, pass, serwer) {

    console.log('enter func_connect_to_ftp(user, pass, serwer)');

    $("#js_ukryty").load('php/funkcje.php', {dzialanie: 1, js_login: user, js_password: pass, js_serwer: serwer}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus === "success") {

            var test = $("#js_ukryty").text();

            if (test.indexOf('Poprawnie') !== -1) {

                $('#js_komunikat').html('<div class="alert alert-success"><strong>Success!</strong> Udane logowanie.</div>');
                
                console.log('proba zmiany strony');
                window.location ='cms.php';

                return true;

            } else {

                $('#js_komunikat').html('<div class="alert alert-warning"><strong>Warning!</strong> Błąd logowania !.</div>');

                return  false;
            }

        }
        if (textStatus === "error") {

            $('#js_komunikat').text('Inny błąd !');
            alert('cos poszło nie tak :( ale nie wiem co :)');

        }
    });
}

function func_dir_files_from_ftp(id_pola, url, id_line, id_tabeli, id_pola_na_plik) {

    console.log('Odpalam func_dir_files_from_ftp(id_pola, url, id_line, id_tabeli,id_pola_na_plik)');


    var wartosc = false;

    $("#" + id_pola + "").load('php/funkcje.php', {dzialanie: 2, scierzka: url}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus === "success") {

            console.log('1 - dodaję funkcjonalnosci func_add_functionality_to_find_records();');

            func_add_functionality_to_find_records(id_tabeli);

            console.log('2 - dodaję funkcjonalnosci  func_add_functionality_next_folder(id_tabeli, id_line, id_pola_na_plik)');

            func_add_functionality_next_folder(id_pola, url, id_line, id_tabeli, id_pola_na_plik);

            wartosc = true;

        } else if (textStatus === "error") {

            console.log('blad');
            alert('cos poszło nie tak :( ale nie wiem co :)');

            wartosc = false;
        }
    });

    return wartosc;
}

function func_add_functionality_to_find_records(id_tabeli) {

    $('#' + id_tabeli + '').find('tr.rekordy').on("click", function () {
        if ($(this).find('td:first-child > input[type=checkbox]').is(':checked')) {
            $(this).addClass('wybrany');
        } else {
            $(this).removeClass('wybrany');
        }

    });
}

function func_add_functionality_next_folder(id_pola, url, id_line, id_tabeli, id_pola_na_plik) {

    $('#' + id_tabeli + '').find('td.plik_from_serwer').on("click", function () {

        console.log('dodałem funkcjonalnosci do komórek tabelki');

        console.log('czy plik zawiera kropke ? : ' + $(this).text().indexOf("."));

        if ($(this).text().indexOf(".") !== -1) {

            var nazwa_pliku = $(this).text();
            //scierzka = scierzka + $(this).text();

            console.log('coś z kropką ! a ścierzka do pliku to' + scierzka);

            //aktualizacja pola aktualna nazwa pliku
            func_update_url_line('id_old_file_name', nazwa_pliku);

            func_load_file_from_ftp(scierzka, id_pola_na_plik, nazwa_pliku);



//func_load_file_from_ftp(scierzka, 'testtest', nazwa_pliku);
        } else {

            scierzka_poprzednia = scierzka;
            scierzka = scierzka + $(this).text() + '/';

            console.log('bez kropki ! a ścierzka do pliku to' + scierzka);

            func_update_url_line(id_line, scierzka);

            func_dir_files_from_ftp(id_pola, scierzka, id_line, id_tabeli, id_pola_na_plik);
        }

    });
}

function func_load_file_from_ftp(url, id_pola_na_plik, nazwa_pliku) {

    //utworzenie text area i nadanie id 
    console.log($("#" + id_pola_na_plik + "").find('#id_div').length);
    if ($("#" + id_pola_na_plik + "").find('#id_div').length > 0)
    {
        $("#id_div").remove();
    }
    var div = document.createElement("div");
    div.setAttribute("id", "id_div");

    var div_button = document.createElement("div");
    div_button.innerHTML = '<div class="col-lg-2"><label for="id_zapisz_zmiany"> </label><input type="button" class="btn btn-default" id="id_zapisz_zmiany" value="Zapisz" disabled /></div>';

    var textArea = document.createElement("textarea");
    textArea.setAttribute("id", "id_text_area");
    textArea.setAttribute("class", "edit_area form-control");

    div.appendChild(textArea);
    div.appendChild(div_button);

    $("#" + id_pola_na_plik + "").append(div);

    $("#" + textArea.id + "").load('php/funkcje.php', {dzialanie: 3, scierzka_do_pliku: url, nazwa: nazwa_pliku}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus == "success") {

            $('#id_zapisz_zmiany').removeAttr("disabled");

        } else if (textStatus == "error") {
            alert('cos poszło nie tak :( ale nie wiem co :)');
        }
    });
}

function func_show_tools() {

    $('#js_ekran_logowania').addClass('ukryj');

    $('#js_zarzadzaj').addClass('pokaz');
}

function func_update_url_line(id_line, url) {

    document.getElementById(id_line).value = url;
}

function func_podaj_nazwe_folder(id) {

    var nazwa = document.getElementById(id).value;

    if (nazwa === '' || nazwa === undefined) {

        alert('Pole nowa nazwa puste folder/plik otrzyma nazwę domyślną !');

        nazwa = 'Nowy folder_' + $("*").find('td:contains("Nowy folder")').length;

    }

    return nazwa;//zwraca string
}
;
function func_folder_do_usuniecia(id) {

    var lista_folderow_do_usuniecia = $('#' + id + '').find('tr.wybrany > td+td');

    //console.log('elementów zaznaczonych do usnięcia :'+lista_folderow_do_usuniecia.length);

    var nazwa_folderu = [];

    for (i = 0; i < lista_folderow_do_usuniecia.length; i++) {
        nazwa_folderu.push(lista_folderow_do_usuniecia.get(i).outerText)
        //console.log(nazwa_folderu[i]);
    }

    return nazwa_folderu;//zwraca tablice z nawami folderow i plikow
}


function func_dodaj_folder(url, nazwa_folderu) {
    if (nazwa_folderu.indexOf('.') === -1) {
        $('#js_ukryty').load('php/funkcje.php', {dzialanie: 4, scierzka_do_pliku: url, nazwa_folderu: nazwa_folderu}, function (responseText, textStatus, XMLHttpRequest) {

            if (textStatus == "success") {

                alert('zakończono dodawanie folderu, wykonaj refresh ');

            } else if (textStatus == "error") {
                alert('cos poszło nie tak :( ale nie wiem co :)');
            }
        });
        return true;
    } else {
        alert('Chcesz dodać plik');
        $('#js_ukryty').load('php/funkcje.php', {dzialanie: 6, scierzka_do_pliku: url, nazwa_folderu: nazwa_folderu}, function (responseText, textStatus, XMLHttpRequest) {

            if (textStatus == "success") {

                alert('zakończono dodawanie pliku, wykonaj refresh ');

            } else if (textStatus == "error") {
                alert('cos poszło nie tak :( ale nie wiem co :)');
            }
        });
        return true;
    }

}
;

function func_usun_folder(url, nazwa_folderow) {

    //nazwa_folderu = 'Nowy folder';//tymczasowo zmieniam nazwę folderu

    $('#js_ukryty').load('php/funkcje.php', {dzialanie: 5, scierzka_do_pliku: url, nazwa_folderow: nazwa_folderow}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus == "success") {

            alert('cos zrobic2');

        } else if (textStatus == "error") {

        }
    });
    return true;
}
;

function func_upload_file_on_serwer(file_local_path, url, remote_file_name) {
    console.log(file_local_path + ' i ' + url);

    $('#js_ukryty').load('php/funkcje.php', {dzialanie: 7, scierzka_do_pliku: url, plik: file_local_path, nazwa_pliku_nas_erwerze: remote_file_name}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus == "success") {

            alert('cos zrobic2');

        } else if (textStatus == "error") {

        }
    });
    return true;

}

function func_zapisz_plik_na_serwerze(url, file_to_save, name_file_to_save, tresc_pliku) {



    if (name_file_to_save === undefined || name_file_to_save === null || name_file_to_save === '') {
        name_file_to_save = file_to_save;
    }

    console.log(url + ' i nazwa pliku ' + file_to_save + ' nowa nazwa ' + name_file_to_save + ' tresc ' + tresc_pliku);

    $('#js_ukryty').load('php/funkcje.php', {dzialanie: 8, scierzka_do_pliku: url, plik_lokalny: file_to_save, plik_zdalny: name_file_to_save, zawartosc: tresc_pliku}, function (responseText, textStatus, XMLHttpRequest) {

        if (textStatus == "success") {

            alert('zapisać plik na serwerze');
            console.log(tresc_pliku);

        } else if (textStatus == "error") {

        }
    });
    return true;

}
