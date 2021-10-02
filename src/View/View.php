<?php

  namespace Src\View;

  use Illuminate\Contracts\Filesystem\FileExistsException;
  use Src\File\File;
  use Jenssegers\Blade\Blade;

  class View
  {


    /**
     * @throws FileExistsException
     */
    public static function render($path, $data=[], $type='blade'){
      if($type =='blade'){
        return self::bladeRender($path, $data);
      }else{
        return self::normalRender($path, $data);
      }
    }


    public static function bladeRender($path, $data=[]): string
    {

      $blade = new Blade(File::makePath( 'App' . File::ds() . 'Views'), File::makePath('storage/cash'));
      return $blade->make($path, $data)->render();
    }

    /**
     * @throws FileExistsException
     */
    public static function normalRender($path, $data =[]){
      $path = str_replace(['/', '\\', '.'], File::ds(), $path);
      $path = File::makePath('App' . File::ds() . 'Views' . File::ds() . $path);
      $path = $path .'.php';
      if(file_exists($path)){
        ob_start();
        extract($data);
        include_once $path;
        $content = ob_get_clean();
        ob_end_clean();
        return $content;
      }else{
        throw new FileExistsException('Not found View: ' . $path . ' in Views Files');
      }
    }

  }