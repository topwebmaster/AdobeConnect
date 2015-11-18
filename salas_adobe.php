<?php
/*header('Content-Type: application/vnd.ms-excel');
header('Pragma: no-cache');
header('Content-Disposition: attachment; filename=prueba.xls');
header('Expires: 0');*/

set_time_limit(0);
error_reporting(0);
require("AdobeConnectClient.class.php");
require('library.php');

$client = new AdobeConnectClient();

$user = $_POST['moderator'];

$table = "<table border='0' cellspacing='0' cellpadding='6'>" .
        "<tr><th class='salas' width='90'>Salas</th>" .
        "<th class='salas'>Titulo</th>" .
        "<th class='salas'>Duraci&oacute;n</th>" .
        "<th class='salas'>URL Sesion</th>" . 
        "<th class='salas'>Comentario</th></tr>";

$client->setUser('moderador' . $user);
$client->setPassword('utp.moderador' . $user);

$client->makeAuth();
$mettings = $client->getAllMeetings();
$count = 0;
foreach ($mettings['my-meetings']['meeting'] as $k => $v) {
    $count++;
    $table .= "<tr><td class='salas'>Sala: " . $count . "</td>" .
            "<td class='salas'>" . $v['name'] . "</td>" .
            "<td class='salas'>&nbsp;</td>" .
            "<td><a href='https://" . $v['domain-name'] . $v['url-path'] . "' target='_blank'>https://" .
            $v['domain-name'] . $v['url-path'] . "</a></td></tr><th>";


    /*     * ***** Comentar esto si se quiere ver solo salas   *** */
    $folder = $v['@attributes']['sco-id'];
    $records = $client->getRecordings($folder);
    $sesiones = $client->getReportSessions($folder);
    
    //echo "<pre>";
    //print_r($records);
    /*exit;*/
    if (!empty($records['recordings'])) {

        if ($records['recordings']['sco'][0]) {

            foreach ($records['recordings']['sco'] as $value) {
                $ul = "";
                $acl_id = $value['@attributes']['sco-id'];
                $comment = (isset($value['description'])) ? $value['description'] : '';


                $ul .= "<ul>";
                foreach ($sesiones['report-meeting-sessions']['row'] as $v) {
                    $brec = strtotime($value['date-begin']);
                    $erec = strtotime($value['date-end']);

                    $bses = strtotime($v['date-created']);
                    $eses = strtotime($v['date-end']);

                    if($brec >= $bses && $erec <= $eses){
                        $asset_id = $v['@attributes']['asset-id'];
                        $attendance = $client->getReportMeetingSessionUser($folder, $asset_id);

                        $onside = unique_multidim_array($attendance['report-meeting-session-users']['row'], "principal-name");
                        foreach ($onside as $usersession) {
                            $ul .= "<li>".trim($usersession['principal-name'])."</li>";
                        }
                    }
                }
                $ul .= "</ul>";


                $table .= "<tr><td>&nbsp;</td>" .
                        "<td class='attendance'>" . stristr($value['date-begin'], 'T', true) . ' - ' . $value['name'] . $ul . "</td>" .
                        "<td valign='top'>" . stristr($value['duration'], '.', true) . "</td>" .
                        "<td valign='top'><a href='https://utp.adobeconnect.com" .
                        $value['url-path'] . "' target='_blank'>https://utp.adobeconnect.com" . $value['url-path'] . "</a></td>" . 
                        "<td valign='top'>".$comment."</td></tr><th>";

                $client->setPublicRecordings($acl_id);
            }
        } else {
            $ul = "";
            $name = $records['recordings']['sco']['name'];
            $url = $records['recordings']['sco']['url-path'];
            $acl_id = $records['recordings']['sco']['@attributes']['sco-id'];
            $duration = $records['recordings']['sco']['duration'];
            $fecha = $records['recordings']['sco']['date-begin'];
            $comment = (isset($records['recordings']['sco']['description'])) ? $records['recordings']['sco']['description'] : '';


            $ul .= "<ul>";
            foreach ($sesiones['report-meeting-sessions']['row'] as $v) {
                $brec = strtotime($records['recordings']['sco']['date-begin']);
                $erec = strtotime($records['recordings']['sco']['date-end']);

                $bses = strtotime($v['date-created']);
                $eses = strtotime($v['date-end']);

                if($brec >= $bses && $erec <= $eses){
                    $asset_id = $v['@attributes']['asset-id'];
                    $attendance = $client->getReportMeetingSessionUser($folder, $asset_id);

                    $onside = unique_multidim_array($attendance['report-meeting-session-users']['row'], "principal-name");
                    foreach ($onside as $usersession) {
                        $ul .= "<li>".trim($usersession['principal-name'])."</li>";
                    }
                }
            }
            $ul .= "</ul>";


            $table .= "<tr><td>&nbsp;</td>" . 
                    "<td class='attendance'>" . stristr($fecha, 'T', true) . ' - ' . $name . $ul . "</td>" . 
                    "<td valign='top'>" . stristr($duration, '.', true) . "</td>" . 
                    "<td valign='top'><a href='https://utp.adobeconnect.com" . 
                    $url . "' target='_blank'>https://utp.adobeconnect.com" . $url . "</a></td>" . 
                    "<td valign='top'>".$comment."</td></tr><th>";

            $client->setPublicRecordings($acl_id);
        }
    }
    /*     * ****************** */
}
$table .= "</table>";
?>
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
        </style>
        <script type="text/javascript" src="jquery-2.1.4.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.attendance ul').css({display:'none'});
                $('.attendance').css({cursor:'pointer'});
                $('.attendance').each(function(){
                    $(this).click(function(){
                        $(this).find('ul').toggle('slow', 'linear');
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="container">
            <h1 style="display: inline;">Moderador <?php echo $user; ?> / Grabaciones</h1>
            <span style="float: right;"><a href="index.php">&laquo; Volver</a></span>
            <hr>
            <?php
                print $table;
            ?>
        </div>
    </body>
</html>