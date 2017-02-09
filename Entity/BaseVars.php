<?php

namespace Zent\VarsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Zent\VarsBundle\Model\Vars;

/**
 * Class BaseVars
 * @package Zent\VarsBundle\Entity
 */
abstract class BaseVars extends Vars
{
    use Timestampable;
}
