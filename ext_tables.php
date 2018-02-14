<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Quizpalme.Tinyaccordion',
			'Pi1',
			'TinyAccordion'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'JavaScript Accordion');
    },
    $_EXTKEY
);



// Include flex forms
$pluginSignature = 'tinyaccordion_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tinyaccordion/Configuration/FlexForms/flexform_pi1.xml');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key';
