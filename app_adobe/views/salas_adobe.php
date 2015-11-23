<!DOCTYPE html>
<html>
    <head>
        <title>API Adobe Connect</title>
        <link href="statics/media/css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="statics/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="statics/media/js/utils.js"></script>
    </head>
    <body>
        <div id="container">
            <h1 style="display: inline;">Moderador <?php //echo $user; ?> / Grabaciones</h1>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" id="btnxls" value="ExportarXLS" />
            <span style="float: right;"><a href="index.php">&laquo; Volver</a></span>
            <hr>
            <table border='0' id='tblsalas' cellspacing='0' cellpadding='6'>
                <tr>
                    <th class='salas' width='90'>Salas</th>
                    <th class='salas'>Titulo</th>
                    <th class='salas'>Duraci&oacute;n</th>
                    <th class='salas'>URL Sesion</th>
                    <th class='salas'>Comentario</th>
                </tr>

                <!--salas-->
                <tr>
                    <td class='salas'>{nrosala}</td>
                    <td class='salas'>{name}</td>
                    <td class='salas'>&nbsp;</td>
                    <td><a href='{link}' target='_blank'>{link}</a></td>
                </tr>
                <!--finsalas-->

                <!--grabacion-->
                <tr class='attendance'>
                    <td>&nbsp;</td>
                    <td>{fechanombre}</td>
                    <td valign='top'>{duracion}</td>
                    <td valign='top'><a href='https://utp.adobeconnect.com/{linkrec}' target='_blank'>{linkrec}</a></td> 
                    <td valign='top'>{comentario}</td>
                </tr>
                <!--fingrabacion-->

                <!--asistentes-->
                <tr>
                    <td>&nbsp;</td>
                    <td colspan='4'>
                        <table class='table'>
                            <tr>
                                <th width='300' class='th'>Usuario</th>
                                <th width='200' class='th'>Hora Inicio</th>
                                <th width='200' class='th'>Hora Fin</th>
                                <th width='200' class='th'>Tiempo de conexi&oacute;n</th>
                            </tr>

                            <tr>
                                <td class='td'>{principal_name}</td>
                                <td align='center' class='td'>{fecha_creacion}</td>
                                <td align='center' class='td'>{fecha_fin}</td>
                                <td align='center' class='td'>{tiempo_conexion}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--finasistentes-->
            </table>
        </div>
    </body>
</html>