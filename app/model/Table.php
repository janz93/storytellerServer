<?php

namespace Storyteller\app\model;

class Table {
  
  private $_whereCondition = array('sql' => '', 'value' => '');
  private $_join = array('table' => '', 'on' => '');
  protected $_PdoConntector = null;
  protected $_name;
  
  public function __construct() {
    try {
      $this->_PdoConntector = new \PDO('mysql:' . DB_HOST . '=localhost;dbname=' . DB_DATABASE, DB_USER, DB_PASS);
      $this->_PdoConntector->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo 'Error' . $e->getMessage();
    }
  }
  
  /**
   * 
   * @param string $table
   * @return mixed|NULL
   */
  public function find($table) {
    $sql = 'SELECT * FROM `' . $table . '`';
    if ($this->_hasWhereCondition()) {
      $query = $this->_PdoConntector->prepare($this->_createWhereCondition($sql));
      $query->execute(array($this->_whereCondition['value']));
    } else {
      $query = $this->_PdoConntector->prepare($sql);
      $query->execute();
    }
    $row = $query->fetch(\PDO::FETCH_OBJ);
    if ($row){
      return $row;
    } else {
      return null;
    }
  }
  
  public function setJoin($table, $on) {
    $this->_join = array('table' => $table, 'on' => $on);
  }
  
  private function _hasJoin() {
    if (!empty($this->_join)) {
      return true;
    } else {
      return false;
    }
  }
  
  private function _createJoin($sql) {
    return $sql . ' JOIN `' . $this->_join['table'] . '` ON ' . $this->_join['on'];
  }
  
  /**
   * 
   * @param string $sql
   * @param mixed $value
   */
  public function setWhereCondition($sql, $value) {
    $this->_whereCondition = array('sql' => $sql, 'value' => $value);
  }
  
  /**
   * 
   * @return boolean
   */
  private function _hasWhereCondition() {
    if (!empty($this->_whereCondition)) {
      return true;
    } else {
      return false;
    }
  }
  
  private function _createWhereCondition($sql) {
    return $sql . ' WHERE ' . $this->_whereCondition['sql'];
  }
}