<?php

namespace YouPloy;

class Server {
  protected $c;
  protected $lastError;
  protected $app;
  protected $rev;

  public function __construct($conn) {
    $this->c = $conn;
  }

  public function prepare() {
    
    /* Take Server OOR */
    if (!$this->doTakeServerOutOfRotation()) {
      throw new \Exception("Failed taking server out of rotation\n");
    }

    /* Prepare backup and rollback */
    if (!$this->doCheckBackupAndRollback()) {
      throw new \Exception("Failed preparing backup and rollback\n");
    }

    if (!$this->doPreparePackage()) {
      throw new \Exception("Failed preparing deployment package\n");
    }

  }

  public function deploy($app, $rev) {
    if (!$this->doDeployPackage($app, $rev)) {
      throw new \Exception("Failed deploying $app#$rev\n");
    }

    if (!$this->doPostDeployStep()) {
      throw new \Exception("Failed processing post-deployment step for: $app#$revision\n");
    }

  }

  public function feedback($app, $rev) {
  }

  protected function doTakeServerOutOfRotation() {
    $cmd = "mv /var/www/healthcheck.html /var/www/healthcheck.html.test";

    if (!$this->c->cmd($cmd)) {
      return false;
    }

    /* This is based on trial and error */
    usleep(10000);

    $cmd = "ls /var/www/healthcheck.html";

    if ($this->c->cmd($cmd)) {
      return false;
    }

    return true;
  }

  protected function doCheckBackupAndRollback($app, $revision) {
    $cmd = "ls -l latest | awk '{print $11}'";
    $this->c->cmd($cmd);
  }

  protected function doDeployPackage($app, $revision) {
    return $this->server->deploy($app, $revision);
  }

  protected function doPostDeployStep() {
    return $this->server->postDeploy();
  }

}
