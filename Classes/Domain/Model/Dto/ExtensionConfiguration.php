<?php
namespace GeorgRinger\NewsPdfview\Domain\Model\Dto;

class ExtensionConfiguration
{
    /** @var string */
    protected $wkhtmltopdf;

    public function __construct()
    {
        $configuration = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news_pdfview']);
        foreach ($configuration as $property => $value) {
            if (property_exists(__CLASS__, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getWkhtmltopdf()
    {
        return $this->wkhtmltopdf;
    }

}