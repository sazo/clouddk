<?php

namespace Sazo\CloudDk\Commands;

use GuzzleHttp\Exception\ServerException;
use Sazo\CloudDk\CloudServer;
use Sazo\CloudDk\NetworkInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SecureServer extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('secure:server')
            ->setDescription('Set server up with secure settings - BETA');
        $this->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Id for server');
        $this->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'Hostname for server');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getOption('hostname') === null && $input->getOption('id') === null){
            $output->writeln('hostname or id is needed');
            return;
        }
        
        if($input->hasOption('hostname')){
            $cloudServers = CloudServer::byHostname($this->client, $input->getOption('hostname'));
            /** @var CloudServer $cloudServer */
            $cloudServer = $cloudServers->first();
            /** @var NetworkInterface $networkInterface */
            $networkInterface = $cloudServer->getNetworkInterfaces()->first();
            
            $response = $this->client->put('cloudservers/'.$cloudServer->getId().'/network-interfaces/'.$networkInterface->getId(), [
                'json' => [
                    'label' => $networkInterface->getLabel(),
                    'default_firewall_rule' => 'DROP'
                ]
            ]);
            $this->printResponse($response, $output);
            
            try{
                $response = $this->client->post('cloudservers/'.$cloudServer->getId().'/network-interfaces/'.$networkInterface->getId().'/firewall-rules', [
                    'json' => [
                        'command' => "ACCEPT",
                        'protocol' => 'TCP',
                        'address' => '217.61.0.0',
                        'bits' => 16,
                        'port' => '1:65535'
                    ]
                ]);
                $this->printResponse($response, $output);
            }catch (ServerException $exception){
                $output->writeln($exception);
            }
    
            try{
                $response = $this->client->post('cloudservers/'.$cloudServer->getId().'/network-interfaces/'.$networkInterface->getId().'/firewall-rules', [
                    'json' => [
                        'command' => "ACCEPT",
                        'protocol' => 'UDP',
                        'address' => '217.61.0.0',
                        'bits' => 16,
                        'port' => '1:65535'
                    ]
                ]);
                $this->printResponse($response, $output);
            }catch (ServerException $exception){
                $output->writeln($exception);
            }
    
            try{
                $response = $this->client->post('cloudservers/'.$cloudServer->getId().'/network-interfaces/'.$networkInterface->getId().'/firewall-rules', [
                    'json' => [
                        'command' => "ACCEPT",
                        'protocol' => 'TCP',
                        'address' => '0.0.0.0',
                        'bits' => 8,
                        'port' => '22'
                    ]
                ]);
                $this->printResponse($response, $output);
            }catch (ServerException $exception){
                $output->writeln($exception);
            }
        }
        
    }
    
}
