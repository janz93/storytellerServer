<?php

namespace Storyteller\app\model;

class TagTable extends Table {
  
  protected $_name = 'tag';
  
  public function getTags() {
    return $this->findAll($this->_name);
  }
  
}