<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\UserTable;

class UserController {
  
  private $_userModel = null;
  
  public function __construct() {
    $this->_userModel = new UserTable();
  }
  
  /**
   * 
   * @param array $postParams
   */
  public function registerUser($postParams) {
    $newUser = $this->_userModel->createUser($postParams);
    if (!empty($newUser)) {
      unset($newUser->pass);
      return array('success' => true, 'user' => $newUser);
    } else {
      return array('error' => 'User could not be created');
    }
  }
  
  /**
   * 
   * @param string $email
   * @param string $pass
   * @return Ambigous <\Storyteller\app\model\mixed, NULL>|string
   */
  public function checkLogin($email, $pass) {
    if ($this->_userModel->checkUser($email, $pass)) {
      $user = $this->_userModel->findUserByEmail($email);
      unset($user->pass);
      return array('success' => true, 'user' => $user);
    } else {
      return array('error' => true, 'message' => 'Invalid User Request');
    }
  }
  
  /**
   * 
   * @param string $id
   * @return \Storyteller\app\model\Ambigous
   */
  public function findUser($id) {
    if (is_numeric($id)) {
      $user = $this->_userModel->findUserByID((int) $id);
      if (!empty($user)) {
        unset($user->pass);
        return array('success' => true, 'user' => $user);
      } else {
        return array('error' => true, 'message' => 'No User found by given ID');
      }
    }
  }
}