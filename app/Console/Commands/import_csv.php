<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class import_csv extends Command {

    protected $signature = 'import:csv {file?}';
    protected $description = 'Import persons.csv, convert to json';
    public function __construct() { parent::__construct(); }

    // ------------------------------------------------------------------------------

    public function handle() {
        $time = date('U');
        $fields = config('pir.fields');

        echo "\n\n--------------------- IMPORT CSV ----------------------\n\n";

        $file = trim($this->argument('file') ?? 'pir_import.csv');
        if (strtolower(substr($file, -4)) !== '.csv') die ('ERROR: only csv files are allowed!'."\n\n");
        if (!file_exists('data/'.$file)) die ('ERROR: '.$file.' not found in /data'."\n\n");

        echo "Reading CSV ... ";
        $file = fopen('data/'.$file, 'r');
        $content = [];
        while (($csv = fgetcsv($file)) !== FALSE) {
            $content[] = $csv;
        }
        echo "done\n".count($content)." records\n\n";

        echo "Checking Keys ... ";
        if ($content[0] === $fields) {
            echo "OK\nThe table structure of the file provided matches the required scheme.\n\n";
            unset($content[0]);
        }
        else {
            echo "ERROR\n\nThe table structure of the file provided differs from the required scheme.\nEnsure the first row of the CSV contains the following keys in the given order and lower case:\n\n";
            echo "\t".implode("\n\t", $fields)."\n\n";
            die ("Abort Script\n\n");
        }

        echo "Processing Items ... \n";
        $items = [];
        foreach ($content as $data) {
            $item = [];
            $data = mb_convert_encoding($data, "UTF-8");

            foreach ($fields as $i => $key) {
                // ID
                if ($key === 'id') {
                    $id = trim($data[$i]);
                    $id = intval($id);

                    if ($id < 1) die ("\tERROR\t".'Invalid ID "'.trim($data[$i]).'" at Row '.(count($items) + 1)."\nAborting Script\n\n");
                    if (!empty($items[$id])) die ("\tERROR\t".'Duplicate detected ID '.$id." at Row ".(count($items) + 1)."\nAborting Script\n\n");

                    $item['id'] = $id;
                }
                // Name
                else if ($key === 'annotated') {
                    // String
                    $data[$i] = trim($data[$i]);
                    $string = preg_replace('/<i>(.*?)<\/i>/', ' ', $data[$i]);
                    $string = trim(strip_tags($string));
                    $string = str_replace('---', '...', $string);
                    $string = trim(preg_replace('/[^a-zA-Z.\s]/', '', $string));
                    $item['string'] = trim(preg_replace('/\s+/', ' ', $string));

                    // annotated
                    $item[$key] = $data[$i];

                    // Class
                    if (str_contains($data[$i], '<b>')) $item['class'] = 'eques';
                    else if (str_contains($data[$i], '<k>')) $item['class'] = 'senator';
                    else $item['class'] = 'normal';
                }
                // Public Status
                else if ($key === 'is_public') $item[$key] = empty($data[$i]) ? false : true;
                // Any other value
                else if (!empty($data[$i])) $item[$key] = trim($data[$i]);
            }
            echo "\tOK\tID ".$item['id']."\t".$item['string']."\n";
            $items[$id] = $item;
        }
        echo "done\n".count($items)." records\n\n";

        echo "Writing Content ... ";
        file_put_contents('data/persons-test.json', json_encode(array_values($items), JSON_UNESCAPED_UNICODE));
        echo "done\n\n";

        echo "SUCCESS!\n\nExecution time: ".(date('U') - $time)." sec\n";
        echo "\n\n--------------------- END ----------------------\n\n";
    }
}
