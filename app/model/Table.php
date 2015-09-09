<?php

namespace Storyteller\app\model;
use Storyteller\core\db\Query;


class Table extends Query {
  
  protected $_name;
  
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

  public function delete($table) {
    $query = null;
    $sql = 'DELETE FROM `' . $table . '`';    
    if ($this->_hasWhereCondition()) {
      $query = $this->_PdoConntector->prepare($sql . 'WHERE ' . $this->_whereCondition['sql']);
      $query->bindParam($this->_whereCondition['value'][0], $this->_whereCondition['value'][1]);
    } else {
      $query = $this->_PdoConntector->prepare($sql);
    }
    $query->execute();
    return $query->rowCount();
  }
  
  public function update($table, $data) {
    $pair = array();
    $sql = 'UPDATE `' . $table .'` SET ';
    foreach ($data as $column => $value) {
      $pair[] = '`' . $column . '` = :' . $column;
    }
    
    $sql .= implode(', ', $pair);
    $query = $this->_PdoConntector->prepare($sql . ' WHERE ' . $this->_whereCondition['sql']); 
    foreach ($data as $column => $value) {
      $query->bindValue($column, $value);
    }
    $query->bindValue($this->_whereCondition['value'][0], $this->_whereCondition['value'][1]);
    $query->execute();
    return $query->rowCount();
  }
  
  public function insert($table, $data) {
    $columns = array();
    $values = array();
    $sql = 'INSERT INTO `' . $table .'` (';
    foreach (array_keys($data) as $column) {
      $columns[] = '`' . $column . '`';
      $values[] = ':' . $column;
    }
    
    $sql .= implode(', ', $columns). ') VALUES(' . implode(', ', $values) . ')';
    $query = $this->_PdoConntector->prepare($sql);
    
    foreach ($data as $column => $value) {
      $query->bindColumn($column, $value);
    }
    if ($query->execute($data)) {
      $this->setWhereCondition('`id` = ?', $this->_PdoConntector->lastInsertId());
      if ($this->_hasWhereCondition()) {
        $query = $this->_prepareSql($table);
        $query->execute(array($this->_whereCondition['value']));
      } else {
        $query = $this->_prepareSql($table);
        $query->execute();
      }
      $row = $query->fetch(\PDO::FETCH_OBJ);
      if ($row){
        return $row;
      } else {
        return null;
      }
      $this->_unsetParams();
    } else {
      return false;
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
    return ' JOIN `' . $this->_join['table'] . '` ON ' . $this->_join['on'];
  }
  
  /**
   * 
   * @param string $sql
   * @param mixed $value
   */
  public function setWhereCondition($sql, $valueArr) {
    $this->_whereCondition = array('sql' => $sql, 'value' => $valueArr);
  }
  
  public function info() {
    return $this->_name;
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
    return ' WHERE ' . $this->_whereCondition['sql'];
  }
  
  private function _prepareSql($table, $select) {
    $sql = $select . ' FROM `' . $table . '`';
    if ($this->_hasJoin()) {
      $sql .= $this->_createJoin($sql);
    } 
    if ($this->_hasWhereCondition()) {
      $sql .= $this->_createWhereCondition($sql);
    }
    
    return $this->_PdoConntector->prepare($sql);
  }
  
  private function _getTableMeta() {
    return array('name' => $this->_name, 'primary' => $this->_primary);
  }
}