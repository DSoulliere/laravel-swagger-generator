<?php

namespace OA;

use Illuminate\Support\Arr;

abstract class BaseAnnotation
{
    /**
     * BaseAnnotation constructor.
     * @param array $values
     */
    public function __construct($values)
    {
        $this->configureSelf($values);
    }

    /**
     * Dumps object data as array
     * @return array
     */
    public function toArray()
    {
        $reflection = new \ReflectionClass($this);
        $data = [];
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $data[$property->name] = $this->{$property->name};
        }
        return $data;
    }

    /**
     * Configure object
     * @param array       $config
     * @param string|null $defaultParam
     */
    protected function configureSelf($config, $defaultParam = null)
    {
        if (isset($config['value']) && !property_exists($this, 'value') && $defaultParam !== null) {
            $this->{$defaultParam} = Arr::pull($config, 'value');
        }
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Get object string representation
     * @return string
     */
    abstract public function __toString();
}