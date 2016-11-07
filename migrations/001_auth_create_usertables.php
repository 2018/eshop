<?php

namespace Fuel\Migrations;

class Auth_Create_Usertables
{
	function up()
	{
        // get the tablename
        \Config::load('simpleauth', true);
        $table = \Config::get('simpleauth.table_name', 'user');

        // make sure the correct connection is used
        $connection = $this->dbconnection('simpleauth');

        // only do this if it doesn't exist yet
        if ( ! \DBUtil::table_exists($table))
        {
            // table users
            \DBUtil::create_table($table, array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'username' => array('type' => 'varchar', 'constraint' => 50),
                'password' => array('type' => 'varchar', 'constraint' => 255),
                'group' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
                'email' => array('type' => 'varchar', 'constraint' => 255),
                'last_login' => array('type' => 'varchar', 'constraint' => 25),
                'login_hash' => array('type' => 'varchar', 'constraint' => 255),
                'profile_fields' => array('type' => 'text'),
                'created_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
                'updated_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
            ), array('id'));

            // add a unique index on username and email
            \DBUtil::create_index($table, array('username', 'email'), 'username', 'UNIQUE');

            // create the user account if needed
            $result = \DB::select('id')->from('user')->where('username', '=', 'user')->execute($connection);
            if (count($result->as_array()) == 0)
            {
                \Auth::instance()->create_user('user', 'password', 'example@example.com', 1, array('fullname' => 'Default user'));
            }
        }

		// reset any DBUtil connection set
		$this->dbconnection(false);
	}

	function down()
	{
        // get the tablename
        \Config::load('simpleauth', true);
        $table = \Config::get('simpleauth.table_name', 'user');

        // make sure the correct connection is used
        $this->dbconnection('simpleauth');

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
			case 'simpleauth':
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
