<?php

class ChatController extends BaseController {

    public function Connect(){
        $output = '';
        $fp = fsockopen("tsumi.dyndns.org", 6666, $errno, $errstr, 15);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "login|";
            $out .= "KJW283NFW9R7H43RT3WH4";
            fwrite($fp, $out);
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }
            fclose($fp);
        }

        return $output;
    }

    public function Poll(){
        $output = 'null';
        $fp = fsockopen("tsumi.dyndns.org", 6666, $errno, $errstr, 15);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "poll|";
            $out .= "KJW283NFW9R7H43RT3WH4";
            fwrite($fp, $out);
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }
            fclose($fp);
        }

        return $output;
    }

    public function Send(){
        $output = 'null';
        $fp = fsockopen("tsumi.dyndns.org", 6666, $errno, $errstr, 15);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "message|";
            $out .= Input::get('message');
            //$out .= Input::get('channel');
            fwrite($fp, $out);
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }
            fclose($fp);
        }

        return $output;
    }
}