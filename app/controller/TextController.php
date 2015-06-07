<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\TextTable;

class TextController {
  
  private $_textModel = null;
  
  public function __construct() {
    $this->_textModel = new TextTable();
  }
  
  public function createText($postArr) {
   $newStoryPart =  $this->_textModel->insertText($postArr);
   if (!empty($newStoryPart)) {
      return array('newStoryPart' => $newStoryPart);
    } else {
      return array('error' => 'new story part could not be created');
    }
  }
  
  public function updateText($id, $postParams) {
    if ($this->_textModel->updateText($id, $postParams) > 0) {
      return array('success' => 'Story part could be updated');
    } else {
      return array('error' => 'Story part could not be updated');
    }
  }
  
}