<?php
$header = header('Content-Type: application/vnd.ms-excel');
$header .= header('Content-Disposition: attachment; filename=Grabaciones Moderador'.$_POST['download'].'.xls');
$header .= header('Pragma: no-cache');
$header .= header('Expires: 0');

set_time_limit(0);
error_reporting(0);

require("AdobeConnectClient.class.php");
require('library.php');

$client = new AdobeConnectClient();

$user = $_POST['download'];

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
            $v['domain-name'] . $v['url-path'] . "</a></td></tr>";


    /*     * ***** Comentar esto si se quiere ver solo salas   *** */
    $folder = $v['@attributes']['sco-id'];
    $records = $client->getRecordings($folder);
    $sesiones = $client->getReportSessions($folder);
    
    if (!empty($records['recordings'])) {

        if ($records['recordings']['sco'][0]) {

            foreach ($records['recordings']['sco'] as $value) {
                $ul = "";
                $acl_id = $value['@attributes']['sco-id'];
                $comment = (isset($value['description'])) ? $value['description'] : '';

                $ul .= "<table class='userlist'>" . 
                    "<tr><th width='300'>Usuario</th>" . 
                    "<th width='200'>Hora Inicio</th>" . 
                    "<th width='200'>Hora Fin</th>" . 
                    "<th width='200'>Tiempo de conexi&oacute;n</th></tr>";
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
                            $ul .= "<tr><td>".trim($usersession['principal-name'])."</td>" . 
                                "<td align='center'>".stristr($usersession['date-created'],"T")."</td>" . 
                                "<td align='center'>".stristr($usersession['date-end'],"T")."</td>" . 
                                "<td align='center'>".conversor_segundos((strtotime($usersession['date-end']) - 
                                    strtotime($usersession['date-created'])))."</td></tr>";
                        }
                    }
                }
                $ul .= "</table>";

                $table .= "<tr class='attendance'><td>&nbsp;</td>" .
                        "<td>" . stristr($value['date-begin'], 'T', true) . ' - ' . $value['name'] . "</td>" .
                        "<td valign='top'>" . stristr($value['duration'], '.', true) . "</td>" .
                        "<td valign='top'><a href='https://utp.adobeconnect.com" .
                        $value['url-path'] . "' target='_blank'>https://utp.adobeconnect.com" . $value['url-path'] . "</a></td>" . 
                        "<td valign='top'>".$comment."</td></tr><tr><td>&nbsp;</td><td colspan='4'>".$ul."</td></tr>";

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


            $ul .= "<table class='userlist'>" . 
                "<tr><th width='300'>Usuario</th>" . 
                "<th width='200'>Hora Inicio</th>" . 
                "<th width='200'>Hora Fin</th>" . 
                "<th width='200'>Tiempo de conexi&oacute;n</th></tr>";
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
                        $ul .= "<tr><td>".trim($usersession['principal-name'])."</td>" . 
                            "<td align='center'>".stristr($usersession['date-created'],"T")."</td>" . 
                            "<td align='center'>".stristr($usersession['date-end'],"T")."</td>" . 
                            "<td align='center'>".conversor_segundos((strtotime($usersession['date-end']) - 
                                strtotime($usersession['date-created']))) . "</td></tr>";
                    }
                }
            }
            $ul .= "</table>";

            $table .= "<tr class='attendance'><td>&nbsp;</td>" . 
                    "<td>" . stristr($fecha, 'T', true) . ' - ' . $name . "</td>" . 
                    "<td valign='top'>" . stristr($duration, '.', true) . "</td>" . 
                    "<td valign='top'><a href='https://utp.adobeconnect.com" . 
                    $url . "' target='_blank'>https://utp.adobeconnect.com" . $url . "</a></td>" . 
                    "<td valign='top'>".$comment."</td></tr><tr><td>&nbsp;</td><td colspan='4'>".$ul."</td></tr>";

            $client->setPublicRecordings($acl_id);
        }
    }
    /*     * ****************** */
}
$table .= "</table>";
echo $table;
?>