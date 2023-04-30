# trait-adapter, [Packagist](https://packagist.org/packages/falbar/trait-adapter)

## Install

To install package, you need run command:

```bash
composer require falbar/trait-adapter
```

## Usage

To create Adapter class, it is necessary to connect trait at the beginning `AdapterTrait`. Next, define the property `protected array $arMappingList`, where are the field correspondences denoted (it can be `= [];`).

> set<PropertyName>Attribute() метод должен быть `protected` и возвращать `void`

## Examples object initialization

```php
$oExampleAdapter = ExampleAdapter::make();
```

```php
$oExampleAdapter = new ExampleAdapter();
```

## Examples

1. Wrapper Adapter object:

```php
<?php namespace App\Classes;

use Falbar\TraitAdapter\AdapterTrait;

/**
 * Class ExampleAdapter
 * @package App\Classes
 */
class ExampleAdapter
{
    use AdapterTrait;

    protected array $arMappingList = [
        'id',
        'name',
    ];

    /* @var int */
    public $id;

    /* @var string */
    public $name;
}
```

Converting data to Adapter object:

```php
$oExampleAdapter = ExampleAdapter::make()
    ->create([
        'id'   => 10,
        'name' => 'string',
    ]);
```

```text
App\Classes\ExampleAdapter {
  id: 10
  name: "string"
}
```

2. Converting object properties:

```php
<?php namespace App\Classes;

use Falbar\TraitAdapter\AdapterTrait;

/**
 * Class ExampleAdapter
 * @package App\Classes
 */
class ExampleAdapter
{
    use AdapterTrait;

    protected array $arMappingList = [
        'id',
        'name',
    ];

    /* @var int */
    public $id;

    /* @var string */
    public $name;

    /* @return void */
    protected function setNameAttribute(): void
    {
        $this->name = $this->name . '_new_value';
    }
}
```

Converting data to Adapter object:

```php
$oExampleAdapter = ExampleAdapter::make()
    ->create([
        'id'   => 10,
        'name' => 'string',
    ]);
```

```text
App\Classes\ExampleAdapter {
  id: 10
  name: "string_new_value"
}
```

3. Mapping object data:

```php
<?php namespace App\Classes;

use Falbar\TraitAdapter\AdapterTrait;

/**
 * Class ExampleAdapter
 * @package App\Classes
 */
class ExampleAdapter
{
    use AdapterTrait;

    protected array $arMappingList = [
        'id'   => 'external_id',
        'name' => 'external_name',
    ];

    /* @var int */
    public $id;

    /* @var string */
    public $name;
}
```

Converting data to Adapter object:

```php
$oExampleAdapter = ExampleAdapter::make()
    ->create([
        'external_id'   => 13,
        'external_name' => 'external',
    ]);
```

```text
App\Classes\ExampleAdapter {
  id: 13
  name: "external"
}
```

4. Creating collection:

```php
<?php namespace App\Classes;

use Falbar\TraitAdapter\AdapterTrait;

/**
 * Class ExampleAdapter
 * @package App\Classes
 */
class ExampleAdapter
{
    use AdapterTrait;

    protected array $arMappingList = [
        'id',
        'name',
    ];

    /* @var int */
    public $id;

    /* @var string */
    public $name;
}
```

Converting data to the Adapter collection:

```php
$oExampleAdapter = ExampleAdapter::make()
    ->createCollection([
        ['id' => 1, 'name' => 'string_1'],
        ['id' => 2, 'name' => 'string_2'],
    ]);
```

```text
App\Classes\ExampleAdapter {
  +"arCollection": array:2 [
    0 => App\Classes\ExampleAdapter {
      +id: 1
      +name: "string_1"
    }
    1 => App\Classes\ExampleAdapter {
      +id: 2
      +name: "string_2"
    }
  ]
}
```

## Methods

#### Initialization methods

* `make()` - initializing object;
* `mapping(array $arMappingList = [])` - defining array of matches;
* `setCustom(array $arCustomData = [])` - transferring custom data to the Adapter class;
* `create(array $arData = [])` - create object Adapter;
* `createCollection(array $arDataList = [], int $iChunk = 500)` - create collection Adapter;
* `toArray()` - convert to an array.

#### Inside the Adapter class

* `getOrigin(?string $sKey = null)` - get original value in the Adapter class;
* `getCustom(?string $sKey = null)` - get a custom value in the Adapter class;
* `getCustomByItemIndex(?string $sKey = null)` - get a custom value by index in the Adapter class.
