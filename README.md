# effectivesloth/clean-arch-maker

effectivesloth/clean-arch-maker is a Symfony bundle designed to facilitate the creation of [clean architecture](https://www.youtube.com/watch?v=LTxJFQ6xmzM) classes and interfaces.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install effectivesloth/clean-arch-maker.

```bash
composer require --dev effectivesloth/clean-arch-maker
```

## Usage

Generate a new use case by running:

```bash
bin/console make:clean:usecase DoStuff
```

This command will create the following files:

```bash
 created: src/Core/Application/UseCase/DoStuff/DoStuffUseCase.php
 created: src/Core/Application/UseCase/DoStuff/DoStuffUseCaseInterface.php
 created: src/Core/Application/UseCase/DoStuff/DoStuffPresenterInterface.php
 created: src/Core/Application/UseCase/DoStuff/DoStuffRequest.php
 created: src/Core/Application/UseCase/DoStuff/DoStuffResponse.php
         
  Success! 
           
 Core\Application\UseCase\DoStuff successfully generated
```
