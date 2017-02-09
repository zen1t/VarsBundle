<?php

namespace Zent\VarsBundle\Model;

interface VarsInterface
{

    /**  Get id */
    public function getId();

    /**
     * Set name
     * @param string $name
     * @return Vars
     */
    public function setName($name);

    /**
     * Get name
     * @return string
     */
    public function getName();

    /**
     * Set value
     * @param string $value
     * @return Vars
     */
    public function setValue($value);

    /**
     * Get value
     * @return string
     */
    public function getValue();

    /**
     * Set title
     * @param string $title
     * @return Vars
     */
    public function setTitle($title);

    /**
     * Get title
     * @return string
     */
    public function getTitle();
}
