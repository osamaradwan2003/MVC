<?php


namespace Src\Cookie;


/**
 * description: session class to mange a sessions in freamworl
 * 
 * @package Cookie
 * @author osamaradwan
 * @category Cookie
 */

class Cookie{

  private function __constract(){}


  /**
   * set function useing to push key and value to session
   * 
   * @param string $key
   * @param any $val
   * @param float $expire
   * @param string $path
   * @param string $domain
   * @param bool $http_only
   * @return void
   */

  public static function set($key, $val, $expire, $path, $domain, $secure,$http_only){
    $expire = $expire ?? time() + (1*365*24*60*60);
    $path = $path ?? '/';
    $domain = $domain ?? '';
    $secure = $secure ?? false;
    $http_only = $http_only ?? true;
    setcookie($key, $value, $expire, $path, $domain, $secure, $http_only);
  }


  /**
   * has function using to check for value isset in $_COOKIE 
   * 
   * @param string $key
   * @return bool
   */

  public static function has($key){
    return isset($_COOKIE[$key]) ? true : false;
  }

  /**
   * get function using to get value form $_COOKIE if isset
   * 
   * @param string $key;
   * 
   * @return any;
   */

  public static function get($key){
    if (self::has($key)){
      return $_COOKIE[$key];
    }
    return null;
  }

  /**
   * remove function usig to remove value from $_COOKIE using key 
   * 
   * @param string $key
   * @return void
   */

  public static function remove($key){
    if (self::has($key)){
      unset($_COOKIE[$key]);
      setcookie($key, null, -1);
    }
  }


  /**
   * destroy function using to remove all vlaues in $_COOKIE
   * 
   * @return void
   */

  public static function destory(){
    foreach($_COOKIE as $key => $value){
      unset($_COOKIE[$key]);
    }
  }


  /**
   * all function using to return all $_COOKIE
   * 
   * @return array
   */

  public static function all(){
    return $_COOKIE;
  }


}