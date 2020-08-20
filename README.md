Requires php 7.4.0

To run go to the root directory and run 
```
php MarsRover.php <argument>
```
The first line on the argument must specify the size of the grid and should be of the form:
```
<width> <height>
```
eg.
```
10 10
```
Subsequent lines of the argument must take the form: 
```
(<x start position (int)>, <y start position (int)>, <initial facing direction (N, E, S or W)>) <list of instruction (L, R or F)> 
``` 
eg.
```
(5, 5, N) LFRFRFL
```

The command to run to run several rovers might look like this:
```
php MarsRover.php "10 10\n(5, 5, N) LFRFRFL\n(4, 2, W) FFRRF"

```

To run tests go to the home directory and call 
```
 ./vendor/bin/phpunit test
```
