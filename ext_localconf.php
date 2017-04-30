<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->singlePdf;News->singlePdfError']
    = 'Single PDF';

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news_memorize')) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->memorizeListPdf;News->memorizeListPdfError']
        = 'PDF of Cart';
}

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Controller/NewsController'][] = 'news_pdfview';
