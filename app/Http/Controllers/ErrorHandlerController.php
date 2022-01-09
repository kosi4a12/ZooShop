<?php


namespace App\Http\Controllers;


class ErrorHandlerController extends Controller
{
    public function returnError($code)
    {
        $route = 'errors/' . $code;

        return view($route);
    }
}
