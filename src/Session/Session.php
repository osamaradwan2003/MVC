<?php


namespace Src\Session;


/**
 * description: session class to mange a sessions in freamworl
 * 
 * @package Session
 * @author osamaradwan
 * @category Session
 */

class Session{

  private function __constract(){}

  /**
   * Session start function to start sessinn if not stared
   * 
   * 
   * @return void
   */

  public static function start(){
    if (! session_id()){
      session_start();
    }
  }

  /**
   * set function useing to push key and value to session
   * 
   * @param string $key
   * @param any $val
   * @return void
   */

  public function set($key, $val){
    $_SESSION[$key] = $val;
  }


  /**
   * has function using to check for value isset in $_SESSION 
   * 
   * @param string $key
   * @return bool
   */

  public static function has($key){
    return isset($_SESSION[$key]) ? true : false;
  }

  /**
   * get function using to get value form $_SESSION if isset
   * 
   * @param string $key;
   * 
   * @return any;
   */

  public static function get($key){
    if (self::has($key)){
      return $_SESSION[$key];
    }
    return null;
  }

  /**
   * remove function usig to remove value from $_SESSION using key 
   * 
   * @param string $key
   * @return void
   */

  public static function remove($key){
    if (self::has($key)){
      unset($_SESSION[$key]);
    }
  }

  /**
   * flash function using to get vlaue if isset and remove it or return null
   * 
   * @param string $key
   * @return value
   */

  public static function flash($key){
    $value = null;
    if(self::has($key)){
      $value = self::get($key);
      self::remove($key);
    }
    return $value;
  }

  /**
   * destroy function using to remove all vlaues in $_SESSION
   * 
   * @return void
   */

  public static function destory(){
    session_destroy();
  }


}