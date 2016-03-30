
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->


<?php
// put your code here
include_once ("php/naglowek.php");
?>
<body>
    <div class="container  col-lg-6 col-md-offset-3 login_page">
        <div class="naglowek row">
            <h1><strong>SIBI</strong>{FTP menadżer}</h1>
            <h2>Edtor plików i treści serwera FTP</h2>
        </div>
        <div  class="logowanie row">
            <form class="" role="form">
                <div class="form-group">
                    <label for="js_serwer">Serwer:</label>
                    <input type="text" class="form-control" id="js_serwer"/>
                </div>
                <div class="form-group">
                    <label for="js_login">Login:</label>
                    <input type="text" class="form-control" id="js_login"/>
                </div>
                <div class="form-group">
                    <label for="js_password">Password:</label>
                    <input type="password" class="form-control" id="js_password"/>
                </div>
                <button type="button" class="btn btn-default" id="id_lacz_z_ftp">Submit</button>
            </form>

        </div>
        <div  class="row" id="js_komunikat">

        </div>

        <div  class="row" id="js_ukryty">

        </div>
        <?php
        // put your code here
        include ("php/stopka.php");
        ?>
    </div>
</body>
</html>
