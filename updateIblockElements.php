<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);
$start = microtime(true);
\Bitrix\Main\Loader::includeModule('iblock');

$arFilter = [
    'IBLOCK_ID' => 1,
    '!PROPERTY_CATEGORY_VALUE' => false
];
$arSelect = [
    'ID', 'IBLOCK_ID', 'TAGS', 'PROPERTY_CATEGORY'
];
$dbres = CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, false, $arSelect);

$result = [];
$ciblock = new CIBlockElement();
while ($row = $dbres->GetNext()) {
    if (empty($row['TAGS'])) {
        $arUpdate['TAGS'] = $row['PROPERTY_CATEGORY_VALUE'];
    } else {
        $tags = explode(',', $row['TAGS']);
        $tags[] = $row['PROPERTY_CATEGORY_VALUE'];
        $tags = array_unique($tags);
        foreach ($tags as &$tag) {
            $tag = trim($tag);
        }
        $arUpdate['TAGS'] = $tags;
    }

    $result[$row['ID']] = [
        'id' => $row['ID'],
        'status' => $ciblock->Update($row['ID'], $arUpdate, false, false),
        'tags' => $arUpdate['TAGS'],
        'oldtags' => $row['TAGS']
    ];

}

foreach ($result as $item) {
	$ciblock->UpdateSearch($item['id'], true);
}

$path = $_SERVER['DOCUMENT_ROOT'] . '/upload/tags.csv';
$h = fopen($path, 'w');
fputcsv($h, ['id', 'status', 'tags', 'oldtags'], ';');
foreach ($result as $item) {
    fputcsv($h, $item, ';');
}
fclose($h);

$end = microtime(true);
$duration = $end - $start;
echo sprintf("Execution time: %.2f\r\n%s", $duration, $_SERVER['HTTP_HOST'] . '/upload/tags.csv');
