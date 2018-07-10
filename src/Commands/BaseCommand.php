<?php

namespace Sazo\CloudDk\Commands;

use DI\Container;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    /**
     * @var Container
     */
    protected $container;
    
    /**
     * @var Client
     */
    protected $client;
    
    public function __construct($name = null, Container $container)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->client = $this->container->get('client');
    }
    
    protected function printResponse(ResponseInterface $response, OutputInterface $output){
        $output->writeln(json_encode(json_decode($response->getBody()->getContents()), JSON_PRETTY_PRINT));
    }
}
