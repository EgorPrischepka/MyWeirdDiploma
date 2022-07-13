<?php
session_start();
$perpage = 20;
$totalcount = 0;
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
	if($_SESSION['status']!='banned')
	{
		if(isset($_GET['id']))
		{
			include 'abbrevation.php';
			$checkdeck = $mysqli->query("SELECT * FROM `decks` WHERE '".$_GET['id']."'");
			$checked = $checkdeck->fetch_assoc();
			if($checkdeck->num_rows!=0)
			{
				$commander = mysqli_fetch_assoc($mysqli->query("SELECT `card_base`.`id`, `card_base`.`fullname`, `card_base`.`layout`, `card_base`.`image`, 
				`card_base`.`mana_cost`, `decks`.`name`, `decks`.`users_id` FROM `card_base`, `decks` WHERE `card_base`.`id` = `decks`.`commander_id` AND `decks`.`id` = '".$_GET['id']."'"));
				$content = $mysqli->query("SELECT `deck_content`.`quantity`, `deck_content`.`card_id`, `card_base`.`fullname`, `card_base`.`layout`, `card_base`.`image`,
				`card_base`.`mana_cost`, `card_base`.`mana_value`, `card_base`.`type_line` FROM `deck_content`, `card_base` WHERE `deck_content`.`card_id` = `card_base`.`id` 
				AND `deck_content`.`deck_id` = '".$_GET['id']."' ORDER BY `card_base`.`type_line` ASC, `card_base`.`mana_value` ASC");	
				$deckuser = mysqli_fetch_assoc($mysqli->query("SELECT * FROM `users` WHERE id = '".$commander['users_id']."'"));
				echo '<title>'.$commander['name'].' — Brainstorm Deck Builder</title>
				<div class = "deckspace"><div class = "deckbox"><div class = "decktitle">
				<div class = "deckname"><p>'.$commander['name'].' Deck by '.$deckuser['name'].'</p></div></div>
				<div class = "deckcontent">
				<table border="0">
				<tr><td colspan = "3">Commander</td></tr>
				<tr>
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
				while($decklist = mysqli_fetch_assoc($content))
				{
					echo '<tr>
					<td width = "50">'.$decklist['quantity'].'</td>
					<td width = "300">';
					if($decklist['layout']=='transform' || $decklist['layout']=='modal_dfc')
					{
						echo '<a class = "imagepreview" href="cardinfo.php?id='.$decklist['card_id'].'">'.$decklist['fullname'].'<span>
						<div class = "cardimage"><img src = "'.json_decode($decklist['image'])[0].'" width = "200">
						</div></span></a></td>
						<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($decklist['mana_cost'])[0]).'</td>
						</tr>
						';
					}
					else if($decklist['layout']=='adventure' || $decklist['layout']=='split' || $decklist['layout']=='flip')
					{
						echo '<a class = "imagepreview" href="cardinfo.php?id='.$decklist['card_id'].'">'.$decklist['fullname'].'<span>
						<div class = "cardimage"><img src = "'.json_decode($decklist['image']).'" width = "200">
						</div></span></a></td>';
						if ($decklist['layout']=='split')
						{
							echo '<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($decklist['mana_cost'])[0]).'
							//'.str_replace($abbrevation, $symbolReplace, json_decode($decklist['mana_cost'])[1]).'</td></tr>';
						}
						else 
						{
							echo '<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($decklist['mana_cost'])[0]).'</td></tr>';
						}
					}
					else
					{
						echo '<a class = "imagepreview" href="cardinfo.php?id='.$decklist['card_id'].'">'.$decklist['fullname'].'<span>
						<div class = "cardimage"><img src = "'.json_decode($decklist['image']).'" width = "200">
						</div></span></a></td>
						<td width = "100" align="right">'.str_replace($abbrevation, $symbolReplace, json_decode($decklist['mana_cost'])).'</td>
						</tr>
						';
					}
					$totalcount = $totalcount+$decklist['quantity'];
				}
				$totalcount=$totalcount+1;
				echo '
				<tr><td colspan = "3">'.$totalcount.' cards total</td></tr>
				</tbody></table></div>';
				if($checked['legality']!='legal')
				{
					echo '<div class = "decklegalitymessage"><table><tbody><tr><td>
					Deck is not legal. Probably:</td></tr>
					<tr><td>1. You have more copies of some cards than permitted quantity</td></tr>
					<tr><td>2. Deck size does not meet size requirement. You should 50 cards in deck including commander</td></tr>
					<tr><td>3. Color identity of some cards does not meet commander color identity</td></tr>
					<tr><td>4. You have banned or illegal cards</td></tr>
					</tbody></table></div>';
				}
				echo '</div>';
			}
			else echo '<h1 class = "indexmessage">No deck found</h1>';
		}
		else
		{
			if(isset($_GET['page']))
			{
				$pageNum = intval(trim(htmlspecialchars($_GET['page'])));
				$count = mysqli_num_rows($mysqli->query("SELECT FOUND_ROWS() FROM `decks` WHERE legality = 'legal'"));
    			$limitmin = $perpage*($pageNum - 1);
    			$pagecount = ceil($count/$perpage);
    			$pagebackward = $_GET['page']-1;
    			$pageforward = $_GET['page']+1;
    			
    			echo '<title>Deck Gallery</title>';
    			echo '<div class = "deckgallery">
    			<h1>User submitted decks</h1>';
    			$submitteddecks = $mysqli->query("SELECT * FROM `decks` WHERE legality = 'legal' ORDER BY id DESC LIMIT $limitmin, $perpage");
    			if(mysqli_num_rows($submitteddecks)!=0)
    			{
    				while($deckpreview = $submitteddecks->fetch_assoc())
    				{
    					echo '<div class = "deckpreview">
    					<table class = "deckcase" border = "0">
    					<tr><td><a href = "deck.php?id='.$deckpreview['id'].'">
    					<img class = "deckimage" src="'.$deckpreview['art_crop'].'" width = "200"></a>
    					</td></tr>
    					<tr><td><a href = "deck.php?id='.$deckpreview['id'].'">'.$deckpreview['name'].'</a></td></tr></table></div>';
    				}
    				echo '<div class = "pagelisting">';
    				
    				if ($_GET['page']!=1)
    				{
    					echo '<a class = "pagelisted" href="?page='.$pagebackward.'"><<</a>';
    				}
    				for ($page = 1; $page <= $pagecount; $page++ )
    				{
    					if ($page <= 3 || $page >= $pagecount - 2 || $page == $_GET['page'])
    					{
    						echo '<a class = "pagelisted" href="?page='.$page.'">'.$page.'</a>';
    					}
    				}
    				if ($_GET['page']!=$pagecount)
    				{
    					echo '<a class = "pagelisted" href="?page='.$pageforward.'">>></a>';
    				}
    				echo '</div></div>';
    			}
    			else echo '<p>No deck found</p>';
			}
			else
			{
				echo '<meta http-equiv="refresh" content="0; url=deck.php?page=1">';
			}
			
		}
	}
	else
	{
	    echo '<meta http-equiv="refresh" content="0; url=index.php?error=banned">';
	}
}