TYPO3 Extension `news_pdfview`
==============================

This extensions makes it possible to render a PDF of a single news item by using wkhtmltopdf.

Requirements
------------

- TYPO3 7.6 LTS
- EXT:news 3.2.0+
- [wkhmltopdf](http://wkhtmltopdf.org/) binary

Usage
-----

1) After installation, you need to set the absolute path to the wkhtmltopdf binary in the Extension Manager.
2) Create a new page and add a news plugin and select plugin `Single PDF`.
3) To get a PDF, link to this page as you would link to a detail view.
4) As a new action has been added, you can copy `Templates/News/Detail.html` to `Templates/News/SinglePdf.html` which is used for creating the PDF.