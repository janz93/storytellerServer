<?php

namespace Storyteller\core\db;

class Delete extends Query {
  
  public function __construct($pdo) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_DELETE . ' ';
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
  
}