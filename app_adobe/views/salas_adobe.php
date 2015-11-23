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
                width: 1300px;
                margin: auto;
                overflow: auto;
                background: #fff;
                padding: 15px;
            }
            .salas{
                font-size: 14px;
                font-weight: bold;
            }

            .table {     
                font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
                font-size: 12px;
                margin: 45px;
                width: 800px; 
                /*text-align: left;*/
                border-collapse: collapse; 
            }

            .th {     
                font-size: 13px;
                font-weight: normal;
                padding: 8px;     
                background: #b9c9fe;
                border-top: 4px solid #aabcfe;    
                border-bottom: 1px solid #fff; 
                color: #039; 
            }

            .td {    
                padding: 8px;     
                background: #e8edff;    
                border-bottom: 1px solid #fff;
                color: #669;    
                border-top: 1px solid transparent; 
            }

            .tr:hover .td { 
                background: #d0dafd; color: #339; 
            }
        </style>
        <script type="text/javascript" src="statics/js/jquery-2.1.4.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                //$('.table').css({display:'none'});
                $('.attendance').css({cursor:'pointer'});
                $('.attendance').each(function(){
                    $(this).click(function(){
                        $(this).next().find('table').toggle('slow', 'linear');
                    });
                });

                var btn = document.getElementById('btnxls');
                btn.addEventListener("click", function(){
                    var html = document.getElementById('tblsalas').outerHTML;
                    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
                });
            });

            
        </script>
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
                    <td>{fecha-nombre}</td>
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