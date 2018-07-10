<?php
namespace Sazo\CloudDk;

use Tightenco\Collect\Support\Collection;

class NetworkInterface
{
    
    /**
     * @var array
     */
    private $network;
    
    public function __construct(Collection $network){
        $this->network = $network;
    }
    
    public function getId() : string{
        return $this->network->get('identifier');
    }
    
    public function getAddress() : string
    {
        return (new Collection($this->network->get('ipAddresses')))->implode('address', ',');
    }
    
    public function getLabel() : string
    {
        return $this->network->get('label');
    }
    
    /**
     * @param array $networks
     *
     * @return Collection
     */
    public static function createNetworkInterfaces(array $networks) : Collection{
        $networkObjs = new Collection();
        foreach ($networks AS $network){
            $networkObjs[] = new static(new Collection($network));
        }
        return $networkObjs;
    }
}
