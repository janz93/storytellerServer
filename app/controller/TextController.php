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
      return array('success' => true, 'newStoryPart' => $newStoryPart);
    } else {
      return array('error' => true, 'message' => 'new story part could not be created');
    }
  }
  
  public function updateText($id, $postParams) {
    if ($this->_textModel->updateText($id, $postParams) > 0) {
      return array('success' => true, 'message' => 'Story part could be updated');
    } else {
      return array('error' => true, 'message' => 'Story part could not be updated');
    }
  }
  
  public function findCollaborateStories($userId) {
    $stories = $this->_textModel->getAllCollaborateStoryForUser((int) $userId);
    if (!empty($stories)) {
      return array('success' => true, 'collaborateStories' => $stories);
    } else {
      return array('error' => true, 'message' => 'No stories found for given user');
    }
  }
  
}