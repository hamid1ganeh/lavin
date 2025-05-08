<?php

namespace App\Enums;

use InvalidArgumentException;

abstract class BaseEnum
{
    protected $key;

    protected abstract function getKeys();

    protected abstract function getValues();

    public function __construct($val, $isValueType = false)
    {
        $key = $isValueType ? array_search($val, $this->getValues()) : $val;

        if (!self::isValidKey($key)) {
            throw new InvalidArgumentException("Invalid input: " . $val . " for " . get_class($this));
        }

        $this->key = $key;
    }

    private function isValidKey($key)
    {
        return in_array($key, $this->getKeys());
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        $values = $this->getValues();
        return $values[$this->key];
    }
}
