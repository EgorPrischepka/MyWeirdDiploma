<?php
session_start();
$mysqli = new mysqli(localhost, 'u1683338_default', 'VUj2k6xnhS8ST5Te', 'u1683338_default');
if ($mysqli -> connect_error) //проверка подключения к базе даных 
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{
	$dif = array('cost'=>8);
	if(isset($_POST['oldp']) && isset($_POST['newp']) && isset($_SESSION['id']) && isset($_SESSION['name']) && isset($_SESSION['status']))
	{
		if((strlen($_POST["newp"])<20 && strlen($_POST["newp"])>5) && (preg_match("/[a-z0-9]/i", $_POST["newp"])))
		{
			$checkpass = $mysqli->query("SELECT * FROM `users` WHERE id = '".$_SESSION['id']."'");
			if(mysqli_num_rows($checkpass)==1)
			{
				$checked = $checkpass->fetch_assoc();
				if(password_verify($_POST['oldp'], $checked['password']))
				{
					$changepass = $mysqli->query("UPDATE `users` SET password = '".password_hash($_POST['newp'], PASSWORD_BCRYPT, $dif)."' WHERE id = '".$_SESSION['id']."'");
					echo 'Password has been changed';
				}
				else echo 'Wrong Password';
			}
			else echo 'Wrong Password';
		}
		else echo '<a href="index.php?error=wrongpassword">Password doesn\'t meet requirements</a> ';
	}
	else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
}
?>