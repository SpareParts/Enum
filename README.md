# Enum
PHP Enum done right

[![Build Status](https://travis-ci.org/SpareParts/Enum.svg?branch=master)](https://travis-ci.org/SpareParts/Enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SpareParts/Enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SpareParts/Enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SpareParts/Enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SpareParts/Enum/?branch=master)

Easy way to use enumerated values in PHP.

## Installation

Please, use composer.
```bash
composer require spareparts/enum
```

## Basic usage

````php
/**
 * @method static OPEN
 * @method static CLOSED
 */
class WindowStateEnum extends \SpareParts\Enum\Enum
{
    protected static $values = [
        'OPEN',
        'CLOSED',
    ];
}

// obtain enum value
$state = WindowStateEnum::OPEN();

// assign enum value
$windows->state = WindowStateEnum::CLOSED();

// compare enum values
if ($window->state === WindowStateEnum::OPEN()) {
    ....
}

// use enum to guard method parameters
function changeWindowState(WindowStateEnum $newState) {
    ...
}

````
### How to prepare Enum

1. extend Enum class
2. set ``protected static $values``  to array containing list of allowed values
3. (optional) Annotate enum class with @method annotations to help IDE autocomplete and hint correct enum values, see below

### How to use Enum
There are two possible ways to use enum values, with first one being preferred.

1. using static methods with same name as your desired value.  

This works with help from magic __callStatic method, meaning you do not have to add any methods - it works immediately after setting up ``$values``.
````php
$state = WindowStateEnum::OPEN();
````
This method is preferred, as it nicely shows its intended value without having to use weakly guarded strings. 

**Important tip**: To have values correctly autocompleted, use @method annotations, like this:
````php
/**
 * @method static OPEN
 * @method static CLOSED
 */
class WindowStateEnum extends \SpareParts\Enum\Enum {
    protected static $values = [
        'OPEN',
        'CLOSED',
    ];
}
````
This way, your IDE should know ``WindowStateEnum`` has 2 methods OPEN and CLOSE and correctly hint on their names. In case you are using IDE without support for @method annotations, you can always just add those methods "for real" :)

2. using ``instance()`` method with desired value as instance parameter
````php
$state = WindowStateEnum::instance('OPEN');
````
There is nothing wrong with this approach, but mistakes/typos can be easily made.  

#### Asking enum for unsupported value
In case you ask for value that is not in the enum values, an InvalidEnumValueException exception is thrown. 
````php
try {
    $window->setState(WindowStateEnum::FLYING());
} catch (InvalidEnumValueException $e) {
    echo "This is not a correct state for window to be in!";
}
````
