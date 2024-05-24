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

class UseCaseMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:clean:usecase';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new use case classes';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'usecase-name',
                InputArgument::REQUIRED,
                sprintf('Choose a name for your UseCase classes (e.g. <fg=yellow>Get%s</>)', Str::asClassName(Str::getRandomTerm()))
            )
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $ucName = $input->getArgument('usecase-name');
        if (is_array($ucName)) {
            throw new RuntimeException('Invalid argument supplied');
        }

        $ns = 'Core\\Application\\UseCase\\'.$ucName;

        $useCaseInterface = $generator->createClassNameDetails($ucName, $ns, 'UseCaseInterface');
        $useCaseClass = $generator->createClassNameDetails($ucName, $ns, 'UseCase');
        $requestClass = $generator->createClassNameDetails($ucName, $ns, 'Request');
        $responseClass = $generator->createClassNameDetails($ucName, $ns, 'Response');
        $presenterInterface = $generator->createClassNameDetails($ucName, $ns, 'PresenterInterface');

        $generator->generateClass(
            $useCaseClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/UseCase.tpl.php',
            [
                'namespace' => $ns,
                'use_case_class_name' => $useCaseClass->getRelativeName(),
                'use_case_interface_name' => $useCaseInterface->getRelativeName(),
                'request_class_name' => $requestClass->getRelativeName(),
                'presenter_interface_name' => $presenterInterface->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $useCaseInterface->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/UseCaseInterface.tpl.php',
            [
                'namespace' => $ns,
                'use_case_interface_name' => $useCaseInterface->getRelativeName(),
                'request_class_name' => $requestClass->getRelativeName(),
                'presenter_interface_name' => $presenterInterface->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $presenterInterface->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/PresenterInterface.tpl.php',
            [
                'namespace' => $ns,
                'presenter_interface_name' => $presenterInterface->getRelativeName(),
                'response_class_name' => $responseClass->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $requestClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/Request.tpl.php',
            [
                'namespace' => $ns,
                'request_class_name' => $requestClass->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $responseClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/Response.tpl.php',
            [
                'namespace' => $ns,
                'response_class_name' => $responseClass->getRelativeName(),
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
        $io->text($ns.' successfully generated');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
