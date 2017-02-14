<?php

namespace Zent\VarsBundle\Model;

interface VarsInterface
{

    /**  Get id */
    public function getId();

    /**
     * Set name
     * @param string $name
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get title
     * @return string
     */
    public function getTitle();
}
