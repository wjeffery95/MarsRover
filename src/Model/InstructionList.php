<?php

declare(strict_types=1);

namespace src\Model;

class InstructionList
{
    /**
     * @var Grid
     */
    private $grid;

    /**
     * @var Robot[]
     */
    private $robots;

    public function __construct(Grid $grid, array $robots)
    {
        $this->grid = $grid;
        $this->robots = $robots;
    }

    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * @return Robot[]
     */
    public function getRobots(): array
    {
        return $this->robots;
    }
}
