<?php
session_start();
echo '<html>';
include 'header.php';
if ($mysqli -> connect_error)
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{
	$totalcount = 0;
	$memecount = 0;
	if($_SESSION['status']!='banned')
	{
		if($_SESSION)
		{
			include 'abbrevation.php';
			$sql = $mysqli->query("SELECT * FROM `decks` WHERE id = '".$_GET['id']."'");
			$checkthisdude = $sql->fetch_assoc();
			if(isset($_GET['id']))
			{
				if($_SESSION['id']==$checkthisdude['users_id'])
				 {
					echo '<title>Deck Editor — Brainstorm</title>';
					if (mysqli_num_rows($mysqli->query("SELECT * FROM `decks` WHERE id = '".$_GET['id']."'"))==1)//если нашли колоду
					{
						$deck = $mysqli->query("SELECT * FROM `decks` WHERE id = '".$_GET['id']."'");
						$decklist = $deck->fetch_assoc();
						$commander = mysqli_fetch_assoc($mysqli->query("SELECT `card_base`.`id`, `card_base`.`fullname`, `card_base`.`layout`, `card_base`.`image`, 
						`card_base`.`mana_cost`, `decks`.`name` FROM `card_base`, `decks` WHERE `card_base`.`id` = `decks`.`commander_id` AND `decks`.`id` = '".$_GET['id']."'"));
						$content = $mysqli->query("SELECT `deck_content`.`quantity`, `deck_content`.`card_id`, `card_base`.`fullname`, `card_base`.`layout`, `card_base`.`image`,
						`card_base`.`mana_cost`, `card_base`.`mana_value` FROM `deck_content`, `card_base` WHERE `deck_content`.`card_id` = `card_base`.`id` 
						AND `deck_content`.`deck_id` = '".$_GET['id']."' ORDER BY `card_base`.`mana_value` ASC;");	
						$contentpreview = $mysqli->query("SELECT `deck_content`.`quantity`, `deck_content`.`card_id`, `card_base`.`fullname`, `card_base`.`layout`, `card_base`.`image`,
						`card_base`.`mana_cost`, `card_base`.`mana_value` FROM `deck_content`, `card_base` WHERE `deck_content`.`card_id` = `card_base`.`id` 
						AND `deck_content`.`deck_id` = '".$_GET['id']."' ORDER BY `card_base`.`mana_value` ASC;");	
						
						echo '<center><div class = "mainwrapper">
						<div class = "deckeditspace"><div class = "deckedit">
						<form action = "submitdeck.php?id='.$_GET['id'].'" method="post">
						<table border="0"><tbody>
						<tr><td><p>Deck Title:</p></td></tr>
						<tr><td><textarea class = "deckcommander" id = "name" name = "name">'.$decklist['name'].'</textarea></td></tr>
						<tr><td><p>Commander:</p></td></tr>
						<tr><td><textarea class = "deckcommander" id = "commander" name = "commander">'.$commander['fullname'].'</textarea></td></tr>
						<tr><td><p>Deck List:</p></td></tr>
						<tr><td><textarea id = "decklist" class = "deckeditor" rows="50" cols="2" name = "decklist">';	
						while($contented = $content->fetch_assoc())
						{
							if($contented['id']!=$decklist['commander_id'])
							{
							    if($memecount!=0)
							    {
							        echo '&#13;&#10;';
							    }
								echo $contented['quantity'].' '.$contented['fullname'];
								$memecount = $memecount + 1;
							}
						}
						
						echo '</textarea></td></tr>
						<tr><td><center>
						<input type="submit" value="Update">
						</center></td></tr></tbody></table>
						</form></div>';
						echo '<div class = "deckeditbox"><div class = "decktitle">
						<div class = "deckname"><p>'.$commander['name'].' Deckpreview</p></div></div>
						<div class = "deckcontent">
						<table border="0">
						<tr><td colspan = "3">Commander</td></tr><tr>
						<td width = "50"></td>
						<td width = "300">';
						if($commander['layout']=='transform' || $commander['layout']=='modal_dfc')
						{
							echo '<a class = "imagepreview" href="cardinfo.php?id='.$commander['id'].'">'.$commander['fullname'].'<span>
							<div class = "cardimage"><img src = "'.json_decode($commander['image'])[0].'" width = "200">
							</div></span></a></td>
							<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($commander['mana_cost'])[0]).'</td>
							</tr>
							';
						}
						else if($commander['layout']=='adventure' || $commander['layout']=='split' || $commander['layout']=='flip')
						{
							echo '<a class = "imagepreview" href="cardinfo.php?id='.$commander['id'].'">'.$commander['fullname'].'<span>
							<div class = "cardimage"><img src = "'.json_decode($commander['image']).'" width = "200">
							</div></span></a></td>
							<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($commander['mana_cost'])[0]).'</td>
							</tr>
							';
						}
						else
						{
							echo '<a class = "imagepreview" href="cardinfo.php?id='.$commander['id'].'">'.$commander['fullname'].'<span>
							<div class = "cardimage"><img src = "'.json_decode($commander['image']).'" width = "200">
							</div></span></a></td>
							<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($commander['mana_cost'])).'</td>
							</tr>
							';
						}
						echo '<tr><td colspan = "3">Main Deck</td></tr>';

						while($previewed = $contentpreview->fetch_assoc())
						{
							echo '<tr>
							<td width = "50">'.$previewed['quantity'].'</td>
							<td width = "300">';
							if($previewed['layout']=='transform' || $previewed['layout']=='modal_dfc')
							{
								echo '<a class = "imagepreview" href="cardinfo.php?id='.$previewed['card_id'].'">'.$previewed['fullname'].'<span>
								<div class = "cardimage"><img src = "'.json_decode($previewed['image'])[0].'" width = "200">
								</div></span></a></td>
								<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($previewed['mana_cost'])[0]).'</td>
								</tr>
								';
							}
							else if($previewed['layout']=='adventure' || $previewed['layout']=='split' || $previewed['layout']=='flip')
							{
								echo '<a class = "imagepreview" href="cardinfo.php?id='.$previewed['card_id'].'">'.$previewed['fullname'].'<span>
								<div class = "cardimage"><img src = "'.json_decode($previewed['image']).'" width = "200">
								</div></span></a></td>';
								if ($previewed['layout']=='split')
								{
									echo '<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($previewed['mana_cost'])[0]).'
									//'.str_replace($abbrevation, $symbolReplace, json_decode($previewed['mana_cost'])[1]).'</td></tr>';
								}
								else 
								{
									echo '<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($previewed['mana_cost'])[0]).'</td></tr>';
								}
							}
							else
							{
								echo '<a class = "imagepreview" href="cardinfo.php?id='.$previewed['card_id'].'">'.$previewed['fullname'].'<span>
								<div class = "cardimage"><img src = "'.json_decode($previewed['image']).'" width = "200">
								</div></span></a></td>
								<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($previewed['mana_cost'])).'</td>
								</tr>
								';
							}
							$totalcount = $totalcount+$previewed['quantity'];
						}
						$totalcount=$totalcount+1;
						echo '
						<tr><td colspan = "3">'.$totalcount.' cards total</td></tr>
						</tbody></table></div>';
						if($decklist['legality']!='legal')
						{
							echo '<div class = "decklegalitymessage"><table><tbody><tr><td>
							Deck is not legal. Probably:</td></tr>
							<tr><td>1. You have more copies of some cards than permitted quantity</td></tr>
							<tr><td>2. Deck size does not meet size requirement. You should 50 cards in deck including commander</td></tr>
							<tr><td>3. Color identity of some cards does not meet commander color identity</td></tr>
							<tr><td>4. You have banned or illegal cards</td></tr>
							</tbody></table></div></div>';
						}
						echo '</div></div></center>';
						
					}
					else
					{
						echo '<h1>Unfortunately, no deck found</h1>';
					}
				}
				else
				{
					header ('Location: index.php?error=wrongdoor', false);
				}
			}
			else 
			{
				echo '<title>Submit a deck — Brainstorm</title>
					<center><div class = "mainwrapper"><center><div class = "deckeditspace"><div class = "deckedit">
					<form action = "submitdeck.php" method="post"><table border="0"><tbody>
					<tr><td><p>Deck Title:</p></td></tr>
					<tr><td><textarea class = "deckcommander" id = "name" name = "name"></textarea></td></tr>
					<tr><td><p>Commander:</p></td></tr>
					<tr><td><textarea class = "deckcommander" id = "commander" name = "commander"></textarea></td></tr>
					<tr><td><p>Deck List:</p></td></tr>
					<tr><td><textarea class = "deckeditor" rows="50" cols="2" id = "decklist" name = "decklist"></textarea></td></tr>
					<tr><td><center><div class="creatingbutton" id="createbutton"><input class="buttonaction" type="submit" value="Create"></div></center></td></tr>
					</tbody></table></div></form></div></div></center></div></center>';			
			}
		}
		else
		{
			echo '<title>Log in or Sign up</title>
			<h1 class = "indexmessage">Log int or Sign up to submit your deck</h1>';
		}
	}
	else
	{
		echo '<meta http-equiv="refresh" content="0; url=index.php?error=banned">';
	}
}
?>
</body>
</html>