<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_addendahtml extends Command {

    protected $signature = 'import:addendahtml';
    protected $description = 'Import and convert raw Addenda data';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');

        echo "\n\n--------------------- IMPORT ADDENDA HTML ----------------------\n\n";

        echo "Geting Content ... ";
        $raw = file_get_contents('/opt/projects/pir/data/pir_add.dat');
        echo "done\n\n";

        echo "Splitting raw content by a-elements ... ";
        $raw = explode('<a href="../../forschung/pir/addenda/', $raw);
        $count = count($raw);
        echo "done\n".$count." records\n\n";

        echo "Iterating raw content ... ";
        $data = $alia = [];
        foreach ($raw as $r) {
            if (!empty($raw)) {
                $split = explode('.html">', $r);
                $id = trim($split[0]);

                if (!empty($id)) {
                    $id_formated = substr($id, 0, 5) === 'alia/' ? substr($id, 5) : str_replace('/', ' ', $id);

                    $split = explode('</a>', $split[1]);
                    $htmlContent = trim($split[0]);
                    $textContent = trim(preg_replace('/\s+/', ' ',strip_tags($htmlContent)));

                    $data[$id] = [
                        'id' => $id_formated,
                        'textContent' => $textContent,
                        'htmlContent' => $htmlContent
                    ];

                    if (substr($id, 0, 5) === 'alia/') $alia[$id] = $data[$id];
                }
            }
        }
        echo "done\n".count($data)." records in total\n".count($alia)." alia records\n";

        echo "Writing Content ... ";
        file_put_contents('data/addenda_html.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        file_put_contents('data/addenda_html_alia.json', json_encode($alia, JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
