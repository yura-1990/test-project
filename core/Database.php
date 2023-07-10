<?php



interface ConnectionDatabase
{
    public function connect();
}

class Database implements ConnectionDatabase
{
    private string $dbDriver;
    private string $host;
    private string $db;
    private int $port;
    private string $user;
    private string $password;

    public function __construct($dbDriver='mysql' ,$host='app_mysql', $db='testproject', $port=3306, $user='root', $password='root')
    {
        $this->dbDriver = $dbDriver;
        $this->host = $host;
        $this->db = $db;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect(){
        try {
            $conn = new PDO("$this->dbDriver:host=$this->host;port=$this->port;dbname=$this->db", $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;

        }catch (PDOException $exception){
            return 'error' . $exception->getMessage();
        }
    }
}

