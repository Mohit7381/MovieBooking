<?php
session_start();

include('../db/db.php');
include ('../db/admin.php');
include('../frontend.php');
//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$frontend=new frontend();

//loation
$array=$frontend->getlocation();
$size=sizeof($array);
$t_size=$size;
$i=0;
$t=0;

$admin=new admin();
$id=$_SESSION['id'];
if(isset($_SESSION['id']))
{
echo $admin->menu();
}
else
{
echo $admin->agentmenu();	
}

$movie_id = $_REQUEST['id'];
$movie_time = $_REQUEST['time'];
$mdate_code = $_REQUEST['mdate'];
$movie_date = base64_decode($mdate_code);
$referer = $_SERVER['HTTP_REFERER'];
$tid=$_REQUEST['fk_theater_id'];

$showqery=myslqi_query("SELECT show_id from assign_show WHERE fk_theater_id='$tid'");
$showrow=myslqi_fetch_array($showqery);
$show_id=$showrow['show_id'];


$sql = "select movies.name N1,theatres.name N2,location.location,assign_show.fk_location_id,assign_show.fk_movie_id,assign_show.fk_theater_id from assign_show join movies on assign_show.fk_movie_id=movies.id join location on assign_show.fk_location_id = location.id join theatres on assign_show.fk_theater_id=theatres.id where show_id='$show_id'";
$result = myslqi_query($sql);
$row = myslqi_fetch_array($result);

$movie_id = $row['fk_movie_id'];
$location_id = $row['fk_location_id'];
$theater_id = $row['fk_theater_id'];

$movie_name = $row['N1'];
$location_name = $row['location'];
$theater_name = $row['N2'];

$today = date('D-d-M');
?>
<!DOCTYPE html>
    <head>
        <title>Cinemachoodu - Book Show</title>
        <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
		<link href="../site1.css" rel="stylesheet"/>
		<link href="../select2.css" rel="stylesheet"/>
		<script src="../jquery-1.8.0.min.js"></script>   
		<script src="../select2.js"></script>
		<script src="../form.js"></script>
		<script src="../jquery.lightbox_me.js"></script>
    </head>
<body>
<div class="container">
	<li style="float:right;"><a href="logout.php">Logout</a></li></ul></header>
<div class='content'>
	<br/>
<div class='content-wrapper'>
<div class="left-content">
<?php
$sql = "select * from movies where id='$movie_id'";
$result = myslqi_query($sql);
$row = myslqi_fetch_array($result);
	$name = $row['name'];
	$imagename = $row['image'];
	$cast = $row['cast'];
	$director = $row['director'];
	$music = $row['music'];
	$desc = $row['desc'];
	echo"<table  cellpadding='5' style='text-align:center;'>";
	echo"<tr>";
	if($imagename != '')
	{
		echo "<td style='text-align:center;'><img src='../uploads/".$imagename."' style='border:solid 5px #eee;box-shadow:2px 2px 2px #333 ' width='120px' heigth='160px'/></td></tr>";
	}
	else{ echo "<td style='text-align:center;'>".$movie_date."</td></tr>"; }
	echo"<tr><td></td></tr>";
	if($cast != '')
	{
		echo"<tr><td><b>".$cast."</b></td></tr>";
	}

	echo"</td></tr>";
	echo"</table>";
?>
</div>
<div class="right-content">
<div class='show-booking'>
<h2><?php echo $name. "&nbsp;(U/A) - ". $movie_date; ?></h2>
<?php
echo"<table><tr><td style='width:150px;'><h3>".$theater_name." Theatre</h3></td><td style='width:150px;'><h3>Location:&nbsp;".$location_name."</h3></td><td style='width:50px;'>Seats : </td><td style='width:175px;font-size:22px;color:#3399ff;font-weight:bold' id='seatno'></td></tr></table>";
echo"<table><tr><td style='width:22px;'><img src='../images/show_date.png' /></td><td style='width:170px;'>Show Date</td><td style='width:22px;'><img src='../images/time_img.png' /></td><td style='width:170px;'>Show Time</td><td style='width:25px;'>Cost</td><td style='width:22px;'><img src='../images/ruesmall.png' /></td><td id='cost' style='width:175px;font-size:22px;color:green;font-weight:bold'></td></tr>";
echo"<tr><td colspan='2' style='text-align:center'><b>".$movie_date."</b></td><td colspan='2' style='text-align:center'><b>".$movie_time."</b></td><td colspan='3'></td></tr>";
echo"</table>";
echo"<table style='float:right;margin:0 163px 0 0;'><tr><td id='buy'><img src='../images/buy.png' width='105px' class='buy'/></td></tr></table>";
?>
</div>
</div>
<div class="seat-wrapper">
<div class='terms' style='display:none;'>
<h2>Terms and condition</h2>
<p>
	1.we are not responsible for the spam messages.2) You should reach theatre 30 min before to get your tickets.3) You should pay money what we mentioned in message and mail. 4) Ticket charges including internet service tax + actual ticket cost. 5) If you reach late to the theatre your tickets get cancelled.
