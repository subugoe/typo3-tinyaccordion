<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'JavaScript Accordion based on TinyAccordion.',
    'description' => 'An easy to use extension to display pages, tt_content, tt_news or Camaliga elements as an Accordion. Runs with TinyAccordion or jQuery UI-Accordion.',
    'category' => 'plugin',
    'version' => '4.0.0',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => false,
    'author' => 'Kurt Gusbeth',
    'author_email' => 'info@quizpalme.de',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
            'php' => '7.0.0-7.2.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
