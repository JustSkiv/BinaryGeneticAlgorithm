<?php
/**
 * User: Nikolay Tuzov
 * Date: 18.10.17
 */

class GeneticAlgorithm
{
    const POPULATION = 300;

    /**
     * Generate random binary string
     *
     * @param $length
     * @return string
     */
    protected function generate($length)
    {
        $characters = '01';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Select 2 different random numbers by Roulette method
     * @param $fitnessArr
     * @return array
     */
    protected function select($fitnessArr)
    {
        $n1 = $n2 = self::getRandomNumRoulette($fitnessArr);
        while ($n1 == $n2) {
            $n2 = self::getRandomNumRoulette($fitnessArr);
        }

        return [$n1, $n2];
    }

    /**
     * Mutate chromosome
     *
     * @param $chromosome
     * @param $p
     * @return string
     */
    protected function mutate($chromosome, $p)
    {
        $newChromosome = '';

        for ($i = 0; $i < strlen($chromosome); $i++) {
            $roll = mt_rand() / mt_getrandmax();

            if ($roll > $p) {
                $newBit = $chromosome[$i];
            } else {
                $newBit = self::invertBit($chromosome[$i]);
            }

            $newChromosome .= $newBit;
        }

        return $newChromosome;
    }

    /**
     * crossover 2 chromosomes
     *
     * @param $chromosome1
     * @param $chromosome2
     * @return array
     * @throws Exception
     */
    protected function crossover($chromosome1, $chromosome2)
    {
        if (strlen($chromosome1) != strlen($chromosome2)) {
            throw new Exception("Chromosomes should be the same length");
        }

        $bitNum = rand(1, strlen($chromosome1) - 2);

        $partA1 = substr($chromosome1, 0, $bitNum);
        $partA2 = substr($chromosome2, $bitNum, strlen($chromosome2) - $bitNum);
        $newChromosome1 = $partA1 . $partA2;

        $partB1 = substr($chromosome2, 0, $bitNum);
        $partB2 = substr($chromosome1, $bitNum, strlen($chromosome1) - $bitNum);
        $newChromosome2 = $partB1 . $partB2;

        return [$newChromosome1, $newChromosome2];
    }

    /**
     * Start computing
     *
     * @param $fitness
     * @param $length
     * @param $p_c
     * @param $p_m
     * @param int $iterations
     * @return mixed
     */
    public function run($fitness, $length, $p_c, $p_m, $iterations = 200)
    {
        $population = [];
        $fitnessArr = [];

        // generate population
        for ($i = 0; $i < self::POPULATION; $i++) {
            $chromosome = $this->generate($length);
            $population[] = $chromosome;
            $fitnessArr[] = $fitness($chromosome);
        }

        for ($i = 0; $i < $iterations; $i++) {
            $newPopulation = [];
            $newFitnessArr = [];

            while (count($newPopulation) < count($population)) {
                list($n1, $n2) = $this->select($fitnessArr);
                $ch1 = $population[$n1];
                $ch2 = $population[$n2];

                $roll = mt_rand() / mt_getrandmax();

                if ($roll < $p_c) {
                    list($ch1, $ch2) = $this->crossover($ch1, $ch2);
                }

                $ch1 = $this->mutate($ch1, $p_m);
                $ch2 = $this->mutate($ch2, $p_m);

                $newPopulation[] = $ch1;
                $newPopulation[] = $ch2;
                $newFitnessArr[] = $fitness($ch1);
                $newFitnessArr[] = $fitness($ch2);
            }

            $population = $newPopulation;
            $fitnessArr = $newFitnessArr;
        }

        return self::getFittest($population, $fitness);
    }

    private static function getFittest($population, $fitness)
    {
        $fittest = $population[0];

        foreach ($population as $ch) {
            if ($fitness($ch) > $fitness($fittest)) {
                $fittest = $ch;
            }
        }

        return $fittest;
    }

    private static function invertBit($bit)
    {
        return $bit == '1' ? '0' : '1';
    }

    private static function getRandomNumRoulette($fitnessArr)
    {
        $summ = array_sum($fitnessArr);

        $value = mt_rand() / mt_getrandmax() * $summ;

        for ($i = 0; $i < $fitnessArr; $i++) {
            $value -= $fitnessArr[$i];
            if ($value <= 0) return $i;
        }

        return count($fitnessArr) - 1;
    }
}