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
	if (isset($_FILES['file']))
	{
		if(!is_dir('content/'))
		{
			mkdir('content/', 0777);
		}
		if(move_uploaded_file($_FILES['file']['tmp_name'], 'content/'.basename($_FILES['file']['name'])))
		{
			$banlist = file_get_contents('content/'.basename($_FILES['file']['name']));
			$ban = explode("\n", $banlist);
			foreach ($ban as $can)
			{
				$banning = $mysqli->query("UPDATE `card_base` SET `legality`='banned' WHERE fullname = '".mysqli_real_escape_string($mysqli, preg_replace("/\r|\n/", "", $can))."'");
			}
			echo '<meta http-equiv="refresh" content="0; url=profile.php">';
		}
		else echo 'Unknown Error!';
	}
	else echo 'Unknown Error!';
}
?>