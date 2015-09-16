<?php

namespace Storyteller\core\db;

use Storyteller\core\db;

class Select extends Query {
  
  public function __construct($pdo) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_SELECT . ' %s ';
  }
  
  private $_columns = array();
  
  public function columns($table, $columns) {
    $this->_columns += array($table->info() => $columns);
    return $this;
  }
  
  public function limit($count, $offset = 0) {
    if (is_numeric($count) && $count > 0) {
      $this->stm .= self::SQL_LIMIT . ' ' . $count . ' ';
      if ($offset > 0) {
        $this->stm .= self::SQL_OFFSET . ' ' . $offset . ' ';
      }
    }
    return $this;
  }
  
  public function order($arg) {
    $argsAmount = count($arg);
    if (is_array($arg) && $argsAmount > 1) {
      $this->stm .= self::SQL_ORDER_BY;
      for ($i = 0; $i < $argsAmount; $i ++) {
        $this->stm .= $this->_checkOrder($arg [$i]) . ', ';
      }
      $this->stm .= ' ';
    } else {
      $this->stm .= self::SQL_ORDER_BY . ' ' . $this->_checkOrder($arg) . ' ';
    }
    return $this;
  }
  
  public function finalQuery() {
    $finalColumns = '';
    if (empty($this->_columns)) {
      $finalColumns = self::SQL_WILDCARD;
    } else {
      $numColumnsTables = count($this->_columns);
      $j = 1;
      foreach ($this->_columns as $table => $columnsArr) {
        $numColums = count($columnsArr);
        for ($i = 0; $i < $numColums; $i++) {
          $finalColumns .= '`' . $table . '`.`' . $columnsArr[$i] . '`';
          if ($j == $numColumnsTables && $i == ($numColums -1)) {
            $finalColumns .= '';
          } else {
            $finalColumns .= ', ';
          }
        }
        $j++;
      }
    }
    $this->stm = sprintf($this->stm, $finalColumns);
    
    return parent::finalQuery($this->PDOConntector);
  }

  
  private function _checkOrder($sql) {
    if (!is_string($sql)) {
      throw new \InvalidArgumentException('$sql argument must be a string');
    }
    if (preg_match('/(A|DE)SC/', $sql)) {
      return $sql;
    } else {
      return $sql . ' ASC';
    }
  }
  
}