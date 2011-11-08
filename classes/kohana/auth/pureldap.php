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
	 * Gets the currently logged in user from the session.
	 * Returns NULL if no user is currently logged in.
	 *
	 * @return  Model_LDAP_User or NULL
	 */
	public function get_user($default = NULL)
	{
		return $this->_session->get($this->_config['session_key'], $default);
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
				$dn = $ldap_cfg->get('dn');
				$filter = "(cn=$username)";
				$attrs_dict = $ldap_cfg->get('attributes');
				$attrs_values = array_values($attrs_dict);
				
				// If needed, add the 'roles attribute' to the list of attributes to fetch
				$roles_attr = $ldap_cfg->get('roles_attr');
				if (($roles_attr != NULL) && (array_search($roles_attr, $attrs_values) === FALSE))
					$attrs_values[] = $roles_attr;
				
				// Perform the lookup
				ldap_bind($conn, $username.$rdn_suffix, $password);
				$search = ldap_search($conn, $dn, $filter, $attrs_values);
				$results = ldap_get_entries($conn, $search);
				$result = $results[0];	// Only interested in one result
				
				// Parse attributes mapping from our config and use the keys provided there
				$attributes = array();
				foreach ($attrs_dict as $userkey => $ldapkey)
				{
					// If search result doesn't contain this attribute, set it to NULL
					if (!isset($result[$ldapkey]))
						$result[$ldapkey] = NULL;

					// If a key wasn't given in the config, default it to the ldapkey
					if (!is_string($userkey))
						$userkey = $ldapkey;
					
					// If this value is a singleton, just grab the 0th index from it
					if ($result[$ldapkey]['count'] == 1)
						$attributes[$userkey] = $result[$ldapkey][0];
					else
						$attributes[$userkey] = $result[$ldapkey];
				}
								
				// Parse roles (group memberships)
				$roles = array();
				for ($i=0; $i < $result[$roles_attr]['count']; $i++)
				{
					$grp_arr = explode(',', $result[$roles_attr][$i]);	// Explode on commas
					$roles[] = str_replace('CN=', '', $grp_arr[0]);		// Strip CN= prefix
				}
				
				// Store user attributes in a LDAP_User object
				$user = Model::factory('LDAP_User');
				$user->username = $username;
				$user->attributes = $attributes;
				$user->roles = $roles;
								
				// Success!
				$success = TRUE;
			}
		}
		catch (Exception $e)
		{
			// Most likely an Invalid Credentials exception. Just do nothing.
			$success = FALSE;	// Just to be sure
			
			// Debugging: Uncomment to see why login is failing
			//die($e);
		}
		
		// Close the connection
		ldap_close($conn);

		// Finish up
		if ($success)
			return $this->complete_login($user);
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
