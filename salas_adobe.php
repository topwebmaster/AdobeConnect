<?php
set_time_limit(0);
error_reporting(0);
include("AdobeConnectClient.class.php");

$client = new AdobeConnectClient();

$user = $_GET['user'];

$table = "<table border='0' cellspacing='0' cellpadding='6'>" .
        "<tr><th class='salas' width='90'>Salas</th>" .
        "<th class='salas'>Titulo</th>" .
        "<th class='salas'>Duraci&oacute;n</th>" .
        "<th class='salas'>URL Sesion</th>" . 
        "<th class='salas'>Comentario</th></tr>";

$client->setUser('moderador' . $user);
$client->setPassword('utp.moderador' . $user);

$client->makeAuth();
//echo "<pre>";
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
    /* print_r($records);
      exit; */
    if (!empty($records['recordings'])) {

        if ($records['recordings']['sco'][0]) {

            foreach ($records['recordings']['sco'] as $key => $value) {

                $acl_id = $value['@attributes']['sco-id'];
                $comment = (isset($value['description'])) ? $value['description'] : '';
                $table .= "<tr><td>&nbsp;</td>" .
                        "<td>" . stristr($value['date-begin'], 'T', true) . ' - ' . $value['name'] . "</td>" .
                        "<td>" . stristr($value['duration'], '.', true) . "</td>" .
                        "<td><a href='https://utp.adobeconnect.com" .
                        $value['url-path'] . "' target='_blank'>https://utp.adobeconnect.com" . $value['url-path'] . "</a></td>" . 
                        "<td>".$comment."</td></tr><th>";

                $client->setPublicRecordings($acl_id);
            }
        } else {
            $name = $records['recordings']['sco']['name'];
            $url = $records['recordings']['sco']['url-path'];
            $acl_id = $records['recordings']['sco']['@attributes']['sco-id'];
            $duration = $records['recordings']['sco']['duration'];
            $fecha = $records['recordings']['sco']['date-begin'];
            $comment = (isset($records['recordings']['sco']['description'])) ? $records['recordings']['sco']['description'] : '';

            $table .= "<tr><td>&nbsp;</td>" .
                    "<td>" . stristr($fecha, 'T', true) . ' - ' . $name . "</td>" .
                    "<td>" . stristr($duration, '.', true) . "</td>" .
                    "<td><a href='https://utp.adobeconnect.com" .
                    $url . "' target='_blank'>https://utp.adobeconnect.com" . $url . "</a></td>" . 
                    "<td>".$comment."</td></tr><th>";

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
    </head>
    <body>
        <div id="container">
            <h1 style="display: inline;">Moderador <?php echo $user; ?> / Grabaciones</h1>
            <span style="float: right;"><a href="index.php"><< Volver</a></span>
            <hr>
            <?php
                print $table;
            ?>
        </div>
    </body>
</html>