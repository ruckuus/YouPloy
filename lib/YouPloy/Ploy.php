<?php

namespace YouPloy;

class Ploy {
  protected $session;
  protected $revision;
  protected $target;
  protected $status;
  protected $log;

  const STATUS_OK 0;
  const STATUS_KO 1;
  const STATUS_SERVER_ISSUE 2;
  const STATUS_TRANSMIT_ISSUE 3;
  const STATUS_OTHER_ISSUE 4;

  public function __construct() {
    //$this->log = new \Monolog\Log();
  } 


  public function setSession($session) {
    $this->session = $session;
  }

  public function setStatus($status) {
    $this->status = $status;
  }

  public function setTarget($target) {
    $this->target = $target;
  }

  public function setRevision($revision) {
    $this->revision = $revision;
  }
  
}
