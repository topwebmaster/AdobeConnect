<?php
	
	class accdb extends mysqli {

		private $host = 'localhost';
		private $user = 'root';
		private $pass = '',
		private $ddbb = 'bd_adobe',
		protected $ccnn;
		private static $instance = null;

		private function __construct(){
			$this->ccnn = parent::connect($this->host, $this->user, $this->pass, $this->ddbb);

			if($this->connect_errno){
				die('Error # ' . $this->connect_errno . ': ' . $this->connect_error);
			}
		}

		function executeNonQuery( $sql ){
			$query = $this->query($sql);
			return ($query) ? true : false;
		}

		function executeDataSet( $sql ){
			$query = $this->query($sql);
			$tabla = array();
			$data = $query->fetch_assoc();
			do{
				$tabla[] = $data;
			}while($data = $query->fetch_assoc());

			return $tabla;
		}

		public static function get(){
			if(!isset(self::$instance)){
				$c = __CLASS__;
				self::$instance = new $c;
			}

			return self::$instance;
		}

	}