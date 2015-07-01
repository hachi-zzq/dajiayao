<?php namespace Dajiayao\Http\Controllers\Buyer;

use Dajiayao\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Session;

class BaseController extends Controller
{


    protected $inputData;

    protected $buyerId;

    public function __construct(Request $request)
    {
        $this->inputData = $request;
        $this->buyerId = Session::get('buyer_id');
    }

}