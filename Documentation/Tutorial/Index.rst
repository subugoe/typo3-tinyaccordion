

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


Tutorial
--------

You should think first about the layout of the accordion.
If you use tt\_content elements, only the header and the bodytext will be displayed.
If you use pages and tt\_content elements, then the page title will be displayed first.
After clicking at the page title, you will see the content elements of that page.
If you use tt\_news, the categories are displayed first.
After clicking at a category, all news-titles are shown. Than you can click at a news-title.

Put your content elements to a folder and select that folder in the plugin.

You can change the layout of the result. See chapter “Administration”.