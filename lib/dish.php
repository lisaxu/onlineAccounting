<?php
require_once("database.php");

//SCHEMA id INTEGER PRIMARY KEY ASC, dishname VARCHAR(20), dishtype INTEGER, price INTEGER

class Dish{
	public $dishid;
	public $dishname;
	public $price;
	public $type;
	
	public function __construct($dishid , $dishname, $price, $type){
		$this->dishid = $dishid;
		$this->dishname = $dishname;
		$this->price = $price;
		$this->type = $type;
	}
	
	public static function initializeMenu()	{
		//populate an empty table from set list of dishes
		//REMINDER: this order determines the dish id and their Chinese translation in switch 
		$allDish = array();
		$allDish[] = array("honey pork meal", 1, 10, 1);
		$allDish[] = array("chashao meal", 1, 10, 1);
		$allDish[] = array("beef meal", 1, 10, 1);
		$allDish[] = array("chicken meal", 1, 10, 1);
		
		$allDish[] = array("wings 3", 2, 5, 1);
		$allDish[] = array("wings 7", 2, 10, 1);
		$allDish[] = array("pork bun 4", 2, 10, 1);
		$allDish[] = array("mashed potato", 2, 3, 1);
		$allDish[] = array("pig feet 2", 2, 5, 1);
		$allDish[] = array("pancake", 2, 5, 1);
		$allDish[] = array("taro ball", 2, 7, 1);
		$allDish[] = array("dumplings small", 2, 7, 1);
		$allDish[] = array("dumplings large", 2, 9, 1);
		$allDish[] = array("soup", 2, 3, 1);
		$allDish[] = array("sour noodle", 2, 7, 1);
		$allDish[] = array("beef noodle", 2, 9, 1);
		$allDish[] = array("flapjack 4", 2, 7, 1);
		$allDish[] = array("egg yolk puff 4", 2, 13, 1);
		$allDish[] = array("Dollar 1", 3, 1, 1);
		$allDish[] = array("Dollar 5", 3, 5, 1);
		$allDish[] = array("Dollar 10", 3, 10, 1); 
		
		
		$dbh = new Database();
		
		$command = "DELETE FROM menu";
		$statement = $dbh->prepare($command);
		$statement->execute();
		$command = "INSERT INTO menu (dishname, dishtype, price,status) VALUES(?,?,?,?)";
		$statement = $dbh->prepare($command);
		
		foreach($allDish as $d){
			if(!$statement->execute($d)){
				echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
				echo '</div>';
			return false;
			}
		}
		return true;
		//echo "<div class=\"alert alert-success\"> All dish  has been successfully registered! </div>";
	}
	
	public static function convertName($name){
		switch($name){
			case 1: case "honey pork meal":
				$chinese = "蜜汁卤肉饭";
				break;
			case 2:  case "chashao meal":
				$chinese = "皇品叉烧饭";
				break;
			case 3:  case "beef meal":
				$chinese = "红烧牛肉饭";
				break;
			case 4:  case "chicken meal":
				$chinese = "口水鸡饭";
				break;
			case 5:  case "wings 3":
				$chinese = "鸡翅3个";
				break;
			case 6:  case "wings 7":
				$chinese = "鸡翅7个";
				break;
			case 7:  case "pork bun 4":
				$chinese = "生煎包4个";
				break;
			case 8:  case "mashed potato":
				$chinese = "土豆泥";
				break;
			case 9:  case "pig feet 2":
				$chinese = "酱烤猪脚2个";
				break;
			case 10:  case "pancake":
				$chinese = "手抓饼";
				break;
			case 11:  case "taro ball":
				$chinese = "芋圆";
				break;
			case 12:  case "dumplings small":
				$chinese = "三鲜红油抄手（小）";
				break;
			case 13:  case "dumplings large":
				$chinese = "三鲜红油抄手（大）";
				break;
			case 14:  case "soup":
				$chinese = "骨头汤";
				break;
			case 15:  case "sour noodle":
				$chinese = "酸辣粉";
				break;
			case 16:  case "beef noodle":
				$chinese = "香辣牛肉粉";
				break;
			case 17:  case "flapjack 4":
				$chinese = "辣肉烤饼4个";
				break;
			case 18:  case "egg yolk puff 4":
				$chinese = "蛋黄酥4个";
				break;
			case 19:  case "Dollar 1":
				$chinese = "1美元";
				break;	
			case 20:  case "Dollar 5":
				$chinese = "5美元";
				break;	
			case 21:  case "Dollar 10":
				$chinese = "10美元";
				break;	
			default:
				$chinese = $name;
		}
		return $chinese;
	}
	
	public static function deleteAndInitialize(){
		$dbh = new Database();
		$command = "DELETE FROM menu";
		$result = $dbh->exec($command);
		if($result == 0){
			echo '<div class="alert alert-danger">Error when deleting all table entries </div>';
			return;
		}
		if(Dish::initializeMenu()){
			echo "<div class=\"alert alert-success\"> Menu has been successfully restored to default! </div>";
		}
	}
	public static function isInMenu($name){
		$dbh = new Database();
		$command = "SELECT COUNT(*) FROM menu WHERE dishname = \"$name\"";
		$statement = $dbh->prepare($command);
		$statement-> execute();
		
		$result = $statement->fetch();
		if($result[0] != 0)
			return true;
		else
			return false;		
	}
	
