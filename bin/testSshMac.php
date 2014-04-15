<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

$ssh = new Net_SSH2('veritron');
$ssh->disableQuietMode();
$key = new Crypt_RSA();
$rv = $key->loadKey(file_get_contents('/Users/dwi/.ssh/test_rsa'));

if (!$ssh->login('dwi', $key)) {

    print_r($ssh->getErrors());
    echo $ssh->getLog();
    exit('Login Failed');
}

echo $ssh->exec('pwd');
echo $ssh->exec('ls ~/REPOSITORY');
