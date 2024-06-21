<?php declare(strict_types=1);?>
<?= "<?php" . PHP_EOL ?>
declare(strict_types=1);

namespace <?= $namespace ?>;

class <?= $serialize_view_class_name ?>
{
    public function generateView(<?= $view_model_class_name ?> $viewModel): string
    {
        // todo : implement view generation using $viewModel
        return '';
    }
}
