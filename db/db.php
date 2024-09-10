<?php 
date_default_timezone_set("Asia/Calcutta");
class db
{
	public function db_connect()
	{

    $hostname="localhost";
	$username="root";
	$password="";

    $con=new mysqli($hostname,$username,$password) or die(mysql_errno());

    return $con;

    }

    public function db_select()
    {
    $data=db_connect();
    $database="cinema_choodu";
    data->querry("CREATE DATABASE IF NOT EXISTS cinema_choodu");
    // $con1=mysq($database) or die(mysql_errno());

    return $con1;

    }
    public function createAndConnectDB($servername, $username, $password, $dbname) {
        // Create connection to MySQL server
        $conn = new mysqli($servername, $username, $password);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        // Create database if it does not exist
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully or already exists.";
        } else {
            echo "Error creating database: " . $conn->error;
            return null;
        }
    
        // Select the database
        $conn->select_db($dbname);
    
        // Return the connection object
        return $conn;
    }

    public function db_close()
    {

    mysql_close();

    }
}

?>