<?php
/**
 * @author: Rustam Akhmedov
 * Date: 2/5/13
 * Time: 3:57 PM
 */

class BaseEnum
{
    protected static $_enums = array();

    public static function getClass()
    {
        return get_called_class();
    }

    /**
     * Returns array of $constName => $constValue
     * @static
     * @return array
     */
    public static function getEnums()
    {
        $className = get_called_class();
        if (!isset( self::$_enums[$className] )) {
            $class = new \ReflectionClass($className);
            return self::$_enums[$className] = $class->getConstants();
        } else
            return self::$_enums[$className];
    }

    public static function getEnum($value)
    {
        $data = self::getEnums();
        $key  = mb_strtoupper($value);

        if (isset( $data[$key] ))
            return $data[$key];

        return null;
    }

    public static function getUiEnums()
    {
        $data = array_flip(self::getEnums());
        foreach ($data as &$value)
            $value = static::normalizeName($value);
        return $data;
    }

    public static function getLabel($value)
    {
        $labels = array_flip(self::getEnums());
        if (isset( $labels[$value] )) {
            return get_called_class() . '.' . $labels[$value];
        } else {
            return null;
        }

    }

    public static function getUiLabel($value)
    {
        if ($value === null)
            return null;

        $labels = array_flip(self::getEnums());

        if (isset( $labels[$value] ))
            return static::normalizeName($labels[$value]);
        else
            return null;
    }

    protected static function normalizeName($value)
    {
        return ucwords(strtolower(str_replace("_", " ", $value)));
    }
}