# Random Password Generator

Simple PHP static class to generate random passwords


## How does it work?

You can simple load the class and call the generate method to get your random password.
If you want more options, you can customize your password's length and characters's types.

## Usage

### Install

```bash
$ composer require rlvendramini/random-password
```

### Setup

It's very simple:

Require the autoload composer's generated class...

```php
require __DIR__ . '/vendor/autoload.php';
```

...and then generate a password!

```php
$password = RandomPassword::generate();
```

#### Set password length
Choose the new password's length giving it as the `generate` function's first parameter
```php
$password = RandomPassword::generate(30);
```
If you dont't, default length is **20**.

#### Set characters types
Choose your prefered characters types from the following options:
'lowercase' : Lowercase characters,
'uppercase' : Uppercase characters,
'numbers' : Numbers,
'special' : Special characters

and then give an array of options as `generate` function's second parameter

```php
$object->setPasswordCharacters(20, ['lowercase', 'uppercase']);
```

If you don't, all types are selected by default
