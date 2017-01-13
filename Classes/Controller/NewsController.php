<?php

namespace GeorgRinger\NewsPdfview\Controller;

class NewsController extends \GeorgRinger\News\Controller\NewsController
{

    public function singlePdfAction()
    {
        try {
            if (!$this->request->hasArgument('news')) {
                throw new \RuntimeException('No news given', 1484249265);
            }

            /** @var \GeorgRinger\News\Domain\Model\News $newsItem */
            $newsItem = $this->newsRepository->findByIdentifier($this->request->getArgument('news'));
            if (!$newsItem) {
                throw new \RuntimeException('No news found', 1484249266);
            }
            $newsId = $newsItem->getUid();

            $this->view->assignMultiple([
                'newsItem' => $newsItem
            ]);

            $html = $this->view->render();
            $pdfService = $this->objectManager->get(\GeorgRinger\NewsPdfview\Service\PdfService::class);
            $pdfFile = $pdfService->run($newsId, $html);
            header('Content-Type: application/pdf');
            header('Content-disposition: attachment;filename=' . $this->getDownloadNameOfNews($newsItem));
            readfile($pdfFile);
            exit;
        } catch (\Exception $e) {
            $this->forward('singlePdfError', null, null, ['error' => $e->getCode()]);
        }
    }

    /**
     * @param int $error
     */
    public function singlePdfErrorAction($error)
    {
        $this->view->assignMultiple([
            'errorCode' => $error
        ]);
    }

    protected function getDownloadNameOfNews($news)
    {
        /** @var \GeorgRinger\News\Domain\Model\News $news */
        $fileName = preg_replace("/[^[:alnum:][:space:]]/u", '', $news->getTitle());
        $fileName .= '.pdf';
        return $fileName;
    }

}