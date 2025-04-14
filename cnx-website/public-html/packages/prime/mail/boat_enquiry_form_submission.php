<?php
defined('C5_EXECUTE') or die("Access Denied.");

$submittedData = '';
foreach ($questionAnswerPairs as $questionAnswerPair) {
    $submittedData .= $questionAnswerPair['label'] . ":\r\n" . $questionAnswerPair['value'] . "\r\n" . "\r\n";
}
$body = t("
There has been a submission of the form Boat Enquiry through your concrete5 website.

%s

", $submittedData);
