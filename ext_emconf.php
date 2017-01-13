<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Download PDF of a single news',
    'description' => 'By using wkhtmltopdf a PDF can be created out of a news record',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => true,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'news' => '3.2.0-8.9.99',
            'typo3' => '7.6.0-8.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
