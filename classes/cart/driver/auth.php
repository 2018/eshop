<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop;


use Fuel\Core\Debug;

class Cart_Driver_Auth extends Cart_Driver
{


    /**
     * Returns serialized string from table cart.content
     *
     * @param	string	$cart_id		    cart identifier
     * @return	string                      serialized string
     */
    protected function _get($cart_id)
    {
        $entry = Model\Cart::fetch_cart($cart_id);
        return $entry->to_object()->content;
    }

    /**
     * Stores the data.
     *
     * @param	string	$cart_id			cart identifier
     * @param	string	$data_string	    serialized data string
     */
    protected function _set($cart_id, $data_string)
    {
        $entry = Model\Cart::fetch_cart($cart_id);
        $entry->content = $data_string;
        $entry->save();
    }

    /**
     * Deletes session data
     *
     * @param	string	$cart_id		    cart identifier
     */
    protected function _delete($cart_id)
    {
        $entry = Model\Cart::fetch_cart($cart_id);
        is_null($entry) or $entry->delete();
    }
}