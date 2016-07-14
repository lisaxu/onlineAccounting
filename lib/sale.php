<?php
require_once("database.php");

class Sale{
	public static function recordSale($username, $orders, $paid, $change, $comment){
		if(!User::isUser($username)){
			echo "<div class=\"alert alert-danger\"> Error: user <b>$username</b> does not exist. Please register.</div>";
			return;
		}
		$name_lower = strtolower($username);
		$name_lower = preg_replace( '/\s+/', '', $name_lower); 
		$date = date("m/d/y"); 
		$time = date("G:i:s");
		$total = Dish::getTotal($orders);
		$string = "";
		foreach ($orders as $key => $value){
			$string .= "[".$key."]*".$value.", ";
		}
		//insert into sale
		$dbh = new Database();
		$command = "INSERT INTO sale (username, date, time, orders, total, paid, change, comment) VALUES(?,?,?,?,?,?,?,?)";
		$statement = $dbh->prepare($command);
		if(!$statement->execute(array($username, $date, $time, $string, $total, $paid, $change, $comment))){
			echo '<div class="alert alert-danger">';
			print_r($dbh->errorInfo());
			echo '</div>';
		}else{
			echo "<div class=\"alert alert-success\"> Order for <b>$username</b> has been successfully placed! </div>";
		}
		//update user balance
		$balanceChange = $paid - $total - $change;
		if($balanceChange != 0){
			User::updateBalance($username, $balanceChange);
		}

	}

	
	
}