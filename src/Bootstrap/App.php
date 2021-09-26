<?php


namespace Src\Bootstrap;

use Src\Session\Session;

class App{


  private function __constract(){}

  public static function run(){
    Session::start();

  }


}