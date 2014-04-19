<?php

namespace YouPloy;

class YouPloy {

  protected $sessionId;
  protected $revision;
  protected $currTarget;
  protected $lock;
  protected $prodLoadBalancer;
  protected $testLoadBalancer;
  protected $server;
  protected $prepared;
  protected $deployed;
  protected $feedback;

  /**
   * YouPloy Object
   *
   * @param YouPloy\Connection $conn
   */
  public function __construct($conn) {
    $this->server = new Server($conn);
  }

  public function doDeploy($app, $revision) {
    if (empty($app))
      throw new \Exception("Application name should not be empty!");

    if (empty($revision))
      throw new \Exception("Revision should not be empty");

    if ($this->lock == true) {
      throw new \Exception("Locked to this session: %s \n", $this->sessionId);
    }

    $this->setLock();

    /* Preparation */

    if (!$this->prepare()) {
      throw new \Exception("Failed in preparation step\n");
    }

    /* Do deployment */

    if (!$this->deploy($app, $revision)) {
      throw new \Exception("Failed in deployment step\n");
    }

    /* Get deployment feedback */

    if (!$this->feedback($app, $revision)) {
      throw new \Exception("Failed in feedback step\n");
    }

  }

  protected function gracefulStop() {
    $this->lock =  false;
    // Destroy Ploy objects
  }

  protected function prepare() {
    return $this->server->prepare();
  
    $this->prepared = true;
  }

  protected function deploy($app, $revision) {
    if ($this->prepared === false) {
      throw new \Exception("Preparation step is not fulfilled\n");
    }

    return $this->server->deploy($app, $revision);

    $this->deployed = true;
  }

  protected function feedback($app, $revision) {
    if ($this->deployed === false) {
      throw new \Exception("Deployment step is not fulfilled\n");
    }

    return $this->server->feedback($app, $revision);
  }

  protected function setLock() {
    // touch some lock file somewhere
    // or setpid()
    $this->lock = true;
  }

  protected function genSessionKey() {
    // return somerandom unique string
  }
  
  protected function getSessionKey() {
    return $this->sessionId;
  }
}
