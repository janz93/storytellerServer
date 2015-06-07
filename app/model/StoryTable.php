<?php

namespace Storyteller\app\model;

use Storyteller\app\model\UserTable;

class StoryTable extends Table {
  
  protected $_name = 'story';
  
  private $_dependentTables = array(
    'UserTable', 
    'CategoryTable',
    'TagTable',
    'ModusTable'
  );
  
  public function getStory($id) {
    $this->setJoin('text', '`' . $this->_name . '`.`id` = `text`.`story_id`');
    $this->setWhereCondition('`' . $this->_name . '`.`id` = ?', $id);
    return $this->findAll($this->_name);
  }
  
  public function getAllStoryForUser($id) {
    $this->setWhereCondition('`author_id` = ?' , $id);
    return $this->findAll($this->_name);
  }
  
  public function createStory($storyArr) {
    $data = array(
      'author_id' => $storyArr['author_id'],
      'title' => $storyArr['title'],
      'category_id' => $storyArr['category_id'],
      'num_text' => $storyArr['num_text'],
      'num_chars' => $storyArr['num_chars'],
      'modus_id' => $storyArr['modus_id']
    );
    return $this->insert($this->_name, $data);
  }
  
  public function updateStory($id, $storyArr) {
    $this->setWhereCondition('`id` = :id', $id);
    $data = array();
    foreach ($storyArr as $column => $value) {
      $data[$column] = $value;
    }
    return $this->update($this->_name, $data);
  }
  
  public function deleteStory($id) {
    $this->setWhereCondition('`id` = ?', $id);
    return $this->delete($this->_name);
  }
  
}