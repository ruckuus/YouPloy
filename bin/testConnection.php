<?php

require dirname(__FILE__) . '/../vendor/autoload.php';
$server = "web1-staging.guruestate.com";
$c = new YouPloy\Connection($server, 22, 'dwi','/home/vagrant/.ssh/id_rsa');
echo $c->cmd('pwd');
echo $c->cmd('ls /var/www/propertyguru.com.sg');
