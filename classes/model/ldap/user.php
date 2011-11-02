<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Kohana-Pure-LDAP User Model
 *
 * @package    kohana-pure-ldap
 * @author     Stephen Eisenhauer
 * @copyright  (c) 2011 Stephen Eisenhauer
 * @license    New BSD License
 */
class Model_LDAP_User extends Model
{
	// Properties
	public $username = '';
	public $attributes = array();
	public $roles = array();
}
