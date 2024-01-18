<?php

require __DIR__.'/vendor/autoload.php';

use HeadlessChromium\BrowserFactory;


// create browser
$browser = BrowserFactory::connectToBrowser("http://172.30.128.2:9222", [ 'debugLogger'     => 'php://stdout' ]);

$page = $browser->createPage();
$page->navigate('https://www.google.com/')->waitForNavigation();

$pdf = $page->pdf();

/////////// METHOD 1: Generating PDF and send it as attachment

if (file_exists('/tmp/export.pdf')) {
    unlink('/tmp/export.pdf');
}

// save the pdf
$pdf->saveToFile('/tmp/export.pdf');

$page->close(); // <--- It seems to be the culprit

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="export.pdf"');

$file = fopen('/tmp/export.pdf', 'r');
while (!feof($file)) {
  $buffer = fread($file, 4096);
  echo $buffer;
}
fclose($file);


//////////////// METHOD 2:  Rendering PDF

// $output = base64_decode($pdf->getBase64());

// $browser->close(); // <--- It seems to be the culprit

// header("Content-Type: application/pdf");
// header("Content-Disposition: inline; filename=\"pdf.pdf\"");

// echo $output;