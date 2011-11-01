<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * LDAP Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 * (Though it should support LDAP groups as "roles" in the future.)
 *
 * @package    kohana-pure-ldap
 * @author     Stephen Eisenhauer
 * @copyright  (c) 2011 Stephen Eisenhauer
 * @license    New BSD License
 */
class Kohana_Auth_PureLDAP extends Auth {

	/**
	 * Constructor loads the user list into the class.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Logs a user in.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @param   boolean  enable autologin (not supported)
	 * @return  boolean
	 */
	protected function _login($username, $password, $remember)
	{
		$success = FALSE;
				
		// Connect to LDAP host
		$ldap_cfg = Kohana::$config->load('pure-ldap');
		$host = $ldap_cfg->get('host');
		$conn = ldap_connect($host);
		
		// Prepare bind attempt
		$rdn_suffix = $ldap_cfg->get('rdn_suffix');
		
		// If bind attempt succeeds, credentials are valid.
		try
		{
			if (ldap_bind($conn, $username.$rdn_suffix, $password))
			{
				// Login is successful. Retrieve additional user info from server.
				// TODO
				// // Store username in session
				// $this->_session->set($this->_config['session_key'], $user);
				
				// Success!
				$success = TRUE;
			}
		}
		catch (Exception $e)
		{
			// Most likely an Invalid Credentials exception. Just do nothing.
			$success = FALSE;	// Just to be sure
		}
		
		// Close the connection
		ldap_close($conn);

		// Finish up
		if ($success)
			return $this->complete_login($username);
		return FALSE;
	}

	/**
	 * Forces a user to be logged in, without specifying a password.
	 *
	 * @param   mixed    username
	 * @return  boolean
	 */
	public function force_login($username)
	{
		// Complete the login
		return $this->complete_login($username);
	}

	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   username
	 * @return  string
	 */
	public function password($username)
	{
		// TODO: Figure out the best thing to do here.
		return '';
	}

	/**
	 * Compare password with original (plain text). Works for current (logged in) user
	 *
	 * @param   string  $password
	 * @return  boolean
	 */
	public function check_password($password)
	{
		$username = $this->get_user();

		if ($username === FALSE)
		{
			return FALSE;
		}

		return ($password === $this->password($username));
	}

} // End Auth File
