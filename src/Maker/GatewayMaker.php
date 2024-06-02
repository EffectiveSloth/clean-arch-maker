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

class GatewayMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:clean:gateway';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new gateway class & interface';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'gateway-name',
                InputArgument::REQUIRED,
                sprintf('Choose a name for your Gateway classes (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm()))
            )
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $gwName = $input->getArgument('gateway-name');
        if (is_array($gwName)) {
            throw new RuntimeException('Invalid argument supplied');
        }
        $nsPort = 'Core\\Application\\Port\\Gateway';
        $nsInfra = 'Infrastructure\\Gateway';

        $gwInterface = $generator->createClassNameDetails($gwName, $nsPort, 'GatewayInterface');
        $gwClass = $generator->createClassNameDetails($gwName, $nsInfra, 'Gateway');

        $generator->generateClass(
            $gwInterface->getFullName(),
            __DIR__.'/../Resources/skeleton/Gateway/GatewayInterface.tpl.php',
            [
                'namespace' => $nsPort,
                'gateway_interface_name' => $gwInterface->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $gwClass->getFullName(),
            __DIR__.'/../Resources/skeleton/Gateway/Gateway.tpl.php',
            [
                'namespace' => $nsInfra,
                'gateway_interface_use' => $gwInterface->getFullName(),
                'gateway_interface' => $gwInterface->getRelativeName(),
                'gateway_class_name' => $gwClass->getRelativeName(),
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
        $io->text($gwName.' successfully generated');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
