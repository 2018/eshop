<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop;


use Eshop\Model\Summary;
use Fuel\Core\Config;
use Fuel\Core\Debug;
use Fuel\Core\Event;
use Fuel\Core\FuelException;

abstract class Cart_Driver
{
    /**
     * Summary model
     * @var Summary
     */
    protected $summary;

    /**
     * @var string  $cart_id        The cart's identifier
     */
    protected $cart_id;

    /**
     * @var array   $config         The cart's settings
     */
    protected $config = array();

    /**
     * @var array   $items          The cart's items
     */
    protected $items = array();

    /**
     * Cart_Driver constructor
     *
     * @param   array   $config     The cart's config
     * @param   string  $cart_id    The cart's identifier
     */
    public function __construct($config,$cart_id)
    {
        Event::register('shutdown', array($this,'save'));
        $items = array();
        $this->config = $config;
        is_null($cart_id) and $cart_id = Config::get('cart.cart_identifier');
        $this->cart_id = $cart_id;
        $raw_data = $this->_get($cart_id);
        is_string($raw_data) and $items = unserialize(stripslashes($raw_data));
        $this->summary = new Summary();
        if($items and count($items)>0)
        {
            foreach ($items as $values)
            {
                if(array_key_exists('code',$values)){
                    $this->items[$values['code']] = new Cart_Item($this, $values);
                }
            }
        }
    }

    /**
     * Adds item to cart
     *
     * @param   array   $values     Array of values
     * @return  string              Item id
     */
    public function add($values)
    {
        array_key_exists('tax',$values) or $values['tax'] = $this->config['tax'];
        $values['quantity'] = array_key_exists('quantity',$values) ? ($values['quantity'] > 0 ? $values['quantity'] : 1) : 1;
        $this->_validate_items($values);
        $item_id = $values['code'];
        if(array_key_exists($item_id, $this->items))
        {
            $this->items[$item_id]->set_quantity($this->items[$item_id]->get_quantity() + $values['quantity']);
        }
        else
        {
            $this->items[$item_id] = new Cart_Item($this, $values);
        }
        return $item_id;
    }

    /**
     * Validates items by required fields
     *
     * @param $items
     * @throws FuelException
     */
    protected function _validate_items($items)
    {
        $required = Cart_Item::get_required();
        foreach ($required as $filed)
        {
            if(!array_key_exists($filed,$items) or (trim($items[$filed]) == ''))
            {
                throw new FuelException('Item field '.$filed.' not valid.');
            }
        }
    }

    /**
     * Deletes item from cart and summary model
     *
     * @param string    $item_id    item id
     */
    public function delete($item_id)
    {
        $this->summary->delete_price($item_id);
        unset($this->items[$item_id]);
    }

    /**
     * Updates item quantity
     *
     * @param string    $item_id
     * @param int       $value
     */
    public function update_quantity($item_id, $value)
    {
        if(array_key_exists($item_id, $this->items))
        {
            $this->items[$item_id]->set_quantity($value);
        }
    }

    /**
     * Saves cart items as serialized string to storage
     */
    public function save()
    {
        if(count($this->items)>0)
        {
            $items = array();
            foreach ($this->items as $key => $cart_item)
            {
                $items[$key] = $cart_item->get_values();
            }
            $this->_set($this->cart_id, serialize($items));
        }
        else
        {
            $this->_delete($this->cart_id);
        }
    }

    /**
     * Delete cart
     */
    public function clear()
    {
        Event::unregister('shutdown', array($this,'save'));
        $this->_delete($this->cart_id);
    }

    /**
     * Returns cart items
     *
     * @return	array               cart items
     */
    public function get_items()
    {
        return $this->items;
    }

    /**
     * Returns the Summary model.
     *
     * @return	object  $summary    Summary model
     */
    public function get_summary()
    {
        return $this->summary;
    }

    /**
     * get total cart taxes
     *
     * @return	string              total cart taxes
     */
    public function get_tax()
    {
        return $this->summary->get_tax();
    }

    /**
     * get total cart price
     *
     * @return	string              total cart price
     */
    public function get_price()
    {
        return $this->summary->get_price();
    }

    /**
     * get total cart price with taxes
     *
     * @return	string              total cart price with taxes
     */
    public function get_price_tax()
    {
        return $this->summary->get_price_tax();
    }

    /**
     * Returns the data_string.
     *
     * @param	string	$cart_id		cart identifier
     * @return	string      		    data_string
     */
    abstract protected function _get($cart_id);

    /**
     * Stores the data.
     *
     * @param	string	$cart_id		cart identifier
     * @param	string	$data_string	serialized data string
     */
    abstract protected function _set($cart_id, $data_string);

    /**
     * Deletes the data.
     *
     * @param	string	$cart_id		cart identifier
     */
    abstract protected function _delete($cart_id);


}