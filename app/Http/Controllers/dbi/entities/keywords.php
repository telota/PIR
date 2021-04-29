<?php

namespace App\Http\Controllers\dbi\entities;

use App\Http\Controllers\dbi\dbiInterface;
use Request;
use App\Http\Controllers\dbi\handler\pagination;


class keywords implements dbiInterface  {

    // Controller-Functions ------------------------------------------------------------------

    public function select ($user, $id) {

        $post = Request::post();

        $offset = empty($post['offset']) ? 0 : intval($post['offset']);
        $limit = empty($post['limit']) ? 50 : (intval($post['limit']) > 100 ? 100 : intval($post['limit']));

        $find = empty($post['string']) ? null : trim($post['string']);
        $isCs = empty($post['isCs']) ? false : true;
        $isOr = empty($post['isOr']) ? false : true;

        // Get Items from File
        $items = file_get_contents('../../data/keywords.json');
        $items = json_decode($items, true);

        // Iterate over Items and check Match
        $data = [];
        foreach ($items as $item) {
            $matched = empty($find) ? true : $this->validateInput([$item['textContent'], $find, $isCs, $isOr]);

            if ($matched === true) $data[] = $item;
        }
        unset($items); // unset $items to save memory

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
