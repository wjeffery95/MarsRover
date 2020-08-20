<?php

declare(strict_types=1);

namespace src\Model;

class Position
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var string
     */
    private $facing;

    /**
     * @var bool
     */
    private $isLost;

    public function __construct(int $x, int $y, string $facing, bool $isLost = false)
    {
        $this->x = $x;
        $this->y = $y;
        $this->facing = $facing;
        $this->isLost = $isLost;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getFacing(): string
    {
        return $this->facing;
    }

    public function getIsLost(): bool
    {
        return $this->isLost;
    }
}
