<?php

  namespace Src\Url;

  use Src\Http\Request;
  use Src\Http\Server;

  class Url
  {
      public static function getPath($path): string
      {
        return Request::getBaseUrl() . $path;
      }

      public static function previous(): string
      {
        return Request::previous();
      }

      public static function redirect($url, $option='', $code=200){
        header("Location: ". $url . ";" . $option, true, $code);
      }
  }