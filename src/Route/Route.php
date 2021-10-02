<?php

  namespace Src\Route;

  use BadFunctionCallException;
  use BadMethodCallException;
  use InvalidArgumentException;
  use ReflectionException;
  use Src\Http\Response;
  use Src\Http\Request;

  class Route
  {

    /**
     * @var array
     */
    private static array $_routes = [];
    /**
     * @var array
     */

    private static array $_routes_with_params = [];
    private static string $_prefix = '';

    /**
     * @var string
     */
    private static string $_middleware = '';

    private function __construct()
    {
    }

    /**
     *
     * set Routes to self::$_routes
     *
     * @param string $methods
     * @param string $route
     * @param callable|object $callback
     *
     */
    private static function add(string $methods, string $route, mixed $callback): void
    {
      $route = str_replace("//", "/", static::$_prefix . '/' . $route);
      foreach (explode("|", $methods) as $method) {
        if(strpos($route, "{")){
          self::$_routes_with_params[$method][$route] =[
            'callback' => $callback,
            'middleware' => static::$_middleware
          ];
        }else{
          self::$_routes[$method][$route] = [
            'callback' => $callback,
            'middleware' => static::$_middleware
          ];
        }
      }


    }

    /**
     * Get: Set Route to using into get method
     *
     * @param string $route
     * @param callable $callback
     */
    public static function Get(string $route, mixed $callback): void
    {
      self::add('GET', $route, $callback);
    }

    /**
     * Get: Set Route to using into POST method
     *
     * @param string $route
     * @param callable $callback
     */
    public static function Post(string $route, mixed $callback): void
    {
      self::add('POST', $route, $callback);
    }

    /**
     * any: Set Route to using into any method get OR Post
     *
     * @param string $route
     * @param callable $callback
     */
    public static function Any(string $route, callable $callback): void
    {
      self::add('GET|POST', $route, $callback);
    }

    /**
     * Custom: Set Route to using into Custom method like [head, option, delete] or
     *
     * pass method like "HEAD|POST|OPTION"
     * pass route like "/home/2/s"
     *
     * @param string $method
     * @param string $route
     * @param callable $callback
     */
    public static function Custom(string $method, string $route, callable $callback): void
    {
      self::add($method, $route, $callback);
    }

    /**
     * prefix function using to add prefix into routes like prefix('admin', function(){Route::GET('/', $callback)})
     *
     * @param string $prefix
     * @param callable $callback
     */

    public static function prefix(string $prefix, callable $callback): void
    {
      $parent_prefix = static::$_prefix;
      static::$_prefix .= '/' . trim($prefix, '/');
      if (is_callable($callback)) {
        call_user_func($callback);
      } else {
        throw new BadFunctionCallException('Pleas Insert Valid Callback');
      }
      self::$_prefix = $parent_prefix;
    }


    /**
     * middleware function using to add prefix into routes like middleware('admin|owner', function(){Route::GET('/',
     * $callback)})
     *
     * @param string $middleware
     * @param callable $callback
     */

    public static function middleware(string $middleware, callable $callback): void
    {
      $parent_middle = static::$_middleware;
      static::$_middleware .= '|' . trim($middleware, '|');
      if (is_callable($callback)) {
        call_user_func($callback);
      } else {
        throw new BadFunctionCallException('Pleas Insert Valid Callback');
      }
      self::$_middleware = $parent_middle;
    }


    /**
     * @throws ReflectionException
     */
    public static function handle(): mixed
    {
      $url = Request::getUri();
      $method = strtoupper(Request::method());
      if(isset(self::$_routes[$method][$url])) {
        return  self::invoke(self::$_routes[$method][$url]);
      }else {
        foreach (static::$_routes_with_params[$method] as $route => $options) {
          $route_string = $route;
          $route = preg_replace('/\/({\s?[A-Z|a-z]+.[0-9]?+\s?})/', '/([\w+]?[a-zA-Z0-9!{})(،؛:~×÷+><|*%_[?؟@\#$%^&*)(+=._-]+)', $route);
          $route = "#^" . $route . '#';
          if (preg_match($route, $url, $matches)) {
              preg_match_all('/\/({\s?[A-Z|a-z]+.[0-9]?+\s?})/', $route_string, $params_name);
              array_shift($params_name);
              array_shift($matches);
              $params = array_values($matches);
              unset($route_string);
              $invoke = self::invoke($options, $params, $params_name);
              unset($route, $params_name, $params);
              return $invoke;
            }
          }
      }

      http_response_code(404);
      return '404';

    }


    /**
     * @throws ReflectionException
     */
    private static function invoke(mixed $route, array $params=[], array $params_name=[])
    {

      $callback = $route['callback'];
      $req = new Request();
      $res = new Response();
      if($params and $params_name){
        for ($i = 0; $i < count($params);) {
          $req->{preg_replace('/(\s?[{|}]\s?)/', '', $params_name[0][$i])} = $params[$i];
          ++$i;
        }
      }
      static::execute_middleware($route['middleware'], $req, $res);
      if (is_callable($callback)) {
        return call_user_func($callback, $req, $res);
      } elseif (is_string($callback) & strpos($callback, "@") != false) {
        list($controller, $method) = explode("@", $callback);
        $controller = 'App\Controllers\\' . $controller;
        if (class_exists($controller)) {
          if (method_exists($controller, $method)) {
            return call_user_func_array([new $controller, $method], [$req, $res]);
          } else {
            throw new BadMethodCallException('Please insert method ' . $method . ' into controller: ' .
              $controller);
          }

        } else {
          throw new ReflectionException('Controller Class: ' . $controller . ' is Not Found');
        }
      } elseif (is_string($callback)) {
        return $callback;
      } else {
        throw new InvalidArgumentException('Please Pass Valid Callback or Method or String');
      }
    }

    /**
     * @throws ReflectionException
     */
    private static function execute_middleware($middleware, $req, $res)
    {
      $middleware = explode("|", $middleware);
      foreach ($middleware as $class) {
        if ($class != '') {
          $class = 'App\Middleware\\' . $class;
          if (class_exists($class)) {
            call_user_func_array([new $class, 'handle'], [$req, $res]);
          } else {
            throw new ReflectionException('Middleware Class: ' . $class . ' is Not Found');
          }
        }
      }
    }



  }