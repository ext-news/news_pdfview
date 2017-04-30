<?php

namespace GeorgRinger\NewsPdfview\Service;

use GeorgRinger\NewsPdfview\Domain\Model\Dto\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class PdfService
{

    /** @var ExtensionConfiguration */
    protected $configuration;

    public function __construct()
    {
        $this->configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    public function run($identifier, $html)
    {
        $pdfPath = $this->createPdf($identifier, $html);
        return $pdfPath;
    }


    protected function createPdf($identifier, $html)
    {
        $tempName = $this->getUniquePath($identifier, $html);
        $pdfName = str_replace('.html', '.pdf', $tempName);

        if (file_exists($pdfName)) {
            return $pdfName;
        }

        if (!file_exists($tempName)) {
            GeneralUtility::writeFile($tempName, $html, true);
        }
        $url = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . str_replace(GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/', '', $tempName);

        $binary = $this->configuration->getWkhtmltopdf();
        if (empty($binary)) {
            throw new \RuntimeException('The wkhtmltopdf binary not set', 1473099350);
        }

        if ($this->externalPdfServiceMustBeUsed($binary)) {
            $remotePdfContent = GeneralUtility::getUrl($binary . '?url=' . rawurlencode($url));
            if ($remotePdfContent) {
                GeneralUtility::writeFile($pdfName, $remotePdfContent, true);
            }
        } else {
            $commandParts = [
                $binary,
                escapeshellarg($url),
                escapeshellarg($pdfName)
            ];
            shell_exec(implode(' ', $commandParts));
        }

        if (!is_file($pdfName)) {
            throw new \RuntimeException('Pdf file could not be created', 1473099547);
        }

        return $pdfName;
    }


    protected function getUniquePath($identifier, $html)
    {
        $path = PATH_site . 'uploads/tx_newspdfview/news_' . $identifier . '_' . GeneralUtility::hmac($html, 'news_pdf') . '.html';
        return $path;
    }

    /**
     * @return bool
     */
    protected function externalPdfServiceMustBeUsed($binary)
    {
        return (StringUtility::beginsWith($binary, 'http://') || StringUtility::beginsWith($binary, 'https://'));
    }
}
