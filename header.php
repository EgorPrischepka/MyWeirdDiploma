<?php
session_start();
$mysqli = new mysqli(localhost, 'u1683338_default', 'VUj2k6xnhS8ST5Te', 'u1683338_default');
echo '
<link rel="stylesheet" href="style.css">
<link rel="icon" href="content/favicon.ico" type="image/x-icon">
<script src="jquery-latest.js"></script>
<script>
		function ShowPosting()
		{
			$("#submitpost").show();
		}
		function HidePosting()
		{
			$("#submitpost").hide();
		}
        function PopUpShow()
    	{
            $("#loginpopup").show();
        }
        function PopUpHide()
    	{
            $("#loginpopup").hide();
        }
    	</script>
<link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon">
<div class = "header">
<a class = "headerlink" href = "index.php">Main</a>
<a class = "headerlink" href = "deck.php">Deck Gallery</a>
<a class = "headerlink" href = "deckeditor.php">Submit a Deck</a>
<a class = "headerlink" href = "search.php">Card Database</a>
<a class = "headerlink" href = "about.php">About</a>';
if(isset($_SESSION['name']) && isset($_SESSION['status']))
{
    $updateStatus = $mysqli->query("SELECT * FROM `users` WHERE name = '".$_SESSION['name']."'");
    $updatingStatus = $updateStatus->fetch_assoc();
    $_SESSION['status'] = $updatingStatus['status'];
	if($_SESSION['status']=='admin')
	{
		?>
		<a class = "headerlink" href = "javascript:ShowPosting()">Submit a Post</a>
		<a class = "headerlink" href = "profile.php">Profile</a>
		<a class = "headerlink" href = "index.php?log=out">Log Out</a>
		</div>
		<div class = "submitpost"  id ="submitpost">
			<div class = "posting">
			<form method="POST" action = "submitpost.php" enctype="multipart/form-data">
			<table></tbody>
			<tr><td>Enter a Post Title:</td></tr>
			<tr><td><textarea class="postname" name="name" placeholder = "Feel free to be yourself"required></textarea></td></tr>
			<tr><td>Write some letters:</td></tr>
			<tr><td><textarea class="postcontent" name="content" placeholder = "Start your masterpiece" required></textarea></td></tr>
			<tr><td><p>Upload a tumbnail. You should use 750x200 picture for better result:       <input type="file" accept=".jpg,.jpeg,.png" name="tumbnail" required></p></td></tr>
			<tr><td><div class = "buttonfield"><input class = "postbutton" type="submit" name="submit" value="Post"><button class = "postbutton"><a class = "cancellink" href="javascript:HidePosting()">Cancel</a></button></div></td></tr>
			</tbody></table>
			</form>
			</div>
		</div>
		<?php
	}
	else
	{
		echo '<a class = "headerlink" href = "profile.php">Profile</a>
		<a class = "headerlink" href = "index.php?log=out">Log Out</a></div>';
	}
}
else
{
	?>
	<a class = "headerlink" href = "javascript:PopUpShow()" id = "login">Log In</a>
	</div>
	<div class = "loginpopup" id ="loginpopup">
			<div class = "login">
				<form method="post">
				<table border = "0"><tbody>
				<tr><td colspan="2"><input type="text" placeholder="Name" name="name" required/></td></tr>
				<tr><td colspan="2"><input type="password" placeholder="Password" name="password" required/></td></tr>
				<tr><td><input class = "buttonaction" formaction="index.php?log=in" formmethod="post" type="submit" value="Log In"/></td>
				<td><input class = "buttonaction" formaction="index.php?log=reg" formmethod="post" type="submit" value="Sign Up"></td></tr>
				</tbody></table></form>
			<button class = "buttonaction"><a href="javascript:PopUpHide()">Close</a></button>
			</div>
	</div>
	<?php	
}

if (isset($_GET["log"]))
{
	switch ($_GET["log"])
	{
		case 'in':
		{
			if (isset($_POST["name"]) && isset($_POST["password"]))
			{
				$user = $mysqli->query("SELECT * FROM `users` WHERE name = '".$_POST["name"]."'");
				if(mysqli_num_rows($user) == 1)
				{
					$userid = $user->fetch_assoc();
					if(password_verify($_POST["password"], $userid['password']))
					{
						$_SESSION['name'] = $_POST['name'];
						$_SESSION['id'] = $userid['id'];
						$_SESSION['status'] = $userid['status'];		
						echo '<meta http-equiv="refresh" content="0; url=profile.php">';
					}
					else
					{
						$_SESSION['username'] = $_POST["name"];
						$_SESSION['password'] = $_POST["password"];
						echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongpassword">';
					}
				}
				else 
				{
					$_SESSION['username'] = $_POST["name"];
					$_SESSION['password'] = $_POST["password"];
					echo '<meta http-equiv="refresh" content="0; url=index.php?error=nouser">';
				}
			}
			else echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
		case 'out':
		{
			session_destroy();
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			break;
		}
		case 'reg':
		{
			$dif = array('cost'=>8);
			if (isset($_POST["name"]) && isset($_POST["password"]))
			{
				if((strlen($_POST["name"])<20 && strlen($_POST["name"])>5) && 
				(preg_match("/[a-z0-9]/i" ,$_POST["name"]) && preg_match("/[a-z0-9]/i", $_POST["password"])))
				{
					if(mysqli_num_rows($mysqli->query("SELECT * FROM `users` WHERE name = '".$_POST["name"]."'")) == 0)
					{
						$sql = $mysqli->query("INSERT INTO `users`(`id`, `name`, `password`, `status`) 
						VALUES (NULL,'".$_POST["name"]."','".password_hash($_POST["password"], PASSWORD_BCRYPT, $dif)."','user')");
						echo '<meta http-equiv="refresh" content="0; url=index.php">';
					}
					else 
					{
						$_SESSION['username'] = $_POST["name"];
						$_SESSION['password'] = $_POST["password"];
						echo '<meta http-equiv="refresh" content="0; url=index.php?error=existinguser">';
					}
				}
				else 
				{
					$_SESSION['username'] = $_POST["name"];
					$_SESSION['password'] = $_POST["password"];
					echo '<meta http-equiv="refresh" content="0; url=index.php?error=registration">';
				}	
			}
			break;
		}
		default:
		{
		    echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
			break;
		}
	}
}
?>