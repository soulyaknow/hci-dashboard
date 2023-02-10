<?php
class database
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = "";
    private $dbms = "db_comshop";
    private $conn;
    private $status;
    function __construct()
    {
        $this->status = false;
        $this->conn = $this->init();
    }
    private function init()
    {
        try {
            $con = new PDO(
                "mysql:host=$this->host;dbname=" . $this->dbms,
                $this->user,
                $this->pass
            );
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->status = true;
            return $con;
        } catch (PDOException $th) {
            echo $th;
        }
    }
    public function getStatus()
    {
        return $this->status;
    }

    public function getCon()
    {
        return $this->conn;
    }
    public function dbClose()
    {
        return $this->conn = null;
    }
}