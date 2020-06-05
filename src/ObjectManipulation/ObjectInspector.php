<?php

namespace Morebec\DomainNormalizer\ObjectManipulation;

/**
 * The inspector will analyse a live instance of an object to determine its structure
 * and save it.
 * Class ObjectInspector.
 */
class ObjectInspector
{
    public static function inspect($object): array
    {
        $accessor = ObjectAccessor::access($object);

        $props = $accessor->getProperties();

        $map = [];
        foreach ($props as $prop) {
            $value = $accessor->readProperty($prop);
            $map[$prop] = self::buildMapForValue($value);
        }

        return $map;
    }

    /**
     * @param $value
     * @param array $map
     * @param $prop
     */
    public static function buildMapForValue($value): array
    {
        $type = self::detectType($value);
        $mapInfo = ['type' => $type];
        if ($type === 'array') {
            $items = [];
            foreach ($value as $key => $item) {
                $items[$key] = self::buildMapForValue($item);
            }
            $mapInfo['items'] = $items;
        }

        return $mapInfo;
    }

    private static function detectType($v): string
    {
        $type = \gettype($v);

        if ($type === 'object') {
            return \get_class($v);
        }

        return $type;
    }
}
