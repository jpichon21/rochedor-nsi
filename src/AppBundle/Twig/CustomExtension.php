<?php

namespace AppBundle\Twig;

class CustomExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('background', array($this, 'backgroundFilter')),
            new \Twig_SimpleFilter('constantBackground', array($this, 'constantBackgroundFilter')),
            new \Twig_SimpleFilter('md5', array($this, 'md5Filter')),
        );
    }

    public function backgroundFilter($url)
    {
        return "background-image: url('$url')";
    }

    public function constantBackgroundFilter($id)
    {
        $url = constant('AppBundle\\Entity\\Page::BACKGROUNDS')[$id];
        return "background-image: url('$url')";
    }

    public function md5Filter($string)
    {
        return md5($string);
    }

    public function getName()
    {
        return 'custom_extension';
    }
}
