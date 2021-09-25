<?php


namespace Src\Bootstrap;

use Src\Session\Session;

class App{


  private function __constract(){}

  public static function run(){
    Session::set('name', 'osama');

    #echo Session::flash('name');
  }


}