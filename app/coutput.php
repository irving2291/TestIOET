<?php
$rootPath = __DIR__;
require $rootPath . '/index.php';

#solved!
$Finder = new \Irving2291\Ioettest\Payroll\Application\EmployeeFinder(
new \Irving2291\Ioettest\Payroll\Infrastructure\Persistence\FileRepository()
);
echo "<pre>";
foreach ($Finder->all() as $employee) {
    echo $employee . PHP_EOL;
}
echo "</pre>";