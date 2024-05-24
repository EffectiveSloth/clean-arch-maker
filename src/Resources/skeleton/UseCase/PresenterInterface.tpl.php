<?php declare(strict_types=1);
echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

interface <?php echo $presenter_interface_name; ?> <?php echo "\n"; ?>
{
    public function present(<?php echo $response_class_name; ?> $response): void;
}
