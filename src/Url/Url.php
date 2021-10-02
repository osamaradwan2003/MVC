<?php

  namespace Src\Url;

  use JetBrains\PhpStorm\Pure;
  use Src\Http\Request;
  use Src\Http\Server;

  class Url
  {
      #[Pure] public static function getPath($path): string
      {
        return Request::getBaseUrl() . $path;
      }

      #[Pure] public static function previous(): string
      {
        return Request::previous();
      }

      public static function redirect($url, $option='', $code=200){
        header("Location: ". $url . ";" . $option, true, $code);
      }
  }