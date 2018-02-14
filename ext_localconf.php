<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Quizpalme.Tinyaccordion',
            'Pi1',
            [
                'Selection' => 'content, content_ui_accordion, news, news_ui_accordion, camaliga, camaliga_ui_accordion, page, page_ui_accordion'
            ],
            [
                'Selection' => ''
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					pi1 {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey) . 'Resources/Public/Icons/ce_wiz.gif
						title = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_title
						description = LLL:EXT:tinyaccordion/Resources/Private/Language/locallang_be.xml:tinyaccordion_plus_wiz_description
						tt_content_defValues {
							CType = list
							list_type = tinyaccordion_pi1
						}
					}
				}
				show = *
			}
	   }'
    );
    },
    $_EXTKEY
);
