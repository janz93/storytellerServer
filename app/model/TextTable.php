<?php

namespace Storyteller\app\model;

class TextTable extends Table {
  
  protected $_name = 'text';
  private $_dependentTables = array(
    'user',
    'Story'
  );
}