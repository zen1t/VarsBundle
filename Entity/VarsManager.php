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
    private $em;

    /** @var array */
    private $vars = [];

    /** @var CacheProvider */
    private $cache = null;

    /** @var EntityRepository */
    private $repository;

    /** @var string */
    private $class;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository($class);

        $metadata = $entityManager->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @param CacheProvider $cacheProvider
     */
    public function setCacheProvider(CacheProvider $cacheProvider)
    {
        $this->cache = $cacheProvider;
    }

    /**
     * @return CacheProvider
     */
    public function getCacheProvider()
    {
        return $this->cache;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
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
    public function getVar($name, $default = '')
    {
        if ($this->cache and $this->cache->contains($name)) {
            return $this->cache->fetch($name);
        }

        if (!$this->vars) {
            $var = $this->get($name);
            $value = $var ? $var['value'] : $default;
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
     * @return null|object
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
