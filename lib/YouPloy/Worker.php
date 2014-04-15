<?php

namespace YouPloy;

class Worker {
  public function doWork(array $session = array()) {
    if (empty($session['id'])) {
      throw new WorkerException("session id is empty");
    }

    if (empty($session['servers'])) {
      throw new WorkerException("servers are empty");
    }

    if (empty($session['apps'])) {
      throw new WorkerException("apps field empty");
    }

    $this->processHost($session);

  }

  protected function processHost($session) {
    /* Check lock */
    if (!empty($session['lock']))
      throw new WorkerException("Session is locked");

    /* Process deployment on each server */
    foreach($session['servers'] as $server) {
      echo "Connecting to ${server} \n";
      // Create new SSH Connection
      try {
        $conn = new Connection($server);
      } catch (Exception $e) {
        die($e->getMessages());
      }

      foreach($session['apps'] as $id => $app) {
        echo "Deploying #${id} ". $app['name'] . "#" . $app['revision'] . " @ ${server} \n";
          break;
        // Now you're on your own
        // Send deploy command via SSH connection $conn
        //$deploy = new YouPloy($conn);
        //$deploy->doDeploy($app, $revision);
      }
    }
  }

  protected function gracefulStop() {}
}
