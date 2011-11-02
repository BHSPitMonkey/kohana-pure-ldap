Kohana Pure LDAP
================

An LDAP auth module for Kohana with no external dependencies.

Version 1.1
Released on 2011-11-02
By Stephen Eisenhauer

Introduction
------------

Kohana-Pure-LDAP is a module for [Kohana] [0] which extends the core
Auth module to allow for authenticating against an LDAP server.

This module is so-called 'Pure' because, unlike other Kohana LDAP auth
modules in existence, this one does not external libraries like PEAR or 
ZEND, instead only utilizing functions from PHP's standard 
[LDAP extension] [1].

Installation
------------

To install, simply clone this repository into your Kohana installation's
`modules` directory.

Configuration
-------------

This module provides an example configuration file located 
in `config/pure-ldap.php`.  Simply copy this file into your Kohana
installation's `application/config` directory and modify its
variables using a text editor.  The example configuration contains
more information on the settings themselves.

In order to activate this module, you will need to do the following:

1.	Edit the `application/bootstrap.php` file in your Kohana install.
	Ensure that both 'auth' and 'kohana-pure-auth' are enabled in 
	the `Kohana::modules` array.
2.	If you don't already have a file in your `application/config`
	directory called `auth.php`, copy the sample from
	`modules/auth/config/auth.php` to this location.
3.	Edit `application/config/auth.php` from the previous step
	and set the 'driver' key's value to `PureLDAP`.

Usage
-----

Since this module extends the core Auth module, use it according to
the Auth module's documentation and API.

In general, you'll want to create a login form whose backend contains
something like this:

```php
	$success = Auth::instance()->login($_POST['user'], $_POST['pass']);

	// If $success, redirect the user.
	// Else, display the login form again with an error message.
```

Additionally, `Auth::instance()->get_user()` will return the username 
of the logged in user (or FALSE if no one is logged in).

License
-------

This code is released under the New BSD License. This means you can use
and modify any of this code freely. See LICENSE for full legal details.

  [0]: http://kohanaframework.org/
  [1]: http://php.net/manual/en/book.ldap.php
