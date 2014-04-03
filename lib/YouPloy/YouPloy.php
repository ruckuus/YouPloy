<?php

namespace YouPloy;

class YouPloy {

  protected $sessionId;
  protected $revision;
  protected $currTarget;
  protected $lock;
  protected $prodLoadBalancer;
  protected $testLoadBalancer;

  public function __construct(array $opts = array()) {
    $this->genSessionKey();
  }

  public function doDeploy($object) {
    if (empty($object))
      throw new \Exception('Object is empty!');

    if (empty($target))
      throw new \Exception('Target is empty!');  

    if (!instanceof('YouPloy\Ploy')) {
      throw new \Exception('Object must be an instance of Ploy');
    }

    if ($this->lock == true) {
      throw new \Exception("Locked to this session: %s \n", $this->sessionId);
    }

    $this->setLock();

    /* Everything should be fine */
    $this->currTarget = $object->getTarget();
    $this->revision = $object->getRevision();

    if ($this->doRealDeploy($this->revision, $this->currTarget)) {
      $object->setStatus(Ploy::STATUS_DEPLOYMENT_FAILURE);
    }


  }

  protected function doRealDeploy($revision, $target) {
    // secure transfer to $target
    // Check whether file $revision is in shared directory or not
    // Check whether this revision has been deployed before
    // Do preparation steps
    if ($this->doTakeServerOutOfRotation($target)) {
      $this->gracefulStop();
      throw new \Exception("Failed to take %s out of rotation\n", $target);
    }

    if ($this->doPackageDeploy($target, $revision)) {

    }

    if ($this->doPostDeploymentCheck($target)) {
    }
    
  }

  protected function doPackageDeploy($target, $revision) {
    /* Check if it's out of rotation */
    if ($this->isOutOfRotation($target)) {
      throw new \Exception("Server: %s is still serving production\n", $target);
    }

    /* SSH to $target */
    // Location of deployment package: DONE by Jenkins
    // Revision: $revision
    // create directory: /// CONFIGURATION!!
    // mkdir -p /var/www/appname#$revision
    // unzip deployment package to /var/www/appname#$revision
    // set RollbackTo $previousRevision
    //
  }

  protected function gracefulStop() {
    $this->lock =  false;
    // Destroy Ploy objects
  }

  protected function doTakeServerOutOfRotation($target) {
    // SSH to server and take it out of rotation
    // take out from prodLoadBalancer, move it to testLoadBalancer
    // Do Guzzle call to this testLoadBalancer, if gets 200
    // return true
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
