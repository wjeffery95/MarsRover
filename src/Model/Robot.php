<?php

declare(strict_types=1);

namespace src\Model;

use src\Enum\Instruction;

class Robot
{
    /**
     * @var Position
     */
    private $initialPosition;

    /**
     * @var String[]
     */
    private $instructions;

    /**
     * @param String[] $instructions
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
     * @return String[]
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }
}
