<?php

namespace Zent\VarsBundle\EventListener;


class EntityVarsListener
{
    /**
     * @var ContainerInterface
     */

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }

    public function postUpdate(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!($entity instanceof Brand)) {
            return;
        }

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($entity);
        if (!isset($changeset['rulesVersion'])) {
            return;
        }

        $oldVersion = $changeset['rulesVersion'][0];
        $newVersion = $changeset['rulesVersion'][1];

        if ($newVersion - $oldVersion !== 1) {
            $this->container->get('session')->getFlashBag()->add(
                'error', 'Не верно указана версия правил: ожидается '.($oldVersion + 1)
            );

            return;
        }

        $this->container->get('delivery.service')->updateRulesViber($entity);
        $this->container->get('session')->getFlashBag()
            ->add('success', 'Поставлена задача на рассылку о обновлении версии правил');
    }

}