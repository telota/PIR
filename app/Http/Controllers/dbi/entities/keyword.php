<?php

namespace App\Http\Controllers\dbi\entities;

use App\Http\Controllers\dbi\dbiInterface;
use Request;
use App\Http\Controllers\dbi\handler\pagination;


class keyword implements dbiInterface  {

    // Controller-Functions ------------------------------------------------------------------

    public function select ($user, $id) {
        $raw = file_get_contents('../../data/data2.dat');
        $lines = preg_split('/\r\n|\r|\n/', $raw);

        $post = Request::post();

        $offset = empty($post['offset']) ? 0 : intval($post['offset']);
        $limit = empty($post['limit']) ? 50 : (intval($post['limit']) > 100 ? 100 : intval($post['limit']));

        $post_string = $find = empty($post['string']) ? null : trim($post['string']);
        $isCs = empty($post['isCs']) ? false : true;
        $isOr = empty($post['isOr']) ? false : true;

        /*$find = null;
        if (!empty($post_string)) {
            foreach (preg_split('/\s+/', $post_string) as $split) {
                $find[] = $split;
            }
        }*/

        $data = [];
        for($i = 0; $i < count($lines) - 1; $i += 2) {
            if (!empty($lines[$i]) && !empty($lines[$i + 1])) {
                $matched = empty($find) ? true : $this->validateInput([trim($lines[$i]), $find, $isCs, $isOr]);

                if ($matched === true) {
                    $info = explode(':', $lines[$i + 1]);
                    $data[] = [
                        'value' => $lines[$i],
                        'html' => trim($info[0]),
                        'reference' => trim($info[1] ?? null),
                    ];
                }
            }
        }

        $count = count($data);

        // Pagination
        $paginator = new pagination;
        $pagination = $paginator -> process($count, ['offset' => $offset, 'limit' => $limit, 'sort_by' => 'string ASC']);
        $pagination['count'] = $count;
        $pagination['sort_by'] = 'string.asc';
        $offset = $pagination['offset'];
        $limit = $pagination['limit'];

        // Data to return
        $to_return = [];
        for($i = $offset; $i < ($offset + $limit) && $i < count($data); ++$i) {
            $to_return[] = $data[$i];
        }

        return [
            'pagination' => $paginator -> finalize($pagination, [
                'string' => $find,
                'isCs' => $isCs,
                'isOr' => $isOr
            ]),
            'contents' => $to_return
        ];
    }

    public function input ($user, $input) {
        die (abort(404, 'Not supported!'));
    }

    public function delete ($user, $input) {
        die (abort(404, 'Not supported!'));
    }

    public function connect ($user, $input) {
        die (abort(404, 'Not supported!'));
    }


    // Helper-Functions ------------------------------------------------------------------

    public function validateInput ($input) {
        $string = empty($input[2]) ? strtolower($input[0]) : $input[0];
        $finds = empty($input[2]) ? strtolower($input[1]) : $input[1];
        $finds = preg_split('/\s+/', $finds);
        $isOr = empty($input[3]) ? false : true;

        $no_match = 0;
        foreach ($finds as $find) {
            $pattern = '/'.$find.'/';
            if (!preg_match($pattern, $string)) ++$no_match;
        }
        return $no_match === 0 || ($isOr && $no_match < count($finds)) ? true : false;
    }
}
