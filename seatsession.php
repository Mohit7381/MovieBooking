<?php
include('db/db.php');

//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$time = $_POST['time'];
$date = $_POST['date'];
$chargeid = $_POST['chargeid'];
$ip=$_POST['ip'];
$timestamp=time();

$check=myslqi_query("SELECT * from seatsession WHERE date='$date' AND time='$time' AND fk_charges_id='$chargeid' ");
if(myslqi_num_rows($check)>0)
{
	echo "blocked";
}
else
{
	$query="INSERT INTO seatsession VALUES ('','$chargeid','$time','$date','$timestamp','$ip')";
if(!$result = myslqi_query($query))
{
	die('SQL ERROR : '.myslqi_error());
}

}

?>