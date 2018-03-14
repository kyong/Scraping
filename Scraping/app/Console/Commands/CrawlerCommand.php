<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crawler';

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
        $this->getInitialStoreEmailList();
 
        return;
    }
    private function getInitialStoreEmailList( )
    {
        $crawler = \Goutte::request('GET', 'https://shopping.yahoo.co.jp/stores/');

        $crawler->filter('.elLetter a')->each(function ($a) {
            $url = $a->attr('href');
            dump($url);
            
            preg_match('/https:\/\/shopping\.yahoo\.co\.jp\/stores\/(.*?)\/(?P<initial>.*?)\//', $url, $mathes);
            $ret_a = $this->getStoreEmailList( $url );
            $filename = "./storage/{$mathes['initial']}.csv";
            dump($filename);
            $f = fopen($filename, "w");
            if ( $f ) {
                $keys = array_keys(reset($ret_a));
                fputcsv($f, $keys);
                foreach($ret_a as $line){
                    fputcsv($f, array_values($line));
                } 
            }
            fclose($f);
        });
    }

    private function getStoreEmailList( $url, $shop_a=[] )
    {
        $crawler = \Goutte::request('GET', $url);
        $ret_a = $crawler->filter('.elItem li .elMain')->each(function ($node) {
            $url = $node->filter('a')->attr("href");
            dump($url);
            $page = \Goutte::request('GET', $url.'info.html');
            $shop = $page->filter('#CentInfoPage1 th')->each(function($th){
                $ret = [];
                if( trim($th->text()) === '会社名（商号）' ){
                    $ret['会社名（商号）'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'ストア名' ){
                    $ret['ストア名'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'ストア名（カタカナ）' ){
                    $ret['ストア名（カタカナ）'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === '関連ストア' ){
                    $ret['関連ストア'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'お問い合わせ窓口' ){
                    $ret['お問い合わせ窓口'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'お問い合わせ電話番号' ){
                    $ret['お問い合わせ電話番号'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'お問い合わせファックス番号' ){
                    $ret['お問い合わせファックス番号'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'お問い合わせメールアドレス' ){
                    $ret['お問い合わせメールアドレス'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }
                if( trim($th->text()) === 'ストア営業日/時間' ){
                    $ret['ストア営業日/時間'] = $th->nextAll('td')->eq(0)->text();
                    return $ret;
                }

            });
            return $this->toArray( $shop );
        });
        $shop_a = array_merge( $shop_a, $ret_a );
        $next = $crawler->filter('.elNext a');
        if(count($next)>0){
            return $this->getStoreEmailList( $next->attr('href'), $shop_a );
        }else{
            return $this->creanArray( $shop_a );
        }
    }

    private function toArray( $shop )
    {
        $array = [];
        if($shop){

            $array = [
                '会社名（商号）' => null,
                'ストア名' => null,
                'ストア名（カタカナ）' => null,
                '関連ストア' => null,
                'お問い合わせ窓口' => null,
                'お問い合わせ電話番号' => null,
                'お問い合わせファックス番号' => null,
                'お問い合わせメールアドレス' => null,
                'ストア営業日' => null,
            ];
        }
        foreach( $shop as $s ){
            if( $s != null ){

                foreach( $array as $k => $v ){
                    if( array_key_exists( $k, $s ) ){
                        $array[$k] = $s[$k];
                    }
                }
            }
        }
        return $array;
    }
    private function creanArray( $shop_a )
    {
        $array = [];
        foreach( $shop_a as $s ){
            if( is_array( $s ) && count( $s ) > 0 ){
                $array[] = $s;
            }
        }
        return $array;
    }
}
