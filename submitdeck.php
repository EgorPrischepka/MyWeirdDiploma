<?php
session_start();
$identitycount = 0;
$quantitycount = 0;
$singleton = 0;
$bannedcount = 0;
$mysqli = new mysqli(localhost, 'u1683338_default', 'VUj2k6xnhS8ST5Te', 'u1683338_default');
if($mysqli -> connect_error)
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{	
	include 'abbrevation.php';
	if ($_SESSION['status']!='banned')
	{
		if (isset($_POST['decklist']) && isset($_POST['commander']) && isset($_POST['name']))
		{
			$commander = $mysqli->query("SELECT * FROM `card_base` WHERE fullname = '".mysqli_real_escape_string($mysqli, $_POST['commander'])."'");
			$commandered = $commander->fetch_assoc();
			if ($commandered['commander']=='Yes')
			{	
				if($_GET['id'])
				{
					$checking = $mysqli->query("SELECT * FROM `decks` WHERE id = '".$_GET['id']."'");
					$checkthisdude = $checking->fetch_assoc();
					if($_SESSION['id']==$checkthisdude['users_id'])
					{
						$remove = $mysqli->query("DELETE FROM `deck_content` WHERE deck_id = '".$_GET['id']."'");
						$updatedeck = $mysqli->query("UPDATE `decks` SET art_crop = '".$commandered['art_crop']."',
						name = '".$_POST['name']."', commander_id='".$commandered['id']."' WHERE id = '".$_GET['id']."'");
						$recentdeck = $_GET['id'];
						
					}
					else
					{
					    echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
					}
				}
				else
				{
					$createdeck = $mysqli->query("INSERT INTO `decks` (`id`, `users_id`, `art_crop`, `name`, `commander_id`, `legality`)
					VALUES (NULL,'".$_SESSION['id']."','".$commandered['art_crop']."','".$_POST['name']."','".$commandered['id']."','not legal')");
					$recentdeck = $mysqli->insert_id;
				}
				$list = explode("\n", $_POST['decklist']);
				foreach($list as $listshrink)
				{	
					
					$cardlist = explode(" ", $listshrink, 2);
					$cardname = str_replace('\r', '', mysqli_real_escape_string($mysqli, $cardlist[1]));
					$cardqty = $cardlist[0];
					$commanderidenity = $mysqli->query("SELECT * FROM `card_base` WHERE fullname = 
					'".$cardname."'");
					$commanderidenitifed = $commanderidenity->fetch_assoc();
					if(strlen($commanderidenitifed['color_identity'])!=0)
					{
						foreach (str_split($commanderidenitifed['color_identity']) as $coloridentity)
						{
							if(stripos($commandered['color_identity'], $coloridentity)===false)
							{
								$identitycount = $identitycount+1;
							}
						}
					}
					if ($commanderidenitifed['legality']!='legal')
					{
						$bannedcount = $bannedcount+1;
						echo $commanderidenitifed['fullname'];
					}
					if($cardqty>$commanderidenitifed['qtylimit'])
					{
						$singleton = $singleton + 1;
					}
					if (preg_replace("/\r|\n/", "", $cardname) != $_POST['commander'])
					{
						$addcontent = $mysqli->query("INSERT INTO `deck_content`(`id`, `deck_id`, `card_id`, `quantity`) 
						VALUES (NULL,'".$recentdeck."','".$commanderidenitifed['id']."','".$cardlist[0]."')");
						$quantitycount = (int)$quantitycount+(int)$cardlist[0];
					}
				}
				if($identitycount==0 && $singleton==0 && $quantitycount==49 && $bannedcount==0)
				{
					$legalize = $mysqli->query("UPDATE `decks` SET legality = 'legal' WHERE id = '".$recentdeck."'");
				}
				else
				{
					$legalize = $mysqli->query("UPDATE `decks` SET legality = 'not legal' WHERE id = '".$recentdeck."'");
				}
				echo '<meta http-equiv="refresh" content="0; url=deckeditor.php?id='.$recentdeck.'">';
			
			}
		}
	}
	else
	{
		echo '<meta http-equiv="refresh" content="0; url=index.php?error=banned">';
	}
}
?>