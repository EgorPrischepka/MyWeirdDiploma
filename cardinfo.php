<?php
session_start();
echo '<html>';
include 'header.php';
echo '<body>';
$mysqli = new mysqli(localhost, 'u1683338_default', 'VUj2k6xnhS8ST5Te', 'u1683338_default');
if ($mysqli -> connect_error)
{
	printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
	exit();
}
else
{
	if($_SESSION['status']=='banned')
	{
	    echo '<meta http-equiv="refresh" content="0; index.php?error=banned">';
	}
	else
	{	
		include 'abbrevation.php';
		if(isset($_GET['id']))
		{
			$sql = $mysqli->query("SELECT * FROM `card_base` WHERE id = '".$_GET['id']."'");
			$cardabout = $sql->fetch_assoc();
			$cardname = json_decode($cardabout['name'], true);
			$cardimage = json_decode($cardabout['image'], true);
			$cardmc = json_decode($cardabout['mana_cost'], true);
			$cardtype = json_decode($cardabout['type_line'], true);
			$cardoracle = json_decode($cardabout['oracle'], true);
			$cardpower = json_decode($cardabout['power'], true);
			$cardtoughness = json_decode($cardabout['toughness'], true);
			$cardloyalty = json_decode($cardabout['loyalty'], true);
			switch($cardabout['layout'])
			{
				case 'normal':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
							<div class = "cardimage">
							<img class = "cardpreview" src = "'.json_decode($cardabout['image'], true).'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.json_decode($cardabout['name'], true).' '.str_replace($abbrevation, $manaReplace, json_decode($cardabout['mana_cost'], true)).'
								</div>
								<div class = "oracle">
								<p>'.json_decode($cardabout['type_line']).'</p>
								<p>'.str_replace($abbrevation, $symbolReplace ,nl2br(json_decode($cardabout['oracle']), true)).'</p>
								</div>
								<div class = "cardmisc">';
									if (json_decode($cardabout['power'], true)!=NULL && json_decode($cardabout['toughness'], true)!=NULL)
									{
										echo '<p>'.json_decode($cardabout['power'], true).'/'.json_decode($cardabout['toughness'], true).'</p></div>';
									}
									else if(json_decode($cardabout['loyalty'], true)!=NULL)
									{
										echo '<p>Loyalty: '.json_decode($cardabout['loyalty'], true).'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'split':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
						
							<div class = "cardimage">
								<img class = "cardpreview" src = "'.$cardimage.'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.$cardname[0].' '.str_replace($abbrevation, $manaReplace, $cardmc[0]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[0].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[0])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[0]!=NULL && $cardtoughness[0]!=NULL)
									{
										echo '<p>'.$cardpower[0].'/'.$cardtoughness[0].'</p>';
									}
									else if($cardloyalty[0]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[0].'</p>';
									}
									echo '
								</div>
								<div class = "cardtitle">
									'.$cardname[1].' '.str_replace($abbrevation, $manaReplace, $cardmc[1]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[1].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[1])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[1]!=NULL && $cardtoughness[1]!=NULL)
									{
										echo '<p>Power: '.$cardpower[1].'/'.$cardtoughness[1].'</p>';
									}
									else if($cardloyalty[1]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[1].'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'transform':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
						
							<div class = "cardimage">
								<img class = "cardpreview" src = "'.$cardimage[0].'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.$cardname[0].' '.str_replace($abbrevation, $manaReplace, $cardmc[0]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[0].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[0])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[0]!=NULL && $cardtoughness[0]!=NULL)
									{
										echo '<p>'.$cardpower[0].'/'.$cardtoughness[0].'</p>';
									}
									else if($cardloyalty[0]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[0].'</p>';
									}
									echo '
								</div>
								<div class = "cardtitle">
									'.$cardname[1].' '.str_replace($abbrevation, $manaReplace, $cardmc[1]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[1].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[1])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[1]!=NULL && $cardtoughness[1]!=NULL)
									{
										echo '<p>'.$cardpower[1].'/'.$cardtoughness[1].'</p>';
									}
									else if($cardloyalty[1]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[1].'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'adventure':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
						
							<div class = "cardimage">
								<img class = "cardpreview" src = "'.$cardimage.'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.$cardname[0].' '.str_replace($abbrevation, $manaReplace, $cardmc[0]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[0].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[0])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[0]!=NULL && $cardtoughness[0]!=NULL)
									{
										echo '<p>'.$cardpower[0].'/'.$cardtoughness[0].'</p>';
									}
									else if($cardloyalty[0]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[0].'</p>';
									}
									echo '
								</div>
								<div class = "cardtitle">
									'.$cardname[1].' '.str_replace($abbrevation, $manaReplace, $cardmc[1]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[1].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[1])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[1]!=NULL && $cardtoughness[1]!=NULL)
									{
										echo '<p>Power: '.$cardpower[1].'/'.$cardtoughness[1].'</p>';
									}
									else if($cardloyalty[1]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[1].'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'leveler':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
							<div class = "cardimage">
							<img class = "cardpreview" src = "'.json_decode($cardabout['image'], true).'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.json_decode($cardabout['name'], true).' '.str_replace($abbrevation, $manaReplace, json_decode($cardabout['mana_cost'], true)).'
								</div>
								<div class = "oracle">
								<p>'.json_decode($cardabout['type_line']).'</p>
								<p>'.str_replace($abbrevation, $symbolReplace ,nl2br(json_decode($cardabout['oracle']), true)).'</p>
								</div>
								<div class = "cardmisc">';
									if (json_decode($cardabout['power'], true)!=NULL && json_decode($cardabout['toughness'], true)!=NULL)
									{
										echo '<p>'.json_decode($cardabout['power'], true).'/'.json_decode($cardabout['toughness'], true).'</p></div>';
									}
									else if(json_decode($cardabout['loyalty'], true)!=NULL)
									{
										echo '<p>Loyalty: '.json_decode($cardabout['loyalty'], true).'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'flip':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
						
							<div class = "cardimage">
								<img class = "cardpreview" src = "'.$cardimage.'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.$cardname[0].' '.str_replace($abbrevation, $manaReplace, $cardmc[0]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[0].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[0])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[0]!=NULL && $cardtoughness[0]!=NULL)
									{
										echo '<p>'.$cardpower[0].'/'.$cardtoughness[0].'</p>';
									}
									else if($cardloyalty[0]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[0].'</p>';
									}
									echo '
								</div>
								<div class = "cardtitle">
									<p>'.$cardname[1].' '.str_replace($abbrevation, $manaReplace, $cardmc[1]).'</p>
								</div>
								<div class = "oracle">
									<p>'.$cardtype[1].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[1])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[1]!=NULL && $cardtoughness[1]!=NULL)
									{
										echo '<p>Power: '.$cardpower[1].'/'.$cardtoughness[1].'</p>';
									}
									else if($cardloyalty[1]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[1].'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'modal_dfc':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
						
							<div class = "cardimage">
								<img class = "cardpreview" src = "'.$cardimage[0].'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.$cardname[0].' '.str_replace($abbrevation, $manaReplace, $cardmc[0]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[0].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[0])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[0]!=NULL && $cardtoughness[0]!=NULL)
									{
										echo '<p>'.$cardpower[0].'/'.$cardtoughness[0].'</p>';
									}
									else if($cardloyalty[0]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[0].'</p>';
									}
									echo '
								</div>
								<div class = "cardtitle">
									'.$cardname[1].' '.str_replace($abbrevation, $manaReplace, $cardmc[1]).'
								</div>
								<div class = "oracle">
									<p>'.$cardtype[1].'</p>
									<p>'.str_replace($abbrevation, $symbolReplace ,nl2br($cardoracle[1])).'</p>
								</div>
								<div class = "cardmisc">';
									if ($cardpower[1]!=NULL && $cardtoughness[1]!=NULL)
									{
										echo '<p>'.$cardpower[1].'/'.$cardtoughness[1].'</p>';
									}
									else if($cardloyalty[1]!=NULL)
									{
										echo '<p>Loyalty: '.$cardloyalty[1].'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'class':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
							<div class = "cardimage">
							<img class = "cardpreview" src = "'.json_decode($cardabout['image'], true).'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.json_decode($cardabout['name'], true).' '.str_replace($abbrevation, $manaReplace, json_decode($cardabout['mana_cost'], true)).'
								</div>
								<div class = "oracle">
								<p>'.json_decode($cardabout['type_line']).'</p>
								<p>'.str_replace($abbrevation, $symbolReplace ,nl2br(json_decode($cardabout['oracle']), true)).'</p>
								</div>
								<div class = "cardmisc">';
									if (json_decode($cardabout['power'], true)!=NULL && json_decode($cardabout['toughness'], true)!=NULL)
									{
										echo '<p>'.json_decode($cardabout['power'], true).'/'.json_decode($cardabout['toughness'], true).'</p></div>';
									}
									else if(json_decode($cardabout['loyalty'], true)!=NULL)
									{
										echo '<p>Loyalty: '.json_decode($cardabout['loyalty'], true).'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
				case 'saga':
				{
					echo '
					<title>'.$cardabout['fullname'].' — Brainstorm Search</title>
					<div class = "boxnigger">
						<div class = "cardbox">
							<div class = "cardimage">
							<img class = "cardpreview" src = "'.json_decode($cardabout['image'], true).'" width = "100%">
							</div>
							<div class = "cardtext">
								<div class = "cardtitle">
									'.json_decode($cardabout['name'], true).' '.str_replace($abbrevation, $manaReplace, json_decode($cardabout['mana_cost'], true)).'
								</div>
								<div class = "oracle">
								<p>'.json_decode($cardabout['type_line']).'</p>
								<p>'.str_replace($abbrevation, $symbolReplace ,nl2br(json_decode($cardabout['oracle']), true)).'</p>
								</div>
								<div class = "cardmisc">';
									if (json_decode($cardabout['power'], true)!=NULL && json_decode($cardabout['toughness'], true)!=NULL)
									{
										echo '<p>'.json_decode($cardabout['power'], true).'/'.json_decode($cardabout['toughness'], true).'</p></div>';
									}
									else if(json_decode($cardabout['loyalty'], true)!=NULL)
									{
										echo '<p>Loyalty: '.json_decode($cardabout['loyalty'], true).'</p>';
									}
									if($cardabout['legality']=='legal')
									{
										echo '<div class = "cardlegalitygreen">'.$cardabout['legality'].'</div>';
									}
									else
									{
										echo '<div class = "cardlegalityred">'.$cardabout['legality'].'</div>';
									}
									echo '
								</div>
							</div>
						</div>
					</div>
					';
					break;
				}
			}
		}
		else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
	}
}
echo '</body></html>';
?>