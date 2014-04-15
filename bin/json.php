<?php

$arr = array(
  'session' => array(
      'id' => '26eb335792a8511a68a599d752afa75b726f5454',
      'servers' => array(
        'zeus',
        'hera',
        'hercules',
        'poseidon'
      ),
      'apps' => array(
        array(
          'name' => 'sg.deploylah.com',
          'revision' => '26eb335792a8511a68a599d752afa75b726f5454'
        ),
        array(
          'name' => 'backend.deploylah.com',
          'revision' => '26eb335792a8511a68a599d752afa75b726f5454'
        )
      )
  )
);

$json_str = json_encode($arr);
echo $json_str;

$j = json_decode($json_str, true);
print_r($j['session']['apps'][0]['name']);
