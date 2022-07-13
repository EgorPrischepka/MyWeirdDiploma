<?php
session_start();
echo '<html>';
include 'header.php';
echo '<body>';
if(isset($_GET['error']))
{
	switch ($_GET['error'])
	{
		case 'wrongpassword':
		{
			if((isset($_SESSION['username']) || isset($_SESSION['password'])) && (!isset($_SESSION['name']) && !isset($_SESSION['status'])))
			{
				echo '<h1 class = "indexmessage">Wrong Password</h1>';
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
		case 'wrongdoor':
		{
			echo '<title>Wrong Door!</title>
			<h1 class = "indexmessage">You got wrong door! The leathership club is two blocks down</h1>';
			break;
		}
		case 'existinguser':
		{
			if((isset($_SESSION['username']) || isset($_SESSION['password'])) && (!isset($_SESSION['name']) && !isset($_SESSION['status'])))
			{
				echo '<title>Existing User</title>
				<h1 class = "indexmessage">This user is already stay with us</h1>';
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
		case 'nouser':
		{
			if((isset($_SESSION['username']) || isset($_SESSION['password'])) && (!isset($_SESSION['name']) && !isset($_SESSION['status'])))
			{
				echo '<title>No User found</title>
				<h1 class = "indexmessage">If it you, you do not exist here. Sign up to join us!</h1>';
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
		case 'banned':
		{
			if($_SESSION['status']=='banned')
			{
				echo '<title>Banned</title>
				<h1 class = "indexmessage">You are banned</h1>';
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
		case 'registration':
		{
			if((isset($_SESSION['username']) || isset($_SESSION['password'])) && (!isset($_SESSION['name']) && !isset($_SESSION['status'])))
			{
				echo '<title>Error</title>
				<div  class = "indexmessage"><h1>Name and/or password doesn\'t meet the follow requirements:</h1>
				<p>1. Latin and Digits only in name and password</p>
				<p>2. Name lenght must be between 6 and 19</p></div>';
				unset($_SESSION['username']);
				unset($_SESSION['password']);
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
	}
}
else
{
	if($_SESSION['status']=='banned')
	{
		echo '<meta http-equiv="refresh" content="0; url=index.php?error=banned">';
	}
	else
	{
		echo '<title>Brainstorm</title>';
		$showlastnews = $mysqli->query("SELECT * FROM `news` ORDER BY id DESC LIMIT 1");
		$showinglastnews = $showlastnews->fetch_assoc();
		$showlastdecks = $mysqli->query("SELECT * FROM `decks` WHERE legality = 'legal' ORDER BY id DESC LIMIT 3");
		echo '
		<div class = "wrapper"><div class = "mainwrapper">
			<div class = "newsgallery">
			<h1>Last Post</h1>
				<div class = "lastnews">
					<img class="newstumbmain" src="'.$showinglastnews['tumbnail'].'">
					<center><h1><a href="news.php?id='.$showinglastnews['id'].'">'.$showinglastnews['name'].'</a></h1></center>
				</div>
				<button class = "gotoarchive"><a class="pagelisted" href="news.php">News Archive</a></button>
			</div>
			<div class = "lastdeckgallery">
			<h1>Last Decks</h1>
				<div class = "lastdeck">';
				while($showinglastdecks = $showlastdecks->fetch_assoc())
				{
					echo '
					<div class = "deckpreview">
					<table class = "deckcase" border = "0">
					<tr><td><a href = "deck.php?id='.$showinglastdecks['id'].'">
					<img class = "deckimage" src="'.$showinglastdecks['art_crop'].'" width = "200"></a>
					</td></tr>
					<tr><td><a href = "deck.php?id='.$showinglastdecks['id'].'">'.$showinglastdecks['name'].'</a></td></tr></table></div>';
				}
				echo '</div>
			</div>
		</div></div>
		';
	}
}
?>