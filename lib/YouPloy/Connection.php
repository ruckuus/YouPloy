<?php

namespace YouPloy;

class Connection{
  protected $host;
  protected $port;
  protected $fingerprint;
  protected $username = 'jenkins';
  protected $password = 'jenkins';
  protected $pubkey = '/home/vagrant/.ssh/id_rsa.pub';
  protected $privkey = '/home/vagrant/.ssh/id_rsa';
  protected $passphrase;
  protected $connection;

  /**
   * Create SSH Connection
   *
   * @access public
   * @param String $host
   * @param String $port optional default 22
   * @param String $username optional default null
   * #param String $password optional default null
   * @param String $privkey optional default null
   * @param String $pubkey optional default null
   * @param String passphrase optional default null
   */
  public function __construct($host, $port = 22, $username = null, $password = null, $privkey = null, $pubkey = null, $passphrase = null) {
    $this->host = $host;
    $this->port = $port;

    if (!empty($privkey))
      $this->privkey = $privkey;

    if (!empty($pubkey))
      $this->pubkey = $pubkey;

    if (!empty($username))
      $this->username = $username;

    if (!empty($password))
      $this->password = $password;

    $this->connection = new \Net_SSH2($host);
    $key = new \Crypt_RSA();

    /* We should not use password protected key */
    if (isset($privkey) && isset($passphrase)) {
      $this->passphrase = $passphrase;
      $key->setPassword($this->passphrase);
    }
    
    $key->loadKey(file_get_contents($this->privkey));

    if (!$this->connection->login($this->username, $key)) {
      throw new \Exception("Unable to connect!");
    }
  }

  public function cmd($command) {
    return $this->connection->exec($command);
  }
}
