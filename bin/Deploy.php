<?php

require dirname(__FILE__) . '/../vendor/autoload.php';

/* YouPloy\Config() reads configuration file and return \YouPloy\Config object */
/**
 * servers:
 *  hostname: kurawa
 *  auth: key # password, etc
 *  responsibilities: frontend, search
 *
 * servers: [
 *  {
 *    hostname: kurawa,
 *    auth: key,
 *    responsibilities: frontend, search
 *  },
 *  {
 *    hostname: pandawa,
 *    auth: key,
 *    responsibilities: worker, elasticsearch
 *  },
 *  {
 *    hostname: hera,
 *    auth: key,
 *    responsibilities: sg.deploylah.com, my.deploylah.com
 *  }
 * ]
 */

//$conf = new YouPloy\Config(); 
//$server = new YouPloy\Server($conf);
$worker = new YouPloy\Worker();

/* Below json file is generated from Ploy\Prepare() step */
$json = '{
  "session":
    {
      "id":"26eb335792a8511a68a599d752afa75b726f5454",
      "servers":
      [
        "zeus",
        "hera",
        "hercules",
        "poseidon"
      ],
      "apps":
      [
      {
        "name":"sg.deploylah.com",
        "revision":"26eb335792a8511a68a599d752afa75b726f5454"
      },
      {
        "name":"backend.deploylah.com",
        "revision":"26eb335792a8511a68a599d752afa75b726f5454"
      }
      ],
      "lock":""
  }
}';

$sessions = json_decode($json, true);

//print_r($sessions);

// sort $session based on queue_number

foreach ($sessions as $session) {
  try {
    $worker->doWork($session);
  } catch (Exception $e) {
    /* Log Exception */
   // \YouPloy\ErrorLog($e->getMessages());
   // \YouPloy\Helper::gracefulStop();
    die($e->getMessage() . "\n");
  }
}


