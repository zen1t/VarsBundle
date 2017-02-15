<?php

namespace Zent\VarsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Zent\VarsBundle\Entity\VarsManager;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class VarsAdmin extends Admin
{
    /** @var VarsManager */
    private $varsManager;

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('value')
            ->add('title')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                ),
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if (!$this->id($this->getSubject())) {
            $formMapper->add('name');
        }

        $formMapper
            ->add('value')
            ->add('title','text')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('show');
        $collection->remove('delete');
    }


    public function setVarsManager(VarsManager $varsManager)
    {
        $this->varsManager = $varsManager;
    }

    /**
     * @return VarsManager
     */
    public function getVarsManager()
    {
        return $this->varsManager;
    }

    public function postUpdate($vars)
    {
        $this->getVarsManager()->initCacheVar($vars);
    }
}
