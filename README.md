php-plesk
============

Introduction
------------

This package contains a PHP client for the Plesk RPC API.

The following features are currently supported:

*	List websites
*	Retrieve website information and subdomains
*	Retrieve/Add/Delete website aliases
*	Create/Delete email addresses
*	Change email address password
*	Email dev.pmill@gmail.com with requests for exposing further functionality

Requirements
------------

This library package requires PHP 5.3 or later and Plesk 9.5 or above.


Usage
-----

The following example shows how to retrieve the list of websites available for the 
supplied user.

	$config = array(
		'host'=>'example.com',
		'username'=>'username',
		'password'=>'password',
	);
	
	$request = new Site_List_Request($config);
	$info = $request->process();

Further examples are available in the examples directory.

Version History
---------------

Unversioned (13 Apr 2013)

*   First public release of php-plesk.

0.1.0 (08/10/2014)

*   Updated Create Email Address xml payload for newer versions of the Plesk API
*   Exposed further functionality


Copyright and License
---------------------

php-plesk
Copyright (c) 2013 pmill (dev.pmill@gmail.com) 
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

*   Redistributions of source code must retain the above copyright 
    notice, this list of conditions and the following disclaimer.

*   Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the 
    distribution.

This software is provided by the copyright holders and contributors "as
is" and any express or implied warranties, including, but not limited
to, the implied warranties of merchantability and fitness for a
particular purpose are disclaimed. In no event shall the copyright owner
or contributors be liable for any direct, indirect, incidental, special,
exemplary, or consequential damages (including, but not limited to,
procurement of substitute goods or services; loss of use, data, or
profits; or business interruption) however caused and on any theory of
liability, whether in contract, strict liability, or tort (including
negligence or otherwise) arising in any way out of the use of this
software, even if advised of the possibility of such damage.
