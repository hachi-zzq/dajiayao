<?php namespace Dajiayao\Http\Controllers\Rest\Buyer\V1;

use Dajiayao\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{

    public function __construct(Request $request)
    {
        $this->inputData = $request;
        $this->buyerId = Session::get('buyer_id');
    }

}