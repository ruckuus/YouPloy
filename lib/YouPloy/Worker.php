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

    /* Check connectivity here */
    foreach($session['servers'] as $s) {
      $target = $this->getServerInfo($s);
      if (!is_array($target)) {
        throw new \Exception("Malformated target server!\n");
      }

      $fp = fsockopen($target['host'], $target['port'], $errno, $errmsg, 10);
      if (!$fp) {
        throw new \Exception("Unable to connect to: ${target['host']}:${target['port']}, reason: $errmsg, error code: $errno\n");
      }
      fclose($fp);
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
      $target = $this->getServerInfo($server);
      if (!is_array($target)) {
        throw new \Exception("Malformated target server!\n");
      }

      echo "Connecting to ${target['host']} \n";
      // Create new SSH Connection
      try {
        /* Testing only, later get from Config lah ...*/        
        $conn = new Connection($target['host'], $target['port'], 'dwi','/home/vagrant/.ssh/id_rsa');
      } catch (Exception $e) {
        die($e->getMessages());
      }

      foreach($session['apps'] as $id => $app) {
        echo "Deploying #${id} ". $app['name'] . "#" . $app['revision'] . " @ ${server} \n";
        // Now you're on your own
        // Send deploy command via SSH connection $conn
        $deploy = new YouPloy($conn);
        $deploy->doDeploy($app['name'], $app['revision']);
      }
    }
  }

  protected function gracefulStop() {}

  protected function getServerInfo($server) {
    $arr = explode(':', $server);
    if (empty($arr))
      return false;

    $host = $arr[0];
    if (empty($host))
      return false;

    $port = isset($arr[1]) ? $arr[1] : 22;

    $rv = array(
      'host' => $host,
      'port' => $port
    );

    return $rv;
  }

}
