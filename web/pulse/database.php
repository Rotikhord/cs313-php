<?php

  class Database{

    private $instance = null; //database instance

    public function __construct() {
      try{

        $dbUrl = getenv('DATABASE_URL');

        $dbOpts = parse_url($dbUrl);

        $dbHost = $dbOpts["host"];
        $dbPort = $dbOpts["port"];
        $dbUser = $dbOpts["user"];
        $dbPassword = $dbOpts["pass"];
        $dbName = ltrim($dbOpts["path"],'/');

        $this->instance = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
        $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      }catch(PDOException $e) {
          echo 'Error!: ' . $e->getMessage();
          die();
      }
    }

    public function getDB() {
      return $this->instance;
    }

  }

  ?>
