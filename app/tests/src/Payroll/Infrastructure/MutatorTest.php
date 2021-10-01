<?php

namespace Irving2291\Ioettest\Test\Payroll\Infrastructure;

use Irving2291\Ioettest\Payroll\Domain\Employee;
use Irving2291\Ioettest\Payroll\Domain\Payment;
use Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File\Mutator;
use PHPUnit\Framework\TestCase;

class MutatorTest extends TestCase
{
    /** @test */
    public function it_is_evaluated_to_return_the_proposed_entities_and_the_result()
    {
        $nameFile = 'test_input.txt';
        $newFile = fopen($nameFile, "w+");
        $fh = "RENE=MO10:00-12:00,TU10:00-12:00,TH01:00-03:00,SA14:00-18:00,SU20:00-21:00\n";
        fwrite($newFile, $fh);
        fclose($newFile);

        $mutator = new Mutator();
        $data = $mutator->getData($nameFile);
        $this->assertInstanceOf(Employee::class, $data[0]);
        $this->assertInstanceOf(Payment::class, $data[0]->getPayment());
        $this->assertIsFloat($data[0]->getPayment()->getRemuneration()->value());
        $this->assertEquals(
            "The amount to pay RENE is 215.00 USD",
            $data[0]->__toString()
        );
        unlink($nameFile);
    }
}