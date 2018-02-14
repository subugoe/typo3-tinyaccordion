.. include:: Images.txt

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


Updating to ver. 1.0.0 or 2.0.0
-------------------------------

If you are updating from version 0.1.0 to version 1.0.0 or higher you
have to do something. The extension was rewritten in Extbase and uses
a new folder-struction.

First: you must include the static TypoScript again. See chapter “User
manual”.

Second: You must select the plugin again. See image:

|img-9|

*Abbildung 8: Select the plugin again...*

From version 2.1.1 up, you don't need to select the plugin again. You
can configure the extension in the “Extension manager” to use the old
extension key.

If you are using your own CSS-file, you must include it now by your
own. See Chapter Configuration/Reference.

If you are using your own JavaScript-file, you must include it now by
your own too. See Chapter Configuration/Reference.

If you are using your own HTML-template-file: you find all new
default-templates in this folder::

    typo3conf/ext/tinyaccordion/Resources/Private/Templates/Selection/

This are the points you must change:

#. You can specify the folder with the template(s) like this::

    plugin.tx_tinyaccordion.view.templateRootPath = fileadmin/templates/tinyaccordion/

#. In the specified folder must be a folder named “Selection”.

#. Copy your template-file into the new folder (e.g.
   fileadmin/templates/tinyaccordion/Selection). Name it Content.html if
   you use tt\_content-elements or News.html if you use tt\_news-
   elements.

#. You have to wrap the content of the HTML-file with this
   lines::

   <f:layoutname="Default"/>
   <f:sectionname="main">your old HTML-content</f:section>

#. You can take a look at the default-templates. Path: see above.

If you are updating from 1.x to 2.x you may need to save your plugins-
settings again or you may need to save one TypoScript-setting.
