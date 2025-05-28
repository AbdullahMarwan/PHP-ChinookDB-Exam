<?php
// filepath: Utils/Logger.php

class Logger {
    public static function log($message) {
        $logFile = __DIR__ . '/../logs/api.log';
        $date = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$date] $message\n", FILE_APPEND);
    }

    public static function logRequest($method, $uri, $params, $status) {
        $msg = "$method $uri | Params: " . json_encode($params) . " | Status: $status";
        self::log($msg);
    }
}