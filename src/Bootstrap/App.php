<?php


namespace Src\Bootstrap;

use ReflectionException;
use Src\File\File;
use Src\Http\Request;
use Src\Http\Response;
use Src\Route\Route;
use Src\Session\Session;
use Src\Exception\Exceptions;

class App{


  /**
   * @throws ReflectionException
   */
  public static function run(){

    Exceptions::handle();
    Session::start();

    Request::handle();

    File::require_dir('routes');

    $data = Route::handle();
    Response::output($data);
  }


}