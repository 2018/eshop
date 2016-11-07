<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

return array(

    /**
     * DB connection, leave null to use default
     */
    'db_connection' => null,
    'driver' => 'auth',
	'fallback_storage' => 'session',
    'cart_identifier' => 'fuel_cart',
    'tax' => 21,
    'price_format' => array(
        'decimals' => 0,
        'dec_point' => '.',
        'thousands_sep' => ' ',
        'currency' => 'Kč',
        'currency_symbol_position' => 1  // 0 - before value, 1 - after value
    )

);
