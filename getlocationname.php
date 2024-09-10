<?php
include('db/db.php');
//Database connection selection
$db=new db();
$db->db_connect();
$db->db_select();

$loc_id = $_REQUEST['loc_id'];

$sql = "SELECT location FROM location WHERE id='$loc_id'";
$result = myslqi_query($sql);
$row=myslqi_fetch_array($result);

echo $row['location'];
?>