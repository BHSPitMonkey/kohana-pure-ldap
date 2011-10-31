<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	// LDAP hostname or URL (for ldap_connect).
	// URLs should begin with ldap:// or ldaps://
	'host'			=> 'ldaps://localhost',
	
	// Suffix added to usernames before bind attempts. 
	// Normally '@' followed by a domain.
	'rdn_suffix'	=> '@example.com',

);
