# Exercise for payroll of the ACME company

## About the solution

for this solution it is a hexagonal architecture pattern. divide the dependencies respecting the communication layers.

The central solution is in the following recursive function that evaluates inversely according to the hourly pay rule.
```injectablephp
private function sumRecursive(float $toPay, array $hourly, int $hoursWorked, $initTime):float
    {
        foreach ($hourly as $betweenHour => $salary) {
            $betweenData = explode('-', $betweenHour);
            if ($initTime>=$betweenData[0]) {
                unset($hourly[$betweenHour]);
                $diffHour = $toPay>0?($betweenData[1]-$initTime)+1:($betweenData[1]-$initTime);
                return (($diffHour - $hoursWorked) > 0)
                    ? $toPay + $this->calculateSalary($salary, $hoursWorked)
                    : $this->sumRecursive(
                        $toPay + $this->calculateSalary($salary, $diffHour)
                        , $hourly, ($hoursWorked-$diffHour), ($initTime+$diffHour)+1);
            }
        }
        return $toPay;
    }
```
It is also evaluated in minutes, so the value per minute done in an hour is calculated and distributed to the time worked according to the time ranges performed.
when the need for recursion is detected, only the missing missing schedules are evaluated.

## Deploy


The repository has a fast deployment thanks to docker

```shell
docker-compose up -d
```

### in the container console called php"testioet_php"
if for some reason when instantiating the container the vendors are not installed automatically, write the following line in the php console

```shell
composer install
```

to execute the solution you can write the following line in the console
```shell
php coutput.php
```
these are the records loaded by default in the file **/var/www/html/input.txt**
```markdown
RENE=MO10:00-12:00,TU10:00-12:00,TH01:00-03:00,SA14:00-18:00,SU20:00-21:00
ASTRID=MO10:00-12:00,TH12:00-14:00,SU20:00-21:00
IRVING=SU10:00-12:00,SA12:00-14:00
ZULAY=SU08:00-12:00,MO08:30:00-17:30
```
You can modify this file to generate inputs and test more cases.
### tests
to run the tests write the following line:
```shell
vendor/bin/phpunit
```
