<?php

namespace Zent\VarsBundle\Command;

use Doctrine\ORM\EntityNotFoundException;
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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $var = $em->getRepository('ZentVarsBundle:Vars')
            ->findOneBy(['name' => $input->getArgument('name')]);

        if (!$var) {
            throw new EntityNotFoundException();
        }

        $em->remove($var);
        $em->flush();
        $output->writeln('Delete var: '.$var->getName());
    }
}