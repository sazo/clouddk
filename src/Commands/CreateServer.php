<?php

namespace Sazo\CloudDk\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateServer extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('cloudserver:create')
            ->setDescription('Create a cloud server');
        
        $this->addArgument('hostname', InputArgument::REQUIRED, 'hostname for the server');
        $this->addOption('label', null,InputOption::VALUE_OPTIONAL, 'label for the server. Default is same as hostname', null);
        $this->addOption('root-password', null,InputOption::VALUE_OPTIONAL, 'Root password', $this->generateRandomString(20));
        $this->addOption('package', null, InputOption::VALUE_OPTIONAL, 'Server type', '3bfdb4628b8989');
        $this->addOption('template', null, InputOption::VALUE_OPTIONAL, 'Template to spawn with', 'centos-7.5');
        $this->addOption('location', null, InputOption::VALUE_OPTIONAL, 'Location', 'dk1');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $body = [
            'hostname' => $input->getArgument('hostname'),
            'label' => $input->getOption('label') ?? $input->getArgument('hostname'),
            'initialRootPassword' => $input->getOption('root-password'),
            'package' => $input->getOption('package'),
            'template' => $input->getOption('template'),
            'location' => $input->getOption('location')
        ];
        $response = $this->client->post('cloudservers', [
            'json' => $body
        ]);
        $output->writeln(json_encode(json_decode($response->getBody()->getContents()), JSON_PRETTY_PRINT));
    }
    
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
