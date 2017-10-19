<?php
/**
 * User: Nikolay Tuzov
 * Date: 18.10.17
 */

require_once '../src/GeneticAlgorithm.php';

use PHPUnit\Framework\TestCase;

class GeneticAlgorithmTest extends TestCase
{
//    public function testGenerate()
//    {
//        $ga = new GeneticAlgorithm();
//        $length = 10;
//        $randStr = $ga->generate($length);
//        $this->assertEquals($length, strlen($randStr));
//    }
//
//    public function testSelect()
//    {
//        $population = ['1111', '0000'];
//        $fitness = [0.5, 0.5];
////        $fitness = function ($ch) {
////            return 1;
////        };
//
//        $ga = new GeneticAlgorithm();
//        $selected = $ga->select(count($population), $fitness);
//
//        $this->assertCount(2, $selected);
//    }
//
//    public function testMutate()
//    {
//        $ga = new GeneticAlgorithm();
//
//        $chromosome = '1010010';
//        $p = 1;
//        $newChromosome = $ga->mutate($chromosome, $p);
//        $this->assertEquals('0101101', $newChromosome);
//
//        $chromosome = '0111010';
//        $p = 0;
//        $newChromosome = $ga->mutate($chromosome, $p);
//        $this->assertEquals('0111010', $newChromosome);
//    }
//
//    public function testCrossover()
//    {
//        $ga = new GeneticAlgorithm();
//
//        $ch1 = '1110000';
//        $ch2 = '0001111';
//
//        $newCh = $ga->crossover($ch1, $ch2);
//        $this->assertEquals(strlen($ch1), strlen($newCh[0]));
//
//    }

    public function testRun()
    {
        //01100110010100110010110010111101100
        $ga = new GeneticAlgorithm();

        $fitness = function ($ch) {
            return 1;
        };
        $length = 4;
        $p_c = 0.6;
        $p_m = 0.002;
        $iterations = 100;

        $result = $ga->run($fitness, $length, $p_c, $p_m, $iterations);

        $this->assertEquals($length, strlen($result));

    }
}
