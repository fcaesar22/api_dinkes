<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Libadapter {
    function execurl($toURL, $post) {
        if(stristr($toURL, 'olap')==true){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $toURL=$toURL.'&ipclient='.$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $toURL,
            CURLOPT_HEADER => 0,
            CURLOPT_VERBOSE => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => "Mozilla/4.0 (compatible;)",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post),
        );
        curl_setopt_array($ch, $options);
        $result = array('data' => curl_exec($ch), 'info' => curl_getinfo($ch));
        curl_close($ch);
        return $result;
    }

    function httpGet($url)
    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output =  curl_error($ch);
        $output .= curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
