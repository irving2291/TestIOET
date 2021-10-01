<?php
$rootPath = __DIR__;
require $rootPath . '/vendor/autoload.php';

//$input = fopen('input.txt', 'r');
//var_dump($input);
//fclose($input);

//$input = file_get_contents('input.txt');
//$explode = explode(" ", $input);
//var_dump($explode);

//$archivo = fopen("input.txt", "r");
//fpassthru($archivo);


// Abriendo el archivo

// name
$Finder = new \Irving2291\Ioettest\Payroll\Application\EmployeeFinder(
    new \Irving2291\Ioettest\Payroll\Infrastructure\Persistence\FileRepository()
);
echo "<pre>";
foreach ($Finder->all() as $employee) {
    echo $employee . '<br>';
}
echo "</pre>";





