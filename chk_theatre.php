<?php
session_start();
include('db/db.php');
//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$theatre_id = $_REQUEST['theatre_id'];

$sql = "select * from seats where fk_theater_id = '$theatre_id'";
$result = myslqi_query($sql);
$cnt = myslqi_num_rows($result);

if($cnt > 0)
{
	echo '1';
}
else
{
	echo '0';
}