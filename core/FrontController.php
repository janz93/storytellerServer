<?php

namespace Storyteller\core;

use Storyteller\core\Middleware\Authentication;
use Storyteller\app\controller\UserController;
use Storyteller\app\controller\StoryController;
use Storyteller\app\controller\CategorieController;
use Storyteller\app\controller\TagController;
use Storyteller\app\controller\ModusController;
use Storyteller\app\controller\TextController;

class FrontController {
  
  private $_app = null;
  private $_userController = null;
  
  public function __construct() {
    $this->_app = new \Slim\Slim();
    $this->_app->add(new Authentication());
  }
  
  public function run() {
    $this->_registerRoutes();
    $this->_app->run();
  }
  
  public function echoResponse($response) {
    if ($this->_checkApiRequest()) {
      $this->_app->response->headers->set(
        'Content-Type',
        'application/json'
      );
      
      if (isset($response['error'])) {
        $this->_app->response->setStatus(400);
      }
      
      echo json_encode($response);
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
      echo 'welcome';
    });
    $this->_app->group('/api', function () {
      $this->_registerUserRoutes();
      $this->_registerStoryRoutes();
      $this->_registerModusRoutes();
      $this->_registerCategorieRoutes();
      $this->_registerTagRoutes();
      $this->_registerTextRoutes();
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
      $post = $this->_decodeUrl();
      $response = $userController->registerUser($post);
      FrontController::echoResponse($response);
    });
    
    $this->_app->post('/login', function () use ($userController) {
      $post = $this->_decodeUrl();
      $response = $userController->checkLogin($post['email'], $post['pass']);
      FrontController::echoResponse($response);
      
    });
    
    $this->_app->get('/user/:id', function ($id) use ($userController)  {
      $response = $userController->findUser($id);
      FrontController::echoResponse($response);
    });
  }
  
  private function _registerStoryRoutes() {
    $storyController = new StoryController();
    $this->_app->get('/own-stories', function () use ($storyController) {
      $response = $storyController->findUserStories(Authentication::$validUser->id);
      FrontController::echoResponse($response);
    });
    $this->_app->get('/story/:id', function ($id) use ($storyController) {
      $response = $storyController->findStory($id);
      FrontController::echoResponse($response);
    });
    $this->_app->get('/search-story/title/:title', function ($title) use ($storyController) {
      $response = $storyController->findStoryByTitle($title);
      FrontController::echoResponse($response);
    });
    $this->_app->get('/search-story/category/:category', function ($category) use ($storyController) {
      $response = $storyController->findStoryByCategory($category);
      FrontController::echoResponse($response);
    });
    $this->_app->get('/search-story/:query+', function ($query) use ($storyController) {
      unset($query[0]);
      $response = $storyController->findStoryByQuery($query);
      FrontController::echoResponse($response);
    });
    $this->_app->post('/story', function () use ($storyController) {
      $post = $this->_decodeUrl();
      $response = $storyController->createStory($post);
      FrontController::echoResponse($response);
    });
    $this->_app->put('/story/:id', function ($id) use ($storyController) {
      $post = $this->_decodeUrl();
      $response = $storyController->updateStory($id, $post);
      FrontController::echoResponse($response);
    });
    $this->_app->delete('/story/:id', function ($id) use ($storyController) {
      $response = $storyController->deleteStory($id);
      FrontController::echoResponse($response);
    });    
  }
  
  private function _registerModusRoutes() {
    $modusController = new ModusController();
    $this->_app->get('/modus', function () use ($modusController) {
      $response = $modusController->getAllModus();
      FrontController::echoResponse($response);
    });
  }
  
  private function _registerCategorieRoutes() {
    $categorieController = new CategorieController();
    $this->_app->get('/categories', function () use ($categorieController) {
      $response = $categorieController->getAllCategories();
      FrontController::echoResponse($response);
    });
  }
  
  private function _registerTagRoutes() {
    $tagController = new TagController();
    $this->_app->get('/tags', function () use ($tagController) {
      $response = $tagController->getAllTags();
      FrontController::echoResponse($response);
    });
  }
  
  private function _registerTextRoutes() {
    $textController = new TextController();
    $this->_app->post('/text', function () use ($textController) {
      $post = $this->_decodeUrl();
      $response = $textController->createText($post);
      FrontController::echoResponse($response);
    });
    $this->_app->put('/text/:id', function ($id) use ($textController) {
      $post = $this->_decodeUrl();
      $response = $textController->updateText($id, $post);
      FrontController::echoResponse($response);
    });
    
    $this->_app->get('/collaborate-stories', function () use ($textController) {
      $response = $textController->findCollaborateStories(Authentication::$validUser->id);
      FrontController::echoResponse($response);
    });
  }
}