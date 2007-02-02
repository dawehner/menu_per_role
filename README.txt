$Id$

Menu per Role Module
------------------------
by Wolfgang Ziegler, nuppla@zites.net


Description
-----------
This module allows you to restrict access of menu items per roles. It depends on the
drupal core menu.module - just activate both modules and edit a menu item as usual.
There will be a new fieldset that allows you to restrict access by role.


Installation
------------
Unfortunately you need to patch the drupal file includes/menu.inc. Use the the patch
provided with the module. If you don't know how to patch, you can just insert the three
additional lines manually - but remove the leading plus.

Then copy the module's whole directory in your drupal modules directory and activate it.


How to use it
-------------
Just activate the menu and the menu_per_role modules and edit a menu item as usual at
/admin/build/menu. There will be a fieldset that allows you to restrict access by role.

If you don't check any roles the default access permissions will be kept. Otherwise the
module will additionally restrict access to the chosen user roles.