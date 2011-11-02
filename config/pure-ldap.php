<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	/* Connection settings */

	// LDAP hostname or URL (for ldap_connect).
	'host'			=> 'ldaps://localhost',
	
	// Suffix added to usernames before bind attempts. 
	// Normally '@' followed by a domain.
	'rdn_suffix'	=> '@example.com',
	
	/* Search parameters used for fetching user attributes after login */

	// A DN string for your organization, like one you would pass to ldap_search().
	'dn'			=> 'OU=example,DC=untexample,DC=com',
	
	// Which attributes to fetch into the user model.
	// If you name an index, that key name will be the key used in the 
	// user object's attributes array. Otherwise, the name of the attribute
	// will be used.
	'attributes'	=> array(
						'user_id' => 'cn',
						'email' => 'mail',
						'lname' => 'sn',
						'phone'
						),

	// Which attribute (or NULL if none) contains a comma-separated
	// lists of groups/roles to use.
	'roles_attr'	=> 'memberof',

);
