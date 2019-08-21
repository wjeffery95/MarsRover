<?php

namespace src\Model;

use src\Enum\Instruction;

class Robot
{
    /**
     * @var Position
     */
    private $initialPosition;

    /**
     * @var Instruction[]
     */
    private $instructions;

    /**
     * @param Instruction[] $instructions
     */
    public function __construct(Position $initialPosition, array $instructions)
    {
        $this->initialPosition = $initialPosition;
        $this->instructions = $instructions;
    }

    public function getInitialPosition(): Position
    {
        return $this->initialPosition;
    }

    /**
     * @return Instruction[]
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }
}
