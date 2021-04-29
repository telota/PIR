<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_addenda extends Command {

    protected $signature = 'import:addenda';
    protected $description = 'Import and convert raw Addenda data';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');

        echo "\n\n--------------------- IMPORT ADDENDA ----------------------\n\n";

        echo "Geting Content ... ";
        $raw = file_get_contents('/opt/projects/pir/data/pir2+_1_.txt');
        $json_html = json_decode(file_get_contents('/opt/projects/pir/data/addenda_html.json'), true);
        echo "done\n\n";

        echo "Splitting raw content by @pk ... ";
        $raw = explode('@pk', $raw);
        array_shift($raw);
        $count = count($raw);
        echo "done\n".$count." records\n\n";

        echo "Iterating raw content ... ";
        $empty = [];
        $public = [];
        $data = [];

        foreach ($raw as $r) {
            if (!empty($raw)) {
                $pk = explode('@', $r);
                $pk = trim($pk[0]);

                $item = $item_nn = ['id' => $pk === '-' ? null : $pk];

                foreach ([
                    'rf' => 'identifier',
                    'pn' => 'name',
                    'tr' => 'tribe',
                    'or' => 'origin',
                    'bv' => 'note',
                    'ge' => 'sex',
                    'st' => 'status',
                    'lz' => 'lifespan',
                    'ka' => 'career',
                    'tb' => 'occupations',
                    'vw' => 'relatives',
                    'ar' => 'article',
                    'qu' => 'sources',
                    'li' => 'literature',
                    'nt' => 'notes',
                    'da' => 'updated_at'
                ] as $at => $key) {
                    if (strpos($r, '@'.$at)) {
                        $val = explode('@'.$at, $r);
                        $val = explode('@', $val[1]);
                        $val = trim($val[0]);

                        if ($at === 'rf' && $val === '-') $val = null;

                        if (!empty($val)) {
                            if ($at === 'da') {
                                $date = explode('.', $val);
                                if (!empty($date[0]) && !empty($date[1]) && !empty($date[2])) $item[$key] = $item_nn[$key] = trim($date[2]).'-'.trim($date[1]).'-'.trim($date[0]);
                            }
                            else {
                                $item[$key] = $item_nn[$key] = $val;
                            }
                        }
                        else $item[$key] = null;
                    }
                }
                unset($item_nn['note']);

                if (empty($item['id']) && empty($item['identifier'])) $empty[] = $item;
                if ($item['note'] === 'F') $public[] = $item_nn;
                $data[] = $item;
            }
        }
        echo "done\n".
        count($data)." records in total\n".
        count($public)." public records\n".
        count($empty)." empty records\n\n";

        echo "Writing Content ... ";
        file_put_contents('data/addenda_all.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        file_put_contents('data/addenda_public.json', json_encode($public, JSON_UNESCAPED_UNICODE));
        file_put_contents('data/addenda_not_identified.json', json_encode($empty, JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
