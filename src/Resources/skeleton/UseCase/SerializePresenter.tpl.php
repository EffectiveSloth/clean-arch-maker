<?php declare(strict_types=1);?>
<?= "<?php" . PHP_EOL ?>
declare(strict_types=1);

namespace <?= $namespace ?>;

use <?= $presenter_interface_full_path ?>;
use <?= $usecase_response_full_path ?>;

class <?= $serialize_presenter_class_name ?> implements <?= $presenter_interface_name ?>
{
    private <?= $view_model_class_name ?> $viewModel;

    public function __construct(
        ) {
            $this->viewModel = new <?= $view_model_class_name ?>();
        }

    public function present(<?= $usecase_response_class_name ?> $response): void
    {
        // fill $this-viewModel here
    }

    public function getViewModel(): <?= $view_model_class_name ?>
    {
        return $this->viewModel;
    }
}
