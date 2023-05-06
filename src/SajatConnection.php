<?php

class SajatConnection
{
  private $user;
  private $pass;
  public function __construct($user = null, $pass = null)
  {
    $this->user = $user;
    $this->pass = $pass;
  }
}
