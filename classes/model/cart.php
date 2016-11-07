<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop\Model;

use Auth\Auth;
use Fuel\Core\Debug;
use \Orm\Model;

class Cart extends Model
{

    protected static $_table_name = 'cart';

    protected static $_properties = array(
        'id',
        'user_id' => array(
            'data_type' => 'int',
        ),
        'identifier' => array(
            'data_type' => 'varchar',
        ),
        'content' => array(
            'data_type' => 'text',
        ),
    );

    public static function fetch_cart($cart_id)
    {
        $result = null;
        if(Auth::check()){
            $auth = Auth::get_user_id();
            $user_id = end($auth);

            $entry = self::find('last', array(
                'where' => array(
                    array('user_id', $user_id),
                    array('identifier', $cart_id),
                ),
            ));
            if(is_null($entry))
            {
                $prop = array(
                    'user_id' => $user_id,
                    'identifier' => $cart_id,
                    'content' => null
                );
                $entry = self::forge($prop);
            }
            $result = $entry;
        }
        return $result;
    }
}