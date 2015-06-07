<?php

namespace Storyteller\core\Middleware;

use Storyteller\app\model\UserTable;

class Authentication extends \Slim\Middleware {
  
  private $_noAuthentificationRoutes = array(
    '/',
    '/registration',
    '/login'
  );
  
  public static $validUser;
  
  private $_userTable = null;
  
  public function __construct() {
    if (!isset($this->app)) {
      $this->app = \Slim\Slim::getInstance();
    }
    
    $this->_userTable = new UserTable();
  }
  
  public function call() {
    $req = $this->app->request();
    $res = $this->app->response();
    $message = array();
    if (!in_array($req->getResourceUri(), $this->_noAuthentificationRoutes)) {
      $header = apache_request_headers();
      // Verifying Authorization Header
      if (!empty($header['Authorization'])) {
        // validating api key
        if (!$this->_isValidApikey($header['Authorization'])) {
          // api key is not present in users table
          $message["error"] = "Access Denied. Invalid Api key";
        } else {
          self::$validUser = $this->_userTable->findUserByApikey($header['Authorization']);
        }
      } else {
        // api key is missing in header
        $message["error"] = "Api key is misssing";
      }
      if (!empty($message)) {
        $res->setStatus(401);
        echo (json_encode(array('message' => $message)));
        exit;
      }
    }
    $this->next->call();
  }
  
  private function _isValidApikey($apikey) {
    $user = $this->_userTable->findUserByApikey($apikey);
    if (!empty($user)) {
      return true;
    } else {
      return false;
    }
  }
}