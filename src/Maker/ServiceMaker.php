<?php

declare(strict_types=1);

namespace EffectiveSloth\CleanArchMakerBundle\Maker;

use RuntimeException;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

use function is_array;

class ServiceMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:clean:service';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new service class & interface';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'service-name',
                InputArgument::REQUIRED,
                sprintf('Choose a name for your Service classes (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm()))
            )
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $serviceName = $input->getArgument('service-name');
        if (is_array($serviceName)) {
            throw new RuntimeException('Invalid argument supplied');
        }
        $nsPort = 'Core\\Application\\Port\\Service';
        $nsInfra = 'Infrastructure\\Service';

        $gwInterface = $generator->createClassNameDetails($serviceName, $nsPort, 'ServiceInterface');
        $gwClass = $generator->createClassNameDetails($serviceName, $nsInfra, 'Service');

        $generator->generateClass(
            $gwInterface->getFullName(),
            __DIR__.'/../Resources/skeleton/Service/ServiceInterface.tpl.php',
            [
                'namespace' => $nsPort,
                'service_interface_name' => $gwInterface->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $gwClass->getFullName(),
            __DIR__.'/../Resources/skeleton/Service/Service.tpl.php',
            [
                'namespace' => $nsInfra,
                'service_interface_use' => $gwInterface->getFullName(),
                'service_interface' => $gwInterface->getRelativeName(),
                'service_class_name' => $gwClass->getRelativeName(),
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
        $io->text($serviceName.' successfully generated');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
