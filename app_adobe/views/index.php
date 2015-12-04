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
        <script type="text/javascript" src="statics/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="statics/js/jquery.ctools.js"></script>
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
                        document.frmsalas.submit();
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id="container">
            <form name="frmsalas" id="frmsalas" method="post" action="index.php">
                <h1>Adobe connect</h1>
                <input type="hidden" name="req" value="request" />
                <input type="hidden" name="mod" value="getListSessions" />
                <table>
                    <tr>
                        <td><label for="select_salas">Seleccione una sala: </label></td>
                        <td>
                            <select id="select_salas" name="moderator">
                                <option value="0">:::: seleccione ::::</option>
                                <?php
                                for ($i = 1; $i <= 14; $i++) {
                                    echo "<option value='" . $i . "'>Moderador " . $i . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="button" id="btn_send" value="Consultar" />
            </form>
            <hr>
        </div>
    </body>
</html>