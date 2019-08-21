<?php

namespace src;

use src\Model\Grid;
use src\Model\InstructionList;
use src\Model\Position;
use src\Model\Robot;

class ArgumentInterpreter
{
    public function interpret(string $argument): InstructionList
    {
        $argumentArray = \explode('\\n', $argument);

        $grid = $this->getGrid($argumentArray[0]);

        return new InstructionList(
            $grid,
            $this->getRobots(\array_slice($argumentArray, 1), $grid)
        );
    }

    private function getGrid(string $gridString): Grid
    {
        $gridSizeArray = \explode(' ', $gridString);
        if (count($gridSizeArray) !== 2) {
            throw new \Exception('Invalid number of grid dimensions');
        }

        if (!\ctype_digit($gridSizeArray[0])) {
            throw new \Exception('Invalid x dimension. It must be a positive integer');
        }

        if (!\ctype_digit($gridSizeArray[1])) {
            throw new \Exception('Invalid y dimension. It must be a positive integer');
        }

        // The -1s account for the fact we have a 0th row and column
        return new Grid( $gridSizeArray[0] - 1, $gridSizeArray[1] - 1);
    }

    /**
     * @return Robot[]
     */
    private function getRobots(array $robotStrings, Grid $grid): array
    {
        $robots = [];

        foreach ($robotStrings as $robotString) {
            $matches = [];

            $isValid = preg_match('/^\(([0-9]+), ([0-9]+), ([NESW])\) ([LRF]+)$/m', $robotString, $matches);
            if (!$isValid) {
                throw new \Exception(sprintf(
                    'Row "%s" is invalid. Input rows after the first must take the form of (<x start position (int)>, <y start position (int)>, <initial facing direction (N, E, S or W)>) <list of instruction (L, R or F)>',
                    $robotString
                ));
            }

            $initialX = (int) $matches[1];
            $initialY = (int) $matches[2];

            if ($initialX >= $grid->getWidth() || $initialY >= $grid->getHeight()) {
                throw new \Exception(\sprintf('Robot for row "%s" would start off grid', $robotString));
            }

            $robots[] = new Robot(
                new Position($initialX, $initialY, $matches[3]),
                \str_split($matches[4])
            );
        }

        return $robots;
    }
}
