<?php

$threshold = (double) $argv[1];

$coverage = simplexml_load_file(__DIR__.'/../coverage.xml');
/* @phpstan-ignore-next-line code only used in pipeline */
$ratio = (double)(($coverage->project->metrics["coveredelements"]/$coverage->project->metrics["elements"])*100);

echo "Coverage: $ratio% of $threshold%\n";

if ($ratio < $threshold) {
    echo "Coverage under $threshold%!\n";
    exit(-1);
}

echo "Coverage above $threshold%!\n";
