Kohana Pure LDAP: Changelog
---------------------------

* Version 1.1 (2011-11-02)
	*	Now uses an included LDAP_User model
	*	Attributes can be defined in the config, and they will be fetched 
		and included in the LDAP_User object
	*	Some initial setup for supporting roles, though not yet implemented

* Version 1.0 (2011-10-31)
	*	Initial release
	*	Only implements basic auth driver via _login() method
	*	Roles and other user info not provided, but this is on the wishlist
