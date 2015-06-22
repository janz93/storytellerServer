<?php

namespace Storyteller\app\model;

class TextTable extends Table {
  
  protected $_name = 'text';
  
  private $__referenceTables = array(
    'user' => array(
      'columns' => 'author_id',
      'refTable' => 'user',
      'refColumn' => 'id'
    ), 
    'story'=> array(
      'columns' => 'story_id',
      'refTable' => 'story',
      'refColumn' => 'id'
    )
  );
  
  public function insertText($textArr) {
    if ($this->_isNotStoryFinished($textArr['story_id'])) {
      $data = array(
        'author_id' => $textArr['author_id'],
        'story_id' => $textArr['story_id'],
        'num' => $this->getTextAmountForStory($textArr['story_id']) + 1,
        'content' => $textArr['content']
      );
      return $this->insert($this->_name, $data);
    }
    return false;
  }
  
  public function updateText($id, $textArr) {
    $this->setWhereCondition('`id` = :id', array(':id', $id));
    $data = array();
    foreach ($textArr as $column => $value) {
      $data[$column] = $value;
    }
    return $this->update($this->_name, $data);
  }
  
  public function getAllCollaborateStoryForUser($id) {
    $this->setJoin('story', '(`story`.`id` = `text`.`story_id` AND `text`.`author_id` != `story`.`author_id`)');
    $this->setWhereCondition('`' . $this->_name . '`.`author_id` = ?' , $id);
    return $this->findAll($this->_name);
  }
  
  private function _isNotStoryFinished($storyId) {
    $sql = 'SELECT `num_text` AS storyLimit FROM `story` WHERE `id` = ?';
    $query = $this->_PdoConntector->prepare($sql);
    $query->execute(array($storyId));
    $storyRow = $query->fetch();
    $storyTextLimit = $storyRow['storyLimit'];
    $storyTextTotal = $this->getTextAmountForStory($storyId);
    return ($storyTextLimit > $storyTextTotal);
  }
  
  private function getTextAmountForStory($storyId) {
    $sql = '
        SELECT count(*) AS storyTotal
        FROM `' . $this->_name . '`
        WHERE `story_id` = ?
    ';
    $query = $this->_PdoConntector->prepare($sql);
    $query->execute(array($storyId));
    $storyAmount = $query->fetch();
    return (int) $storyAmount['storyTotal'];
  }
}