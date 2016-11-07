<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop;


use Eshop\Model\Price;
use Eshop\Model\Summary;
use Fuel\Core\Debug;

class Cart_Item
{
    /**
     * Object Cart_Driver
     *
     * @var Cart_Driver
     */
    protected $cart;

    /**
     * Object Summary model
     *
     * @var Summary
     */
    protected $summary;

    /**
     * @var array   $values     Item values
     */
    protected $values = array();

    /**
     * @var array   $required   Required item fields
     */
    protected static $required = array('code','title','price','quantity');

    /**
     * Cart_Item constructor.
     *
     * @param   array     $cart         The cart driver object
     * @param   string    $values       The item values
     */
    public function __construct($cart, $values)
    {
        $this->cart = $cart;
        $this->values = $values;
        $this->summary = $this->cart->get_summary();
        $this->summary->add_price(Price::create($values['code'], $values['price'], $values['tax'], $values['quantity']));
    }

    /**
     * Sets the quantity value and updates quantity in Summary model
     *
     * @param integer   $value          The quantity value
     */
    public function set_quantity($value)
    {
        $this->summary->update_quantity($this->get_code(),$value);
        $this->values['quantity'] = $value;
    }

    /**
     * Returns item code
     *
     * @return string           Returns item code
     */
    public function get_code()
    {
        return $this->values['code'];
    }

    /**
     * Returns item title
     *
     * @return string           Returns item title
     */
    public function get_title()
    {
        return $this->values['title'];
    }

    /**
     * Returns price value per peice excluding taxes
     *
     * @return string           Returns rounded, formated, calculated price
     */
    public function get_price()
    {
        return $this->summary->get_item_price($this->get_code(),false);
    }

    /**
     * Returns price value per peice including taxes
     *
     * @return string           Returns rounded, price value
     */
    public function get_price_tax()
    {
        return $this->summary->get_item_price($this->get_code(),true);
    }

    /**
     * Returns total price value excluding taxes
     *
     * @return string           Returns rounded total price
     */
    public function get_total_price()
    {
        return $this->summary->get_item_total_price($this->get_code(),false);
    }

    /**
     * Returns total price value including taxes
     *
     * @return string           Returns rounded total price
     */
    public function get_total_price_tax()
    {
        return $this->summary->get_item_total_price($this->get_code(),true);
    }

    /**
     * Returns total tax value
     *
     * @return string           Returns total tax value
     */
    public function get_total_tax()
    {
        return $this->summary->get_item_total_tax($this->get_code());
    }

    /**
     * Returns percent tax value
     *
     * @return string           Returns percent tax value
     */
    public function get_tax()
    {
        return $this->values['tax'];
    }

    /**
     * Returns item quantity
     *
     * @return string           Returns item quantity
     */
    public function get_quantity()
    {
        return $this->values['quantity'];
    }

    /**
     * Returns required item fileds
     *
     * @return string           Returns required item fields
     */
    public static function get_required()
    {
        return self::$required;
    }

    /**
     * Returns item values
     *
     * @return string           Returns item values
     */
    public function get_values()
    {
        return $this->values;
    }

}