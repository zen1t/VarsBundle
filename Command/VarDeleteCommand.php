<?php

namespace Zent\VarsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class VarDeleteCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('zent:var:delete')
            ->setDescription('Удалить переменную')
            ->addArgument('name', InputArgument::REQUIRED, 'Название переменной');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $this->getContainer()->get('zent.vars_manager')->delete($name);
        $output->writeln('Delete success');
    }
}
