<?php declare(strict_types=1);?>
<?= "<?php" . PHP_EOL ?>
declare(strict_types=1);

namespace <?= $namespace ?>;

class <?= $view_model_class_name ?>
{

    // TODO : add proper data to view model
    public function __construct(
        public string $response = '',
        public int $httpCode = 200,
        public ?string $errorMessage = null,
    ) {
    }
}
