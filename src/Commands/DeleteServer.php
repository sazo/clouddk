<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 7/7/18
 * Time: 6:46 PM
 */


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
        $this->addArgument('id', InputArgument::REQUIRED, 'Id for the server');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->client->delete('cloudservers/'.$input->getArgument('id'));
        $output->writeln($response->getBody()->getContents());
    }
    
}
