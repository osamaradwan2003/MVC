<?php


namespace Src\Session;

use JetBrains\PhpStorm\Pure;

/**
 * description: session class to manage a sessions in framework
 * 
 * @package Session
 * @author osamaradwan
 * @category Session
 */

class Session{

    /**
   * Session start function to start session if not stared
   * 
   * 
   * @return void
   */

  public static function start(){
    ini_set('session.use_only_cookies', 1);
    if (! session_id()){
      session_start();
    }
  }

  /**
   * set function using to push key and value to session
   * 
   * @param string $key
   * @param  $val
   * @return void
   */

  public static function set(string $key, $val){
    $_SESSION[$key] = $val;
  }


  /**
   * has function using to check for value isset in $_SESSION 
   * 
   * @param string $key
   * @return bool
   */

  public static function has(string $key): bool
  {
    return isset($_SESSION[$key]);
  }

    /**
     * get function using to get value form $_SESSION if isset
     *
     * @param string $key ;
     *
     * @return mixed;
     */

   #[Pure] public static function get(string $key): mixed{
    if (self::has($key)){
      return $_SESSION[$key];
    }
    return null;
  }

  /**
   * remove function using to remove value from $_SESSION using key
   * 
   * @param string $key
   * @return void
   */

  public static function remove(string $key){
    if (self::has($key)){
      unset($_SESSION[$key]);
    }
  }

    /**
     * flash function using to get value if isset and remove it or return null
     *
     * @param string $key
     * @return mixed
     */

  public static function flash(string $key): mixed
  {
    $value = null;
    if(self::has($key)){
      $value = self::get($key);
      self::remove($key);
    }
    return $value;
  }

  /**
   * destroy function using to remove all values in $_SESSION
   *
   * @return void
   */

  public static function destroy(){
    foreach($_SESSION as $key => $value){
        unset($_SESSION[$key]);
    }
}


  /**
   * all function using to return all $_SESSION
   * 
   * @return array
   */

  public static function all(): array{
    return $_SESSION;
  }


}