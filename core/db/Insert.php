<?php

namespace Storyteller\core\db;

class Insert extends Query {
  
  public function __construct($pdo, $data) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_INSERT . ' ';
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
}