</p>
<br/><input type='checkbox' name='accept' id='accept'> Check if accept Term's and Condition's above<br/></br/><span id='payoo'><img src='../images/pay.png' ></span>
</div><br/><br/>
<center>
<div class="seat-arrangement">
<img src='../images/all.png' width='653px' height='85px' /><br/><br/>
<?php
$sql = "select * from seats where fk_theater_id = '$theater_id'";
if(!$result = myslqi_query($sql)){
	die('SQL ERROR : '.myslqi_error());
}
else
{
	$cnt = myslqi_num_rows($result);

	if($cnt > 0)
	{
		$sql = "select * from assign_show where fk_movie_id='$movie_id' and  fk_theater_id='$theater_id' and fk_location_id = '$location_id'";
		$result = myslqi_query($sql);
		$rescnt = myslqi_num_rows($result);
		if($rescnt == 0)
		{
			echo "2";
		}
		else
		{
			$row = myslqi_fetch_array($result);
			$showid = $row['show_id'];
			$tdIdArray = array('');
			$chargesArray = array('');
			$seatNameArray = array('');
			$rowArray = array();
			$colArray = array();
			$seatAlphabet = array();
			
			$sql1 = "select * from charges where fk_show_id = '$showid'";
			$result1 = myslqi_query($sql1);
			
			while($row = myslqi_fetch_array($result1))
			{
				array_push($tdIdArray,$row['td_id']);
				array_push($chargesArray,$row['charges']);
				array_push($seatNameArray,$row['seat_name']);
				$explodeId = array();
				$explodeId = explode('.',$row['td_id']);
				array_push($rowArray,$explodeId[0]);
				array_push($colArray,$explodeId[1]);
			}

			 $maxrow = max($rowArray);
			 $maxCol = max($colArray);

			$dist_charges = array_unique($chargesArray);
 

			echo"<table cellpadding=1 >";
			for($i=1; $i<=$maxrow;$i++)
			{
				$alphabet = '';
				echo"<tr><td colspan='$maxCol' id='head$i'></td></tr>";
				echo"<tr>"; 
				echo"<td class='seat_alphabet$i' style='padding: 0 10px 0 0;'></td>";

				for($j=1; $j<=$maxCol; $j++)
				{
                    $tdid = $i.".".$j;
					$flag = true;
					$flag1 = false;
					for($m=0; $m<count($tdIdArray); $m++)
					{
						if($tdid === $tdIdArray[$m])
						{
							$seatnm = $seatNameArray[$m];
							$charges = $chargesArray[$m];
							if($charges != '' && $charges != '0')
							{
								$alphabet = substr($seatnm,0,1);
								$gcharges = $charges;
							}
							$sql2 = "select * from admin_booking where fk_show_id = '$showid' and seat_id='$tdid'";
							$result2 = myslqi_query($sql2);
							$res2cnt = myslqi_num_rows($result2);
							if($res2cnt > 0)
							{
								$sql2 = "select * from customer_booking where fk_show_id = '$showid' and seat_id='$tdid' and show_time='$movie_time' and show_date='$movie_date' and fk_movie_id='$movie_id'";
								$result2 = myslqi_query($sql2);
								$cnt = myslqi_num_rows($result2);
								if($cnt > 0)
								{
									echo"<td><img src='../images/sold.png'></td>";
								}
								else
								{
									echo"<td id='$tdid' class='seat'><img class='a' src='../images/available.png' title='".$charges."Rs' id='$tdid' /><input type='hidden' id='charges".$tdid."' value='$charges'><input type='hidden' id='seatname".$tdid."' value='$seatnm'></td>";
								}
							}
							else
							{
								echo"<td><img src='../images/unavailable.png' class='u' title='".$charges."Rs-$seatnm' /></td>";
							}
							$flag1 = true;
						}
					}
					if(!$flag1)
					{
						echo"<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/></td>";
					}
	 	
				}
				echo"<td class='seat_alphabet$i' style='padding: 0 0 0 10px;'></td>";
				echo"<input type='hidden' class='alphabet' id='getalphbet$i' value='".$alphabet."'>";
				echo"<input type='hidden' class='gcharges' id='gcharges$i' value='".$gcharges."'>";
				echo"</tr>";
			}
			echo"<input type='hidden' name='showid' id='showid' value='$showid' />";
			echo"</table><br/><br/>";
			echo"<span class='screen'><img src='../images/screen.jpg' /></span>";
			?>
			<!--<div class='cart' id='cart' style='display:none;'>
			<table>
				<tr><td colspan='2'><img src='images/cart.jpg' /></td>
				<tr><td colspan='2' style='font-weight:bold;padding:5px;'><?php echo $movie_name;?></td></tr>
				<tr><td style='padding:5px;'><?php echo $location_name; ?></td><td><?php echo $theater_name;?></td></tr>
				<tr><td style='padding:5px;'><img src='images/time_img.png' /><?php echo $movie_time?></td><td><img src='images/show_date.png' /><?php echo $movie_date?></td>
				<tr><td colspan='2' style='padding:5px;'>Seats : </td></tr>
				<tr><td colspan='2' id='cartseats' style='padding:5px;'></td></tr>
				<tr><td style='padding:5px;'>Cost <img src="images/ruesmall.png" /></td><td id="carttotal"></td></tr>
				<tr><td id='buy' colspan='2' style='padding:5px;'><img src='images/buy.png' width='105px' class='buy'/></td></tr>
			</table>
			</div>-->
			<?php
		}
	}
	else
	{
		echo "0";
	}
}
	//echo"<input type='button' id='book_seat' value='Book Movie' />";
	
	echo"<div id='result' style='visibility: hidden;'></div>";
