<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send:sns';

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
        $client = \AWS::createClient('sns', [
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => 'AKIAIXBL4QTYSHWWJLYA',
                'secret' => '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'
            ]
        ]);
        // SNS -> アプリケーション -> ARN
        // $result = $client->getPlatformApplicationAttributes([
        //     'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:423243451001:app/APNS_SANDBOX/4komagram_dev'
        // ]);
        // var_dump($result);


        // // アプリケーションに紐付けられている端末リスト
        // $result = $client->ListEndpointsByPlatformApplication([
        //     'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:423243451001:app/APNS_SANDBOX/4komagram_dev'
        // ]);
        // var_dump($result);

        // トークンが重複したらエラーが出る。 resultのEndpointArnを保存しておく必要がある。
        // $result = $client->createPlatformEndpoint([
        //     'CustomUserData' => '2',
        //     'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:423243451001:app/APNS_SANDBOX/4komagram_dev',
        //     'Token' => 'f7541303c6e944376e00da06730eb482abec342f2308c7aafd65035ccce109b0',
        // ]);
        // var_dump($result);

        // $message = 'test';
        // $param =json_encode(array(
        //     'APNS_SANDBOX' => json_encode(array(
        //         'aps' => array(
        //             'alert' => $message,
        //             'badge' => 1,
        //             'url' => "https://4komagram.com/post/4462/"
        //         )
        //     ))
        // ));

        // $result = $client->publish([
        //     'MessageStructure' => 'json',
        //     'Message' => $param,
        //     'MessageAttributes' => [],
        //     'TargetArn' => "arn:aws:sns:us-east-1:423243451001:endpoint/APNS_SANDBOX/4komagram_dev/a36a3e94-72e5-3b6a-9964-b54c3e3362b1",
            
        // ]);
        // var_dump($result);
        $message = '4コマgramに動画コーナーが登場!!
要チェック!!😊';


        $param = json_encode(array(
            "default" => $message,            
            'APNS_SANDBOX' => json_encode(array(
                'aps' => array(
                    'alert' => $message,
                    'badge' => 1,
                    'url' => "https://4komagram.com/video/"
                )
            ))
        ));

        $res = $client->publish([
            'MessageStructure' => 'json',
            'Message' => $param,
            'MessageAttributes' => [],
            'TargetArn' => "arn:aws:sns:us-east-1:423243451001:topic1",
        ]);
        var_dump($res);

    }
}
