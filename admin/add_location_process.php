<?php
session_start();
include_once('chk_login.php');
?>
<?php
include('../db/db.php');

//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$location=$_POST['location'];

$query1=myslqi_query("SELECT * FROM location WHERE location='$location' ");
if(myslqi_num_rows($query1))
{
	echo "Already Location Existed";
}
else
{
	$query=myslqi_query("INSERT INTO location VALUES('','$location')");

if($query)
{
    header('Location:location.php');
}
else
{
	echo "Insertion Failed";
}

}
?>