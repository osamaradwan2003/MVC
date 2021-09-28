<?php

  namespace Src\File;

  class File
  {

    private function __construct()
    {
    }

    /**
     * return root Path
     *
     * @return string
     */
    public static function root():string{
      return ROOT;
    }

    /**
     * return directory separator
     *
     * @return string
     */
    public static function ds():string{
      return DS;
    }

    /**
     * return full path
     *
     * @param string $path
     * @return string
     */
    public static function makePath(string $path):string{
      $path = static::root() . static::ds() . trim($path, "/");
      return str_replace(['\\', '/'], static::ds(), $path);
    }


    /**
     * return file exist bool
     *
     * @param string $path
     * @return bool
     */
    public static function exist(string $path):bool{
      return file_exists(static::makePath($path));
    }

    /**
     * require_once file if exist
     *
     * @param string $path
     * @return mixed
     */
    public static function require_file(string $path):mixed {
      if (static::exist($path)){
        return require_once static::makePath($path);
      }
      return null;
    }

    /**
     * include_once file if exist
     *
     * @param string $path
     * @return mixed
     */

    public static function include_file(string $path):mixed{
      if (static::exist($path)){
        return include_once static::makePath($path);
      }
      return null;
    }

    /**
     * require all directory  files if exist
     *
     * @param string $dirname
     * @return mixed
     */

    public static function require_dir(string $dirname):mixed{
      if (static::exist($dirname)){
        $files = array_diff( scandir(static::makePath($dirname)), ['.', '..']);
        foreach ($files as $file){
          $file = $dirname . self::ds() . $file;
          self::require_file($file);
        }

      }
      return null;
    }


  }