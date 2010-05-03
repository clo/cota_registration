#########################################
# PHPLiveX Library			#
# (C) Copyright 2006 Arda Beyazoğlu	#
# Version: 2.4				#
# Home Page: www.phplivex.com		#
# Contact: ardabeyazoglu@gmail.com	#
# Release Date: 10.09.2007		#
# License: LGPL				#
#########################################

PHPLiveX 2.4
------------

 This version has new features which make its usage easier and more professional so that it is possible to use ajax with more complex and professional algorithms easily.  Now every type of data can be transmitted between PHP and Java Script each other as PHP-JS interaction is very important. For more effective coding with practical features of php, this version is written considering PHP 5. So, this version does not work with PHP 4.

    - Version Changes:

    * Character encoding bugs are fixed by adding "Unicode" and "Ansi" options. Default is "Unicode" (utf-8).
    * Fixed bug occured when exporting a static class method.
    * Fixed bug occured when using "GET" method because of caching the old request.
    * With "targetProperty" parameter, you can indicate an attribute of the target dom element to be manipulated (e.g. Style, label, id, name...). Default is "value" for input elements and "innerHTML" for the others.
    * With "preloadStyle" parameter, you can indicate a style attribute to be used for preloading (visibility or display). Default is "display".
    * For "target" and "preload" parameters, an instance of a dom element object can be passed as value in addition to "id" attribute.
    * The argument including phplivex parameters must be a java script object now. Each parameter is a property of this object. (e.g. {target: "mydiv", preload: "mySpan"})
    * The objects and arrays returned from php functions are converted to java script object to be used in any callback function. Also, the objects and arrays passed to the js function are converted to php arrays and passed to its php reflection. Note that this conversions need json support that's PHP 5.2 or higher.
    * With "ExternalJS" property, the js codes are included to page by an external file so that you don't see constant js codes in the source.
    * Run method must be called outside the script tags now.
    * With the help of "GetFunctions" method, nested ajax requests can be used now. It is recommended to see the documentation for example usage.
    * With "ExternalCall" method and "url" parameter, an ajax request can be sent to an external file.
    * With "SubmitForm" method, forms can be sent by using ajax requests.

  Some methods and properties were deprecated with this version.  Please see the documentation for changes!

### Please inform me (ardabeyazoglu@gmail.com) if you catch a bug or request a new feature.