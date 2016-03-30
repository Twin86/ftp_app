<?php
// put your code here
require_once ("php/uwierzytelnienie.php");
include_once ("php/naglowek.php");
?>
    <body>
        <div id = "id_glowne_okno" class="container sibi_ftp">
            <div class="naglowek row">
                <div class="col-lg-10">
                    <?php
                    // put your code here
                    return_serwer_name();
                    ?>
                    <h2>Edtor plików i treści serwera FTP</h2>
                </div>
                <div class="col-lg-2 logout">
                    <span>wyloguj</span> 
                </div>
            </div>
            <div class="panel_edycji_cms row">
                <div class="interfejs col-lg-12">
                    <div class="url_line row">
                        <div class="col-lg-2 url_zone">
                            <label for="id_url_line">Scierzka folderu FTP </label><input id="id_url_line"  class="form-control" type="text" <?php
                            // put your code here
                            return_folder_path();
                            ?>  /><i class="fa fa-undo" id ="id_back_button"></i>
                        </div>
                        <div class="col-lg-2">
                            <label for="id_old_file_name">Aktualna nazwa pliku </label><input id="id_old_file_name"  class="form-control" type="text"  readonly/>
                        </div>
                        <div class="col-lg-2">
                            <label for="id_new_file_name">Nowa nazwa pliku\folderu </label><input id="id_new_file_name"  class="form-control" type="text"  />
                        </div>
                        <div class="col-lg-3">
                            <form action="php/funkcje.php" enctype="multipart/form-data" method="post">
                                <label for="id_new_file_to_upload">Wskaż plik </label><input id="id_new_file_to_upload" class="btn btn-default" name="file_to_upload" type="file" >
                                <button class="btn btn-default" id="id_upload_file_submit" type="submit" value=""><i class="fa fa-upload"></i></button>
<!--<input class="btn btn-default" id="id_upload_file_submit" type="submit" >-->
                                <!-- przekazywane parametry metodą post do funkcja.php -->
                                <input type="hidden" name="dzialanie" value="7" />
                                <input type="hidden" id="id_url_dla_upload_file" name="scierzka" value="" />
                                <input type="hidden" id="id_page_adress" name="previus_page" value="" />
                                <input type="hidden" id="id_new_file_name_to_upload" name="new_file_name" value="" />
                            </form>   
                        </div>
                        <div class="col-lg-3">
                            <label for="id_zapisz_zmiany">Narzędzia </label><br>
                                                    <input id="id_dodaj_plik" type="button" class="btn btn-default" value="Dodaj"><input id="id_usun_plik" type="button" class="btn btn-default" value="Usun"><input type="button" class="btn btn-default" value="Popraw"> 
                            
                            <input type="button" class="btn btn-default" id="id_zapisz_zmiany" value="Zapisz" disabled />
                        </div>
                    </div>
                </div>
                <div class="shadow"></div>
                <div class="wybor_lokalizacji dane col-lg-12">
                    <div id="id_dir_files" class=" dir_window files col-lg-3">
                        <!-- list file from serwer load !-->
                    </div>
                    <div id="id_load_content_file_from_ftp" class="dir_window col-lg-9">
                       <!-- <textarea id ="testtest" name='myTextArea'></textarea> -->
                        <!-- list file from serwer load !-->
                    </div>
                </div>
            </div>
            <?php
            // put your code here
            include ("php/stopka.php");
            ?>
        </div>
        <div id="js_ukryty">

        </div>
    </body>
</html>
