
<?php
 error_reporting(0);
	$host	 = "localhost";
	$user	 = "u4081107_user_apik";
	$pass	 = "R00tAp1k";
	$dabname = "u4081107_apik";
 
	$conn = mysqli_connect($host, $user, $pass) or die('Could not connect to mysql server.' );
	 mysqli_select_db($conn,$dabname) or die('Could not select database.');
	$baseurl="localhost/apik/";
?>