<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class export_csv extends Command {

    protected $signature = 'export:csv {file?}';
    protected $description = 'Export persons.json as csv';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');
        $fields = config('pir.fields');

        echo "\n\n--------------------- EXPORT CSV ----------------------\n\n";

        $file = trim($this->argument('file') ?? 'pir_export_'.date('Y-m-d'));
        $file = explode('.', $file);
        $file = trim($file[0].'.csv');

        echo "Geting Content ... ";
        $json = file_get_contents('data/persons.json');
        $json = json_decode($json, true);
        echo "done\n".count($json)." records in JSON file\n\n";

        $csv = fopen('data/'.$file, 'w');
        fputcsv($csv, $fields);

        echo "Writing CSV ... \n";

        foreach ($json as $j) {
            echo "\t".$j['id']."\n";

            if ($j['is_public'] === true) $j['is_public'] = 'F';

            $item = [];
            foreach ($fields as $field) {
                $item[$field] = $j[$field] ?? '';
            }
            fputcsv($csv, $item);
        }
        echo "done\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
