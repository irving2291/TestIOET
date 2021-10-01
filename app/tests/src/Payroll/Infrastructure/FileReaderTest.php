<?php

namespace Irving2291\Ioettest\Test\Payroll\Infrastructure;

use Irving2291\Ioettest\Payroll\Infrastructure\Persistence\File\FileReader;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    protected string $nameFile;

    protected function setUp(): void
    {
        $this->nameFile = 'input_test.txt';
    }

    /** @test */
    public function it_should_get_an_array()
    {
        $newFile = fopen($this->nameFile, "w");
        fclose($newFile);
        $input = FileReader::nextLine($this->nameFile);
        $this->assertIsArray($input);
        $this->assertEmpty($input);
        $this->assertNotNull($input);

    }

    /** @test */
    public function it_should_set_and_return_array_with_data()
    {
        $newFile = fopen($this->nameFile, "w+");
        $fh = "RENE=MO10:00-12:00,TU10:00-12:00,TH01:00-03:00,SA14:00-18:00,SU20:00-21:00\n";
        fwrite($newFile, $fh);
        fclose($newFile);

        $input = FileReader::nextLine($this->nameFile);
        $this->assertNotEmpty($input);
}

    /** @test  */
    public function remove_file_created_for_testing()
    {
        $this->assertTrue(unlink($this->nameFile), "$this->nameFile has been deleted");
    }
}