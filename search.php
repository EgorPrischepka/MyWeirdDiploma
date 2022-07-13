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
		if (isset($_GET["name"]))
		{
			echo '<title>Search '.$_GET["name"].'</title>';
		}
		else 
		{
			echo '<title>Brainstorm Card Database</title>';
		}
		echo '<div class = "imagegallery"><center>
		<form action="search.php" method="post">
		<table border="0"><tbody>
		<td><input class = "cardsearch" type="text" name="name" value = "'.$_GET["name"].'"></textarea></td>
		<td><input class = "buttonaction" type="submit" value="Search"/></td>
		</tbody></table>
		</form>';
		$perpage = 20;
		if ($mysqli -> connect_error) 
		{
			printf("Соединение не удалось: %s\n", $mysqli -> connect_error);
			exit();
		}
		else
		{	
			if (isset($_GET["name"]))	
			{
				echo '<h2>You searched: "'.$_GET["name"].'"</h2>';
				if ($name == ' ')
				{
					echo '<h1>Unfortunately, our database can\'t understand you</h1>';
				}
				else
				{
					if(isset($_GET['page']))
					{
						$pageNum = intval(trim(htmlspecialchars($_GET['page'])));
					}
					else
					{
						$pageNum = 1;
					}
					$cnt = mysqli_num_rows($mysqli->query("SELECT FOUND_ROWS() FROM card_base WHERE fullname LIKE '%".$_GET["name"]."%'"));
					$limitmin = $perpage*($pageNum - 1);
					$pic = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * FROM card_base WHERE fullname LIKE '%".$_GET["name"]."%' LIMIT $limitmin, $perpage"); 	
					$qty = mysqli_num_rows($pic);
					if($qty == 0)
					{
						echo '<h2>Unfortunately, you found absolutely nothing</h2>';
					}
					else if($qty == 1)
					{
						$onecard = $pic->fetch_assoc();
						echo '<meta http-equiv="refresh" content="0; url=cardinfo.php?id='.$onecard['id'].'">';
					}
					else
					{
						echo '<p>Shown '.(1+$limitmin).' — '.($qty+$limitmin).' of '.$cnt.' cards</p>';
						while($row = $pic->fetch_assoc())
						{
							if($row['layout']=='transform' || $row['layout']=='modal_dfc')
							{
								echo '<a href = "cardinfo.php?id='.$row['id'].'"><img class = "cardpreview" src= "'.json_decode($row['image'], true)[0].'"></a>';
							}
							else
							{
								echo '<a href = "cardinfo.php?id='.$row['id'].'"><img class = "cardpreview" src= "'.json_decode($row['image'], true).'"></a>';
							}
						}
						echo '<div class = "cardpagelisting">';
						$pagecount = ceil($cnt/$perpage);
						$pagebackward = $_GET['page']-1;
						$pageforward = $_GET['page']+1;
						if ($_GET['page']!=1)
						{
							echo '<a class = "pagelisted" href="?name='.$_GET["name"].'&page='.$pagebackward.'">Back</a>';
						}
						for ($page = 1; $page <= $pagecount; $page++ )
						{
							if ($page <= 3 || $page >= $pagecount - 2 || $page == $_GET['page'])
							{
								echo '<a class = "pagelisted" href="?name='.$_GET["name"].'&page='.$page.'">'.$page.'</a>';
							}
							
						}
						if ($_GET['page']!=$pagecount)
						{
							echo '<a class = "pagelisted" href="?name='.$_GET["name"].'&page='.$pageforward.'">Next</a>';
						}
						echo '</div>';
					}
				}
			}
			elseif (isset ($_POST["name"]))
			{
			    echo '<meta http-equiv="refresh" content="0; url=search.php?name='.$_POST["name"].'&page=1">';
			}
			else
			{
				echo '<h1>Begin your search by entering a card name</h1>';
			}
		}
		echo '</center></body></html>';
	}
}
?>
