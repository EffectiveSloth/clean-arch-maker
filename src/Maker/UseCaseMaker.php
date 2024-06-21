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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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

    private function askGeneratePresenter(ConsoleStyle $io): ?string
    {
        $question = new ConfirmationQuestion('Do you want to generate a presenter?', false);
        /** @var bool $generatePresenter */
        $generatePresenter = $io->askQuestion($question);

        if (!$generatePresenter) {
            return null;
        }

        $question = new ChoiceQuestion('Select a presenter type', ['serialize']);
        /** @var string $presenterType */
        $presenterType = $io->askQuestion($question);

        return $presenterType;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $ucName = $input->getArgument('usecase-name');
        if (is_array($ucName)) {
            throw new RuntimeException('Invalid argument supplied');
        }

        $ucNs = 'Core\\Application\\UseCase\\'.$ucName;
        $presenterNs = 'UserInterface\\Presentation\\'.$ucName.'\\Serialize';

        $useCaseInterface = $generator->createClassNameDetails($ucName, $ucNs, 'UseCaseInterface');
        $useCaseClass = $generator->createClassNameDetails($ucName, $ucNs, 'UseCase');
        $requestClass = $generator->createClassNameDetails($ucName, $ucNs, 'Request');
        $responseClass = $generator->createClassNameDetails($ucName, $ucNs, 'Response');
        $presenterInterface = $generator->createClassNameDetails($ucName, $ucNs, 'PresenterInterface');

        $generator->generateClass(
            $useCaseClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/UseCase.tpl.php',
            [
                'namespace' => $ucNs,
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
                'namespace' => $ucNs,
                'use_case_interface_name' => $useCaseInterface->getRelativeName(),
                'request_class_name' => $requestClass->getRelativeName(),
                'presenter_interface_name' => $presenterInterface->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $presenterInterface->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/PresenterInterface.tpl.php',
            [
                'namespace' => $ucNs,
                'presenter_interface_name' => $presenterInterface->getRelativeName(),
                'response_class_name' => $responseClass->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $requestClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/Request.tpl.php',
            [
                'namespace' => $ucNs,
                'request_class_name' => $requestClass->getRelativeName(),
            ]
        );

        $generator->generateClass(
            $responseClass->getFullName(),
            __DIR__.'/../Resources/skeleton/UseCase/Response.tpl.php',
            [
                'namespace' => $ucNs,
                'response_class_name' => $responseClass->getRelativeName(),
            ]
        );

        switch ($this->askGeneratePresenter($io)) {
            case 'serialize':
                $serializePresenter = $generator->createClassNameDetails($ucName, $presenterNs, 'SerializePresenter');
                $serializeView = $generator->createClassNameDetails($ucName, $presenterNs, 'SerializeView');
                $viewModel = $generator->createClassNameDetails($ucName, $presenterNs, 'ViewModel');

                $generator->generateClass(
                    className: $serializePresenter->getFullName(),
                    templateName: __DIR__.'/../Resources/skeleton/UseCase/SerializePresenter.tpl.php',
                    variables: [
                        'namespace' => $presenterNs,
                        'presenter_interface_full_path' => $presenterInterface->getFullName(),
                        'usecase_response_full_path' => $responseClass->getFullName(),
                        'serialize_presenter_class_name' => $serializePresenter->getShortName(),
                        'presenter_interface_name' => $presenterInterface->getShortName(),
                        'view_model_class_name' => $viewModel->getShortName(),
                        'usecase_response_class_name' => $responseClass->getShortName(),
                    ]
                );

                $generator->generateClass(
                    className: $serializeView->getFullName(),
                    templateName: __DIR__.'/../Resources/skeleton/UseCase/SerializeView.tpl.php',
                    variables: [
                        'namespace' => $presenterNs,
                        'serialize_view_class_name' => $serializeView->getShortName(),
                        'view_model_class_name' => $viewModel->getShortName(),
                    ]
                );

                $generator->generateClass(
                    className: $viewModel->getFullName(),
                    templateName: __DIR__.'/../Resources/skeleton/UseCase/ViewModel.tpl.php',
                    variables: [
                        'namespace' => $presenterNs,
                        'view_model_class_name' => $viewModel->getShortName(),
                    ]
                );
                break;
        }

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
        $io->text($ucNs.' successfully generated');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
