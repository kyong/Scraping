<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvtTest extends TestCase
{
    /**
     */
    public function testOutPutTest()
    {

        $array = [
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3'
            ],
            [
                'key1' => 'value4',
                'key2' => 'value5',
                'key3' => 'value6'
            ]
        ];
        $outputpath = './test.csv';
        \Csv::outputArrayKeyValue( $array, $outputpath );
        $csvtext = file_get_contents( $outputpath );
        $text = 'key1,key2,key3
value1,value2,value3
value4,value5,value6
';
        $this->assertEquals( $text, $csvtext );
    }

    public function testLoadCsvTest()
    {
        $inputpath = './test.csv';
        $ret = \Csv::load( $inputpath );
        $array = [
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3'
            ],
            [
                'key1' => 'value4',
                'key2' => 'value5',
                'key3' => 'value6'
            ]
        ];
        $this->assertEquals( $array, $ret );
    }
    public function testLoadCsv2Test()
    {
        $inputpath = './test.csv';
        $ret = \Csv::load( $inputpath, false );
        $array = [
            [
                'key1',
                'key2',
                'key3'
            ],
            [
                'value1',
                'value2',
                'value3'
            ],
            [
                'value4',
                'value5',
                'value6'
            ]
        ];
        $this->assertEquals( $array, $ret );
    }
}
