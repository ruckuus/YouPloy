<?php

require dirname(__FILE__) . '../vendor/autoload.php';

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

$conf = new YouPloy\Config(); 
$server = new YouPloy\Server($conf);
$worker = new YouPloy\Worker();

/* Below json file is generated from Ploy\Prepare() step */

$json = 'session: [
  {
    id: 26eb335792a8511a68a599d752afa75b726f5454,
    priority: 2,
    queue_number: 10,
    server: zeus,
    responsibilities: [
      {
        application: sg.deploylah.com, 
        revision: 26eb335792a8511a68a599d752afa75b726f5454,
      },
      {
        application: my.deploylah.com,
        revision: 447e894dd3d48e2b18299b130799631555e7663d,
      }
    ]
  },
  {
    id: 26eb335792a8511a68a599d752afa75b726f5454,
    priority: 2,
    queue_number: 11,
    server: hera,
    responsibilities: [
      {
        application: sg.deploylah.com, 
        revision: 26eb335792a8511a68a599d752afa75b726f5454,
      },
      {
        application: my.deploylah.com,
        revision: 447e894dd3d48e2b18299b130799631555e7663d,
      }
    ]
  },
  {
    id: 26eb335792a8511a68a599d752afa75b726f5454,
    priority: 2,
    queue_number: 11,
    server: hercules,
    responsibilities: [
      {
        application: sg.deploylah.com, 
        revision: 26eb335792a8511a68a599d752afa75b726f5454,
      },
      {
        application: my.deploylah.com,
        revision: 447e894dd3d48e2b18299b130799631555e7663d,
      }
    ]
  }
]';

$sessions = json_decode($json);

// sort $session based on queue_number

foreach ($session as $session) {
  try {
    $worker->doWork($session);
  } catch (Exception $e) {
    /* Log Exception */
    \YouPloy\ErrorLog($e->getMessages());
    \YouPloy\Helper::gracefulStop();
  }
}


