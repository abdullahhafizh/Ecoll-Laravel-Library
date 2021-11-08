<?php

namespace Ecoll\Common;

use Ecoll\Common\BniEnc;
use Ecoll\Common\Config;

class PaycodeGenerator
{
    public static function post($config, $params)
    {
        $client_id = $config['client_id'];
        $secret_key = $config['shared_key'];
        $url = Config::getBaseUrl($config['environment']);

        $data_asli = $params;

        $hashed_string = BniEnc::encrypt(
            $data_asli,
            $client_id,
            $secret_key
        );

        $data = array(
            'client_id' => $client_id,
            'data' => $hashed_string,
        );

        $response = self::get_content($url, json_encode($data));
        $response_json = json_decode($response);

        $log = PHP_EOL . 'HIT BNI ECOLL: '. $url;
        $log .= PHP_EOL . 'REQUEST: '. json_encode($data, JSON_PRETTY_PRINT);
        $log .= PHP_EOL . 'RESPONSE: '. json_encode($response_json, JSON_PRETTY_PRINT);
        \Log::info($log);

        if ($response_json->status !== '000') return $response_json;

        $data_response = BniEnc::decrypt($response_json->data, $client_id, $secret_key);
        \Log::info('DECRYPTED RESPONSE: ' . json_encode($data_response, JSON_PRETTY_PRINT));
        return $data_response;
    }

    private static function get_content($url, $post = '') {
        $header[] = 'Content-Type: application/json';
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $rs = curl_exec($ch);

        if(empty($rs)){
            var_dump($rs, curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $rs;
    }
}
