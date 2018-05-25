<?php
/**
 *  Sberbank Yandex Feed Parser
 *  made by vashe
 *  www.dalee.ru
 */
class parseYandexFeed
{

	protected $xml;
	protected $xmlPath = 'http://www.sberbank.ru/sberbank_oib_filial.xml';
	protected $filename;
	protected $headers = [
		'Shop code',
		'Business name',
		'Address line 1',
		'Address line 2',
		'Address line 3',
		'Address line 4',
		'Address line 5',
		'Sub-locality',
		'Locality',
		'Administrative area',
		'Country',
		'Postcode',
		'Latitude',
		'Longitude',
		'Primary phone',
		'Additional phones',
		'Website',
		'Primary category',
		'Additional categories',
		'Sunday hours',
		'Monday hours',
		'Tuesday hours',
		'Wednesday hours',
		'Thursday hours',
		'Friday hours',
		'Saturday hours',
		'Special hours',
		'Infor from company',
		'Opening date',
		'Profile photo',
		'Logo',
		'Cover',
		'Other photos',
		'Main photo',
		'Tag',
		'Phone for AdWords',
	];

	public function __construct()
	{
		$this->loadXml();
		$this->exportCSV();
		$this->report();
	}

	protected function loadXml()
	{
		$this->xml = simplexml_load_file($this->xmlPath);
		if ($this->xml === false) {
			throw new Exception('Can\'t load XML source');
		}
	}

	protected function reFill($in)
	{
		$result = [];
		foreach (array_flip($this->headers) as $k => $v) {
			$result[$k] = $in[$k] ?? '';
		}
		return $result;
	}

	protected function exportCSV()
	{
		$this->filename = $this->setExportFilePath();
		$fp = fopen($this->filename, 'w');
		fputcsv($fp, $this->headers, ';');
		foreach ($this->xml->company as $company) {
			$whours = $this->parseWorkhours($company->{'working-time'});
			$fields = $this->reFill([
				'Shop code' => $company->{'company-id'}->__toString(),
				'Business name' => $company->{'name-ru'}->__toString(),
				'Address line 1' => $company->address->__toString(),
				'Locality' => $company->{'locality-name'}->__toString(),
				'Administrative area' => $company->{'admn-area'}->__toString(),
				'Country' => 'RU',
				'Postcode' => $company->{'post-index'}->__toString(),
				'Latitude' => $company->coordinates->lat->__toString(),
				'Longitude' => $company->coordinates->lon->__toString(),
				'Primary phone' => $company->phone[0]->number->__toString(),
				'Website' => $company->url->__toString(),
				'Primary category' => 'Банк',
				'Sunday hours' => $whours['вс'],
				'Monday hours' => $whours['пн'],
				'Tuesday hours' => $whours['вт'],
				'Wednesday hours' => $whours['ср'],
				'Thursday hours' => $whours['чт'],
				'Friday hours' => $whours['пт'],
				'Saturday hours' => $whours['сб'],
				'Infor from company' => 'Подразделение Сбербанка',
				'Phone for AdWords' => $company->phone[0]->number->__toString(),
			]);
			fputcsv($fp, $fields, ';');
		}
		fclose($fp);
	}

	protected function setExportFilePath($ext = 'csv')
	{
		return getcwd() . DIRECTORY_SEPARATOR . 'sberbank_oib_filial_for_google_import_' . date('Y-m-d') . '.' . $ext;
	}

	protected function parseWorkhours($str)
	{
		$str = $this->normalizeWorkhours($str);
		$pieces = explode('; ', $str);
		$week = ['пн' => '', 'вт' => '', 'ср' => '', 'чт' => '', 'пт' => '', 'сб' => '', 'вс' => ''];
		foreach ($pieces as $piece) {
			$dnt = explode(': ', $piece);
			$days = explode(', ', $dnt[0]);
			$times = explode(', ', $dnt[1]);
			foreach ($times as &$time) {
				$time = preg_replace_callback("/\d{2}:\d{2}/u", function($matches) {
					return date('h:iA', strtotime($matches[0]));
				}, $time);
			}

			foreach ($days as $d) {
				$week[$d] = implode(', ', $times);
			}
		}
		return $week;
	}

	protected function normalizeWorkhours($str)
	{
		$pattern = "/([а-я]{2})-([а-я]{2})/u";
		$line = preg_replace_callback($pattern, function($matches) {
			$dPos = ['пн' => 0,'вт' => 1,'ср' => 2,'чт' => 3,'пт' => 4,'сб' => 5,'вс' => 6];
			$days = ['пн','вт','ср','чт','пт','сб','вс'];
			$slice = array_slice($days, $dPos[$matches[1]], $dPos[$matches[2]] + 1);
			$string = implode(', ', $slice);
			return $string;
		}, $str);
		return $line;
	}

	protected function report()
	{
		echo "Done: " . $this->filename . "\n";
	}
	
}

new parseYandexFeed();