<?php

class requestController {

    /**
     * Almacena toda la informacion del cliente
     * @var Object
     */
    private $cliente;

    /**
     * Almacena el usuario moderador enviado por post
     * @var String
     */
    private $room;

    public function __construct() {
        session_start();
        set_time_limit(0);
        error_reporting(0);

        require("common/libs/AdobeConnectClient.class.php");
        require("common/tools/library.php");

        $this->cliente = new AdobeConnectClient();

        $this->room = (isset($_POST['moderator'])) ? $_POST['moderator'] : $_SESSION['room'];

        $this->cliente->setUser("moderador" . $this->room);
        $this->cliente->setPassword("utp.moderador" . $this->room);

        $this->cliente->makeAuth();

        $_SESSION['room'] = $this->room;
    }

    function getListSessions() {
        $replaced = "";

        $meetings = $this->cliente->getAllMeetings();

        $template = file_get_contents('app_adobe/views/salas.php');

        $part_of_html = "|<!--salas-->(.*?)<!--finsalas-->|is";

        preg_match($part_of_html, $template, $match);

        $tmp = array("{scoid}", "{date}", "{salas}");

        foreach ($meetings['my-meetings']['meeting'] as $v) {
            $parse = array($v['@attributes']['sco-id'], strtotime($v['date-begin']), $v['name']);

            $replaced .= str_replace($tmp, $parse, $match[0]);
        }

        $tmpview = array("{usuario}", $match[0]);

        $parseview = array($this->room, $replaced);

        $html = str_replace($tmpview, $parseview, $template);

        print $html;
    }

    function getRec() {
        $rec = $_POST['sco_id'];
        $records = $this->cliente->getRecordings($rec);

        $begrecord = ($_POST['inicio'] == "") ? $_POST['tbegin'] : $_POST['inicio'];
        $endrecord = ($_POST['finald'] == "") ? date('Y-m-d') : $_POST['finald'];

        if (!empty($records['recordings'])) {
            if ($records['recordings']['sco'][0]) {
                $data = $records['recordings']['sco'];
            } else {
                $data[] = $records['recordings']['sco'];
            }
        } else {
            $recData->data[]['name'] = 'Sin registros';
            echo json_encode($recData);
            exit;
        }

        foreach ($data as $key => $value) {
            $acl_id = $value['@attributes']['sco-id'];
            $this->cliente->setPublicRecordings($acl_id);
            $date_created = strtotime(date('Y-m-d', strtotime($value['date-created'])));
            if ($date_created >= strtotime($begrecord) && $date_created <= strtotime($endrecord)) {
                $recData->data[] = $data[$key];
            }
        }

        echo json_encode($recData);
    }

    /**
     * Obtiene el listado de usuarios por cada sesion
     * @return json
     */
    function getUserSession() {
        $sco_id = $_GET['sco_id'];
        $date_record_ini = $_GET['date_record_ini'];
        $date_record_fin = $_GET['date_record_fin'];

        $sesiones = $this->cliente->getReportSessions($sco_id);

        foreach ($sesiones['report-meeting-sessions']['row'] as $value) {
            $attendance = $this->cliente->getReportMeetingSessionUser($sco_id, $value['@attributes']['asset-id']);
            $filtrado = unique_multidim_array(
                    $attendance['report-meeting-session-users']['row'], "principal-name", $date_record_ini, $date_record_fin
            );

            if ($filtrado != null) {
                $considerar = $filtrado;
            }
        }

        $json->usuarios = $considerar;

        echo json_encode($json);
    }

}
