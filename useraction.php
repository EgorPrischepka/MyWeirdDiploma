<?php
session_start();
$mysqli = new mysqli(localhost, 'u1683338_default', 'VUj2k6xnhS8ST5Te', 'u1683338_default');
if ($mysqli -> connect_error)
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{
	if($_SESSION['status']=='admin' && isset($_GET['id']) && isset($_GET['act']))
	{
		$useract = $mysqli->query("SELECT * FROM `users` WHERE id = '".$_GET['id']."'");
		$useraction = $useract->fetch_assoc();
		switch($_GET['act'])
		{
			case 'ban':
			{
				$useracted = $mysqli->query("UPDATE `users` SET status = 'banned' WHERE id = '".$_GET['id']."'");
				echo '<meta http-equiv="refresh" content="0; url=profile.php">';
				break;
			}
			case 'unban':
			{
				$useracted = $mysqli->query("UPDATE `users` SET status = 'user' WHERE id = '".$_GET['id']."'");
				echo '<meta http-equiv="refresh" content="0; url=profile.php">';
				break;
			}
			default:
			{
				echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
				break;
			}
			
		}
	}
	else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
}
?>