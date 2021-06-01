<?php

namespace App\Http\Controllers\dbi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\dbi\dbiManager;
use App\Http\Controllers\dbi\DataConverter;
use Response;


class dbiController extends Controller {

    public function provideJson ($id) {
        $split = explode('-', $id);
        $split = explode('/', end($split));
        $id = intval(end($split));

        $user = ['id' => 1, 'level' => 11];
        $manager = new dbiManager();
        $dbi = $manager->select($user, 'persons', $id);

        if (empty($dbi['error'])) {
            if (empty($dbi['contents'][0]['id'])) die(abort(404, 'unknown ID'));
            else {
                $converter = new DataConverter();
                return $converter->json($dbi['contents']);
            }
        }
        else return Response::json($dbi, 400);
    }

    public function provideTxt ($id) {
        $split = explode('-', $id);
        $split = explode('/', end($split));
        $id = intval(end($split));

        $user = ['id' => 1, 'level' => 11];
        $manager = new dbiManager();
        $dbi = $manager->select($user, 'persons', $id);

        if (empty($dbi['error'])) {
            if (empty($dbi['contents'][0]['id'])) die(abort(404, 'unknown ID'));
            else {
                $converter = new DataConverter();
                return $converter->txt($dbi['contents']);
            }
        }
        else return Response::json($dbi, 400);
    }

    public function redirectID ($id) {
        $split = explode('-', $id);
        $split = explode('/', end($split));
        $id = intval(end($split));

        return redirect('/#/search?id='.$id);
    }
}
