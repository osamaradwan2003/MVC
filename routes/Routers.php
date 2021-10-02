<?php

use Src\http\Request;

  /**
 * Template File Doc Comment
 *  this is Routers for  application Add all Urls hear
 * PHP version >= 7.4
 *
 * @category Application
 * @package  Mvc
 * @author   osama <osamaradwan2003@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost/
 */

use Src\Route\Route;

  Route::Get("/", function ($req, $res){

});


Route::middleware("CheckLogin", function (){
  Route::Get('/users/{id}/{name}/profile', 'UserController@login');
});

Route::Post('/users/{id}/{name}/signup', function(Request $req){
  return [$req->id, $req->name];
});

