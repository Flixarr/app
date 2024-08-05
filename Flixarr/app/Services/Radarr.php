<?php

namespace App\Services;

class Radarr
{
    public string $protocol;
    public string $host;
    public int $port;
    public string $key;

    public function __construct($connection = [])
    {
        if (!empty($connection)) {
            // Use custom connection (mainly for setup)
            $this->protocol = $connection['protocol'];
            $this->host = $connection['host'];
            $this->port = $connection['port'];
            $this->key = $connection['key'];
        } else {
            // Grab default Radarr server
        }
    }
}
