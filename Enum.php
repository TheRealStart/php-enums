<?php
/**
 * @author: Rustam Akhmedov
 * Date: 2/5/13
 * Time: 3:57 PM
 */

namespace TRS\Enum;

class Enum
{
    protected static $_enums = array();

    /**
     * Get caller name
     * @return string
     */
    public static function getClass()
    {
        return get_called_class();
    }

    /**
     * Get array where $constName => $constValue
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

    /**
     * Get constant value by name
     * @param string $constName
     * @return mixed|null
     */
    public static function getEnum($constName)
    {
        $data = self::getEnums();
        $key  = mb_strtoupper($constName);

        if (isset( $data[$key] ))
            return $data[$key];

        return null;
    }

    /**
     * Get array where constValue => constName
     * Each constName of result array are normalized
     * @see Enum::normalizeName for more info
     * @return array
     */
    public static function getUiEnums()
    {
        $data = array_flip(self::getEnums());
        foreach ($data as &$value)
            $value = static::normalizeName($value);
        return $data;
    }

    /**
     * Get constant name by it value
     * Returning value also contains caller name with dot as prefix
     *
     * ```php
     * class OrderStatusEnum extends Enum{
     *      const New=1;
     *      const Canceled=2;
     * }
     * $s = OrderStatusEnum::getLabel(1); // 'OrderStatusEnum.New'
     * ```
     *
     * @param mixed $constValue
     * @return string|null
     */
    public static function getLabel($constValue)
    {
        $labels = array_flip(self::getEnums());
        if (isset( $labels[$constValue] )) {
            return get_called_class() . '.' . $labels[$constValue];
        } else {
            return null;
        }
    }

    /**
     * Get normalized constant name by it value
     *
     * ```php
     * class OrderStatusEnum extends Enum{
     *      const STATUS_NEW=1;
     *      const STATUS_CANCELED=2;
     * }
     * $s = OrderStatusEnum::getUiLabel(1); // 'Status New'
     * ```
     *
     * @param mixed $constValue
     * @return string|null
     */
    public static function getUiLabel($constValue)
    {
        if ($constValue === null)
            return null;

        $labels = array_flip(self::getEnums());

        if (isset( $labels[$constValue] ))
            return static::normalizeName($labels[$constValue]);
        else
            return null;
    }

    /**
     * Get normalized constant name
     *
     * ```php
     * $s = Enum::normalizeName('ORDER_STATUS_CANCELED'); // $s now equals 'Order Status Canceled'
     * ```
     *
     * @param string $constName
     * @return string
     */
    protected static function normalizeName($constName)
    {
        return ucwords(strtolower(str_replace("_", " ", $constName)));
    }
}