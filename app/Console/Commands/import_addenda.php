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
                $pk = str_replace('PIR1', 'PIR¹', $pk);
                $pk = str_replace('PIR2', 'PIR²', $pk);
                $pk = str_replace('#H:2#G:', '²', $pk);

                $item = $item_nn = ['id_pir' => $pk === '-' ? null : $pk];

                foreach ([
                    'rf' => 'reference',
                    'pn' => 'string',
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

                        if (!empty($val)) {
                            $val = str_replace('%>s', 'š', $val);
                            $val = str_replace('%>S', 'Š', $val);
                            $val = str_replace("\n", ' ', $val);
                        }

                        if ($at === 'rf') {
                            if ($val === '-') $val = null;
                            else {
                                $val = preg_replace('/\r\n|\r|\n/', ' ', $val);
                                $val = str_replace('PIR1', 'PIR¹', $val);
                                $val = str_replace('PIR2', 'PIR²', $val);
                                $val = str_replace('#H:2#G:', '²', $val);
                            }
                            $item['reference'] = $item_nn['reference'] = $val;
                        }

                        if (!empty($val)) {
                            if ($at === 'da') {
                                $date = explode('.', $val);
                                if (!empty($date[0]) && !empty($date[1]) && !empty($date[2])) $item[$key] = $item_nn[$key] = trim($date[2]).'-'.trim($date[1]).'-'.trim($date[0]);
                            }
                            else if ($at === 'pn') {
                                $unesc = $this->unescapeName($val);
                                $item[$key] = $item_nn[$key] = $unesc[$key];
                                $item['annotated'] = $item_nn['annotated'] = $unesc['annotated'];
                            }
                            else {
                                if (!in_array($at, ['bv', 'tr', 'to', 'lz', 'ka', 'tb', 'vw'])) $item_nn[$key] = $val;
                                $item[$key] = $val;
                            }
                        }
                        else $item[$key] = null;
                    }
                }

                if (empty($item['id_pir']) && empty($item['reference'])) $empty[] = $item;
                if ($item['note'] === 'F') {
                    $public[] = $item_nn;
                    echo "\t".($item_nn['id_pir'] ?? '-')."\t".($item_nn['reference'] ?? '-')."\n";
                }
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

    public function unescapeName ($val) {
        $annotated = $val;

        foreach ([
            'F' => 'b',
            '/' => 'i'
        ] as $tustep => $html) {
            $char = $tustep;
            if ($char === '/') $char = '\/';
            $pattern = '/#'.$char.'\+(.*?)#'.$char.'-/';

            $annotated = preg_replace_callback($pattern, function ($match) use ($html) {
                return '<'.$html.'>'.trim($match[1]).'</'.$html.'>';
            }, $annotated);
        }


        $val = preg_replace('/<i>(.*?)<\/i>/', ' ', $annotated);

        // Handle Capitalize
        $val = preg_replace_callback('/#K\+(.*?)#K-/', function ($match) {
            return trim($match[1]);
        }, $val);

        $val = trim(strip_tags($val));
        $val = trim(preg_replace('/[^a-zA-Z.\s]/', '', $val));
        $val = trim(preg_replace('/\s+/', ' ', $val));

        // Handle Capitalize
        $annotated = preg_replace_callback('/#K\+(.*?)#K-/', function ($match) {
            return strtoupper(trim($match[1]));
        }, $annotated);
        $annotated = trim(preg_replace('/\s+/', ' ', $annotated));

        return [
            'string' => $val,
            'annotated' => $annotated
        ];
    }
}
