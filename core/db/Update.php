<?php

namespace Storyteller\core\db;

class Update extends Query {
  
  const WHERE_PATTERN = "/\?/";
  
  public function __construct($pdo, $table, $data, $where) {
    $this->PDOConntector = $pdo;
    $this->stm = self::SQL_UPDATE . ' `' . $table->info() . '` ' . self::SQL_UPDATE_SET . ' ';
    $this->stm .= $this->_addData($data);
    if (!is_null($where)) {
      if($this->_hasAnymoousWhereCondition($where)) {
        $this->_replaceAnynimousWhereCondition($where);
      }
      $this->stm .= $where->stm;
      $this->_isWhereExsists = $where->_isWhereExsists;
      $this->_bind += $where->_bind;
    }
  }
   
  public function finalQuery() {
    return parent::finalQuery($this->PDOConntector);
  }
  
  private function _addData($data) {
    $queryStr = '';
    $i = 1;
    foreach ($data as $column => $value) {
      $queryStr .= '`' . $column . '` = :' . $column;
      $queryStr .= ($i > count($data)) ? ', ' : ' ';
      $this->_bind += array(':' . $column => $value);      
      $i++;
    }
    return $queryStr;
  }
  
  private function _hasAnymoousWhereCondition($where) {
    $result = strpos($where->stm, '?');
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  
  private function _replaceAnynimousWhereCondition($where) {
    $uniqueIds = array();
    for ($i = 1; $i <= count($where->_bind); $i++) {
      $uniqueId = ':' . uniqid();
      $where->_bind += array($uniqueId => $where->_bind[$i]);
      array_push($uniqueIds, $uniqueId);
      unset($where->_bind[$i]);
    }
    $replacements = $uniqueIds;
    $search_array = array_fill(0, sizeof($replacements), self::WHERE_PATTERN);
    $where->stm = preg_replace($search_array, $replacements, $where->stm, 1);
  }
}