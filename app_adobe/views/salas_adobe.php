<?php
set_time_limit(0);
error_reporting(0);
require("common/libs/AdobeConnectClient.class.php");
require('common/tools/library.php');

$client = new AdobeConnectClient();

$user = $room;

$table = "<table border='0' id='tblsalas' cellspacing='0' cellpadding='6'>" .
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

foreach ($mettings['my-meetings']['meeting'] as $v) {
    $count++;

    if ($begrecord == "") {
        $begrecord = $v['date-begin'];
    }

    if ($endrecord == "") {
        $endrecord = date('Y-m-d');
    }

    $table .= "<tr><td class='salas'>Sala: " . $count . "</td>" .
            "<td class='salas'>" . $v['name'] . "</td>" .
            "<td class='salas'>&nbsp;</td>" .
            "<td><a href='https://" . $v['domain-name'] . $v['url-path'] . "' target='_blank'>https://" .
            $v['domain-name'] . $v['url-path'] . "</a></td></tr>";


    /*     * ***** Comentar esto si se quiere ver solo salas   *** */
    $folder = $v['@attributes']['sco-id'];
    $records = $client->getRecordings($folder);
    $sesiones = $client->getReportSessions($folder);
    /*echo "<pre>";
    print_r($records);
    continue;*/
    if (!empty($records['recordings'])) {

        if ($records['recordings']['sco'][0]) {

            foreach ($records['recordings']['sco'] as $value) {
                $ul = "";
                $acl_id = $value['@attributes']['sco-id'];
                $comment = (isset($value['description'])) ? $value['description'] : '';

                $date_created = strtotime(date('Y-m-d', strtotime($value['date-created'])));

                if ($date_created >= strtotime($begrecord) && $date_created <= strtotime($endrecord)) {

                    /*                     * ** Usuarios por grabacion * */
                    $ul .= "<table class='table'>" .
                            "<tr><th width='300' class='th'>Usuario</th>" .
                            "<th width='200' class='th'>Tiempo de conexi&oacute;n</th></tr>";
                    foreach ($sesiones['report-meeting-sessions']['row'] as $v) {
                        $brec = strtotime($value['date-begin']);
                        $erec = strtotime($value['date-end']);

                        $bses = strtotime($v['date-created']);
                        $eses = strtotime($v['date-end']);

                        if ($brec >= $bses && $erec <= $eses) {

                            $asset_id = $v['@attributes']['asset-id'];
                            $attendance = $client->getReportMeetingSessionUser($folder, $asset_id);

                            $onside = unique_multidim_array(
                                $attendance['report-meeting-session-users']['row'], 'principal-name', $value['date-created'], $value['date-end']
                            );

                            foreach ($onside as $key => $usersession) {
                                if(strlen($key) == 1)
                                    continue;
                                
                                $ul .= "<tr><td class='td'>" . $key . "</td>" .
                                        "<td align='center' class='td'>" . $usersession['TCon'] . "</td></tr>";
                            }
                        }
                    }
                    $ul .= "</table>";

                    /*                     * ************************** */

                    $table .= "<tr class='attendance'><td>&nbsp;</td>" .
                            "<td>" . stristr($value['date-begin'], 'T', true) . ' - ' . $value['name'] . "</td>" .
                            "<td valign='top'>" . stristr($value['duration'], '.', true) . "</td>" .
                            "<td valign='top'><a href='https://utp.adobeconnect.com" .
                            $value['url-path'] . "' target='_blank'>https://utp.adobeconnect.com" . $value['url-path'] . "</a></td>" .
                            "<td valign='top'>" . $comment . "</td></tr><tr><td>&nbsp;</td><td colspan='4'>" . $ul . "</td></tr>";

                    $client->setPublicRecordings($acl_id);
                }
            }
        } else {
            $ul = "";
            $name = $records['recordings']['sco']['name'];
            $url = $records['recordings']['sco']['url-path'];
            $acl_id = $records['recordings']['sco']['@attributes']['sco-id'];
            $duration = $records['recordings']['sco']['duration'];
            $fecha = $records['recordings']['sco']['date-begin'];
            $creation = $records['recordings']['sco']['date-created'];
            $end = $records['recordings']['sco']['date-end'];
            $comment = (isset($records['recordings']['sco']['description'])) ? $records['recordings']['sco']['description'] : '';

            $date_created = strtotime(date('Y-m-d', strtotime($creation)));

            if (strtotime($date_created) >= strtotime($begrecord) && strtotime($date_created) <= strtotime($endrecord)) {

                /*                 * ** Usuarios por grabacion * */
                $ul .= "<table class='table'>" .
                        "<tr><th width='300' class='th'>Usuario</th>" .
                        "<th width='200' class='th'>Tiempo de conexi&oacute;n</th></tr>";
                foreach ($sesiones['report-meeting-sessions']['row'] as $v) {
                    $brec = strtotime($records['recordings']['sco']['date-begin']);
                    $erec = strtotime($records['recordings']['sco']['date-end']);

                    $bses = strtotime($v['date-created']);
                    $eses = strtotime($v['date-end']);

                    if ($brec >= $bses && $erec <= $eses) {
                        $asset_id = $v['@attributes']['asset-id'];
                        $attendance = $client->getReportMeetingSessionUser($folder, $asset_id);
                        $onside = unique_multidim_array(
                                $attendance['report-meeting-session-users']['row'], "principal-name", $creation, $end
                        );

                        foreach ($onside as $key => $usersession) {
                            if(strlen($key) == 1)
                                continue;

                            $ul .= "<tr><td class='td'>" . $key . "</td>" .
                                    "<td align='center' class='td'>" . $usersession['TCon'] . "</td></tr>";
                        }
                    }
                }
                $ul .= "</table>";

                /*                 * **************************** */

                $table .= "<tr class='attendance'><td>&nbsp;</td>" .
                        "<td>" . stristr($fecha, 'T', true) . ' - ' . $name . "</td>" .
                        "<td valign='top'>" . stristr($duration, '.', true) . "</td>" .
                        "<td valign='top'><a href='https://utp.adobeconnect.com" .
                        $url . "' target='_blank'>https://utp.adobeconnect.com" . $url . "</a></td>" .
                        "<td valign='top'>" . $comment . "</td></tr><tr><td>&nbsp;</td><td colspan='4'>" . $ul . "</td></tr>";

                $client->setPublicRecordings($acl_id);
            }
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
        <link href="statics/media/css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="statics/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="statics/media/js/utils.js"></script>
    </head>
    <body>
        <div id="container">
            <h1 style="display: inline;">Moderador <?php echo $user; ?> / Grabaciones</h1>&nbsp;&nbsp;&nbsp;&nbsp;
            <!--<input type="button" id="btnxls" value="ExportarXLS" />-->
            <input type="hidden" name="download" value="<?php echo $user; ?>">
            <span style="float: right;"><a href="index.php">&laquo; Volver</a></span>
            <hr>
            <?php
                print $table;
            ?>
        </div>
    </body>
</html>