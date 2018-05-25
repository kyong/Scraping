<?php
namespace Kyong\Csv;

class Csv {
    /**
     * [
     *  [
     *      'key1' => 'value1,
     *      'key2' => 'value2,
     *      'key3' => 'value3
     *  ],
     *  [
     *      'key1' => 'value4,
     *      'key2' => 'value5,
     *      'key3' => 'value6
     *  ]
     * ]
     * ↓
     * key1, key2, key3
     * value1, value2, value3
     * value4, value5, value6
     */
    static public function outputArrayKeyValue( $array, $outputpath )
    {
        $outputarray = [];
        $outputheader = array_keys( current($array) );
        
        foreach ( $array as $linenum => $keyToValue ){
            $outputarray[$linenum] = array_values($keyToValue);
        }
        $handle = fopen($outputpath, "w");
        if ( $handle ) {
            fputcsv($handle, $outputheader);
            foreach($outputarray as $line){
                fputcsv($handle, $line);
            } 
        }
        fclose($handle);
    }


    static public function load( $inputpath, $isHeader=true, $length=0, $delimiter=',', $enclosure='"', $escape="\\" )
    {
        $returndatas = [];
        if (($handle = fopen($inputpath, "r")) !== false) {
            $keys = [];
            $linenum = 0;        
            while (($data = fgetcsv($handle, $length, $delimiter, $enclosure, $escape)) !== false) {
                if ( $linenum === 0 ){
                    if( $isHeader ){
                        foreach ( $data as $index => $column ){
                            $keys[$index] = $column;
                        }
                        $linenum++;
                        continue;
                    }else{
                        foreach ( $data as $index => $column ){
                            $keys[$index] = $index;
                        }
                    }
                }
                $column = [];
                foreach( $keys as $index => $key ){
                    $column[$key] = $data[$index];
                }
                $returndatas[] =  $column;
                $linenum++;
            }
            fclose($handle);
        }
        return $returndatas;
    }




}
