<?php

namespace YouPloy;

class Worker {
  public function doWork(array $session = array()) {
    if (empty($session['id'])) {
      throw new WorkerException("session id is empty");
    }

    if (empty($session['priority'])) {
      throw new WorkerException("priority is empty");
    }

    if (empty($session['server'])) {
      throw new WorkerException("server is empty");
    } else {
      // Check connectivity, yes do it here!
      getaddress_by_hostname($session['server']); 
    }

    if (empty($session['responsibilities'])) {
      throw new WorkerException("responsibilities field empty");
    }

    $this->processHost($session);

  }

  protected function processHost($session) {
    // take server out of rotation
    // ssh to $session['server'];
    if (YouPloy\Helper::takeServerOutOfRotationCommand($session)) {
      throw new WorkerException("Failed to take %s out of rotation.", $session['server']);
    }

    if (YouPloy\Helper::doDeployArtefactCommand($session))) {
      throw new WorkerException("Failed to deploy artefact to %s.", $session['server']);
    }

    if (YouPloy\Helper::getFeedbackCommand($session)) {
      throw new WorkerException("Failed to get satisfying feedback for %s.", $session['server']);
    }
    
  }
}
