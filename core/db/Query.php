<?php
namespace Storyteller\core\db;

use Storyteller\core\db\Select;

class Query implements Mysql {
  
  public $PDOConntector = null;
  public $stm = '';
  protected $_bind = array();
  protected $_isWhereExsists = false;
  
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
  
  public function insert($table, array $data, $showResult = false) {
    $insert = new Insert($this->PDOConntector, $table, $data);
    return $this->rowCount($insert->finalQuery());
  }
  
  public function update($table, array $data, $where, $showResult = false) {
    $update = new Update($this->PDOConntector, $table, $data, $where);
    return $this->rowCount($update->finalQuery());
  }
  
  public function delete($table, $where) {
    $delete = new Delete($this->PDOConntector, $table, $where);
    return $this->rowCount($delete->finalQuery());
  }
  
  public function from($table) {
    $this->stm .= self::SQL_FROM . ' `' . $table->info() . '` ';
    return $this;
  }
  
  public function where($sql, $value = null) {
    if (! $this->_isWhereExsists) {
      $this->stm .= self::SQL_WHERE . ' ' . $sql . ' ';
      $this->_isWhereExsists = true;
    } else {
      $this->stm .= self::SQL_AND . ' ' . $sql . ' ';
    }
    if (!is_null($value)) {
      $this->_addBindValue($sql, $value);
    }
    return $this;
  }
  
  public function orWhere($sql, $value = null) {
    if (!$this->_isWhereExsists) {
      $this->stm .= self::SQL_WHERE . ' ' . $sql . ' ';
      $this->_isWhereExsists = true;
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
//     var_dump($this);
//     exit;
    if (! empty($this->_bind)) {
      foreach ($this->_bind as $parameter => $value) {
        $query->bindValue($parameter, $value);
      }
    }
    return $query;
  }
  
  public function hasWhereCondition() {
    return $this->_isWhereExsists;
  }
  
  private function _addBindValue($sql, $value) {
    if (preg_match('/:\w+/', $sql, $match)) {
      $this->_bind += array (
        $match [0] => $value 
      );
    } elseif (preg_match('/\?/', $sql, $match)) {
      $this->_bind += array (
        count($this->_bind) + 1 => $value 
      );
    }
  }
  
  private function rowCount($query) {
    $query->execute();
    return $query->rowCount();
  }
}