<?php

namespace Zent\VarsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class VarCreateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('zent:var:create')
            ->setDescription('Создать переменную')
            ->addArgument('name', InputArgument::REQUIRED, 'Название переменной')
            ->addArgument('value', InputArgument::REQUIRED, 'Значение переменной')
            ->addArgument('info', InputArgument::OPTIONAL, 'Описание');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');
        $info = $input->getArgument('info') ?: '';
        $this->getContainer()->get('zent.vars')->create($name, $value, $info);
        $output->writeln('Create var: '.$name);
    }

}