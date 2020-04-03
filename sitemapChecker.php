<?php
/**
 * Sitemap checker
 * It prints bad links
 */

$host = 'https://medsi.ru';
$sitemap = '/sitemaps/sitemap.xml';
$output = '/Users/vashe/web/medsi/index/medsi/_tmp/links.txt';

$start = microtime(true);

function getLoc($url, $cut = 0) {
    $xml = simplexml_load_file($url);
    $result = [];
    foreach ($xml as $el) {
        $str = substr($el->loc->__toString(), $cut);
        $result[$str] = true;
    }
    return $result;
}

$count = strlen($host);
$sitemapCollection = getLoc($host . $sitemap, $count);

$links = [];
foreach ($sitemapCollection as $smap => $bool) {
    echo "load: ${smap}\n";
    $res = getLoc($host . $smap, $count);
    $links = $links + $res;
}

$totalLinks = count($links);

echo "total links: ${totalLinks}\n";

echo "curl init\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$fp = fopen($output, 'w');
foreach ($links as $link => $bool) {
    echo sprintf("%s : %s\n", $totalLinks, $link);
    $totalLinks--;
    curl_setopt($ch, CURLOPT_URL, $host . $link);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode > 400) {
        fwrite($fp, sprintf("%s | %s\n", $httpCode, $host . $link));
    }
}

curl_close($ch);

fclose($fp);

$end = microtime(true);

echo "Job done\n\n";

echo sprintf("time min: %.0f\n", ($end - $start) / 60);
