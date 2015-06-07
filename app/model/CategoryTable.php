<?php

namespace Storyteller\app\model;

class CategoryTable extends Table {
  
  protected $_name = 'category';
  
  public function getCategories() {
    return $this->findAll($this->_name);
  }
  
}