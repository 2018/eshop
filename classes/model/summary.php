<?php
/**
 * @package     fproject
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop\Model;


class Summary
{
    /**
     * @var array   $prices     Array of Price models
     */
    protected $prices = array();

    /**
     * @var bool  $started      Whether the start method called
     */
    protected $started = false;

    /**
     * @var string  $price_tax  Summary price including taxes
     */
    protected $price_tax;

    /**
     * @var string  $price      Summary price excluding taxes
     */
    protected $price;

    /**
     * @var string  $tax        Summary taxes
     */
    protected $tax;

    /**
     * Returns unique tax rates from array of Price models
     *
     * @return array
     */
    protected function _get_tax_rates()
    {
        $taxes = array();
        foreach ($this->prices as $price)
        {
            $taxes[] = $price->get_tax();
        }

        $taxes = array_unique($taxes);
        sort($taxes);
        return $taxes;
    }

    /**
     * Returns prices by tax rate
     *
     * @param   int     $tax_rate   Tax rate
     * @return array
     */
    protected function _get_prices_by_tax($tax_rate)
    {
        $prices = array();
        foreach ($this->prices as $price)
        {
            if ((int) $tax_rate == (int) $price->get_tax())
            {
                $prices[] = $price;
            }
        }
        return $prices;
    }

    /**
     * Adds new Price model to $prices
     *
     * @param Price $price      Price model
     */
    public function add_price(Price $price)
    {
        $this->prices[] = $price;
    }

    /**
     * Updates price model quantity
     *
     * @param string    $id         price identifier
     * @param int       $quantity   item quantity
     */
    public function update_quantity($id,$quantity)
    {
        foreach ($this->prices as $index => $price)
        {
            if($price->get_id() == $id)
            {
                $this->prices[$index]->set_quantity($quantity);
                break;
            }
        }
    }

    /**
     * Unset price model from prices
     *
     * @param string    $id         price identifier
     */
    public function delete_price($id)
    {
        foreach ($this->prices as $index => $price)
        {
            if($price->get_id() == $id)
            {
                unset($this->prices[$index]);
                break;
            }
        }
    }

    /**
     * Returns item Price model
     *
     * @param string    $id         price identifier
     * @return Price
     */
    protected function _get_price_by($id) {
        foreach ($this->prices as $price)
        {
            if ($price->get_id() == $id)
            {
                return $price;
            }
        }
    }

    /**
     * Returns item price value
     *
     * @param string    $id         price identifier
     * @param bool      $incl_tax   Whether the price including taxes
     * @return float
     */
    public function get_item_price($id, $incl_tax = true)
    {
        $price = $this->_get_price_by($id);
        $single_price = clone $price;
        $single_price->set_quantity(1);
        $price_value = $incl_tax ? $single_price->get_price_tax() : $single_price->get_price();
        return $price_value;
    }

    /**
     * Returns total item price
     *
     * @param string    $id         price identifier
     * @param bool      $incl_tax   Whether the price including taxes
     * @return float
     */
    public function get_item_total_price($id, $incl_tax = true)
    {
        $price = $this->_get_price_by($id);
        $price_value = $incl_tax ? $price->get_price_tax() : $price->get_price();
        return $price_value;
    }

    /**
     * Returns total item tax
     *
     * @param string    $id     price identifier
     * @return float
     */
    public function get_item_total_tax($id)
    {
        $price = $this->_get_price_by($id);
        return $price->get_price_tax() - $price->get_price();

    }

    /**
     * Calculates summary tax, price and price with taxes
     */
    protected function _start()
    {
        if(!$this->started)
        {
            $sum = array();
            $tax_rates = $this->_get_tax_rates();
            foreach ($tax_rates as $tax_rate)
            {
                $prices = $this->_get_prices_by_tax($tax_rate);
                $excl_tax = 0;
                $incl_tax = 0;
                foreach ($prices as $price)
                {
                    $excl_tax += $price->get_price();
                    $incl_tax += $price->get_price_tax();
                }
                $sum[$tax_rate]['tax'] = $incl_tax - $excl_tax;
                $sum[$tax_rate]['price'] = $excl_tax;
                $sum[$tax_rate]['price_tax'] = $incl_tax;
                if ($sum[$tax_rate]['price_tax'] == 0)
                {
                    unset($sum[$tax_rate]);
                }
            }

            $final_price = 0;
            $final_price_tax = 0;

            foreach ($sum as $item)
            {
                $final_price += $item['price'];
                $final_price_tax += $item['price_tax'];
            }

            $this->tax = $final_price_tax - $final_price;
            $this->price_tax = $final_price_tax;
            $this->price = $final_price;
            $this->started = true;
        }

    }

    /**
     * Returns total price value with taxes
     *
     * @return string           total price value with taxes
     */
    public function get_price_tax()
    {
        $this->_start();
        return $this->price_tax;
    }

    /**
     * Returns total price value
     *
     * @return string           total price value
     */
    public function get_price()
    {
        $this->_start();
        return $this->price;
    }

    /**
     * Returns total tax value
     *
     * @return string           total tax value
     */
    public function get_tax()
    {
        $this->_start();
        return $this->tax;
    }
}