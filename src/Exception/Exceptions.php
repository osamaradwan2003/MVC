<?php

  namespace Src\Exception;

  class Exceptions
  {

      public static function handle(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
      }

  }