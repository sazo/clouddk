<?php

namespace Sazo\CloudDk\Commands;

use Sazo\CloudDk\CloudServer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RootPassword extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('cloudserver:root-pass')
            ->setDescription('Set root password');
        $this->addArgument('hostname', InputArgument::REQUIRED, 'hostname for server');
        $this->addArgument('password', InputArgument::REQUIRED, 'New password');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cloudServers = CloudServer::byHostname($this->client, $input->getArgument('hostname'));
        /** @var CloudServer $cloudServer */
        $cloudServer = $cloudServers->first();
        $response = $this->client->post('cloudservers/'.$cloudServer->getId().'/change-root-password', [
            'json' => [
                'newRootPassword' => $input->getArgument('password')
            ]
        ]);
        $this->printResponse($response, $output);
    }
    
}
