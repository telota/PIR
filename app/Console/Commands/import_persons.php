<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_persons extends Command {

    protected $signature = 'import:persons';
    protected $description = 'Import and convert raw Persons data';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');

        echo "\n\n--------------------- IMPORT Persons ----------------------\n\n";

        echo "Geting Content ... ";
        $raw = file_get_contents('/opt/projects/pir/data/pir2+_1_.txt');
        echo "done\n\n";

        echo "Splitting raw content by @pk ... ";
        $raw = explode('@pk', $raw);
        array_shift($raw);
        $count = count($raw);
        echo "done\n".$count." records\n\n";

        echo "Iterating raw content ... ";
        $data = [];

        foreach ($raw as $r) {
            if (!empty($r)) {
                $item = [];
                //$item['id'] = 'pir-id-'.sprintf('%06d', count($data) + 1);
                $item['id'] = 'https://pir.bbaw.de/id/'.(count($data) + 1);
                echo "\t".$item['id'];

                $r = ' @pk'.$r;

                foreach ([
                    'pk' => 'pir',
                    'rf' => 'reference',
                    'pn' => 'string',
                    'tr' => 'tribe',
                    'or' => 'origin',
                    'ge' => 'gender',
                    'st' => 'status',
                    'lz' => 'lifespan',
                    'ka' => 'career',
                    'tb' => 'occupations',
                    'vw' => 'relatives',
                    'ar' => 'article',
                    'qu' => 'sources',
                    'li' => 'literature',
                    'nt' => 'notes',
                    'da' => 'updated_at',
                    'bv' => 'is_public'
                ] as $at => $key) {

                    if (strpos($r, '@'.$at)) {
                        $val = explode('@'.$at, $r);
                        $val = explode('@', $val[1]);
                        $val = trim($val[0]);

                        if (!empty($val)) {
                            $val = str_replace('%>s', 'š', $val);
                            $val = str_replace('%>S', 'Š', $val);
                            $val = str_replace("\n", ' ', $val);
                            $val = preg_replace('/\r\n|\r|\n/', ' ', $val);
                        }

                        // PIR or alia reference
                        if ($at === 'pk' || $at === 'rf') {
                            if ($val != '-') {
                                $val = trim($val);
                                $val = $this->unescapeTustep($val);

                                if (empty($item['reference'])) $item['reference'] = ($at === 'pk' ? 'PIR ' : '').trim($val);
                                else $item['reference'] .= '; '.trim($val);
                            }
                        }

                        if (!empty($val)) {
                            // Personname
                            if ($at === 'pn') {
                                $item[$key] = $val;
                                $item['annotated'] = $this->unescapeTustep($val);
                                $item[$key] = $this->createSearchString($item['annotated']);
                                echo "\t".$item[$key]."\n";

                                if (str_contains($val, '#F+')) $item['class'] = 'eques';
                                else if (str_contains($val, '#K+')) $item['class'] = 'senator';
                                else $item['class'] = 'normal';
                            }
                            // Date
                            else if ($at === 'da') {
                                $date = explode('.', trim($val));
                                if (!empty($date[0]) && !empty($date[1]) && !empty($date[2])) {
                                    $item[$key] = trim($date[2]).'-'.trim($date[1]).'-'.trim($date[0]);
                                }
                            }
                            else if ($at !== 'pk' && $at !== 'rf') {
                                $item[$key] = $this->unescapeTustep($val);
                            }
                        }
                        // Note about Publication ("F" means "show")
                        if ($at === 'bv') {
                            $item[$key] = $val === 'F' ? true : false;
                        }
                    }
                }
                $data[] = $item;
            }
        }
        echo "done\n".count($data)." records\n\n";

        echo "Writing Content ... ";
        file_put_contents('data/persons.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }

    public function unescapeTustep ($val) {

        foreach ([
            'K' => 'k',
            'F' => 'b',
            '/' => 'i'
        ] as $tustep => $html) {
            $char = $tustep;
            if ($char === '/') $char = '\/';
            $pattern = '/#'.$char.'\+(.*?)#'.$char.'-/';

            $val = preg_replace_callback($pattern, function ($match) use ($html) {
                return '<'.$html.'>'.trim($match[1]).'</'.$html.'>';
            }, $val);
        }

        $val = preg_replace_callback('/#H:(.*?)#G:/', function ($match) use ($html) {
            return '<sup>'.trim($match[1]).'</sup>';
        }, $val);


        $val = trim(preg_replace('/\s+/', ' ', $val));

        return $val;
    }

    public function createSearchString ($string) {

        $string = preg_replace('/<i>(.*?)<\/i>/', ' ', $string);
        $string = trim(strip_tags($string));
        $string = str_replace('---', '...', $string);
        $string = trim(preg_replace('/[^a-zA-Z.\s]/', '', $string));
        $string = trim(preg_replace('/\s+/', ' ', $string));

        return $string;
    }
}
