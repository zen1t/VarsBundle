<?php

namespace Zent\VarsBundle\Command;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class VarUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zent:var:update')
            ->setDescription('Изменить переменную')
            ->addArgument('name', InputArgument::REQUIRED, 'Название переменной')
            ->addArgument('value', InputArgument::REQUIRED, 'Значение переменной');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');
        $this->getContainer()->get('zent.vars_manager')->update($name, $value);
        $output->writeln('Update success');
    }

}