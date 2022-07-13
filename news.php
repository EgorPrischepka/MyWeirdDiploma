<?php
session_start();
echo '<html>';
include 'header.php';
echo '<body>';
if ($mysqli -> connect_error) 
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{
	if($_SESSION['status']=='banned')
	{
	    echo '<meta http-equiv="refresh" content="0; url=index.php?error=banned">';
	}
	else
	{
		if(isset($_GET['id']))
		{
			$showmenews = $mysqli->query("SELECT * FROM `news` WHERE id = '".$_GET['id']."'");
			$showingnews = $showmenews->fetch_assoc();
			$userpost = $mysqli->query("SELECT * FROM `users` WHERE id = '".$showingnews['users_id']."'");
			$userposted = $userpost->fetch_assoc();
			echo '
			<title>'.$showingnews['name'].'</title>
			<div class = "newspage">
			<div class = "newswrapper">
			<h1>'.$showingnews['name'].'</h1>
			<img class = "newstumb" src="'.$showingnews['tumbnail'].'">
			<div class = "newscontent">'.nl2br($showingnews['content']).'</div>
			<p class = "newsposted">Posted by '.$userposted['name'].' at '.explode(" ", $showingnews['date'], 2)[0].'</p>
			</div></div>
			';
		}
		else
		{
			$showmenews = $mysqli->query("SELECT * FROM `news` ORDER BY date DESC");
			echo '<title>Brainstorm News Archive</title>
			<div class = "newsarchive"><h1>News Archive</h1>
			<table class = "newstable"><tbody>
			<tr><th width="300">News Article</th><th width = "150">Publication Date</th><tr>';
			while($shownnews = $showmenews->fetch_assoc())
			{
				echo '<tr><td><a href="news.php?id='.$shownnews['id'].'">'.$shownnews['name'].'</td><td>'.explode(" ", $shownnews['date'], 2)[0].'</td><tr>';
			}
			echo '</tbody></table></div>';
		}
	}
}
echo '</body></html>';
?>