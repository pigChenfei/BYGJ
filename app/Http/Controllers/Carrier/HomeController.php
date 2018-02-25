<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Controllers\AppBaseController;
use App\Models\CarrierUser;
use Illuminate\Http\Request;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:carrier');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(route('players.index'));
    }
}
