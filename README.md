# Random Password Generator
Simple PHP class to generate random passwords
[![Build Status][rlvendramini-image]][rlvendramini-url]


## How does it work?

You can simple load the class and call the generate method to get your random password.
If you want more options, you can customize your password's length, characters's types and add some extra characters too.

## Usage

### Install

```bash
$ composer require rlvendramini/randompassword
```

### Setup

Require the autoload composer's generated class

```php
require __DIR__ . '/vendor/autoload.php';
```

And then, create your object

```php
$var = new randomPassword();
```

### Configuration

If you just want a simple 12 length random uppercase characters, lowercase characters, symbols and numbers password, create a randompassword object and call generate method.
If you want to set your own options, you can call the configuration methods.

#### Set password length
Choose the new password's length
```php
$object->setPasswordLength( int );
```

#### Set characters types
Choose your prefered characters types from the default options: 
'lower' : Lowercase chars,
'upper' : Uppercase chars,
'num' : Numbers,
'sym' : Symbols

```php
$object->setPasswordCharacters( array('lower', 'upper') );
```

If you don't call this method, all types are selected by default

#### Set extra characters
You can set an array with your custom chars you want to be considered in password generating

```php
$object->setExtraCharacters( array('prefered characters', 'can add how many you want', 'seriously!') );
```
If you set this extra characters, they will automatically considered in generating proccess

#### Unset extra characters
You can destroy the extra characters to go back default

```php
$object->unsetExtraCharacters();
```

### Run!

#### Generate!
```php
$password = $object->generate();
```

#### Chain methods
You can call config methods in chain, and then generate, like this
```php
$password = $object->setPasswordLength( int )->setPasswordCharacters( array )->generate();
```

[rlvendramini-url]: http://rlvendramini.com.br
[rlvendramini-image]: http://rlvendramini.com.br/img/logo.svg

