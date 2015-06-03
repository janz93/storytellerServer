<?php

namespace Storyteller\app\model;

class StoryTagTable extends Table {
  
  protected $_name = 'story_tag';
  
  private $_dependentTables = array(
    'story',
    'tag'
  );
}