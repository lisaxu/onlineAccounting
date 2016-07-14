
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- self-defined javascript-->
	<script src="lib/dynamic.js"></script>
	<link href="style.css" type="text/css" rel="stylesheet">
	<title>online accountant</title>
</head>
<body>
	<div class = "container">
		<header>
			<h1>Online Accounting System (Alpha Ver.)</h1>
			<h3><?php echo $subtitle ?></h3>
			<nav class = "nav">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="menu.php">Menu</a></li>
					<li><a href="userManage.php">User Management</a></li>
					<!-- <li><a href="stat.php">Statistics</a></li>
					<li><a href="export.php">Export Data</a></li> -->
					<li><a href="login.php">Log <?php 
					if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1){
						echo "Out";
					}else{
						echo "In";
					}
					?></a></li>
				</ul>
			</nav>
		</header>
		<div class = "content">