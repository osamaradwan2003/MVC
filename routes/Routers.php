<?php


/**
 * Template File Doc Comment
 *  this is Routers for  application Add all Urls hear
 * PHP version 7.4
 *
 * @category Application
 * @package  Mvc
 * @author   osama <osamaradwan2003@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost/
 */

use Mvc\app\Router;

Router::GET("/", "hello");


Router::GET(
    "/users/{id}/{name}",
    function ($req, $res) {

        return "id";
    }
);
