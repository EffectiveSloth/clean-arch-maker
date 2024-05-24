<?php declare(strict_types=1);
echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

interface <?php echo $use_case_interface_name; ?> <?php echo "\n"; ?>
{
    public function execute(<?php echo $request_class_name; ?> $request, <?php echo $presenter_interface_name; ?> $presenter): void;
}
