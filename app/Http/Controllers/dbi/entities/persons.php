<?php

namespace App\Http\Controllers\dbi\entities;

use App\Http\Controllers\dbi\dbiInterface;
use Request;
use App\Http\Controllers\dbi\handler\pagination;


class persons implements dbiInterface  {

    // Controller-Functions ------------------------------------------------------------------

    public function select ($user, $id) {

        $post = Request::post();

        // pagination parameters
        $offset = empty($post['offset']) ? 0 : intval($post['offset']);
        $limit = empty($post['limit']) ? 20 : (intval($post['limit']) > 100 ? 100 : intval($post['limit']));

        // ID
        if (empty($post['id']) && $id === null) $id = null;
        else {
            $id = $post['id'] ?? $id;
            $split = explode('-', $id);
            $split = explode('/', end($split));
            $id = intval(end($split));
        }
        // Keywords or Addenda
        $isAdd = empty($post['resource']) || $post['resource'] === 'keywords' ? false : true;
        // String
        $string = empty($post['string']) ? null : trim($post['string']);
        // Case
        $isCs = empty($post['case']) || $post['case'] === 'insensitive' ? false : true;
        // Connector
        $isOr = empty($post['connector']) || strtolower($post['connector']) === 'and' ? false : true;
        // Gender
        if (empty($post['gender'])) $gender = null;
        else {
            if (strtolower($post['gender']) === 'female') $gender = 'female';
            if (strtolower($post['gender']) === 'male') $gender = 'male';
        }
        // Class
        if (empty($post['class'])) $class = null;
        else {
            if (strtolower($post['class']) === 'normal') $class = 'normal';
            if (strtolower($post['class']) === 'eques') $class = 'eques';
            if (strtolower($post['class']) === 'senator') $class = 'senator';
        }

        // Get Items from File
        $items = file_get_contents('../data/persons.json');
        $items = json_decode($items, true);

        // Iterate over Items and check Match
        $data = [];
        foreach ($items as $item) {
            $matched = true;
            // Check ID
            if (!empty($id) && $item['id'] != $id) $matched = false;
            // Check Gender
            $item_gender = $item['gender'] ?? null;
            if ($matched === true && !empty($gender) && substr($gender, 0, 1) !== substr($item_gender, 0, 1)) $matched = false;
            // Check Social Class
            $item_class = $item['class'] ?? null;
            if ($matched === true && !empty($class) && $class !== $item_class) $matched = false;
            // Check String
            if ($matched === true && !empty($string)) $matched = $this->checkMatch([
                'isAdd' => $isAdd,
                'item' => $item,
                'find' => $string,
                'isCs' => $isCs,
                'isOr' => $isOr
            ]);

            if ($matched === true) {
                // Check if additional data can be shown
                if ($item['is_public'] === true) {
                    unset($item['is_public']);
                    $data[] = $item;
                }
                else {
                    $data[] = [
                        'id' => $item['id'],
                        'reference' => $item['reference'],
                        'string' => $item['string'],
                        'annotated' => $item['annotated'],
                        'class' => $item['class'],
                        'gender' => $item['gender'] ?? null,
                        'updated_at' => $item['updated_at'] ?? null
                    ];
                }
            }
        }
        unset($items); // unset $items to save memory

        $count = count($data);

        // Pagination
        $paginator = new pagination;
        $pagination = $paginator -> process($count, ['offset' => $offset, 'limit' => $limit, 'sort_by' => 'string ASC']);
        $pagination['count'] = $count;
        //$pagination['sort_by'] = 'string.asc';
        $offset = $pagination['offset'];
        $limit = $pagination['limit'];

        // Data to return
        $to_return = [];
        for($i = $offset; $i < ($offset + $limit) && $i < count($data); ++$i) {
            if (empty($data[$i])) break;
            $data[$i]['id'] = env('APP_URL').'/id/'.$data[$i]['id'];
            $to_return[] = $data[$i];
        }

        return [
            'pagination' => $paginator -> finalize($pagination, [
                'resource' =>  empty($isAdd) ? 'keywords' : 'addenda',
                'string' => $string,
                'connector' => $isOr === true ? 'OR' : 'AND',
                'case' => $isCs === true ? 'sensitive' : 'insensitive',
                'class' => empty($class) ? 'all' : $class,
                'gender' => empty($gender) ? 'all' : $gender
            ]),
            'contents' => $to_return
        ];
    }

    // Helper-Functions ------------------------------------------------------------------

    public function checkMatch ($input) {

        if ($input['isAdd'] === true) {
            if ($input['item']['is_public'] !== true) return false;
            else {
                unset($input['item']['is_public']);
                unset($input['item']['updated_at']);
                unset($input['item']['class']);
                unset($input['item']['gender']);
                unset($input['item']['annotated']);

                foreach ($input['item'] as $key => $val) {
                    $input['item'][$key] = strip_tags($val);
                }

                $string = implode('||', $input['item']);
            }
        }
        else $string = $input['item']['string'];

        if (empty($input['isCs'])) {
            $string = mb_strtolower($string,'UTF-8');
            $finds = mb_strtolower($input['find'],'UTF-8');
        }
        else $finds = $input['find'];

        $finds = preg_split('/\s+/', $finds);
        $isOr = empty($input['isOr']) ? false : true;

        $no_match = 0;
        foreach ($finds as $find) {
            $pattern = '/'.$find.'/';
            if (!preg_match($pattern, $string)) ++$no_match;
        }
        return $no_match === 0 || ($isOr && $no_match < count($finds)) ? true : false;
    }
}
