<?php

namespace Zent\VarsBundle\Twig\Extension;

use Zent\VarsBundle\Entity\VarsManager;

class VarsExtension extends \Twig_Extension
{
    /** @var VarsManager */
    public $vars;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('vars', function ($name) {
                return $this->vars->getValue($name);
            }),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'zent_vars';
    }
}
