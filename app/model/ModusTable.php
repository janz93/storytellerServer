<?php

namespace Storyteller\app\model;

class ModusTable extends Table {
  
  protected $_name = 'modus';
  
  public function getModus() {
    return $this->findAll($this->_name);
  }
  
}