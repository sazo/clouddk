<?php

namespace Sazo\CloudDk\Commands;

use Sazo\CloudDk\CloudServer;
use Sazo\CloudDk\NetworkInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListServer extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('cloudserver:list')
            ->setDescription('List all cloud servers');
        $this->addOption('print-json', null, InputOption::VALUE_NONE, 'Print json response');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->client->get('cloudservers');
        $body = json_decode($response->getBody()->getContents(), true);
        
        if($input->getOption('print-json')){
            $output->writeln(json_encode($body, JSON_PRETTY_PRINT));
        }else{
            $cloudServers = CloudServer::createCloudServers($body);
            foreach ($cloudServers AS $host){
                $hostData = [];
                $hostData[] = $host->getId();
                $hostData[] = $host->getHostname();
                $hostData[] = $host->getNetworkInterfaces()->map(function(NetworkInterface $item){
                    return $item->getAddress();
                })->__toString();
                $data[] = $hostData;
            }
            $table = new Table($output);
            $table
                ->setHeaders(['Id','Host', 'Ips'])
                ->setRows($data)
            ;
            $table->render();
        }
        
        
    }
    
}
