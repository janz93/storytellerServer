<?php

namespace Storyteller\app\model;

class StoryTable extends Table {
  
  protected $_name = 'story';
  
  private $_dependentTables = array(
    'UserTable', 
    'CategoryTable',
    'TextTable',
    'ModusTable'
  );
  
}