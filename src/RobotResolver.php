<?php

declare(strict_types=1);

namespace src;

use src\Enum\Facing;
use src\Enum\Instruction;
use src\Model\Grid;
use src\Model\Position;
use src\Model\Robot;

class RobotResolver
{
    private const CLOCKWISE_DIRECTIONS = [
        0 => Facing::NORTH,
        1 => Facing::EAST,
        2 => Facing::SOUTH,
        3 => Facing::WEST,
    ];

    public function resolve(Robot $robot, Grid $grid): string
    {
        $position = $robot->getInitialPosition();

        foreach ($robot->getInstructions() as $instruction) {
            $position = $this->getNewPosition($position, $instruction, $grid);

            if ($position->getIsLost()) {
                break;
            }
        }

        return $this->formatPosition($position);
    }

    private function getNewPosition(Position $position, string $instruction, Grid $grid): Position
    {
        switch ($instruction) {
            case Instruction::FORWARD:
                return $this->getForwardPosition($position, $grid);
            case Instruction::LEFT:
                return $this->getLeftPosition($position);
            case Instruction::RIGHT:
                return $this->getRightPosition($position);
        }

        throw new \Exception(\sprintf('Invalid instruction "%s"', $instruction));
    }

    private function getForwardPosition(Position $position, Grid $grid): Position
    {
        if ($this->isFacingEdgeOfGrid($position, $grid)) {
            return new Position($position->getX(), $position->getY(), $position->getFacing(), true);
        }

        switch ($position->getFacing()) {
            case Facing::NORTH:
                return new Position($position->getX(), $position->getY() + 1, $position->getFacing());
            case Facing::EAST:
                return new Position($position->getX() + 1, $position->getY(), $position->getFacing());
            case Facing::SOUTH:
                return new Position($position->getX(), $position->getY() - 1, $position->getFacing());
            case Facing::WEST:
                return new Position($position->getX() - 1, $position->getY(), $position->getFacing());
        }

        throw $this->getInvalidFacingException($position->getFacing());
    }

    private function isFacingEdgeOfGrid(Position $position, Grid $grid): bool
    {
        switch ($position->getFacing()) {
            case Facing::NORTH:
                return $position->getY() === $grid->getHeight();
            case Facing::EAST:
                return $position->getX() === $grid->getWidth();
            case Facing::SOUTH:
                return $position->getY() === 0;
            case Facing::WEST:
                return $position->getX() === 0;
        }

        throw $this->getInvalidFacingException($position->getFacing());
    }

    private function getLeftPosition(Position $position): Position
    {
        $facingNumber = $this->getDirectionAsNumber($position->getFacing());
        $newFacing = self::CLOCKWISE_DIRECTIONS[($facingNumber + 3)  % 4];

        return new Position($position->getX(), $position->getY(), $newFacing);
    }

    private function getRightPosition(Position $position): Position
    {
        $facingNumber = $this->getDirectionAsNumber($position->getFacing());
        $newFacing = self::CLOCKWISE_DIRECTIONS[($facingNumber + 1) % 4];

        return new Position($position->getX(), $position->getY(), $newFacing);
    }

    private function getDirectionAsNumber(string $facing) {
        return \array_search($facing, self::CLOCKWISE_DIRECTIONS, true);
    }

    private function getInvalidFacingException(string $facing): \Exception
    {
        return new \Exception(\sprintf('Invalid facing "%s"', $facing));
    }

    private function formatPosition(Position $position): string
    {
        return \sprintf(
            '(%d, %d, %s)%s',
            $position->getX(),
            $position->getY(),
            $position->getFacing(),
            $position->getIsLost() ? ' LOST' : ''
        );
    }
}
