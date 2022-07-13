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
			$user = $mysqli->query("SELECT * FROM `users` WHERE id = '".$_GET['id']."'");
			$usered = $user->fetch_assoc();
			echo '<title>'.$usered['name'].' Profile</title>
			<div class = "mainwrapper">
				<div class = "profilepage">
					<h1>'.$usered['name'].'</h1>
					<div class = "userdecks">
					<h1>User Decks</h1>';
						$userdex = $mysqli->query("SELECT * FROM `decks` WHERE users_id = '".$_GET['id']."'");
						if(mysqli_num_rows($userdex)==0)
						{
							echo '<h1>No submitted decks</h1>';
						}
						else
						{
							if($_SESSION['id']==$usered['id'])
							{
								echo '<table class = "profiletable" border="0"><tbody>
								<tr><th width = "200">Deck</th><th width = "125">Legality</th><th width = "150">Action</th><tr>';
								while($userdexed = $userdex->fetch_assoc())
								{
									echo '<tr><td><a href="deck.php?id='.$userdexed['id'].'">'.$userdexed['name'].'</a></td>
									<td>'.$userdexed['legality'].'</td>
									<td>
									<button class = "buttonaction">
									<a class = "linkaction" href="deckeditor.php?id='.$userdexed['id'].'">Edit</a>
									</button>
									<button class = "buttonaction">
									<a class = "linkaction" href="deletedeck.php?id='.$userdexed['id'].'">Delete</a>
									</button>
									</td></tr>';
								}	
							}
							else
							{
								echo '<table class = "profiletable" border="0"><tbody>
								<tr><th width = "200">Deck</th><th width = "125">Legality</th><tr>';
								while($userdexed = $userdex->fetch_assoc())
								{
									echo '<tr><td><a href="deck.php?id='.$userdexed['id'].'">'.$userdexed['name'].'</a></td>
									<td>'.$userdexed['legality'].'</td>
									</tr>';
								}
							}	
						}
						
					echo '</tbody></table></div><br>';
			if($_SESSION['id']==$_GET['id'] && ($_SESSION['status']=='admin'))
			{
				echo '
				<div class = "usertable">
				<h1>User Table</h1>';
				$usertable = $mysqli->query("SELECT * FROM `users`");
				echo '<table class = "profiletable" border="0"><tbody>
				<tr><th width = "200">User</th><th width = "100">Status</th><th width = "225">Action</th><tr>';
				while($usertabled = $usertable->fetch_assoc())
				{
					echo '<tr><td><a href="profile.php?id='.$usertabled['id'].'">'.$usertabled['name'].'</a></td><td>'.$usertabled['status'].'</td><td>';
					switch($usertabled['status'])
					{
						case 'admin':
						{
							echo 'No action permitted';
							break;
						}
						case 'user':
						{
							echo '<button class = "buttonaction"><a class = "linkaction" href="useraction.php?id='.$usertabled['id'].'&act=ban">Ban</a></button>';
							break;
						}
						case 'banned':
						{
							echo '<button class = "buttonaction"><a class = "linkaction" href="useraction.php?id='.$usertabled['id'].'&act=unban">Unban</a></button>';
							break;
						}
					}
					echo '</td></tr>';
				}
				echo'</tbody></table></div><br>
				';
				?>
				
				<div class = "cardbaseupdate">
					<h1>Banlist Update</h1>
					<form action="banlist.php" method="post" enctype="multipart/form-data">
					<p><input type="file" name="file" required></p>
					<input type="submit" name="update banlist" value="Submit">
					</form>
					<h1>Card Database update</h1>
					<form id="dbupload">
					<p><input type="file" name="file" class="file" required></p>
					<input type="submit" name="submit" class="submit" value="Submit">
					</form>
					<style>#loading{position:absolute; z-index:1000; display:none}</style>
					<div id="loading"><img id="loadImg" src="content/loading.gif" width="40%"/>
					<p>Updating a Card Database. Please, be patient and play Genshin Impact</p></div>
					<div id="msg"></div>
					<script src="jquery-latest.js"></script>
					<script>
					    $(function() 
						{
					        $('.submit').on('click', function() 
							{
								
					            var file_data = $('.file').prop('files')[0];
					            if(file_data != undefined) 
								{
									$("#loading").show();
					                var form_data = new FormData();                  
					                form_data.append('file', file_data);
					                $.ajax(
									{
					                    type: 'POST',
					                    url: 'massupload.php',
					                    contentType: false,
					                    processData: false,
					                    data: form_data,
					                    success:function(response) 
										{
					                        if(response) 
											{
					                            $("#msg").html(response);
												$("#dbupload").hide();
												$("#loading").hide();
					                        }
											else
											{
												$("#msg").html('No file detected');
											}
					                        $('.file').val('');
					                    }
					                });
					            }
					            return false;
					        });
					    });
					</script>
				</div><br>
				<?
			}
			if($_SESSION['id']==$usered['id'])
			{
				echo '
				</div><div class = "usersettings">
					<h1>User Settings</h1>
					<div class = "userpass">
						<table><tbody>
						<tr><td>Old Pass: </td><td><input type="password" id = "oldpass" required></td></tr>
						<tr><td>New Pass: </td><td><input type="password" id = "newpass" required></td></tr>
						<tr><td colspan="2"><div class="changemsg" id="passmsg"></div></td></tr>
						</tbody></table>
						<input class = "changebutton" type="submit" value = "Change" onClick = "changePassword()">';
						?>
						<script src="jquery-latest.js"></script>
						<script>
						function changePassword()
						{
							var oldp = $('#oldpass').val();
							var newp = $('#newpass').val();
							$.ajax(
							{
								type: "POST",
								url: "changepassword.php",
								dataType: 'html',
								data: {oldp: oldp, newp: newp},
							}
							).done(function(response)
							{
								$("#passmsg").html(response);
							});
						}
						</script>
						<?php
					echo '</div>
				</div>
				';
			}
			echo '</div></div>';
		}
		else if(isset($_SESSION['id']))
		{
		    echo '<meta http-equiv="refresh" content="0; url=profile.php?id='.$_SESSION['id'].'">';
		}
		else
		{
			echo '<meta http-equiv="refresh" content="0; url=index.php?error=wrongdoor">';
		}
	}
}
?>