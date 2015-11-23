<?php

class requestController {

    function getList() {

        set_time_limit(0);
        //error_reporting(0);

        $template = file_get_contents('app_adobe/views/salas_adobe.php');

        $salas_regexp   = "/<!--salas-->(.|\n){1,}<!--finsalas-->/";
        $records_regexp = "/<!--grabacion-->(.|\n){1,}<!--fingrabacion-->/";
        $user_regexp    = "/<!--asistentes-->(.|\n){1,}<!--finasistentes-->/";



        preg_match($salas_regexp, $template, $salasMatch);
        if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
            print 'Backtrack limit was exhausted!';
        }
        //preg_match($records_regexp, $template, $recordsMatch);
        //preg_match($user_regexp, $template, $userMatch);

        require("common/libs/AdobeConnectClient.class.php");
        require('common/tools/library.php');

        $room = $_POST['moderator'];
        $begrecord = str_replace("/", "-", $_POST['textdesde']);
        $endrecord = str_replace("/", "-", $_POST['texthasta']);

        $client = new AdobeConnectClient();

        $client->setUser('moderador' . $room);
        $client->setPassword('utp.moderador' . $room);

        $client->makeAuth();
        $mettings = $client->getAllMeetings();
        $count = 0;

        foreach ($mettings['my-mettings']['metting'] as $v) {
            $count++;

            if ($begrecord == "") {
                $begrecord = $v['date-begin'];
            }

            if ($endrecord == "") {
                $endrecord = date('Y-m-d');
            }

            $stringTemp = array("{nrosala}", "{name}", "{link}");
            $stringReal = array($count, $v['name'], $v['domain-name'] . $v['url-path']);

            $replaceTemplate = str_replace($stringTemp, $stringReal, $salasMatch[0]);
        }

        print $replaceTemplate;

        //include('app_adobe/views/salas_adobe.php');
    }

}
