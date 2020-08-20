#!/usr/bin/env  php

<?php

    require __DIR__ . '/vendor/autoload.php';

    use src\ArgumentInterpreter;
    use src\RobotResolver;

    // count($argc) should be 2 as the first argument is this file and the second is the expected input
    if ($argc !== 2) {
        echo '"MarsRover.php" only accepts 1 argument';
    }

    $argumentInterpreter = new ArgumentInterpreter();

    try {
        $instructions = $argumentInterpreter->interpret($argv[1]);
    } catch (\Exception $e) {
        echo $e->getMessage();
        echo PHP_EOL;
        exit;
    }

    $grid = $instructions->getGrid();

    $robotResolver = new RobotResolver();
    foreach ($instructions->getRobots() as $robot) {
        echo $robotResolver->resolve($robot, $grid);
        echo PHP_EOL;
    }
