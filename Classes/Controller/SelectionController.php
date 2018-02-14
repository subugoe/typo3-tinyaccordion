<?php
namespace Quizpalme\Tinyaccordion\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Kurt Gusbeth <info@myquizandpoll.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * TinyAccordion: Ausgabe der Dokumenten-Auswahl als Accordion
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SelectionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var Content Object
     */
    protected $cObj;

    /**
     * Parse a content element
     *
     * @param	int			UID of any content element
     * @return 	string		Parsed Content Element
     */
    public function myRender($table, $uid)
    {
        $conf = [
            'tables' => $table,
            'source' => $uid,
            'dontCheckPid' => 1
        ];
        //return $this->cObj->RECORDS($conf);
        return $this->cObj->cObjGetSingle('RECORDS', $conf);
    }

    /**
     * get the PID(s)
     *
     * @return string
     */
    public function getPidAndInit()
    {
        $this->cObj = $this->configurationManager->getContentObject();

        if (!($this->cObj->data['pages'] == '')) {
            $pid = addslashes($this->cObj->data['pages']);
        } else {
            // Unter-Ordner mit Dokumenten finden
            $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'uid',
                    'pages',
                    'pid=' . intval($GLOBALS['TSFE']->id) . ' AND doktype=254 ' . $this->cObj->enableFields('pages'),
                    '',
                    'sorting',
                    '1'
            );
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5);
                $pid = intval($row['uid']);
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res5);
        }
        if (!$pid) {
            $pid = intval($GLOBALS['TSFE']->id);
        }

        /*	JS-File will be included via TypoScript and page
            if ($this->settings['jsFile']) {
                $datei = str_replace('EXT:tinyaccordion/', t3lib_extMgm::siteRelPath('tinyaccordion'), $this->settings['jsFile']);
                $GLOBALS['TSFE']->additionalHeaderData['tinyaccordion'] = '<script language="JavaScript" type="text/javascript" src="'.$datei.'"></script>';
            } */

        return $pid;
    }

    /**
     * action content
     */
    public function contentAction()
    {
        $pids = $this->getPidAndInit();
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        $renderEverything = ($this->settings['flexform']['renderEverything']=='1') ? true : false;
        $colPos = intval($this->settings['flexform']['colPos']);
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }
        if ($colPos == -1) {
            $whereColPos = '';
        } else {
            $whereColPos = ' AND colPos=' . $colPos;
        }
        if ($renderEverything) {
            $whereCType = '';
        } else {
            $whereCType = " AND (CType='text' OR CType='textpic' OR CType='textmedia')";
        }

        // Dokumente holen
        foreach ($pidsForeach as $pid) {
            if ($mode) {
                $wherePid = 'pid=' . intval($pid);
            } else {
                $wherePid = 'pid IN (' . $pid . ')';
            }
            //
            $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(

                'pid, uid, header, tstamp',
                    'tt_content',
                    $wherePid . $whereColPos . $whereCType .
                        ' AND sys_language_uid=' . intval($GLOBALS['TSFE']->sys_language_uid) . $this->cObj->enableFields('tt_content'),
                    '',
                    'sorting ' . $order

            );
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                    $dataArray[$row['uid']] = [];
                    $dataArray[$row['uid']]['pid'] = $row['pid'];
                    $dataArray[$row['uid']]['header'] = $row['header'];
                    $dataArray[$row['uid']]['datetime'] = $row['tstamp'];
                    $noData = false;
                }
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res5);
        }

        // Dokumente rendern
        if (count($dataArray)>0) {
            foreach ($dataArray as $uid => $value) {
                $dataArray[$uid]['bodytext'] = $this->myRender('tt_content', $uid);
            }
        }

        $this->view->assign('elements', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
    }

    /**
     * action tt_content + UI
     */
    public function content_ui_accordionAction()
    {
        $this->contentAction();
    }

    /**
     * action pages
     */
    public function pagesAction()
    {
        $pids = $this->getPidAndInit();
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        $renderEverything = ($this->settings['flexform']['renderEverything']=='1') ? true : false;
        $colPos = intval($this->settings['flexform']['colPos']);
        $select = ($this->settings['flexform']['select']=='pid') ? 'pid' : 'uid';
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }
        if ($colPos == -1) {
            $whereColPos = '';
        } else {
            $whereColPos = ' AND colPos=' . $colPos;
        }
        if ($renderEverything) {
            $whereCType = '';
        } else {
            $whereCType = " AND (CType='text' OR CType='textpic' OR CType='textmedia')";
        }
        $uids = '';
        $foundPids = [];

        // Ordner holen
        foreach ($pidsForeach as $pid) {
            if ($mode) {
                $wherePid = '=' . intval($pid);
            } else {
                $wherePid = ' IN (' . $pid . ')';
            }
            $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'pid, uid, title, subtitle, abstract, description',
                    'pages',
                    $select . $wherePid . ' AND doktype=1' . $this->cObj->enableFields('pages'),
                    '',
                    'sorting ' . $order
            );
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                    $uid = $row['uid'];
                    $uids .= ($uids) ? ',' . $uid : $uid;
                    $foundPids[] = $uid;
                    $dataArray[$uid] = [];
                    $dataArray[$uid]['pid'] = $row['pid'];
                    $dataArray[$uid]['title'] = $row['title'];
                    $dataArray[$uid]['subtitle'] = $row['subtitle'];
                    $dataArray[$uid]['abstract'] = $row['abstract'];
                    $dataArray[$uid]['description'] = $row['description'];
                    $dataArray[$uid]['elements'] = [];
                    $noData = false;
                }
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res5);
        }

        if (intval($GLOBALS['TSFE']->sys_language_uid) > 0) {
            // andere Sprache?
            foreach ($pids as $pid) {
                $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'pid, title, subtitle, abstract, description',
                        'pages_language_overlay',
                        'pid IN (' . $uids . ')'
                );
                if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                        $dataArray[$row['pid']]['title'] = $row['title'];
                        $dataArray[$row['pid']]['subtitle'] = $row['subtitle'];
                        $dataArray[$row['pid']]['abstract'] = $row['abstract'];
                        $dataArray[$row['pid']]['description'] = $row['description'];
                    }
                }
                $GLOBALS['TYPO3_DB']->sql_free_result($res5);
            }
        }

        // Dokumente aus den Ordnern holen
        $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'pid, uid, header, tstamp',
                'tt_content',
                'pid IN (' . $uids . ')' . $whereCType . $whereColPos .
                ' AND sys_language_uid=' . intval($GLOBALS['TSFE']->sys_language_uid) . $this->cObj->enableFields('tt_content'),
                '',
                'sorting ' . $order
        );
        if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                $uid = $row['uid'];
                $pid = $row['pid'];
                $dataArray[$pid]['elements'][$uid] = [];
                $dataArray[$pid]['elements'][$uid]['pid'] = $pid;
                $dataArray[$pid]['elements'][$uid]['header'] = $row['header'];
                $dataArray[$pid]['elements'][$uid]['datetime'] = $row['tstamp'];
                $noData = false;
            }
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res5);

        // Dokumente rendern
        if (count($dataArray)>0) {
            foreach ($foundPids as $pid) {
                foreach ($dataArray[$pid]['elements'] as $uid => $value) {
                    $dataArray[$pid]['elements'][$uid]['bodytext'] = $this->myRender('tt_content', $uid);
                }
            }
        }

        $this->view->assign('pages', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
    }

    /**
     * action pages + UI
     */
    public function pages_ui_accordionAction()
    {
        $this->pagesAction();
    }

    /**
     * action tt_news
     */
    public function newsAction()
    {
        $pids = $this->getPidAndInit();
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $childs = 0;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }

        foreach ($pidsForeach as $pid) {
            if ($mode) {
                $wherePid = 'pid=' . intval($pid);
            } else {
                $wherePid = 'pid IN (' . $pid . ')';
            }
            // News+Kategorien holen
            $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tt_news.uid AS newsid, tt_news.title AS newstitle, tt_news.datetime AS newsdate, cat.uid AS catid, cat.title AS cattitle',
                    'tt_news, tt_news_cat AS cat, tt_news_cat_mm AS mm',
                    'tt_news.' . $wherePid . ' AND mm.uid_local=tt_news.uid AND mm.uid_foreign=cat.uid' .
                        ' AND tt_news.sys_language_uid=' . intval($GLOBALS['TSFE']->sys_language_uid) . $this->cObj->enableFields('tt_news'),
                    '',
                    'cattitle ' . $order . ', tt_news.datetime ' . $order
            );
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                    if (!is_array($dataArray[$row['catid']])) {
                        $dataArray[$row['catid']] = [];
                        $dataArray[$row['catid']]['news'] = [];
                    }
                    $dataArray[$row['catid']]['header'] = $row['cattitle'];
                    $dataArray[$row['catid']]['news'][$row['newsid']] = [];
                    $dataArray[$row['catid']]['news'][$row['newsid']]['header'] = $row['newstitle'];
                    $dataArray[$row['catid']]['news'][$row['newsid']]['datetime'] = $row['newsdate'];
                    $noData = false;
                }
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res5);
        }

        // Dokumente rendern
        if (count($dataArray)>0) {
            foreach ($dataArray as $uid => $value) {
                $childs++;
                foreach ($dataArray[$uid]['news'] as $newsid => $newsvalue) {
                    $dataArray[$uid]['news'][$newsid]['bodytext'] = $this->myRender('tt_news', $newsid);
                }
            }
        }

        $this->view->assign('elements', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('childs', $childs);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
    }

    /**
     * action tt_news + UI
     */
    public function news_ui_accordionAction()
    {
        $this->newsAction();
    }

    /**
     * action news
     */
    public function camaligaAction()
    {
        $pids = $this->getPidAndInit();
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $childs = 0;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }

        foreach ($pidsForeach as $pid) {
            if ($mode) {
                $wherePid = 'pid=' . intval($pid);
            } else {
                $wherePid = 'pid IN (' . $pid . ')';
            }
            // News+Kategorien holen
            $res5 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'tx_camaliga_domain_model_content.uid AS camid, tx_camaliga_domain_model_content.title AS camtitle, tx_camaliga_domain_model_content.shortdesc, tx_camaliga_domain_model_content.longdesc, tx_camaliga_domain_model_content.link, cat.uid AS catid, cat.title AS cattitle',
                    'tx_camaliga_domain_model_content, sys_category AS cat, sys_category_record_mm AS mm',
                    'tx_camaliga_domain_model_content.' . $wherePid . ' AND mm.uid_local=cat.uid AND mm.uid_foreign=tx_camaliga_domain_model_content.uid' .
                        ' AND tx_camaliga_domain_model_content.sys_language_uid=' . intval($GLOBALS['TSFE']->sys_language_uid) . $this->cObj->enableFields('tx_camaliga_domain_model_content'),
                    '',
                    'tx_camaliga_domain_model_content.sorting ' . $order
            );
            if ($GLOBALS['TYPO3_DB']->sql_num_rows($res5)>0) {
                while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res5)) {
                    if (!is_array($dataArray[$row['catid']])) {
                        $dataArray[$row['catid']] = [];
                        $dataArray[$row['catid']]['camaliga'] = [];
                    }
                    $camid = $row['camid'];
                    $dataArray[$row['catid']]['header'] = $row['cattitle'];
                    $dataArray[$row['catid']]['camaliga'][$camid] = [];
                    $dataArray[$row['catid']]['camaliga'][$camid]['header'] = $row['camtitle'];
                    $dataArray[$row['catid']]['camaliga'][$camid]['shortdesc'] = $row['shortdesc'];
                    $dataArray[$row['catid']]['camaliga'][$camid]['longdesc'] = $row['longdesc'];
                    $dataArray[$row['catid']]['camaliga'][$camid]['link'] = $row['link'];
                    $noData = false;
                }
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res5);
        }

        $this->view->assign('elements', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('childs', $childs);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
    }

    /**
     * action Camaliga + UI
     */
    public function camaliga_ui_accordionAction()
    {
        $this->camaligaAction();
    }
}
