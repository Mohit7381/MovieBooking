<?php
session_start();
include_once('chk_login.php');
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="style.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Load jQuery UI Main JS  -->
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
    <!-- Load SCRIPT.JS which will create datepicker for input field  -->
    <script src="script.js"></script>

<title>Add Movies by admin</title>
<?php
include('../db/db.php');
include("../db/admin.php");
include('../frontend.php');

//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$frontend=new frontend();

$array=$frontend->getlocation();
$size=sizeof($array);
$i=0;

$admin=new admin();

if($_REQUEST['id'])
{
	echo $admin->menu();
	$id=$_REQUEST['id'];
	$query=myslqi_fetch_array(myslqi_query("SELECT * FROM movies WHERE id='$id'"));
?>
<li style="float:right;"><a href="logout.php">Logout</a></li></ul></header>
<div id="content">
<form action="addmovies_process.php" method="POST" enctype="multipart/form-data" >
<table>
	<tr>
		<td>Movie Name</td>
		<td><input type="text" name="movie_name" id="movie_name" value="<?php echo $query['name'];?>" ></td>
	</tr>
	<tr>
		<td>Rating</td>
		<td><input type="text" name="movie_rating" id="movie_rating" value="<?php echo $query['rating'];?>" ></td>
	</tr>
	<tr>
		<td>Cast</td>
		<td><textarea name="movie_cast" id="movie_cast" ><?php echo $query['cast'];?></textarea></td>
	</tr>
	<tr>
		<td>Director</td>
		<td><input type="text" name="director" id="director" value="<?php echo $query['director'];?>" ></td>
	</tr>
	<tr>
		<td>Music</td>
		<td><input type="text" name="music" id="music" value="<?php echo $query['music'];?>"></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><textarea name="movie_desc" id="movie_desc" ><?php echo $query['desc'];?></textarea></td>
	</tr>
	<tr>
		<td>image</td>
		<td><img src="../uploads/<?php echo $query['image'];?>" style="width:100px !important" height=100px></td>
	</tr>
	<tr>
		<td>Pick a Date: </td><td><input type="text" id="datepicker" name="datepicker" value="<?php echo $query['release_date'];?>" /></td>
	</tr>
	<tr>
		<td>Upload image</td>
		<td><input type="file" name="photoimg" id="photoimg" /></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="submit" id="submit"></td>
		<td><a href="movies.php">Cancel Edit</a></td>
	</tr>

</form>
</div>
<?php
}

?>

