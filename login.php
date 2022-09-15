
<form id="loginform" action="index.php" method="post">
    <p class="animate4 bounceIn"><input type="text" id="username" name="username" placeholder="Username" /></p>
    <p class="animate5 bounceIn"><input type="password" id="password" name="password" placeholder="Password" /></p>
    <p class="animate6 bounceIn"><button name="login" class="btn btn-default btn-block">Masuk</button></p> 
    
</form>
<?php 
if(isset($_POST['login']))
{
    
    	$conn = mysql_connect($host, $user, $pass) or die('Could not connect to mysql server.' );
	 mysql_select_db($dabname,$conn) or die('Could not select database.');
	
$spf=sprintf("Select a.id_user,a.user_name ,c.role_name  from user a inner join user_role b on a.id_user=b.id_user
	inner join role c on b.id_role=c.id_role where user_name='%s' and password='%s'",$_POST['username'],md5($_POST['password']));
	  
	 $rs=mysql_query($spf,$conn); 
	 
	if($rs){$rw=mysql_fetch_array($rs);}else { echo mysql_error;}
	if($rw){
	$rc=mysql_num_rows($rs);
	}else { echo mysql_error;}
	
	if($rc==1)
	{
		$_SESSION['login_hash']=$rw['role_name'];
		$_SESSION['login_user']=$rw['user_name'];
		$_SESSION['login_user_id']=$rw['id_user'];
		echo "<script>window.location='dashboard.php'</script>";
	}else{
	    echo "kgak ada";
	}

}else if(isset($_POST['signup']))
{echo "<script>window.location='signup.php'</script>";
}
?>
