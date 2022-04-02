# Warface TrueSight Parser

Парсер использует кастомное регулярное выражение, которое строится исходя из заданных полей.

## Установка

С помощью Composer:

```shell
$ composer require wnull/wfts-parser
```

## Использование

Простой пример, по умолчанию будет доступно единственное поле `id`.

```php
$parser = new WarfaceTrueSight\Parser();
$collection = $parser->parse();
```

Пример с использованием метода `include()`.

Метод принимает в себя массив со списком полей, по которым будет строиться паттерн для парсинга.

```php
$parser = new WarfaceTrueSight\Parser();

/* Список всех доступных полей */
$fields = [
    'id',          /* ID достижения */
    'picture',     /* Ссылка на изображение */
    'href',        /* Ссылка на TOP */
    'name',        /* Название */
    'description'  /* Описание */
];

$parser->include($fields);

$collection = $parser->parse();
```

## Лицензия

[MIT](LICENSE)