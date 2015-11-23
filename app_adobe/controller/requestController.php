<?php
class requestController{

	function getList(){
		$room = $_POST['moderator'];
		$begrecord = str_replace("/", "-", $_POST['textdesde']);
		$endrecord = str_replace("/", "-", $_POST['texthasta']);

		include('app_adobe/views/salas_adobe.php');
	}
}