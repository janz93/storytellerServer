<?php
namespace Storyteller\core\db;

use Storyteller\core\db\Select;

class Query {
  
  private $_PDOConntector = null;
  private $_finalQuery = '';
  
  public function __construct() {
    try {
      $this->_PDOConntector = new \PDO('mysql:' . DB_HOST . '=localhost;dbname=' . DB_DATABASE, DB_USER, DB_PASS);
      $this->_PDOConntector->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo 'Error' . $e->getMessage();
    }
  }
  
  public function select() {
    return new Select($this->_PDOConntector);
  }
  
  public function insert(array $data) {
    $this->_finalQuery = 'INSERT ';
  }
  
  public function update(array $data) {
    $this->_finalQuery = 'UPDATE ';
  }
  
  public function delete() {
    $this->_finalQuery = 'DELETE ';
  }
}