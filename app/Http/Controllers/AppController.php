<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Request;

class AppController extends Controller {

    public function initiate () {
        return view('app.template', ['settings' => [
            'language' => substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2) === 'de' ? 'de' : 'en',
            'baseURL' => rtrim(env('APP_URL', '/')),
            'statistics' => $this->statistics()
        ]]);
    }

    public function redirectSearch () {
        $query = Request::server('QUERY_STRING');
        if (!empty($query)) $query = '?'.$query;

        return redirect('/#/search'.$query);
    }

    public function statistics () {

        $items = file_get_contents('../data/persons.json');
        $items = json_decode($items, true);

        $count = [
            'date' => date ("Y-m-d", filemtime('../data/persons.json')),
            'total' => count($items),
            'addenda' => 0,
            'male' => [
                'normal' => 0,
                'eques' => 0,
                'senator' => 0
            ],
            'female' => [
                'normal' => 0,
                'eques' => 0,
                'senator' => 0
            ],
            'notKnown' => [
                'normal' => 0,
                'eques' => 0,
                'senator' => 0
            ]
        ];

        foreach ($items as $item) {
            if (!empty($item['is_public'])) ++$count['addenda'];

            if (empty($item['gender'])) {
                foreach(['normal', 'eques', 'senator'] as $key) {
                    if ($item['class'] === $key) ++$count['notKnown'][$key];
                }
            }
            else if (strtolower(substr($item['gender'], 0, 1)) === 'f') {
                foreach(['normal', 'eques', 'senator'] as $key) {
                    if ($item['class'] === $key) ++$count['female'][$key];
                }
            }
            else {
                foreach(['normal', 'eques', 'senator'] as $key) {
                    if ($item['class'] === $key) ++$count['male'][$key];
                }
            }
        }

        return $count;
    }
}
