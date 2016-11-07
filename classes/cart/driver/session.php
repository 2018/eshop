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
use Fuel\Core\Event;
use Fuel\Core\Session;

class Cart_Driver_Session extends Cart_Driver
{

    /**
     * Returns the datastring.
     *
     * @param	string	$cart_id	    cart identifier
     * @return	string|array		    datastring or empty array if not found
     */
    protected function _get($cart_id)
    {
        return Session::get($cart_id, array());
    }

    /**
     * Stores the data.
     *
     * @param	string	$cart_id		cart identifier
     * @param	string	$data_string	serialized data string
     */
    protected function _set($cart_id, $data_string)
    {
        Session::set($cart_id, $data_string);
    }

    /**
     * Deletes session data
     *
     * @param	string	$cart_id		cart identifier
     */
    protected function _delete($cart_id)
    {
        Session::delete($cart_id);
    }


}