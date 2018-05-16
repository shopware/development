<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    
    Shopware\Administration\Administration::class => ['all' => true],
    Shopware\Storefront\Storefront::class => ['all' => true],

    Shopware\Framework\Framework::class => ['all' => true],
    Shopware\System\System::class => ['all' => true],
    Shopware\Content\Content::class => ['all' => true],
    Shopware\Checkout\Checkout::class => ['all' => true],
    Shopware\Application\Application::class => ['all' => true],

    Shopware\Profiling\Profiling::class => ['dev' => true],
];