	public static function getPriceById($id){
		//precondition: id mest exist
		$dbh = new Database();
		$command = "SELECT price FROM menu WHERE id = $id";
		$statement = $dbh->prepare($command);
		$statement-> execute();
		
		$result = $statement->fetch();
		return $result[0];	
	}
	
	public static function getPriceByName($name){
		if(!Dish::isInMenu($name)){
			return null;
		}
		
		$dbh = new Database();
		$command = "SELECT price FROM menu WHERE dishname = \"$name\"";
		$statement = $dbh->prepare($command);
		$statement-> execute();
		$result = $statement->fetch();
		return $result[0];
	}
	
		public static function getNameById($id){
		//precondition: id is valid
		$dbh = new Database();
		$command = "SELECT dishname FROM menu WHERE id = $id";
		$statement = $dbh->prepare($command);
		$statement-> execute();
		$result = $statement->fetch();
		return $result[0];
	}
	
	public static function updateDish($id, $dishtype, $price, $status){
		$dbh = new Database();
		$command = "UPDATE menu SET price=$price,dishtype=$dishtype,status= $status WHERE  id= $id";
		$statement = $dbh->prepare($command);
		$result = $statement-> execute();
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}else{
			echo "<div class=\"alert alert-success\"> Dish <b>$id</b>'s price has been successfully updated! </div>";
		}
	}
	
	public static function updatePrice($name, $price){
		$dbh = new Database();
		$command = "UPDATE menu SET price=$price WHERE dishname = \"$name\"";
		$statement = $dbh->prepare($command);
		$result = $statement-> execute();
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}else{
			echo "<div class=\"alert alert-success\"> Dish <b>$name</b>'s price has been successfully updated to $price! </div>";
		}
		//$result = $statement->fetch();
		//return $result[0];
	}
	
	public static function addDish($name, $price, $type, $available){
		$dbh = new Database();
		$command = "INSERT INTO menu (dishname, dishtype, price, status) VALUES(?,?,?,?)";
		$statement = $dbh->prepare($command);
		if(!$statement->execute(array($name, $type, $price, $available))){
			echo '<div class="alert alert-danger">';
			print_r($dbh->errorInfo());
			echo '</div>';
		}else{
			echo "<div class=\"alert alert-success\"> Dish <b>$name</b> has been successfully added to menu! </div>";
		}
		
	}
	
	public static function updateORadd($name, $balance){
		if(menu::isInMenu($name))
			User::updatePrice($name, $balance);
		else
			User::addDish($name, $balance);
	}
	
	public static function deleteById($id){
		$dbh = new Database();
		$command = "DELETE FROM menu WHERE id = $id";
		$result = $dbh->exec($command);
		if($result != 1){
			echo "<div class=\"alert alert-danger\">Error deleting: this dishID may not exist. (deleted $result rows) </div>";
		}else{
			echo "<div class=\"alert alert-success\"> Dish <b>$id</b> has been deleted from menu! </div>";
		}
	}
	
	
	public static function getAllDishes(){
		$dbh = new Database();
		$command = "SELECT * FROM menu ORDER BY id ASC ";
		$result = $dbh->query($command);
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}
		$dish = array();
		foreach($result as $row){
			$dish[] = array($row['id'],$row['dishname'], $row['price'], $row['dishtype'],$row['status'] );
		}
		return $dish;
	}
	
	public static function getAllMeals(){
		$dbh = new Database();
		$command = "SELECT * FROM menu where dishtype=1 ORDER BY id ASC ";
		$result = $dbh->query($command);
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}
		$dish = array();
		foreach($result as $row){
			$dish[] = array($row['id'],$row['dishname'], $row['price'], $row['dishtype'],$row['status'] );
		}
		return $dish;
	}
	
	public static function getAllAppetizers(){
		$dbh = new Database();
		$command = "SELECT * FROM menu where dishtype=2 ORDER BY id ASC ";
		$result = $dbh->query($command);
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}
		$dish = array();
		foreach($result as $row){
			$dish[] = array($row['id'],$row['dishname'], $row['price'], $row['dishtype'],$row['status'] );
		}
		return $dish;
	}
	
	public static function getAllSpeedKey(){
		$dbh = new Database();
		$command = "SELECT * FROM menu where dishtype=3 ORDER BY id ASC ";
		$result = $dbh->query($command);
		if($result === FALSE ){
			echo '<div class="alert alert-danger">';
				print_r($dbh->errorInfo());
			echo '</div>';
			return array();
		}
		$dish = array();
		foreach($result as $row){
			$dish[] = array($row['id'],$row['dishname'], $row['price'], $row['dishtype'],$row['status'] );
		}
		return $dish;
	}

	
	public static function getTotal($info){
		$total =0;
		foreach($info as $id => $qty){
			//$string .= $id."=".$qty."price:".Dish::getPriceById($id).";"; //+ "=" + $qty
			$total += $qty * Dish::getPriceById($id);
		}
		//
		return $total;
	}
}

if(isset ($_POST["functionname"])){
	 $info = $_POST['arguments'];
	echo Dish::getTotal($info);
}
