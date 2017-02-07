<?php

namespace Zent\VarsBundle\Services;

use Zent\VarsBundle\Repository\VarsRepository;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Zent\VarsBundle\Entity\Vars;

/**
 * Class VarsService
 * @package Zent\VarsBundle\Services
 */
class VarsService
{
    private $em;
    private $vars = [];
    /**
     * @var CacheProvider
     */
    private $cache = null;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setCacheProvider(CacheProvider $cacheProvider)
    {
        $this->cache = $cacheProvider;
    }

    public function getCacheProvider()
    {
        return $this->cache;
    }

    /**
     * @return VarsRepository
     */
    public function getRepo()
    {
        return $this->em->getRepository('ZentVarsBundle:Vars');
    }

    /**
     * @param bool $force
     * @return array|bool|null
     */
    public function loadVars($force = false)
    {
        if (!$this->vars || $force === true) {
            $vars = $this->getRepo()->getVars();
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
            $var = $this->getRepo()->getVar($name);
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
     * @throws \Exception
     */
    public function setVar($name, $value)
    {
        $var = $this->getRepo()->findOneBy(['name' => $name]);
        if (!$var) {
            throw new EntityNotFoundException('Not found var: '.$name);
        }

        $var->setValue($value);
        $this->em->flush();
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $title
     */
    public function create($name, $value, $title = '')
    {
        $var = new Vars();
        $var->setName($name);
        $var->setValue($value);
        $var->setTitle($title);
        $this->em->persist($var);
        $this->em->flush($var);
    }

    public function initCacheVar(Vars $var)
    {
        if ($this->cache) {
            $this->cache->save($var->getName(), $var->getValue());
        }
    }
}
