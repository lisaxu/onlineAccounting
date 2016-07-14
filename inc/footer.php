</div>  <!--end class "content" -->
<footer>
	<div>
		<?php
			$usrIp = $_SERVER['REMOTE_ADDR'];
			$host = $_SERVER['SERVER_NAME'];
			echo "Connected IP: $usrIp <br>";
			echo "Current Date: ".date( "D, j M. Y g:i a", time());
			echo "<br>Host: $host";
		?>
	</div>
</footer>
</div>
</body>
</html>