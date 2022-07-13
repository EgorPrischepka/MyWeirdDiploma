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
	if($_SESSION && isset($_GET['id']))
	{
		$deletedeck = $mysqli->query("DELETE FROM `decks` WHERE id = '".$_GET['id']."'");
		echo '<meta http-equiv="refresh" content="0; url=profile.php">';
	}
	else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
}
?>