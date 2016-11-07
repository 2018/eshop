<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

function smarty_modifier_price($price)
{
    $config = \Fuel\Core\Config::get('cart.price_format');
    $decimals = $dec_point = $thousands_sep = null;
    isset($config['decimals']) and $decimals = $config['decimals'];
    isset($config['dec_point']) and $dec_point = $config['dec_point'];
    isset($config['thousands_sep']) and $thousands_sep = $config['thousands_sep'];
    $number = number_format($price, $decimals, $dec_point, $thousands_sep);
    if(isset($config['currency']) and isset($config['currency_symbol_position']))
    {
        if($config['currency_symbol_position'] == 1)
        {
            $number = $number . ' ' . $config['currency'];
        }
        else
        {
            $number = $config['currency'] . ' ' . $number;
        }

    }
    return $number;
}