<?php
    $breast = mysqli_connect("92.113.24.51", "u847989251_athena", "SevenSeas@112233", "u847989251_seven_seas");
    // $breast = mysqli_connect("localhost", "root", "", "seven_seas");
    mysqli_set_charset($breast, 'utf8');

    class db
    {
        private $host   = "92.113.24.51";
        private $dbname = "u847989251_seven_seas";
        private $user   = "u847989251_athena";
        private $psw    = "SevenSeas@112233";
        public $connect;

        // private $host   = "localhost";
        // private $dbname = "seven_seas";
        // private $user   = "root";
        // private $psw    = "";
        // public $connect;

        public function getConnection()
        {
            $this->connect = null;
            try
            {
                $this->connect = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->psw);
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $excep)
            {
                echo "Erro de conexao: ".$excep->getMessage();
            }
            return $this->connect;
        }
    }