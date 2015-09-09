<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\StoryTable;

class StoryController {
  
  private $_storyModel = null;
  
  public function __construct() {
    $this->_storyModel = new StoryTable();
  }
  
  public function testFind() {
    $this->_storyModel->testFind();
  }
  
  public function findStory($id) {
    if (is_numeric($id)) {
      $story = $this->_storyModel->getStory((int) $id);
      if (!empty($story)) {
        return array('success' => true, 'storyParts' => $story);
      } else {
        return array('error' => true, 'message' => 'No story found by given ID');
      }
    }
  }
  
  public function findStoryByTitle($title) {
     if (!empty($title)) {
      $searchResult = $this->_storyModel->findStoryByTitle($title);
      if (!empty($searchResult)) {
        return array('success' => true, 'searchResult' => $searchResult);
      } else {
        return array('error' => true, 'message' => 'No story found by given Title');
      }
    } 
  }
  
  public function findStoryByCategory($categoryName) {
    if (!empty($categoryName)) {
      $searchResult = $this->_storyModel->findStoryByCategory($categoryName);
      if (!empty($searchResult)) {
        return array('success' => true, 'searchResult' => $searchResult);
      } else {
        return array('error' => true, 'message' => 'No story found by given Category');
      }
    }
  }
  
  public function findStoryByQuery($searchQueryArr) {
    if (!empty($searchQueryArr)) {
      $searchResult = $this->_storyModel->findStoryByQuery($searchQueryArr);
      if (!empty($searchResult) && (count($searchQueryArr) == 2)) {
        return array('success' => true, 'searchResult' => $searchResult);
      } else {
        return array('error' => true, 'message' => 'No story found by given Category');
      }
    }
  }
  
  public function findUserStories($userId) {
    $stories = $this->_storyModel->getAllStoryForUser((int) $userId);
    if (!empty($stories)) {
      return array('success' => true, 'ownStories' => $stories);
    } else {
      return array('error' => true, 'message' => 'No stories found for given user');
    }
  }
  
  public function createStory($postParams) {
    $newStory = $this->_storyModel->createStory($postParams);
    if (!empty($newStory)) {
      return array('success' => true, 'story' => $newStory);
    } else {
      return array('error' => true, 'message' => 'Story could not be created');
    }
  }
  
  public function updateStory($id, $postParams) {
    if ($this->_storyModel->updateStory($id, $postParams) > 0) {
      return array('success' => true, 'message' => 'Story could be updated');
    } else {
      return array('error' => true, 'message' => 'Story could not be updated');
    }
  }
  
  public function deleteStory($id) {
    if ($this->_storyModel->deleteStory($id) > 0) {
      return array('success' => true, 'message' => 'Story could be deleted');
    } else {
      return array('error' => true, 'message' => 'Story could not be deleted');
    }
  }
  
}