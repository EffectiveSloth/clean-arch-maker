<?php

declare(strict_types=1);

namespace EffectiveSloth\CleanArchMakerBundle\DependencyInjection;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EffectiveSlothCleanArchMakerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        try {
            $loader->load('services.xml');
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('%s file not found', 'services.xml'));
        }
    }

    public function getXsdValidationBasePath(): string
    {
        return __DIR__.'/../Resources/config/';
    }
}
