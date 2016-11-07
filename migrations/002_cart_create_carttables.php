<?php

namespace Fuel\Migrations;

use Fuel\Core\Config;

class Cart_Create_Carttables
{
	function up()
	{
        // get the tablename
        $table = Config::get('cart.table_name', 'cart');

        // make sure the correct connection is used
        $this->dbconnection('cart');

        // only do this if it doesn't exist yet
        if ( ! \DBUtil::table_exists($table))
        {
            // table users
            \DBUtil::create_table($table, array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'user_id' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
                'identifier' => array('type' => 'varchar', 'constraint' => 50),
                'content' => array('type' => 'text'),
            ), array('id'));

            // add a unique index on username and email
            \DBUtil::create_index($table, 'id', 'id', 'UNIQUE');
        }

		// reset any DBUtil connection set
		$this->dbconnection(false);
	}

	function down()
	{
        // get the tablename
        \Config::load('cart', true);
        $table = \Config::get('cart.table_name', 'cart');

        // make sure the correct connection is used
        $this->dbconnection('cart');

        // drop the admin_users table
        \DBUtil::drop_table($table);
		// reset any DBUtil connection set
		$this->dbconnection(false);
	}

	/**
	 * check if we need to override the db connection for auth tables
	 */
	protected function dbconnection($type = null)
	{
		static $connection;

		switch ($type)
		{
			// switch to the override connection
			case 'cart':
				if ($connection = \Config::get($type.'.db_connection', null))
				{
					\DBUtil::set_connection($connection);
				}
				break;

			// switch back to the configured migration connection, or the default one
			case false:
				if ($connection)
				{
					\DBUtil::set_connection(\Config::get('migrations.connection', null));
				}
				break;

			default:
				// noop
		}
	}
}
