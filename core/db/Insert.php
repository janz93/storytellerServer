<?php

namespace Storyteller\core\db;

class Insert extends Query {
  
  public function __construct($pdo, $table, $data) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_INSERT . ' `' . $table->info() . '` ' . $this->_addData($data);
  }
  
  private function _addData($data) {
    $queryStr = '';
    $dataCount = count($data);
    $queryStr .= '(' . implode(', ', array_keys($data[0])) . ') ' . self::SQL_INSERT_VALUES . ' ';
    for ($i = 0; $i < $dataCount; $i++) {
      $queryStr .= '(';
      $c = 1;
      foreach ($data[$i] as $column => $value) {
        $queryStr .= ':' . $column . $i;
        $queryStr .= ($c < count($data[$i])) ? ', ' : '';
        $this->_bind += array(':' . $column . $i => $value);
        $c++;
      }
      $queryStr .= (($i + 1) < $dataCount) ? '), ' : ')';
    }
    return $queryStr;
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
}