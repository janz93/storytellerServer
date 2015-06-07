<?php

namespace Storyteller\app\model;

class UserTable extends Table {
  
  protected $_name = 'user';
  
  public function createUser($userArr) {
    $data = array(
      'name' => $userArr['name'],
      'email' => $userArr['email'],
      'pass' => $this->_createPasswordHash($userArr['pass']),
      'apikey' => $this->_createApikey()
    );
    
    return $this->insert($this->_name, $data);
  }
  
  /**
   * 
   * @param string $email
   * @param string $pass
   * @return boolean
   */
  public function checkUser($email, $pass) {
    $user = $this->findUserByEmail($email);
    if (empty($user) || !$user->status || !$this->_verifyPassword($pass, $user->pass)) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * 
   * @param int $id
   * @return Ambigous <NULL, unknown>
   */
  public function findUserByID($id) {
    $this->setWhereCondition('`id` = ?', $id);
    return $this->find($this->_name);
  }
  
  /**
   * 
   * @param string $email
   * @return Ambigous <\Storyteller\app\model\mixed, NULL>
   */
  public function findUserByEmail($email) {
    $this->setWhereCondition('`email` = ?', $email);
    return $this->find($this->_name);
  }
  
  /**
   * 
   * @param string $apikey
   * @return Ambigous <\Storyteller\app\model\mixed, NULL>
   */
  public function findUserByApikey($apikey) {
    $this->setWhereCondition('`apikey` = ?', $apikey);
    return $this->find($this->_name);
  }
  
  /**
   * 
   * @param string $password
   * @return string
   */
  private function _createPasswordHash($password) {
    $options = [
      'cost' => 11,
      'salt' => $this->_createUniqueSalt()
    ];
    
    return password_hash($password, PASSWORD_BCRYPT, $options);
  }
  
  /**
   * 
   * @return string
   */
  private function _createUniqueSalt() {
    return mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
  }
  
  
  private function _createApikey() {
    do {
      $apikey = rtrim(base64_encode(md5(microtime())),"=");
      $this->setWhereCondition('`apikey` = ?', $apikey);
    } while ($this->find($this->_name) !== null);
    return $apikey;
  }
  
  /**
   * 
   * @param string $pass
   * @param string $hash
   * @return boolean
   */
  private function _verifyPassword($pass, $hash) {
    return password_verify($pass, $hash);
  }
}