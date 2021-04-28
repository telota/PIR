<?php

namespace App\Http\Controllers\dbi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\dbi\dbiManager;
use Response;
use Auth;


class dbiController extends Controller {

    public function select ($entity, $id = NULL) {

        $user = ['id' => 1, 'level' => 11];
        $manager = new dbiManager();
        $dbi = $manager->select($user, $entity, $id);

        if (empty($dbi['error'])){

            // Detailed Response (if Contents is given by entity)
            if(isset($dbi['contents'])) {

                // Add url to pagination
                $dbi['pagination']['self']   = env('APP_URL').'/api/'.$entity.(empty($dbi['pagination']['self']) ? '' : ('?'.$dbi['pagination']['self']));
                $dbi['pagination']['pageOf'] = env('APP_URL').'/api/'.$entity.(empty($dbi['pagination']['pageOf']) ? '' : ('?'.$dbi['pagination']['pageOf']));
                foreach(['firstPage', 'previousPage', 'nextPage', 'lastPage'] as $i) {
                    $dbi['pagination'][$i] = (empty($dbi['pagination'][$i]) ? null : (env('APP_URL').'/api/'.$entity.'?'.$dbi['pagination'][$i]));
                }

                $response = [
                    'pagination' => isset($dbi['pagination']) ? $dbi['pagination'] : [],
                    'contents'  => $dbi['contents']
                ];
            }
            // Minimal Response (just array of results given)
            else {
                $response = [
                    'pagination' => ['count' => count($dbi)],
                    'contents' => $dbi
                ];
            }

            return Response::json($response, 200);
        }
        else {
            return Response::json($dbi, 200);
        }
    }

    // Helper Functions ---------------------------------------------------

    public function identitifyUser () {
        return [
            'id' => Auth::user()->id,
            'level' => Auth::user()->access_level
        ];
    }
}
