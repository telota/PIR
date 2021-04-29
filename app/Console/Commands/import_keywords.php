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
        echo "done\n\n";

        echo "Splitting raw content in lines ... ";
        $lines = preg_split('/\r\n|\r|\n/', $raw);
        $count = count($lines);
        echo "done\n".$count." lines, ".($count / 2)." records\n\n";

        echo "Iterating lines ... ";
        $data = [];
        for($i = 0; $i < $count - 1; $i += 2) {
            if (!empty($lines[$i]) && !empty($lines[$i + 1])) {
                $info = explode(':', $lines[$i + 1]);
                $data[] = [
                    'textContent' => $lines[$i],
                    'htmlContent' => trim(array_shift($info)),
                    'reference' => trim(empty($info[0]) ? null : implode(':', $info)),
                ];
            }
        }
        echo "done\n".count($data)." records\n\n";

        echo "Writing Content ... ";
        $raw = file_put_contents('data/keywords.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
