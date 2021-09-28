<?php

use Src\http\Request;
use Src\Http\Response;

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

use Src\Route\Route;
use Src\View\View;


Route::Get("/", function ($req, $res){
  return View::render('user.home', ['title'=>'home']);
});


Route::middleware("CheckLogin", function (){
  Route::Get('/users/{id}/{name}/profile', 'UserController@login');
});

Route::Post('/users/{id}/{name}/signup', function(Request $req,Response $res){
  return [$req->id, $req->name];
});

