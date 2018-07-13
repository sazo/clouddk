<?php

namespace Sazo\CloudDk\Commands\Secure;

use Sazo\CloudDk\CloudServer;
use Sazo\CloudDk\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SshKeyCopy extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('secure:ssh-copy')
            ->setDescription('Copy your SSH key to the server');
        $this->addArgument('hostname', InputArgument::REQUIRED, 'hostname for the cloud machine');
        $this->addArgument('password', InputArgument::REQUIRED, 'password for the host');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CloudServer $cloudServer */
        $cloudServer = CloudServer::byHostname($this->client, $input->getArgument('hostname'))->first();
        $process = new Process('sshpass -p \''.$input->getArgument('password').'\' ssh-copy-id -o StrictHostKeyChecking=no root@'.$cloudServer->getIp());
        $process->run();
    
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        $output->write($process->getOutput());
    }
    
}
