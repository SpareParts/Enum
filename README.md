# Enum
PHP Enum done right

[![Build Status](https://travis-ci.org/SpareParts/Enum.svg?branch=master)](https://travis-ci.org/SpareParts/Enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SpareParts/Enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SpareParts/Enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SpareParts/Enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SpareParts/Enum/?branch=master)

Easy way to use enumerated values in PHP.

## Installation

Best way to install is to use [composer](https://getcomposer.org/download/)
```bash
composer require spareparts/enum
```

## Basic usage

````php
/**
 * @method static WindowStateEnum OPEN()
 * @method static WindowStateEnum CLOSED()
 */
class WindowStateEnum extends \SpareParts\Enum\Enum
{
}

// obtain enum value
$state = WindowStateEnum::OPEN();

// assign and compare enum values
if ($window->state === WindowStateEnum::OPEN()) {
    // close the doors if opened
    $window->state = WindowStateEnum::CLOSED();
}

// use enum to guard method parameters
function changeWindowState(WindowStateEnum $newState) {
    ...
}

````
### How to prepare Enum

1. extend Enum class
2. add @method annotations to let both the Enum class and your IDE know which enum values are allowed 
````php
/**
 * @method static WindowStateEnum OPEN()
 * @method static WindowStateEnum CLOSED()
 */
class WindowStateEnum extends \SpareParts\Enum\Enum 
{
}
````

### How to use Enum
There are two possible ways to use enum values, with first one being preferred.

1. using static methods with same name as your desired value.  

This works with help from magic __callStatic method, meaning you do not have to add any methods - it works immediately after setting up ``$values``.
````php
$state = WindowStateEnum::OPEN();
````
This method is preferred, as it nicely shows its intended value without having to use weakly guarded strings. 

2. using ``instance()`` method with desired value as instance parameter
````php
$state = WindowStateEnum::instance('OPEN');
````
There is nothing inherently wrong with this approach, but mistakes/typos can be easily made.  

#### Asking enum for unsupported value
In case you ask for value that is not in the enum values, an InvalidEnumValueException exception is thrown. 
````php
try {
    $window->setState(WindowStateEnum::FLYING());
} catch (\SpareParts\Enum\Exception\InvalidEnumValueException $e) {
    echo "This is not a correct state for window to be in!";
}
````
