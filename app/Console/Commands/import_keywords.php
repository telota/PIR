<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_keywords extends Command {

    protected $signature = 'import:keywords';
    protected $description = 'Import and convert raw keywords data';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');

        echo "\n\n--------------------- IMPORT KEYWORDS ----------------------\n\n";

        echo "Geting Content ... ";
        $raw = file_get_contents('/opt/projects/pir/data/data2.dat');
        $ap = json_decode(file_get_contents('/opt/projects/pir/src/data/addenda_public.json'), true);
        echo "done\n\n";

        echo "Splitting raw content in lines ... ";
        $lines = preg_split('/\r\n|\r|\n/', $raw);
        $count = count($lines);
        echo "done\n".$count." lines, ".($count / 2)." records\n\n";

        echo "Iterating lines ... ";
        $data = $missing = $data_addenda = [];
        for($i = 0; $i < $count - 1; $i += 2) {
            if (!empty($lines[$i]) && !empty($lines[$i + 1])) {
                $id = 'pir-id-'.sprintf('%06d', count($data) + 1);
                $item = ['id' => $id];

                $item['plaintext'] = $lines[$i];
                $info = explode(':', $lines[$i + 1]);

                $item['annotated'] = trim(array_shift($info));
                $item['annotated'] = str_replace('<b> ', ' <b>', $item['annotated']);
                $item['annotated'] = str_replace(' </b>', '</b> ', $item['annotated']);
                $item['annotated'] = trim(preg_replace('/\s+/', ' ', $item['annotated']));

                $item['reference'] = trim(empty($info[0]) ? null : trim(implode(':', $info)));

                // Handle Superscript in Reference
                if (!empty($item['reference'])) {
                    foreach(['¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'] as $s => $u) {
                        $item['reference'] = str_replace('<sup>'.($s + 1).'</sup>', $u, $item['reference']);
                    }
                }

                // Look for hmtl entities
                foreach(['plaintext', 'annotated', 'reference'] as $key) {
                    if (!empty($item[$key])) {
                        $item[$key] = str_replace('&#154;', 'š', $item[$key]);
                        $item[$key] = str_replace('&#138;', 'Š', $item[$key]);
                        $item[$key] = str_replace("\n", ' ', $item[$key]);
                        $item[$key] = html_entity_decode($item[$key]);
                    }
                }

                $item['status'] = null;

                // Check if record has addendum
                if (substr($item['annotated'], 0, 37) === '<a href="../../forschung/pir/addenda/') {
                    $html = $item['annotated'];
                    $html = explode('.html">', $html);

                    $link = explode('../../forschung/pir/addenda/', $html[0]);
                    $item['addendum'] = $link[1];
                    $item['annotated'] = trim(str_replace('</a>', '', $html[1]));

                    // Look for match in addenda
                    foreach ($ap as $ai => $add) {
                        $ref = $item['reference'];
                        $ref = trim(substr($ref, 0, 3) === 'PIR' ? substr($ref, 5) : $ref);

                        if (in_array($ref, [$add['id_pir'], $add['reference']])) {
                            unset($add['id_pir']);
                            unset($add['reference']);
                            $item['addendum'] = $add;
                            unset($ap[$ai]);
                            break;
                        }
                        else {
                            $ref = explode(' (=', $ref);
                            $ref = trim($ref[0]);
                            if (in_array($ref, [$add['id_pir'], $add['reference']])) {
                                unset($add['id_pir']);
                                unset($add['reference']);
                                $item['addendum'] = $add;
                                unset($ap[$ai]);
                                break;
                            }
                        }
                    }
                    if (!is_array($item['addendum'])) {
                        $missing[] = $item['reference'];
                        unset($item['addendum']);
                    }
                }

                // Analyse Annotations
                if (str_contains($item['annotated'], '<b>')) $item['status'] = 'eques';
                else {
                    $LC = trim(preg_replace('/[^a-z]/', '', preg_replace('/<i>(.*?)<\/i>/', '', $item['annotated'])));
                    $UC = trim(preg_replace('/[^A-Z]/', '', $item['annotated']));
                    if (empty($LC) && !empty($UC)) $item['status'] = 'senator';
                }
                if (empty($item['status'])) unset($item['status']);

                $data[$id] = $item;
                if (!empty($data[$id]['addendum'])) $data_addenda[$id] = $data[$id];
            }
        }
        echo "done\n".count($data)." records\n";
        echo "done\n".count($ap)." remaining addenda\n\n";

        sort($missing);

        foreach ($missing as $miss) echo $miss."\n";
        echo "\n";

        $ap = array_map(function ($add) { return empty($add['id_pir']) ? $add['reference'] : $add['id_pir']; }, $ap);
        sort($ap);

        foreach ($ap as $add) echo $add."\n";
        echo "\n";

        echo "Writing Content ... ";
        file_put_contents('data/keywords.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        file_put_contents('data/keywords_addenda.json', json_encode($data_addenda, JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
