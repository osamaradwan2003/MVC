<?php


namespace Src\Cookie;


use JetBrains\PhpStorm\Pure;

/**
 * description: session class to manage a sessions in framework
 * 
 * @package Cookie
 * @author osamaradwan
 * @category Cookie
 */

class Cookie{

  /**
   * set function using to push key and value to session
   * 
   * @param string $key
   * @param mixed $val
   * @param float $expire
   * @param string $path
   * @param string $domain
   * @param bool $secure
   * @param bool $http_only
   * @return void
   */

  public static function set(string $key, mixed $val, float $expire, string $path, string $domain, bool $secure, bool $http_only){
    $expire = $expire ?? time() + (1*365*24*60*60);
    $path = $path ?? '/';
    $domain = $domain ?? '';
    $secure = $secure ?? false;
    $http_only = $http_only ?? true;
    setcookie($key, $val, $expire, $path, $domain, $secure, $http_only);
  }


  /**
   * has function using to check for value isset in $_COOKIE 
   * 
   * @param string $key
   * @return bool
   */

  public static function has(string $key): bool
  {
    return isset($_COOKIE[$key]);
  }

  /**
   * get function using to get value form $_COOKIE if isset
   *
   * @param string $key ;
   *
   * @return mixed;
   */

  #[Pure] public static function get(string $key) :mixed
  {
    if (self::has($key)){
      return $_COOKIE[$key];
    }
    return null;
  }

  /**
   * remove function using to remove value from $_COOKIE using key
   * 
   * @param string $key
   * @return void
   */

  public static function remove(string $key){
    if (self::has($key)){
      unset($_COOKIE[$key]);
      setcookie($key, null, -1);
    }
  }


  /**
   * destroy function using to remove all values in $_COOKIE
   * 
   * @return void
   */

  public static function destroy(){
    foreach($_COOKIE as $key => $value){
      unset($_COOKIE[$key]);
    }
  }


  /**
   * all function using to return all $_COOKIE
   * 
   * @return array
   */

  public static function all(): array
  {
    return $_COOKIE;
  }


}