<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\CategoryTable;

class CategorieController {
  
  private $_categorieModel = null;
  
  public function __construct() {
    $this->_categorieModel = new CategoryTable();
  }
  
  public function getAllCategories() {
  $categories = $this->_categorieModel->getCategories();
  if (!empty($categories)) {
      return array('success' => true, 'categories' => $categories);
    } else {
      return array('error' => true, 'message' => 'No stories found for given user');
    }
  }
}