<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Request;

class AppController extends Controller {

    public function initiate () {
        return view('app.template', [
            'language' => substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2) === 'de' ? 'de' : 'en',
            'data' => []
        ]);
    }
}
