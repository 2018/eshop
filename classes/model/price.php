<?php
/**
 * @package     fproject
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop\Model;


class Price
{
    private $id;
    public $value = 0;
    private $tax = 0;
    public $quantity = 1;

    /**
     * create fully configured price model
     *
     * @param string    $id         price identifier
     * @param int       $value      price value
     * @param int       $tax        tax value
     * @param int       $quantity   quantity
     * @return Price
     */
    public static function create($id, $value, $tax, $quantity) {
        $price = new Price();
        $price->set_id($id);
        $price->set_value($value);
        $price->set_tax($tax);
        $price->set_quantity($quantity);
        return $price;
    }

    /**
     * Returns total price excluding taxes
     *
     * @return float
     */
    public function get_price()
    {
        return $this->_calc($this->value, 0, $this->quantity);
    }

    /**
     * Returns total price including taxes
     *
     * @return float
     */
    public function get_price_tax()
    {
        return $this->_calc($this->value, $this->tax, $this->quantity);
    }

    /**
     * Calculate price value
     *
     * @param   int     $value      price value
     * @param   int     $tax        tax value
     * @param   int     $quantity   item quantity
     * @return  float               calculated price
     */
    private function _calc($value, $tax, $quantity = 1) {
        return round($quantity * $value * (1 + $tax / 100));
    }

    /**
     * Get price identifier
     *
     * @return int              item price identifier
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set price identifier
     *
     * @param int   $id         item price identifier
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Get price value
     *
     * @return int              item price value
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set price value
     *
     * @param int   $value      item price value
     */
    public function set_value($value)
    {
        $this->value = $value;
    }

    /**
     * Get tax
     *
     * @return int              item tax
     */
    public function get_tax()
    {
        return $this->tax;
    }

    /**
     * Set tax
     *
     * @param int   $tax         item tax
     */
    public function set_tax($tax)
    {
        $this->tax = $tax;
    }

    /**
     * Get quantity
     *
     * @return int              item quantity
     */
    public function get_quantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param string   $quantity       item quantity
     */
    public function set_quantity($quantity)
    {
        $this->quantity = $quantity;
    }

}