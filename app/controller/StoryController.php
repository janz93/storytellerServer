<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\StoryTable;

class StoryController {
  
  private $_storyModel = null;
  
  public function __construct() {
    $this->_storyModel = new StoryTable();
  }
  
  public function findStory($id) {
    if (is_numeric($id)) {
      $story = $this->_storyModel->getStory((int) $id);
      if (!empty($story)) {
        return array('storyPart' => $story);
      } else {
        return array('error' => 'No story found by given ID');
      }
    }
  }
  
  public function findUserStories($userId) {
    $stories = $this->_storyModel->getAllStoryForUser((int) $userId);
    if (!empty($stories)) {
      return array('stories' => $stories);
    } else {
      return array('error' => 'No stories found for given user');
    }
  }
  
  public function createStory($postParams) {
    $newStory = $this->_storyModel->createStory($postParams);
    if (!empty($newStory)) {
      return array('story' => $newStory);
    } else {
      return array('error' => 'Story could not be created');
    }
  }
  
  public function updateStory($id, $postParams) {
    if ($this->_storyModel->updateStory($id, $postParams) > 0) {
      return array('success' => 'Story could be updated');
    } else {
      return array('error' => 'Story could not be updated');
    }
  }
  
  public function deleteStory($id) {
    if ($this->_storyModel->deleteStory($id) > 0) {
      return array('success' => 'Story could be deleted');
    } else {
      return array('error' => 'Story could not be deleted');
    }
  }
}