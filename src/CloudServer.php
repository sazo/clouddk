<?php

namespace Sazo\CloudDk;

use GuzzleHttp\Client;
use Tightenco\Collect\Support\Collection;

class CloudServer extends Collection
{
    
    public function getId() : string{
        return $this->get('identifier');
    }
    
    public function getHostname() : string{
        return $this->get('hostname');
    }
    
    /**
     * @param array $cloudServers
     *
     * @return CloudServer[]
     */
    public static function createCloudServers(array $cloudServers) : Collection{
        $cloudServersObjs = new Collection();
        foreach ($cloudServers AS $cloudServer){
            $cloudServersObjs[] = new static($cloudServer);
        }
        return $cloudServersObjs;
    }
    
    /**
     * @return NetworkInterface[]
     */
    public function getNetworkInterfaces() : Collection{
        return NetworkInterface::createNetworkInterfaces($this->get('networkInterfaces'));
    }
    
    public static function byHostname(Client $client, string $hostname) : Collection{
        $response = $client->get('cloudservers?hostname='.$hostname);
        $body = json_decode($response->getBody()->getContents(), true);
        return CloudServer::createCloudServers($body);
    }
    
}
