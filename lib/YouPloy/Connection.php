<?php

namespace YouPloy;

class Connection{
  protected $host;
  protected $port;
  protected $fingerprint;
  protected $username;
  protected $password;
  protected $pubkey;
  protected $privkey;
  protected $passphrase;
  protected $connection;

  public function __construct($host, $port = 22, $auth = null, $priv_key = null, $pub_key = null) {
    $this->host = $host;
    $this->port = $port;

    if(!($this->connection = ssh2_connect($this->host, $this->port))) {
      throw new \Exception("Could not connect to host: $this->host:$this->port");
    }

  }

  public function command($command) {
  }
}
