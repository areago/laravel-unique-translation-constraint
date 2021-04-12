<?php

namespace Areago\UniqueTranslationConstraint\Asserts;

class Assert
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function shouldNotBeEmpty(mixed $value, string $message = '')
    {
        if (empty($value)) {
            static::reportInvalidArgument(sprintf(
                $message ?: 'Expected a non empty value. Got: empty %s',
                static::typeToString($value)
            ));
        }
    }

    protected static function typeToString(mixed $value): string
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }

    protected static function reportInvalidArgument(string $message): void
    {
        throw new \InvalidArgumentException($message);
    }
}
