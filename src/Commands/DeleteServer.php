<?php
namespace Sazo\CloudDk\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteServer extends BaseCommand
{
    
    protected function configure()
    {
        $this->setName('cloudserver:delete')
            ->setDescription('Delete a server');
        $this->addArgument('id', InputArgument::IS_ARRAY, 'Id for the server');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($input->getArgument('id') as $id){
            $response = $this->client->delete('cloudservers/'.$id);
            $output->writeln($response->getBody()->getContents(), OutputInterface::VERBOSITY_DEBUG);
            $output->writeln('OK, server deleted');
        }
    }
    
}
