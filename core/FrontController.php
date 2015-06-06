<?php

namespace Storyteller\core;
use Storyteller\app\controller\UserController;
use Storyteller\app\controller\StoryController;
use Storyteller\core\Middleware\Authentication;

class FrontController {
  
  private $_app = null;
  private $_userController = null;
  private $_authenticate = null;
  
  public function __construct() {
    $this->_app = new \Slim\Slim();
    $this->_app->add(new Authentication());
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
      $this->_app->stop();
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
    $this->_app->get('/', function () {
      echo 'Welcome to storyteller';
    });
    $this->_app->group('/api', function () {
      $this->_registerUserRoutes();
    });
  }
  
  
  private function _registerUserRoutes() {
    $userController = new UserController();
    $this->_app->post('/register', function () use ($userController) {
      $response = $userController->registerUser($this->_app->request->post());
      FrontController::echoResponse($response);
    });
    
    $this->_app->post('/login', function () use ($userController) {
      $response = $userController->checkLogin($this->_app->request->post('email'), $this->_app->request->post('pass'));
      FrontController::echoResponse($response);
      
    });
    
    $this->_app->get('/user/:id', function ($id) use ($userController)  {
      $response = $userController->findUser($id);
      FrontController::echoResponse($response);
    });
  }
}