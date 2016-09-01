php-plesk
============

Introduction
------------

This package contains a PHP client for the Plesk RPC API.

The following features are currently supported:

*   List IP addresses
*   List service plans
*   Get server information and stats
*   List/add/update/delete clients
*   List/add/update/delete subscriptions
*   List/add/update/delete sites
*   List/add/update/delete email addresses
*   List/add/update/delete domain aliases
*   List/add/update/delete subdomains
*   List database servers
*   List/add/delete databases
*   Add database users
*   Further functionality can be seen in the examples folder
*   Email dev.pmill@gmail.com with requests for exposing further functionality

Requirements
------------

This library package requires PHP 5.4 or later and Plesk 12.0 or above.


Usage
-----

The following example shows how to retrieve the list of websites available for the 
supplied user.

    $config = array(
        'host'=>'example.com',
        'username'=>'username',
        'password'=>'password',
    );
    
    $request = new \pmill\Plesk\ListClients($config);
    $info = $request->process();

Further examples are available in the examples directory.

Version History
---------------

Unversioned (13 Apr 2013)

*   First public release of php-plesk.

0.1.0 (08/10/2014)

*   Updated Create Email Address xml payload for newer versions of the Plesk API
*   Exposed further functionality

0.2.0 (09/10/2014)

*   Added support for composer installs
*   Updated code for psr-0 autoloading
*   Updated code for psr-1 basic coding standard
*   Updated code for psr-2 coding style guide

0.3.0 (16/10/2014)

*   Added functionality for ip addresses, service plans, clients, subscriptions and server information 
*   Added test script
*   Updated classes to throw exceptions when requests fail
*   Sorted examples folder into areas of functionality

0.4.0 (05/02/2015)

*   Added functionality for databases 
*   Updated test script

0.5.0 (08/03/2016)

*   Added secret key functionality
*   Updated error handling to expose Plesk error code
*   Added html entity escaping

0.5.1 (05/04/2016)

*   Added mail preferences functionality
*   Added wordpress functionality
*   Added APS functionality
*   Added ssl certificate functionality

0.5.2 (18/05/2016)

*   Updated ListSubscriptions to retrieve plan guids
*   Updated GetServicePlan to accept 'guid' as a filter option
*   Updated DeleteSiteAlias to accept 'alias' as a filter option

0.5.3 (24/05/2016)

*   Code quality improvements
*   Updated ListSubscriptions to retrieve subscription status

0.5.4 (13/06/2016)

*   Added GetSubscription functionality (thanks [ghermans](https://github.com/ghermans))

0.5.5 (15/08/2016)

*   Added GetTraffic functionality (thanks [texh](https://github.com/texh))
*   Added ListDNS functionality (thanks [carlswart](https://github.com/carlswart))

0.5.6 (01/09/2016)

*   Error handling bug fix
*   Exposed service plan guid property (thanks [ghermans](https://github.com/ghermans))
  

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
