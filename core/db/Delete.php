<?php

namespace Storyteller\core\db;

class Delete extends Query {
  
  public function __construct($pdo, $table, $where = null) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_DELETE . ' ';
    $this->from($table);
    if (!is_null($where)) {
      $this->stm .= $where->stm;
      $this->_isWhereExsists = $where->_isWhereExsists;
      $this->_bind = $where->_bind;
    }
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
  
}