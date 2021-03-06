<?php

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 */
class ext_update
{

    /**
     * Array of flash messages (params) array[][status,title,message]
     *
     * @var array
     */
    protected $messageArray = [];

    private function getDatabaseConnection(): \TYPO3\CMS\Core\Database\DatabaseConnection
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * Main update function called by the extension manager.
     *
     * @return string
     */
    public function main()
    {
        $this->processUpdates();
        return $this->generateOutput();
    }

    /**
     * Called by the extension manager to determine if the update menu entry should by showed.
     *
     * @return bool
     */
    public function access()
    {
        return true;
    }

    /**
     * The actual update function. Add your update task in here.
     */
    protected function processUpdates()
    {
        $title = 'Update tt_content (plug-in list type).';
        $update = ['list_type' => 'tinyaccordion_pi1'];
        $this->getDatabaseConnection()->exec_UPDATEquery(
            'tt_content',
            "(list_type='tinyaccordion_tinyaccordion' OR list_type='tinyaccordion_Pi1')",
            $update
        );
        $this->messageArray[] = [FlashMessage::OK, $title, 'tt_content has been updated!'];

        $title = 'Update sys_template (path to the TypoScript files).';
        $this->getDatabaseConnection()->sql_query(
            "UPDATE sys_template SET include_static_file = replace(include_static_file, 'tinyaccordion/Configuration/TypoScript7', 'tinyaccordion/Configuration/TypoScript') WHERE include_static_file!=''"
        );
        $this->messageArray[] = [FlashMessage::OK, $title, 'sys_template has been updated!'];

        $title = 'Update sys_template (path to the HTML templates).';
        $this->getDatabaseConnection()->sql_query(
            "UPDATE sys_template SET config = replace(config, 'plugin.tx_tinyaccordion.view.partialRootPath =', 'plugin.tx_tinyaccordion.view.partialRootPaths.1 =') WHERE 1=1"
        );
        $this->getDatabaseConnection()->sql_query(
            "UPDATE sys_template SET config = replace(config, 'plugin.tx_tinyaccordion.view.templateRootPath =', 'plugin.tx_tinyaccordion.view.templateRootPaths.1 =') WHERE 1=1"
        );
        $this->getDatabaseConnection()->sql_query(
            "UPDATE sys_template SET config = replace(config, 'plugin.tx_tinyaccordion.view.layoutRootPath =', 'plugin.tx_tinyaccordion.view.layoutRootPaths.1 =') WHERE 1=1"
        );
        $this->messageArray[] = [FlashMessage::OK, $title, 'sys_template has been updated!'];
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput(): string
    {
        $out = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\Renderer\BootstrapRenderer::class);

        $messages = [];

        foreach ($this->messageArray as $messageItem) {
            /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                $messageItem[2],
                $messageItem[1],
                $messageItem[0]
            );
            $messages[] = $flashMessage;
        }
        return $out->render($messages);
    }
}
