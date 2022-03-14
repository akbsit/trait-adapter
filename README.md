# trait-adapter, [Packagist](https://packagist.org/packages/falbar/trait-adapter)

## Установка

Для установки пакета нужно:

```bash
composer require falbar/trait-adapter
```

## Подключение

Для создания класса Adapter, необходимо в начале подключить трейт `AdapterTrait`. Далее определить
свойство `protected array $arMappingList`, где обозначаются соответствия полей (оно может быть `= [];`).

> set<PropertyName>Attribute() метод должен быть `protected` и возвращать `void`

## Примеры использования

1. Обертка в Adapter объект:

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

Преобразуем данные в объект Adapter:

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

2. Преобразование свойств объекта:

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

Преобразуем данные в объект Adapter:

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

3. Мэпим данные объекта:

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

Преобразуем данные в объект Adapter:

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

4. Создание коллекции:

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

Преобразуем данные в коллекцию Adapter:

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

## Список методов

#### Методы инициализации

* `make()` - инициализация объекта;
* `mapping(array $arMappingList = [])` - определение массива соответствий;
* `setCustom(array $arCustomData = [])` - передача кастомных данных в класс Adapter;
* `create(array $arData = [])` - создать объект Adapter;
* `createCollection(array $arDataList = [])` - создать коллекцию Adapter;
* `toArray()` - преобразовать в массив.

#### Внутри класса Adapter

* `getOrigin(?string $sKey = null)` - получить оригинальное значение в классе Adapter;
* `getCustom(?string $sKey = null)` - получить кастомное значение в классе Adapter;
* `getCustomByItemIndex(?string $sKey = null)` - получить кастомное значение по индексу в классе Adapter.
