<?php

declare(strict_types=1);

namespace WarfaceTrueSight;

class Parser
{
    /**
     * Источник парсинга
     *
     * @var string
     */
    protected string $url = 'https://wfts.su/achievements';

    /**
     * Список полей, по которым будет строиться паттерн
     * По умолчанию установлено единственное обязательное поле id
     *
     * @var string[]
     */
    protected array $fields = [
        'id'
    ];

    /**
     * Структура всех доступных полей включая их паттерны
     *
     * @var string[]
     */
    protected array $struct = [
        'id'          => 'id_(?<id>\d+)',
        'picture'     => 'src="(?<picture>[^"]+)',
        'href'        => 'href="(?<href>[^"]+)',
        'name'        => 'blank">(?<name>[^<]+)',
        'description' => 'description">(?<description>[^<]+)'
    ];

    /**
     * Каталог достижений в формате массива с явно указанными полями
     *
     * @var array
     */
    protected array $collection = [];

    /**
     * Сепаратор для объединения кастомного паттерна
     *
     * @var string
     */
    protected string $separator = '.*?';

    /**
     * Включение нужных полей для парсинга
     *
     * @param array $fields
     */
    public function include(array $fields)
    {
        $this->fields = array_merge($this->fields, $fields);
    }

    /**
     * Парсинг достижений по кастомному регулярному выражению
     *
     * @return array
     */
    public function parse(): array
    {
        $this->struct = array_filter(
            $this->struct,
            fn ($key) => in_array($key, $this->fields) || $key === 'id',
            ARRAY_FILTER_USE_KEY
        );

        preg_match_all($this->pattern(), file_get_contents($this->url), $matches, PREG_SET_ORDER);

        foreach ($matches as $k => $match) {
            foreach ($this->fields as $field) {
                if (array_key_exists($field, $match)) {
                    $this->collection[$k][$field] = $match[$field];
                }
            }
        }

        return $this->collection;
    }

    /**
     * Объединение сабпаттернов в финальный паттерн
     *
     * @return string
     */
    protected function pattern(): string
    {
        return '/'. join($this->separator,  $this->struct) .'/us';
    }
}
