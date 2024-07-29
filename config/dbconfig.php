<?php error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
DEFINE("TITLE","Kadmin - BEST CMS SYSTEM IN THE WORLD");
class Database
{   
    private $host = "localhost";
    private $db_name = "raw";
    private $username = "root";
    private $password = "";
    public $conn;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>