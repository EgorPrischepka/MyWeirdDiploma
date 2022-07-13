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
			foreach (json_decode(file_get_contents('content/'.basename($_FILES['file']['name'])), true) as $object)
			{
				$checkindb = ("SELECT * FROM `card_base` WHERE fullname = '".$object['name']."'");
				if(($object['legalities']['vintage']=='legal' || $object['legalities']['vintage']=='restricted') && $object['cmc']<=3 && $checkindb->num_rows==0)
				{
					switch($object['layout'])
					{
						case 'normal':
						{
							$addnormal = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES(NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode($object['name']))."','".json_encode($object['image_uris']['normal'])."', 
							'".$object['image_uris']['art_crop']."','".json_encode($object['mana_cost'])."', '".$object['cmc']."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['type_line']))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['oracle_text']))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode($object['power'])."',
							'".json_encode($object['toughness'])."','".json_encode($object['loyalty'])."','legal', 'No', '1')");
							break;
						}
						case 'split':
						{
							$addsplit = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES (NULL,'".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."','".mysqli_real_escape_string($mysqli, json_encode(array($object['card_faces'][0]['name'], $object['card_faces'][1]['name'])))."'
							,'".json_encode($object['image_uris']['normal'])."','".$object['image_uris']['art_crop']."',
							'".json_encode(array($object['card_faces'][0]['mana_cost'], $object['card_faces'][1]['mana_cost']))."',
							'".$object['cmc']."','".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['type_line'], $object['card_faces'][1]['type_line'])))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['oracle_text'], $object['card_faces'][1]['oracle_text'])))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode(array($object['card_faces'][0]['power'], $object['card_faces'][1]['power']))."',
							'".json_encode(array($object['card_faces'][0]['toughness'], $object['card_faces'][1]['toughness']))."',
							'".json_encode(array($object['card_faces'][0]['loyalty'], $object['card_faces'][1]['loyalty']))."', 'legal','No','1')");
							break;
						}
						case 'transform':
						{
							$addtransform = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES (NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode(array($object['card_faces'][0]['name'], $object['card_faces'][1]['name'])))."'
							,'".json_encode(array($object['card_faces'][0]['image_uris']['normal'], $object['card_faces'][1]['image_uris']['normal']))."','".$object['card_faces'][0]['image_uris']['art_crop']."',
							'".json_encode(array($object['card_faces'][0]['mana_cost'], $object['card_faces'][1]['mana_cost']))."',
							'".$object['cmc']."','".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['type_line'], $object['card_faces'][1]['type_line'])))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['oracle_text'], $object['card_faces'][1]['oracle_text'])))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode(array($object['card_faces'][0]['power'], $object['card_faces'][1]['power']))."',
							'".json_encode(array($object['card_faces'][0]['toughness'], $object['card_faces'][1]['toughness']))."',
							'".json_encode(array($object['card_faces'][0]['loyalty'], $object['card_faces'][1]['loyalty']))."',
							'legal','No','1')");
							break;
						}
						case 'adventure':
						{
							$addadventure = $mysqli->query( "INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES (NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode(array($object['card_faces'][0]['name'], $object['card_faces'][1]['name'])))."'
							,'".json_encode($object['image_uris']['normal'])."','".$object['image_uris']['art_crop']."',
							'".json_encode(array($object['card_faces'][0]['mana_cost'], $object['card_faces'][1]['mana_cost']))."',
							'".$object['cmc']."','".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['type_line'], $object['card_faces'][1]['type_line'])))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['oracle_text'], $object['card_faces'][1]['oracle_text'])))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode(array($object['card_faces'][0]['power'], $object['card_faces'][1]['power']))."',
							'".json_encode(array($object['card_faces'][0]['toughness'], $object['card_faces'][1]['toughness']))."',
							'".json_encode(array($object['card_faces'][0]['loyalty'], $object['card_faces'][1]['loyalty']))."',
							'legal','No','1')");
							break;
						}
						case 'leveler':
						{
							$addleveler = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES(NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode($object['name']))."','".json_encode($object['image_uris']['normal'])."', 
							'".$object['image_uris']['art_crop']."','".json_encode($object['mana_cost'])."', '".$object['cmc']."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['type_line']))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['oracle_text']))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode($object['power'])."',
							'".json_encode($object['toughness'])."','".json_encode($object['loyalty'])."','legal', 'No', '1')");
							break;
						}
						case 'flip':
						{
							$addflip = $mysqli->query( "INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES (NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode(array($object['card_faces'][0]['name'], $object['card_faces'][1]['name'])))."'
							,'".json_encode($object['image_uris']['normal'])."','".$object['image_uris']['art_crop']."',
							'".json_encode(array($object['mana_cost']))."',
							'".$object['cmc']."','".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['type_line'], $object['card_faces'][1]['type_line'])))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['oracle_text'], $object['card_faces'][1]['oracle_text'])))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode(array($object['card_faces'][0]['power'], $object['card_faces'][1]['power']))."',
							'".json_encode(array($object['card_faces'][0]['toughness'], $object['card_faces'][1]['toughness']))."',
							'".json_encode(array($object['card_faces'][0]['loyalty'], $object['card_faces'][1]['loyalty']))."',
							'legal','No','1')");
							break;
						}
						case 'modal_dfc':
						{
							$addmodaldfc = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES (NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode(array($object['card_faces'][0]['name'], $object['card_faces'][1]['name'])))."'
							,'".json_encode(array($object['card_faces'][0]['image_uris']['normal'], $object['card_faces'][1]['image_uris']['normal']))."','".$object['card_faces'][0]['image_uris']['art_crop']."',
							'".json_encode(array($object['card_faces'][0]['mana_cost'], $object['card_faces'][1]['mana_cost']))."',
							'".$object['cmc']."','".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['type_line'], $object['card_faces'][1]['type_line'])))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode(array($object['card_faces'][0]['oracle_text'], $object['card_faces'][1]['oracle_text'])))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode(array($object['card_faces'][0]['power'], $object['card_faces'][1]['power']))."',
							'".json_encode(array($object['card_faces'][0]['toughness'], $object['card_faces'][1]['toughness']))."',
							'".json_encode(array($object['card_faces'][0]['loyalty'], $object['card_faces'][1]['loyalty']))."',
							'legal','No','1')");
							break;
						}
						case 'class':
						{
							$addclass = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES(NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode($object['name']))."','".json_encode($object['image_uris']['normal'])."', 
							'".$object['image_uris']['art_crop']."','".json_encode($object['mana_cost'])."', '".$object['cmc']."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['type_line']))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['oracle_text']))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode($object['power'])."',
							'".json_encode($object['toughness'])."','".json_encode($object['loyalty'])."','legal', 'No', '1')");
							break;
						}
						case 'saga':
						{
							$addsaga = $mysqli->query("INSERT INTO `card_base`(`id`, `layout`, `fullname`, `name`, `image`, `art_crop`, `mana_cost`, `mana_value`, `type_line`, 
							`oracle`, `color`, `color_identity`, `power`, `toughness`, `loyalty`, `legality`, `commander`, `qtylimit`) 
							VALUES(NULL, '".$object['layout']."', '".mysqli_real_escape_string($mysqli, $object['name'])."', '".mysqli_real_escape_string($mysqli, json_encode($object['name']))."','".json_encode($object['image_uris']['normal'])."', 
							'".$object['image_uris']['art_crop']."','".json_encode($object['mana_cost'])."', '".$object['cmc']."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['type_line']))))."',
							'".mysqli_real_escape_string($mysqli, str_replace('\\—', '—', str_replace('u2014', '—', json_encode($object['oracle_text']))))."',
							'".$object['colors'][0].$object['colors'][1].$object['colors'][2].$object['colors'][3].$object['colors'][4]."',
							'".$object['color_identity'][0].$object['color_identity'][1].$object['color_identity'][2].$object['color_identity'][3].$object['color_identity'][4]."',
							'".json_encode($object['power'])."',
							'".json_encode($object['toughness'])."','".json_encode($object['loyalty'])."','legal', 'No', '1')");
							break;
						}
					}
				}
			}
		}
		//$notlegal = $mysqli->query("UPDATE `card_base` SET legality = 'not legal' WHERE mana_value>3");
		$commanderize = $mysqli->query("UPDATE `card_base` SET `commander`='Yes' WHERE `type_line` LIKE '%Legendary%' AND (`type_line` LIKE '%Creature%') 
		OR `type_line` LIKE '%Planeswalker%'");
		$backtobasics = $mysqli->query("UPDATE `card_base` SET `qtylimit`='Yes' WHERE `type_line` LIKE '%Basic%' AND or `oracle` LIKE '%A deck can have any%'");
		echo 'Card Database successfully updated!';
	}
	else echo 'Unknown Error!';
}
?>