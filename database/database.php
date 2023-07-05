<?php

class Database
{
  private $host, $db_name, $username, $password;
  public $conn;

  function __construct()
  {
    // 1357
    $online = array("srv158.niagahoster.com", "u1796449_rianseptiadi", "Rtbcvyfhgnpozx1!", "u1796449_distribusiamanah");
    $offline = array("localhost", "root", "", "distribusiamanah");

    $koneksi = 1; // 1 online, 0 offline

    if ($koneksi == 1) {
      $this->host = $online[0];
      $this->username = $online[1];
      $this->password = $online[2];
      $this->db_name = $online[3];
    } else {
      $this->host = $offline[0];
      $this->username = $offline[1];
      $this->password = $offline[2];
      $this->db_name = $offline[3];
    }
  }

  public function getConnection()
  {
    $this->conn = null;
    try {
      $this->conn = new PDO(
        "mysql:host=" . $this->host .
          ";dbname=" . $this->db_name,
        $this->username,
        $this->password
      );

      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Koneksi Error : " . $e->getMessage();
    }
    return $this->conn;
  }
}
