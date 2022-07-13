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
	if($_SESSION['status']=='admin' && isset($_POST['name']) && isset($_POST['content']) && isset($_FILES['tumbnail']))
	{
		if(!is_dir('tumbnails/'))
		{
			mkdir('tumbnails/', 0777);
		}
		
		$tumbnailUpload = 'tumbnails/'.uniqid().'.jpg';
		if(move_uploaded_file($_FILES['tumbnail']['tmp_name'], $tumbnailUpload))
		{
			$addnews = $mysqli->query("INSERT INTO `news`(`id`, `users_id`, `name`, `tumbnail`, `content`, `date`) 
			VALUES (NULL,'".$_SESSION['id']."','".mysqli_real_escape_string($mysqli, $_POST['name'])."','".$tumbnailUpload."','".mysqli_real_escape_string($mysqli, $_POST['content'])."',now())");
			var_dump($addnews);
			if($mysqli->insert_id!=NULL)
			{
			    echo '<meta http-equiv="refresh" content="0; url=news.php?id='.$mysqli->insert_id.'">';
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php">';
		}
	}
	else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
}
?>