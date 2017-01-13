<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->singlePdf;News->singlePdfError']
    = 'Single PDF';

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Controller/NewsController'][] = 'news_pdfview';