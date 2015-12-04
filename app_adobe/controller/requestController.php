<?php

class requestController {

    private $cliente;
    private $room;

    public function __construct(){
        session_start();
        set_time_limit(0);
        error_reporting(0);

        require("common/libs/AdobeConnectClient.class.php");

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

    function getRec(){
        $rec = $_POST['sco_id'];
        $records = $this->cliente->getRecordings($rec);

        $begrecord = ($_POST['inicio'] == "") ? $_POST['tbegin'] : $_POST['inicio'];
        $endrecord = ($_POST['finald'] == "") ? date('Y-m-d') : $_POST['finald'];

        $_SESSION['fbegin'] = $begrecord;
        $_SESSION['fend']   = $endrecord;

        if(!empty($records['recordings'])){
            if($records['recordings']['sco'][0]){
                $data = $records['recordings']['sco'];
            }else{
                $data[] = $records['recordings']['sco'];
            }
        }else{
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

    function getUserSession(){
        $sco_id = $_POST['sco_id'];

        echo $sco_id;

        exit;

        $sesiones = $this->cliente->getReportSessions($sco_id);

        foreach ($sesiones['report-meeting-sessions']['row'] as $value) {
            $asset_id = $value['@attributes']['asset-id'];

            $attendance = $this->cliente->getReportMeetingSessionUser($sco_id, $asset_id);
        }
    }

}
