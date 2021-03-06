<?php

namespace Runn\Core;

/**
 * Class TypedCollectionTrait
 * @package Runn\Core
 *
 * @implements \Runn\Core\TypedCollectionInterface
 */
trait TypedCollectionTrait
    /*implements TypedCollectionInterface*/
{

    use CollectionTrait {
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
        innerGet as protected collectionInnerGet;
        innerSet as protected collectionInnerSet;
    }

    protected function isValueTypeValid($value): bool
    {
        $type = static::getType();

        if (class_exists($type) || interface_exists($type)) {
            return ($value instanceof $type);
        }

        switch (gettype($value)) {
            case 'boolean':
                if ('bool' == $type || 'boolean' == $type) {
                    return true;
                }
            default:
                $typeChecker = 'is_' . $type;
                if (function_exists($typeChecker)) {
                    return $typeChecker($value);
                }
        }
        return false;
    }

    protected function checkValueType($value)
    {
        if (true !== $this->isValueTypeValid($value)) {
            throw new Exception('Typed collection type mismatch');
        }
    }

    public function append($value)
    {
        $this->checkValueType($value);
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        $this->checkValueType($value);
        return $this->collectionPrepend($value);
    }

    public function innerGet($key)
    {
        if ('type' === $key) {
            return $this->__data[$key];
        }
        return $this->collectionInnerGet($key);
    }

    public function innerSet($key, $value)
    {
        $this->checkValueType($value);
        $this->collectionInnerSet($key, $value);
    }
}
