Menu per Role Module
------------------------
by Wolfgang Ziegler, nuppla@zites.net

Modified for D6 by
jrowny, jrowng@gmail.com on 12/5/2008
hutch
AlexisWilke



Description
-----------
This module allows you to restrict access of menu items per roles. It depends on the
drupal core menu.module - just activate both modules and edit a menu item as usual.
There will be a new fieldset that allows you to restrict access by role.

<DRUPAL 5>
Installation
------------
Unfortunately you need to patch the drupal file includes/menu.inc. Use the the patch
provided with the module. If you don't know how to patch, you can just insert the three
additional lines manually - but remove the leading plus.

Then copy the module's whole directory in your drupal modules directory and activate it.

<DRUPAL 6>
Installation
------------
Unfortunately you need to apply to patches to the drupal core files.

Note: The 6.6 patches will work as is in 6.7.

o Patch drupal-menu-submit-6.6.patch

  Fix a problem with the menu/add feature (that does not otherwise give
  you the mlid and thus renders the submit function totally useless.)

  See http://drupal.org/node/326210 for more information

o Patch drupal-6.6-menu_per_role.patch

  Fix a problem with the menu access feature. There is no hook for that
  purpose so we actually add our own code to get called. Adding a hook
  would certainly be a lot better, but at this time we do a direct call
  to the menu per role module (that's a lot faster anyway.)

It is smart to patch the menu files before installing the Menu per Role
so it works immediately. Although you can patch at any time. It just
won't work properly until then.

How to use it
-------------
Just activate the menu and the menu_per_role modules and edit a menu item as usual at
/admin/build/menu. There will be a fieldset that allows you to restrict access by role.

If you don't check any roles the default access permissions will be kept. Otherwise the
module will additionally restrict access to the chosen user roles.
