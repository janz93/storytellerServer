<?php

namespace Storyteller\app\controller;

use Storyteller\app\model\ModusTable;
class ModusController {
  
  private $_modusModel = null;
  
  public function __construct() {
    $this->_modusModel = new ModusTable();
  }
  
  public function getAllModus() {
  $modus = $this->_modusModel->getModus();
  if (!empty($modus)) {
      return array('modus' => $modus);
    } else {
      return array('error' => 'No modus found');
    }
  }
}