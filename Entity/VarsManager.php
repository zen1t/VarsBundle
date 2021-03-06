<?php

namespace Zent\VarsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Zent\VarsBundle\Model\VarsInterface;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class VarsManager
 * @package Zent\VarsBundle\Entity
 */
class VarsManager
{
    /** @var EntityManager */
    protected $em;

    /** @var array */
    protected $vars = [];

    /** @var CacheProvider */
    protected $cache;

    /** @var EntityRepository */
    protected $repository;

    /** @var string */
    protected $class;

    /**
     * @param EntityManager $entityManager
     * @param string        $class
     * @param CacheProvider|null          $cache
     */
    public function __construct(EntityManager $entityManager, $class, $cache = null)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository($class);
        $this->cache = $cache;

        $metadata = $entityManager->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return EntityRepository
     */
    public function getRepo()
    {
        return $this->repository;
    }

    /**
     * @param bool $force
     * @return array|bool|null
     */
    public function loadVars($force = false)
    {
        if (!$this->vars || $force === true) {
            $vars = $this->repository->createQueryBuilder('v')
                ->select('v.name', 'v.value')
                ->getQuery()->getArrayResult();
            foreach ($vars as $var) {
                $this->vars[$var['name']] = $var['value'];
            }
        }

        if ($this->cache) {
            $this->cache->saveMultiple($this->vars);
        }

        return $this->vars;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->loadVars();
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getValue($name, $default = '')
    {
        if ($this->cache && $this->cache->contains($name)) {
            return $this->cache->fetch($name);
        }

        if (!$this->vars) {
            $var = $this->get($name);
            $value = $var ? $var->getValue() : $default;
        } else {
            $value = isset($this->vars[$name]) ? $this->vars[$name] : $default;
        }

        if ($this->cache) {
            $this->cache->save($name, $value);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $title
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    public function create($name, $value, $title = '')
    {
        $class = $this->getClass();
        /** @var VarsInterface $var */
        $var = new $class;
        $var->setName($name);
        $var->setValue($value);
        $var->setTitle($title);
        $this->em->persist($var);
        $this->em->flush($var);
    }

    public function initCacheVar(VarsInterface $var)
    {
        if ($this->cache) {
            $this->cache->save($var->getName(), $var->getValue());
        }
    }

    public function update($name, $value)
    {
        $var = $this->get($name);
        if (!$var) {
            throw new EntityNotFoundException('Not found var: '.$name);
        }

        $var->setValue($value);
        $this->em->flush($var);

        if ($this->cache) {
            $this->cache->save($name, $value);
        }
    }

    /**
     * @param $name
     * @return null|VarsInterface
     */
    public function get($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function delete($name)
    {
        $var = $this->get($name);
        if (!$var) {
            throw new EntityNotFoundException('Not found var: '.$name);
        }

        $this->em->remove($var);
        $this->em->flush($var);

        if ($this->cache) {
            $this->cache->delete($name);
        }
    }
}