?>
</div>
</center>
<div id='sign_up' style='display:none;'>
<form method='post' action='../confirm-booking.php' name='bookform' id='bookform'>
	<span id='close'>X</span>


<div id="user">

	<table>
		<tr><td>Enter your name</td><td><input type='text' name='fname' id='fname' required></td></tr>
		<tr><td>Contact Number</td><td><input type='tel' pattern="[0-9]{10,10}" name='contact' id='contact' autocomplete="off"  maxlength="10" required></td></tr>
		<tr><td>E-mail Id</td><td><input type='email' name='email' id='email' /></td></tr>
		<input type='hidden' name='location_id' id='location_id' value='<?php echo $location_id ?>'>
		<input type='hidden' name='movie_id' id='movie_id' value='<?php echo $movie_id?>'>
		<input type='hidden' name='theater_id' id='theater_id' value='<?php echo $theater_id?>'>
		<input type='hidden' name='movie_time' id='movie_time' value='<?php echo $movie_time?>'>
		<input type='hidden' name='movie_date' id='movie_date' value='<?php echo $movie_date?>'>
		<tr><td colspan='2'><input type='button' id='book_seat' id='book_seat' value='Confirm Booking' /></td></tr>
	</table>

</div>
	</form>
</div>
</div>
</div>
<script src="showbookings.js"></script>
<script>
$(document).ready(function(){
	var alpha = $('.alphabet');
	for(i=0; i<=alpha.length; i++)
	{
		var aplhabet = alpha.eq(i).val();
		var j = i+1;
		id = 'seat_alphabet'+j;
		$('.'+id).html(aplhabet);
		var gid = 'gcharges'+j;
		currentChrg = document.getElementById(gid).value;
		var col = 'head'+j;
		if(i == 0)
		{
			$('#'+col).html('Rs - '+currentChrg);
			$('#'+col).css('text-align','center');
			$('#'+col).css('color','#1a73c7');
			$('#'+col).css('padding','3px 0');
		}
		if(i != 0)
		{
			gprev = 'gcharges'+i;
			prevChrg = document.getElementById(gprev).value;
			if(currentChrg != prevChrg)
			$('#'+col).html('Rs - '+currentChrg);
			$('#'+col).css('text-align','center');
			$('#'+col).css('color','#1a73c7');
			$('#'+col).css('padding','3px 0');
		}
		
	}
});
</script>
</body>
<style type="text/css">
header {
	width: 100%;
	background: #3c8dbc;
	float: left;
}

header ul {
	width: 97%;
}

header ul li {
	color: #fff;
    float: left;
    font-family: verdana;
    font-size: 13px;
    list-style: none outside none;
    margin: 10px 2px;
    padding: 5px 0;
    text-align: center;
    width: 100px;
}

header ul li a {
	color: #fff;
	text-decoration: none;
}

header ul li a:hover {
	text-decoration: underline;
}
#payoo:hover
{
	cursor: hand;
}
</style>
</html>