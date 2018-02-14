

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


ChangeLog
---------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Version
         0.1.0

   Changes
         Initial upload.


.. container:: table-row

   Version
         1.0.0

   Changes
         New rewritten in Extbase. See chapter Updating! You must configure
         everything new!


.. container:: table-row

   Version
         1.1.0

   Changes
         Uid of the content element added to the UL-ID. Code cleaning.


.. container:: table-row

   Version
         1.1.2

   Changes
         Titles of content elements are now hidden by default.


.. container:: table-row

   Version
         2.0.0

   Changes
         FlexForms and templates extended. Maybe you need to save your plugin-
         settings again if you use the templates from typo3conf...


.. container:: table-row

   Version
         2.1.1

   Changes
         The extension can be configured to use the extension key from version
         0.1.0.


.. container:: table-row

   Version
         2.2.0

   Changes
         New documentation format.

         The UI Accordion template can be selected directly without moving the file to fileadmin.

         Camaliga-elements can be used now too.


.. container:: table-row

   Version
         2.2.1

   Changes
         HTML5 problem solved.


.. container:: table-row

   Version
         3.0.0

   Changes
         Moved to namespaces. Now compatible to TYPO3 7. Note: you need to change the TypoScript in TYPO3 7.6.
         See chapter Configuration.

.. container:: table-row

   Version
         3.0.1

   Changes
         TYPO3 7 related bugfix.

.. container:: table-row

   Version
         3.0.2

   Changes
         TypoScript for TYPO3 7 added.

         Bugfix: {uid} was empty in TYPO3 7.

.. container:: table-row

   Version
         3.0.3

   Changes
         sys_language_uid will now not be ignored.

.. container:: table-row

   Version
         3.0.5

   Changes
         Actions pages, pages_ui_accordion and content_ui_accordion added.

         Sorting option added: first sort by pid (Record Storage Page).

         New variables: {pids} and {element.pid}.

.. container:: table-row

   Version
         4.0.0

   Changes
         Redesigned for TYPO3 7. TYPO3 6 compatibilty removed. You need to run the update script in the extension manager after installing.

         Extension manager configuration and TypoScript for TYPO3 6 removed. The extension key changed.

         New templates: Pages and templates for UI accordion.

         Several new FlexForm settings.

.. ###### END~OF~TABLE ######
