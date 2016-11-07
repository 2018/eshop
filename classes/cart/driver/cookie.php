<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop;


use Fuel\Core\Cookie;

class Cart_Driver_Cookie extends Cart_Driver
{

    /**
     * Returns the data_string.
     *
     * @param	string	$cart_id		cart identifier
     * @return	string|array		    data_string or empty array if not found
     */
    protected function _get($cart_id)
    {
        return Cookie::get($cart_id, array());
    }

    /**
     * Stores the data.
     *
     * @param	string	$cart_id		cart identifier
     * @param	string	$data_string	serialized data string
     */
    protected function _set($cart_id, $data_string)
    {
        Cookie::set($cart_id, $data_string);
    }

    /**
     * Deletes the data.
     *
     * @param	string	$cart_id		cart identifier
     */
    protected function _delete($cart_id)
    {
        Cookie::delete($cart_id);
    }

}