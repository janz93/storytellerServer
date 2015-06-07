<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\TagTable;
class TagController {
  
  private $_tagModel = null;
  
  public function __construct() {
    $this->_tagModel = new TagTable();
  }
  
  public function getAllTags() {
    $tags = $this->_tagModel->getTags();
    if (!empty($tags)) {
      return array('tags' => $tags);
    } else {
      return array('error' => 'No tags found');
    }
  }
}