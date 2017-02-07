<?php

namespace Zent\VarsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VarListCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('zent:var:list')
            ->setDescription('Выдать списк внутренних переменных');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vars = $this->getContainer()->get('zent.vars')->getVars();

        $table = new Table($output);
        $table->setHeaders(['name', 'value']);

        foreach ($vars as $name => $value) {
            $table->addRow([$name, $value]);
        }
        $table->render();
    }

}