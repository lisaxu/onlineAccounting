<?php
//require_once("user.php");

class Database extends PDO{
	
	public function __construct(){
		parent::__construct("sqlite:/s/bach/k/under/lisaxu/public_html/meal/data.db");
	}
	
	
	function initDatabase(){
		//user table
		$command = "CREATE TABLE if not exists user (id INTEGER PRIMARY KEY ASC, username VARCHAR(50) NOT NULL, balance INTEGER NOT NULL)";
		$status = $this->exec($command);
		if($status === FALSE){
			echo 'fail to create user';
			print_r($this->errorInfo());
			return;
		}else{
			//echo 'success in creating table1';
		}
		//sales table
		$command = "CREATE TABLE if not exists sale (id INTEGER PRIMARY KEY, username VARCHAR(50) NOT NULL, date VARCHAR(8), time VARCHAR(6), orders VARCHAR(100), total INTEGER, paid INTEGER, change INTEGER, comment VARCHAR(50))";
		$status = $this->exec($command);
		if($status === FALSE){
			echo 'fail to create sale';
			print_r($this->errorInfo());
			return;
		}else{
			//echo 'success in creating table2';
		}
		
		$command = "CREATE TABLE if not exists userRecord (id INTEGER PRIMARY KEY, username VARCHAR(50) NOT NULL, date VARCHAR(8), time VARCHAR(6), original INTEGER, changed INTEGER, comment VARCHAR(50))";
		$status = $this->exec($command);
		if($status === FALSE){
			echo 'fail to create userRecord';
			print_r($this->errorInfo());
			return;
		}else{
			//echo 'success in creating table2';
		}
		
		//menu table
		$command = "CREATE TABLE if not exists menu (id INTEGER PRIMARY KEY ASC, dishname VARCHAR(20), dishtype INTEGER, price INTEGER, status INTEGER)";
		$status = $this->exec($command);
		if($status === FALSE){
			echo 'fail to create menu';
			print_r($this->errorInfo());
			return;
		}else{
			//echo 'success in creating table3';
		}
		//Dish::initializeMenu();
	}
	
	
	function dropTable($name){
		$command = "DROP TABLE IF EXISTS " . $name;
		$status = $this->exec($command);
		if($status === FALSE){
			echo '<pre class="bg-danger">';
			print_r($this->errorInfo());
			echo '</pre>';
		}else{
			echo '<pre class="bg-success">';
			echo 'Number of rows effected: ' . $status;
			echo '</pre>';
		}
	} 
	
}


?>