<?php

namespace Sazo\CloudDk\Commands\Apps\Rancher;

use Sazo\CloudDk\CloudServer;
use Sazo\CloudDk\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class SetupWorker extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('rancher:setup-worker')
            ->setDescription('Adds a worker to a rancher cluster');
        $this->addArgument('hostname', InputArgument::REQUIRED, 'Hostname for the cloud machine');
        $this->addOption('skip-docker-install', null,InputOption::VALUE_NONE, 'Skip docker install');
        $this->addArgument('checksum', InputArgument::REQUIRED, 'Checksum when joining rancher cluster');
        $this->addArgument('token', InputArgument::REQUIRED, 'Token for joining rancher cluster');
        $this->addArgument('server', InputArgument::REQUIRED, 'Rancher server URL fx https://rancher.clouddk.dk');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CloudServer $cloudServer */
        $cloudServer = CloudServer::byHostname($this->client, $input->getArgument('hostname'))->first();
        $this->installDocker($cloudServer, $input);
        $this->installRancherAgent($cloudServer, $input);
    }
    
    /**
     * @param $cloudServer
     */
    protected function installDocker(CloudServer $cloudServer, InputInterface $input) : void
    {
        if(!$input->getOption('skip-docker-install')){
            $process = new Process([
                'ssh',
                '-o StrictHostKeyChecking=no',
                'root@' . $cloudServer->getIp(),
                'curl https://releases.rancher.com/install-docker/17.03.sh | sh'
            ]);
            $this->runAndEcho($process);
        }
    }
    
    /**
     * @param $cloudServer
     */
    protected function installRancherAgent(CloudServer $cloudServer, InputInterface $input) : void
    {
        $process = new Process([
            'ssh',
            '-o StrictHostKeyChecking=no',
            'root@' . $cloudServer->getIp(),
            'docker run -d --privileged --restart=unless-stopped --net=host -v /etc/kubernetes:/etc/kubernetes -v /var/run:/var/run rancher/rancher-agent:v2.0.2 --server '.$input->getArgument('server').' --token '.$input->getArgument('token').' --ca-checksum '.$input->getArgument('checksum').' --worker'
        ]);
        $this->runAndEcho($process);
    }
    
    /**
     * @param $process
     */
    protected function runAndEcho(Process $process) : void
    {
        $process->start();
        foreach ($process as $type => $data) {
            echo $data;
        }
    }
    
}
