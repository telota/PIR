<?php

namespace App\Http\Controllers\dbi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\dbi\dbiManager;
use App\Http\Controllers\dbi\DataConverter;
use Response;


class APIController extends Controller {

    public function select ($opt = NULL) {

        $user = ['id' => 1, 'level' => 11];
        $manager = new dbiManager();
        $dbi = $manager->select($user, 'persons', null);

        if (empty($dbi['error'])){
            return Response::json([
                'meta' => $this->addMeta(),
                'pagination' => $this->remapPagination($dbi['pagination']),
                'contents' => $this->remapContents($opt, $dbi['contents'])
            ], 200);
        }
        else {
            return Response::json($dbi, 400);
        }
    }

    public function addMeta () {
        return [
            'serviceProvider' => [
                'name' => 'Berlin-Brandenburg Academy of Sciences and Humanties, TELOTA - IT/DH',
                'link' => 'https://www.bbaw.de/en/bbaw-digital/telota'
            ],
            'dataProvider' => [
                'name' => 'Prosopographia Imperii Romani',
                'link' => 'https://pir.bbaw.de'
            ],
            'operatingInstructions' => [
                'manual' => env('APP_URL').'/#/api',
                'availableParameters' => [
                    'resource' => '[keywords, addenda]',
                    'gender' => '[all, female, male]',
                    'class' => '[all, normal, eques, senator]',
                    'string' => 'STRING (several strings separated by blank)',
                    'connector' => '[AND, OR]',
                    'case' => '[insensitive, sensitive]'
                ]
            ]
        ];
    }

    public function remapPagination ($pagination) {
        $pagination['self']   = env('APP_URL').'/api'.(empty($pagination['self']) ? '' : ('?'.$pagination['self']));
        $pagination['pageOf'] = env('APP_URL').'/api'.(empty($pagination['pageOf']) ? '' : ('?'.$pagination['pageOf']));

        foreach([
            'firstPage',
            'previousPage',
            'nextPage',
            'lastPage'
        ] as $i) {
            $pagination[$i] = (empty($pagination[$i]) ? null : (env('APP_URL').'/api'.'?'.$pagination[$i]));
        }
        return $pagination;
    }

    public function remapContents ($opt, $data) {
        if ($opt === 'website') {
            $converter = new DataConverter();
            return array_map(function ($item) use ($converter) {
                return $converter->removeTustepComments($item);
            }, $data);
        }
        else {
            return array_map(function ($item) {
                return $item['id'].'.jsonld';
            }, $data);
        }
    }
}
