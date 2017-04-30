<?php

namespace GeorgRinger\NewsPdfview\Controller;

class NewsController extends \GeorgRinger\News\Controller\NewsController {

    public function memorizeListPdfAction()
    {
        try {
            $user = $GLOBALS['TSFE']->fe_user;

            if ((int)$user->user['uid'] === 0) {
                throw new \RuntimeException('no user', 1493571480);
            }

            $cartList = $user->getKey('user', 'news_memorize');
            $cartList = json_decode($cartList, true);
            if (empty($cartList)) {
                throw new \RuntimeException('cart empty', 1493571481);
            }
            $list = [];
            foreach($cartList as $id) {
                $newsItem = $this->newsRepository->findByIdentifier((int)$id);
                if ($newsItem) {
                    $list[] = $newsItem;
                }
            }

            $this->view->assignMultiple([
                'news' => $list
            ]);
            $listIdentifier = md5(implode('__', $cartList));
            $name = 'List.pdf';

            $html = $this->view->render();
            $pdfService = $this->objectManager->get(\GeorgRinger\NewsPdfview\Service\PdfService::class);
            $pdfFile = $pdfService->run($listIdentifier, $html);
            header('Content-Type: application/pdf');
            header('Content-disposition: attachment;filename=' . $name);
            readfile($pdfFile);
            exit;
        } catch (\Exception $e) {
            $this->forward('singlePdfError', null, null, ['error' => $e->getCode()]);
        }
    }

    /**
     * @param int $error
     */
    public function memorizeListPdfErrorAction($error)
    {
        $this->view->assignMultiple([
            'errorCode' => $error
        ]);
    }

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
