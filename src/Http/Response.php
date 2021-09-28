<?php

  namespace Src\Http;

  class Response
  {

    public static function output($data){
      if(!$data){
        return ;
      }
      if(!is_string($data)){
        $data = self::json($data);
      }

      echo $data;
    }

    public static function json($data): string
    {
      return json_encode($data);
    }


  }