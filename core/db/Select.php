<?php
namespace Storyteller\core\db;

use Storyteller\core\db;

class Select {
  
  private $_db = null;
  private $_stm = '';
  private $_bind = array();
  private $isWhereExsists = false;
  
  public function __construct(\PDO $db) {
    $this->_db = $db;
    $this->_stm = 'SELECT ';
  }
  
  public function from($table, $columns = array()) {
    if (empty($columns)) {
      $this->_stm .= '* ';
    } else  {
      $this->_stm .= implode(', ' . $table->info() . '.', $columns) . ' ';
    }
    $this->_stm .= 'FROM ' . $table->info() . ' ';
    return $this;
  }
  
  public function where($sql, $value = null) {
    if (!$this->isWhereExsists) {
      $this->_stm .= 'WHERE ' . $sql . ' ';
      $this->isWhereExsists = true;
    } else {
      $this->_stm .= 'AND ' . $sql . ' ';
    }
    if (!is_null($value)) {
      $this->_addBindValue($sql, $value);
    }
    return $this;
  }
  
  public function orWhere($sql, $value = null) {
  if (!$this->isWhereExsists) {
      $this->_stm .= 'WHERE ' . $sql . ' ';
      $this->isWhereExsists = true;
    } else {
      $this->_stm .= 'OR ' . $sql . ' ';
    }
    if (!is_null($value)) {
      $this->_addBindValue($sql, $value);
    }
    return $this;
  }
  
  public function order($arg) {
    $argsAmount = count($arg);
    if (is_array($arg) && $argsAmount > 1) {
      $this->_stm .= 'ORDER BY ';
      for ($i = 0; $i < $argsAmount; $i++) {
        $this->_stm .= $this->_checkOrder($arg[$i]) . ', ';
      }
      $this->_stm .= ' ';
    } else {
      $this->_stm .= 'ORDER BY ' . $this->_checkOrder($arg) . ' ';
    }
    return $this;
  }
  
  public function limit($count, $offset = 0) {
    if (!is_numeric($count) && $count > 0) {
      $this->_stm .= 'LIMIT ' . $count . ' ';
      if ($offset > 0) {
        $this->_stm .= ' OFFSET ' . $offset . ' ';
      }
    }
  }
  
  public function finalQuery() {
//     var_dump($this->_bind);
//     exit;
//     $string = 'john';
//     $sql = "select * from user where name = ? AND email = ?";
//     $query = $this->_db->prepare($sql);
//     $query->bindValue(1, $string);
//     $query->bindValue(2, $string);
    
    $query = $this->_db->prepare($this->_stm);
    if (!empty($this->_bind)) {
      foreach ($this->_bind as $parameter => $value) {
        var_dump($parameter);
        var_dump($value);
        $query->bindValue($parameter, $value);
      }
    }
    return $query;
  }
  
  private function _addBindValue($sql, $value) {
    if (preg_match('/:\w+/', $sql, $match)) {
      $this->_bind += array($match[0] => $value);
    } elseif (preg_match('/\?/', $sql, $match)) {
      $this->_bind += array(count($this->_bind) + 1 => $value);
    }
  }
  
  private function _checkOrder($sql) {
    if (preg_match('/(A|DE)SC/', $sql)) {
      return $sql;
    } else {
      return $sql . ' ASC';
    }
  }
  
}