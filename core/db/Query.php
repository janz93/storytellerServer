<?php
namespace Storyteller\core\db;

use Storyteller\core\db\Select;

class Query implements Mysql {
  
  public $PDOConntector = null;
  public $stm = '';
  private $_bind = array();
  private $isWhereExsists = false;
  
  
  
  public function __construct() {
    try {
      $this->PDOConntector = new \PDO('mysql:' . DB_HOST . '=localhost;dbname=' . DB_DATABASE, DB_USER, DB_PASS);
      $this->PDOConntector->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo 'Error' . $e->getMessage();
    }
  }
  
  public function select() {
    return new Select($this->PDOConntector);
  }
  
  public function insert($table, array $data) {
    $this->_finalQuery = 'INSERT ';
  }
  
  public function update($table, array $data) {
    $this->_finalQuery = 'UPDATE ';
  }
  
  public function delete() {
    $this->_finalQuery = 'DELETE ';
  }
  
  public function from($table, $columns = array()) {
    $this->stm .= self::SQL_FROM . ' `' . $table->info() . '` ';
    if (!empty($columns)) {
      $this->columns($table, $columns);
    }
    return $this;
  }
  
  
  
  public function where($sql, $value = null) {
    if (! $this->isWhereExsists) {
      $this->stm .= self::SQL_WHERE . ' ' . $sql . ' ';
      $this->isWhereExsists = true;
    } else {
      $this->stm .= self::SQL_AND . ' ' . $sql . ' ';
    }
    if (!is_null($value)) {
      $this->_addBindValue($sql, $value);
    }
    return $this;
  }
  
  public function orWhere($sql, $value = null) {
    if (!$this->isWhereExsists) {
      $this->stm .= self::SQL_WHERE . ' ' . $sql . ' ';
      $this->isWhereExsists = true;
    } else {
      $this->stm .= self::SQL_OR . ' ' . $sql . ' ';
    }
    if (!is_null($value)) {
      $this->_addBindValue($sql, $value);
    }
    return $this;
  }
  
  public function finalQuery() {
    $query = $this->PDOConntector->prepare($this->stm);
    if (! empty($this->_bind)) {
      foreach ($this->_bind as $parameter => $value) {
        $query->bindValue($parameter, $value);
      }
    }
    return $query;
  }
  
  private function _addBindValue($sql, $value) {
    if (preg_match ('/:\w+/', $sql, $match)) {
      $this->_bind += array (
        $match [0] => $value 
      );
    } elseif (preg_match ('/\?/', $sql, $match)) {
      $this->_bind += array (
        count($this->_bind) + 1 => $value 
      );
    }
  }
  
}