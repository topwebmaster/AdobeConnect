<!DOCTYPE html>
<html>
    <head>
        <title>API Adobe Connect</title>
        <style type="text/css">
            body{
                background: #f4f4f4;
                font-family: "Arial", sans-serif;
                font-size: 12px;
                color: #666;
            }

            #container{
                width: 1200px;
                margin: auto;
                overflow: auto;
                background: #fff;
                padding: 15px;
            }
        </style>
        <script type="text/javascript" src="jquery-2.1.4.js"></script>
        <script type="text/javascript" src="jquery.ctools.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#btn_send').click(function () {
                    $('#select_salas').validate({
                        required: true,
                        message: {
                            required: 'Requerido'
                        }
                    });
                    if ($.isValid) {
                        $('#btn_send').attr('disabled', true).val('Consultando, Espere por favor...');
                        var user = $('#select_salas').val();
                        document.location.href = "salas_adobe.php?user=" + user;
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id="container">
            <h1>Adobe connect</h1>
            <label for="select_salas">Seleccione una sala: </label>
            <select id="select_salas">
                <option value="0">:::: seleccione ::::</option>
                <?php
                for ($i = 1; $i <= 14; $i++) {
                    echo "<option value='" . $i . "'>Moderador " . $i . "</option>";
                }
                ?>
            </select>
            <input type="button" id="btn_send" value="Consultar" />
            <hr>
        </div>
    </body>
</html>