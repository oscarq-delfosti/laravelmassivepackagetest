<?php

namespace Oscar\Massive\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function index(Request $request)
    {
        return "Hola Mundo";
    }

}
