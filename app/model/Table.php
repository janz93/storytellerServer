<?php

namespace Storyteller\app\model;
use Storyteller\core\db\Query;

class Table {
  
  protected $_name;
  
  public function getAdapter() {
    return new Query();
  }
  
  public function select() {
    return $this->getAdapter()->select();
  }
  
  
  
  public function find() {
    $tableInfo = $this->_getTableMeta();
    var_dump($tableInfo);
    $args = func_get_args();
    $sql = 'SELECT * FROM' . $tableInfo['name'];
    if (is_array($args) && !is_array($tableInfo['primary'])) {
      for ($i = 0; $i < count($args[0]); $i++) {
        if ($i == 0) {
          $sql .= 'WHERE ' . $tableInfo['primary'] . ' = :' . $tableInfo['primary'];
        } else {
          $sql .= 'OR ' . $tableInfo['primary'] . ' = :' . $tableInfo['primary'];
        }
        
      }
    } else {
      $sql .= 'WHERE ' . $tableInfo['primary'] . ' = :' . $tableInfo['primary'];
    }
    
    $this->
    var_dump($args);
    exit;
  }
  
  /**
   * 
   * @param string $table
   * @return mixed|NULL
   */
  public function fetch($Query) {
    $query = $Query->finalQuery();
    $query->execute();
    $row = $query->fetch(\PDO::FETCH_OBJ);
    if ($row){
      return $row;
    } else {
      return null;
    }
  }
  
  
  public function fetchAll($Query) {
    $query = $Query->finalQuery();
    var_dump($query);
    $query->execute();
    $rowset = $query->fetchAll(\PDO::FETCH_OBJ);
    if ($rowset){
      return $rowset;
    } else {
      return null;
    }
  }
  
  
  
  public function info() {
    return $this->_name;
  }
  
  private function _getTableMeta() {
    return array('name' => $this->_name, 'primary' => $this->_primary);
  }
}