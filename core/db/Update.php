<?php

namespace Storyteller\core\db;

class Update extends Query {
  
  public function __construct($pdo, $data) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_UPDATE . ' ';
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
}