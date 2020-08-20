<?php

declare(strict_types=1);

namespace test;

use PHPUnit\Framework\TestCase;
use src\ArgumentInterpreter;
use src\Enum\Facing;
use src\Enum\Instruction;
use src\Model\Grid;
use src\Model\InstructionList;
use src\Model\Position;
use src\Model\Robot;

final class ArgumentInterpreterTest extends TestCase
{
    /**
     * @dataProvider provideTestInterpret
     */
    public function testInterpret(string $argument, ?\Exception $expectedException, ?InstructionList $expectedResult): void
    {
        $argumentInterpreter = new ArgumentInterpreter();

        if ($expectedException) {
            $this->expectExceptionObject($expectedException);
        }

        $result = $argumentInterpreter->interpret($argument);

        if ($expectedResult) {
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function provideTestInterpret(): array
    {
        return [
            'Should throw an exception when two many grid dimensions are specified' => [
                'argument' => '10 20 30\n(5, 5, N) FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Invalid number of grid dimensions'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when two few grid dimensions are specified' => [
                'argument' => '30\n(5, 5, N) FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Invalid number of grid dimensions'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when grid dimensions are not integers' => [
                'argument' => 'ten ten\n(5, 5, N) FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Invalid x dimension. It must be a positive integer'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when some grid dimensions are not integers' => [
                'argument' => '10 10.5\n(5, 5, N) FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Invalid y dimension. It must be a positive integer'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when rover arguments are not formatted correctly' => [
                'argument' => '10 10\n5 5 N FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Row "5 5 N FLFR" is invalid. Input rows after the first must take the form of (<x start position (int)>, <y start position (int)>, <initial facing direction (N, E, S or W)>) <list of instruction (L, R or F)>'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when rover arguments have invalid directions' => [
                'argument' => '10 10\n(5, 5, U) FLFR',
                'expectedException' => new \InvalidArgumentException(
                    'Row "(5, 5, U) FLFR" is invalid. Input rows after the first must take the form of (<x start position (int)>, <y start position (int)>, <initial facing direction (N, E, S or W)>) <list of instruction (L, R or F)>'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when rover has invalid instruction' => [
                'argument' => '10 10\n(5, 5, N) UDLR',
                'expectedException' => new \InvalidArgumentException(
                    'Row "(5, 5, N) UDLR" is invalid. Input rows after the first must take the form of (<x start position (int)>, <y start position (int)>, <initial facing direction (N, E, S or W)>) <list of instruction (L, R or F)>'
                ),
                'expectedResult' => null,
            ],
            'Should throw an exception when rover start position is off the grid' => [
                'argument' => '10 10\n(12, 12, N) FLR',
                'expectedException' => new \InvalidArgumentException(
                    'Robot for row "(12, 12, N) FLR" would start off grid'
                ),
                'expectedResult' => null,
            ],
            'Should create an instruction list with only one robot when one robot is given' => [
                'argument' => '10 10\n(5, 5, N) FLR',
                'expectedException' => null,
                'expectedResult' => new InstructionList(
                    new Grid(9, 9),
                    [
                        new Robot(
                            new Position(5, 5, Facing::NORTH),
                            [Instruction::FORWARD, Instruction::LEFT, Instruction::RIGHT]
                        ),
                    ]
                ),
            ],
            'Should create an instruction list with multiple robots when one multiple robots are given' => [
                'argument' => '10 10\n(5, 5, N) FLR\n(7, 4, E) LFFR',
                'expectedException' => null,
                'expectedResult' => new InstructionList(
                    new Grid(9, 9),
                    [
                        new Robot(
                            new Position(5, 5, Facing::NORTH),
                            [Instruction::FORWARD, Instruction::LEFT, Instruction::RIGHT]
                        ),
                        new Robot(
                            new Position(7, 4, Facing::EAST),
                            [Instruction::LEFT, Instruction::FORWARD, Instruction::FORWARD, Instruction::RIGHT]
                        ),
                    ]
                ),
            ],
        ];
    }
}
