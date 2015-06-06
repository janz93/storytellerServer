<?php

namespace Storyteller\core\Middleware;

use Storyteller\app\model\UserTable;
use Slim\Slim;

class Authentication extends \Slim\Middleware {
  
  private $_noAuthentificationRoutes = array(
    '/',
    '/registration',
    '/login'
  );
  
  public function __construct() {
    if (!isset($this->app)) {
      $this->app = \Slim\Slim::getInstance();
    }
  }
  
  public function call() {
    $req = $this->app->request();
    $res = $this->app->response();
    $message = array();
    if (!in_array($req->getResourceUri(), $this->_noAuthentificationRoutes)) {
      $apikey = apache_request_headers()['Authorization'];
      // Verifying Authorization Header
      if (!empty($apikey)) {
        // validating api key
        if (!$this->_isValidApikey($apikey)) {
          // api key is not present in users table
          $message["error"] = "Access Denied. Invalid Api key";
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
    $userTable = new UserTable();
    $user = $userTable->findUserByApikey($apikey);
    if (!empty($user)) {
      return true;
    } else {
      return false;
    }
  }
}