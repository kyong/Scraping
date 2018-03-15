<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = './storage/merege.csv';
        $output = [];
        $keys = [];

        $csvlist = glob('./storage/*.csv');
        foreach( $csvlist as $csvfile){
            $handle = fopen($csvfile, "r");
            $count = 0;
            while ( ($data = fgetcsv($handle) ) !== FALSE ) {
                if( $count!==0 ){
                    $output[] =  $data;
                }else{
                    $keys = $data;
                }
                $count++;
            }
        }
        $f = fopen($filename, "w");
        if ( $f ) {
            fputcsv($f, $keys);
            foreach($output as $line){
                fputcsv($f, array_values($line));
            } 
        }
        fclose($f);
        return;
    }
}
