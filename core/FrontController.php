<?php

class FrontController{

	public static function init(){
		$req = (isset($_REQUEST['req'])) ? $_REQUEST['req'] . "Controller" : "indexController";
		$mod = (isset($_REQUEST['mod'])) ? $_REQUEST['mod'] : "index";

		$file = "app_adobe/controller/" . $req . ".php";

		(is_file($file)) ? require($file) : die('Error 404');

		$controller = new $req();
		$controller->$mod();
	}

}