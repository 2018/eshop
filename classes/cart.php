<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop;

use Auth\Auth;
use \Fuel\Core\Config;
use Fuel\Core\Debug;
use Fuel\Core\FuelException;

/**
 * Session Class
 *
 * @package		Eshop
 * @category	Modules
 * @author      Oleksiy
 */
class Cart
{
    /**
     * loaded cart driver instance
     */
    protected static $_instance = null;

    /**
     * array of loaded instances
     */
    protected static $_instances = array();

    /**
     * array of global config defaults
     */
    protected static $_defaults = array(
        'driver' => 'session',
    );

    // --------------------------------------------------------------------
    /**
     * Class init.
     */
    public static function _init()
    {
        Config::load('cart', true);
    }

    // --------------------------------------------------------------------

    /**
     * Factory
     *
     * Produces fully configured cart driver instances
     *
     * @param	string	$cart_id    Cart identifier
     * @return	mixed
     * @throws	FuelException
     */
    public static function forge($cart_id = null)
    {
        $result = null;

        $config = Config::get('cart');

        $config = array_merge(static::$_defaults, $config);

        if (empty($config['driver']))
        {
            throw new FuelException('No cart driver given or no default cart driver set.');
        }
        if (empty($config['fallback_storage']))
        {
            throw new FuelException('No cart fallback storage given or no default cart fallback storage set.');
        }

        // determine the driver to load
        if(Auth::check()){
            $class = '\\Eshop\\Cart_Driver_'.ucfirst($config['driver']);
        }else{
            $class = '\\Eshop\\Cart_Driver_'.ucfirst($config['fallback_storage']);
        }

        if (!class_exists($class))
        {
            throw new FuelException('Cart driver class not exist.');
        }
        $driver = new $class($config,$cart_id);

        static::$_instances[$cart_id] = $driver;

        if (array_key_exists($cart_id, static::$_instances))
        {
            $result = static::$_instances[$cart_id];
        }
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Create or return the Cart driver instance
     *
     * @param	string $cart_id     Cart identifier
     * @return	Cart Driver object
     */
    public static function instance($cart_id = null)
    {
        if ($cart_id !== null)
        {
            if ( ! array_key_exists($cart_id, static::$_instances))
            {
                return false;
            }

            return static::$_instances[$cart_id];
        }

        if (static::$_instance === null)
        {
            static::$_instance = static::forge($cart_id);
        }

        return static::$_instance;
    }

    /**
     * Static call forwarder
     *
     * @param   string  $func  method name
     * @param   array   $args  passed arguments
     * @return  mixed
     * @throws  \BadMethodCallException
     */
    public static function __callStatic($func, $args)
    {
        $instance = static::instance();

        if (method_exists($instance, $func))
        {
            return call_fuel_func_array(array($instance, $func), $args);
        }

        throw new \BadMethodCallException('Call to undefined method: '.get_called_class().'::'.$func);
    }
}