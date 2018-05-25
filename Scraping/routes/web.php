<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/goutte', function( $initial ) {
    $ret_a = [];
    $crawler = Goutte::request('GET', 'https://shopping.yahoo.co.jp/stores/alphabet/'.$initial.'/');
 
    $ret_a = $crawler->filter('.elItem li .elMain')->each(function ($node) {
        $url = $node->filter('a')->attr("href");
        $page = Goutte::request('GET', $url.'info.html');
        $shop = $page->filter('#CentInfoPage1 th')->each(function($th){
            $ret = [];
            if( $th->text() === 'お問い合わせメールアドレス' ){
                $email = $th->nextAll('td')->eq(0)->text();
                $ret['email'] = $email;
                return $ret;
            }
            if( $th->text() === 'ストア名' ){
                $storename = $th->nextAll('td')->eq(0)->text();
                $ret['storename'] = $storename;
                return $ret;
            }
        });
        $array = [];
        foreach( $shop as $s ){
            if( $s != null ){
                if( array_key_exists( 'storename', $s ) ){
                    $array['storename'] = $s['storename'];
                }
                if( array_key_exists( 'email', $s ) ){
                    $array['email'] = $s['email'];
                }
            }
        }
        return $array;
    });

    dump($ret_a);
    return;
});