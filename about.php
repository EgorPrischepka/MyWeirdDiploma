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
	    echo '<title>About Tiny Leaders</title>
	    <div class = "wrapper"><div class = "abouttl">
	    <h1>About Tiny Leaders</h1>'.nl2br('Tiny Leaders is another nontraditional way to play Magic: the Gathering. Bring your low mana cost cards and have a fun!').'<h1>Tiny Leaders Rules:</h1>'.
	    nl2br('1. Tiny Leaders Deck contains 50 cards including commander.
	    2. Only cards with mana value 3 and less allowed. X is 0.
	    3. Tiny Leaders is a Singleton format, so no more one copy of any card allowed except basic lands and cards those have "A deck can have (any/up to X) cards.
	    4. Starting Life Total equals 25.
	    5. No commander damage.
	    6. If player doesn\'t like their hand, they can take mulligan. That player shuffles their hand and draw 7 cards, then put X cards on the bottom of their library where X is number of taken mulligans.
	    
	    Additional Rule. If you play multiplayer variant of Tiny Leaders, you may concede <b>only as a sorcery</b>.').
        '<h1>Banlist</h1>
        <table>';
        $bannedcards = $mysqli->query("SELECT * FROM `card_base` WHERE legality = 'banned' ORDER BY fullname ASC");
        while($banned=$bannedcards->fetch_assoc())
        {
            echo '<tr><td><a class = "imagepreview" href="cardinfo.php?id='.$banned['id'].'">'.$banned['fullname'].'<span>
					<div class = "cardimage"><img src = "'.json_decode($banned['image']).'" width = "200">
					</div></span></a></td>
					</tr>';
        }
        
        echo '</table></div></div>' 
	    ;
	}
}
echo '</body></html>';
?>