<?php

namespace Storyteller\core;
use Storyteller\app\controller\UserController;
use Storyteller;

class FrontController {
  
  private $_app = null;
  private $_userController = null;
  
  public function __construct() {
    $this->_app = new \Slim\Slim();
  }
  
  public function run() {
    $this->_registerRoutes();
    $this->_app->run();
  }
  
  public function echoResponse($message) {
    if ($this->_checkApiRequest()) {
      $this->_app->response->headers->set(
        'Content-Type',
        'application/json'
      );
      
      if (isset($message['error'])) {
        $this->_app->response->setStatus(400);
      }
      
      echo json_encode(array('message' => $message));
    }
  }
  
  /**
   * 
   * @return boolean
   */
  private function _checkApiRequest() {
    return (bool) preg_match('|\/api\/|', $this->_app->request->getPath());
  }
  
  private function _registerRoutes() {
    $this->_app->group('/api', function () {
      $this->_registerUserRoutes();
    });
  }
  
  private function _decodeUrl() {
      $post = array();
      foreach (explode('&', $this->_app->request->getBody()) as $postParam) {
          $param = explode('=', $postParam);
          $post[urldecode($param[0])] = urldecode($param[1]);
      }
      return $post;
  }
  
  private function _registerUserRoutes() {
    $userController = new UserController();
    $this->_app->post('/register', function () use ($userController) {
      $result = $userController->registerUser($this->_app->request->post());
      Storyteller\core\FrontController::echoResponse($result);
    });
    
    $this->_app->post('/login', function () use ($userController) {
      $result = $userController->authenticate($this->_app->request->post('email'), $this->_app->request->post('pass'));
      Storyteller\core\FrontController::echoResponse($result);
    });
    
    $this->_app->get('/user/:id', function ($id) use ($userController)  {
      $result = $userController->findUser($id);
      Storyteller\core\FrontController::echoResponse($result);
    });
  }
}