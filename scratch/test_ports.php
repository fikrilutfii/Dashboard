<?php
foreach ([3306, 3307] as $port) {
    foreach (['127.0.0.1', 'localhost'] as $host) {
        echo "Probing $host:$port ... ";
        $fp = @fsockopen($host, $port, $errno, $errstr, 2);
        if ($fp) {
            echo "OPEN\n";
            fclose($fp);
        } else {
            echo "CLOSED ($errstr)\n";
        }
    }
}
