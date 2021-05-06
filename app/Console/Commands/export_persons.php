<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class export_persons extends Command {

    protected $signature = 'export:persons';
    protected $description = 'Export and persons.json as csv';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');

        $fields = [
            'id',
            'reference',
            'annotated',
            'status',
            'gender',
            'tribe',
            'origin',
            'lifespan',
            'career',
            'occupations',
            'relatives',
            'article',
            'sources',
            'literature',
            'notes',
            'updated_at',
            'is_public'
        ];

        echo "\n\n--------------------- EXPORT Persons ----------------------\n\n";

        echo "Geting Content ... ";
        $json = file_get_contents('data/persons.json');
        $json = json_decode($json, true);
        echo "done\n".count($json)." records in JSON file\n\n";

        $csv = fopen('data/persons.csv', 'w');
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
