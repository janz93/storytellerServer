<?php

namespace Storyteller\app\model;

class UserTable extends Table {
  
  protected $_name = 'user';
  
  public function createUser($userArr) {
    $data = array(
      $userArr['name'],
      $userArr['email'],
      $this->_createPasswordHash($userArr['pass']),
      $this->_createApikey()
    );
    
    $sql = $this->_PdoConntector->prepare('
      INSERT INTO `' . $this->_name . '` (`name`, `email`, `pass`, `apikey`, `status`) 
      VALUES (?, ?, ?, ?, 1);
    ');
    if($sql->execute($data)) {
      return $this->findUserByID($this->_PdoConntector->lastInsertId());
    } else {
      return false;
    }
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
  
  public function findUserByEmail($email) {
    $this->setWhereCondition('`email` = ?', $email);
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