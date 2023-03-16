<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\Chart\Gridlines;
use PhpOffice\PhpPresentation\Shape\Chart\Series;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Bar;
use PhpOffice\PhpPresentation\Shape\Chart\Type\Line;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Outline;
use PhpOffice\PhpPresentation\Style\Shadow;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpPresentation\Shape\Drawing\File as DrawingFile;

class ReportService
{
	public $writers = ['PowerPoint2007' => 'pptx'];

	public function __construct()
	{
	}

	function write($phpPresentation, $filename, $writers)
	{
		if (!File::exists('public/reports/pptx')) {
			Storage::makeDirectory('public/reports/pptx');
		}

		foreach ($writers as $writer => $extension) {
			if (!is_null($extension)) {
				$xmlWriter = IOFactory::createWriter($phpPresentation, $writer);
				$xmlWriter->save(storage_path('app/public/reports/pptx') . "/{$filename}.{$extension}");
			} else {
				return false;
			}
		}

		return asset('storage/reports/pptx') . "/{$filename}.{$extension}";
	}

	function createTemplatedSlide(PhpPresentation $objPHPPresentation, $type = null)
	{
		switch ($type) {
			case 'cover':
				$backgroundLink = public_path() . '/reports/images/cover_bg.png';
				break;

			case 'menu':
				$backgroundLink = public_path() . '/reports/images/menu_bg.png';
				break;

			default:
				$backgroundLink = public_path() . '/reports/images/slide_bg.png';
				break;
		}

		$slide = $objPHPPresentation->createSlide();
		$oBkgImage = new Image();
		$oBkgImage->setPath($backgroundLink);
		$slide->setBackground($oBkgImage);
		return $slide;
	}

	public function createTopTitle($currentSlide, $title)
	{
		$currentSlide->setName($title);
		$shape = $currentSlide->createRichTextShape()
			->setOffsetX(convertInchToPixel(0.41))
			->setOffsetY(convertInchToPixel(0.39))
			->setWidth(convertInchToPixel(7.18))
			->setHeight(convertInchToPixel(0.33));
		$shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$textRun = $shape->createTextRun($title);
		$textRun->getFont()->setName('Yu Gothic')
			->setSize(16)
			->setBold(true)
			->setCharacterSpacing(-1)
			->setColor(new Color(Color::COLOR_BLUE));
	}

	public function createTableRow($shape, $value, $bold = true, $width = 0.67, $height = 0.42, $horizontal = Alignment::HORIZONTAL_CENTER, $vertical = Alignment::VERTICAL_CENTER)
	{
		$row = $shape->createRow();
		$row->setHeight(convertInchToPixel($height));
		$oCell = $row->nextCell();
		$oCell->setWidth(convertInchToPixel($width));

		$oCell->createTextRun($value)
			->getFont()
			->setBold(true)
			->setName('Yu Gothic')
			->setSize(7);
		$oCell->getActiveParagraph()->getAlignment()->setHorizontal($horizontal);
		$oCell->getActiveParagraph()->getAlignment()->setVertical($vertical);
		$oCell->getActiveParagraph()->getAlignment()->setMarginLeft(convertInchToPixel(0.04));

		return $row;
	}

	public function createTableCell($row, $value, $bold = false, $width = 0.67, $horizontal = Alignment::HORIZONTAL_RIGHT, $vertical = Alignment::VERTICAL_CENTER)
	{
		$oCell = $row->nextCell();
		$oCell->setWidth(convertInchToPixel($width));
		$oCell->createTextRun($value)
			->getFont()
			->setName('Yu Gothic')
			->setBold($bold)
			->setSize(7);
		$oCell->getActiveParagraph()->getAlignment()->setHorizontal($horizontal);
		$oCell->getActiveParagraph()->getAlignment()->setVertical($vertical);
		$oCell->getActiveParagraph()->getAlignment()->setMarginLeft(convertInchToPixel(0.04));
		$oCell->getActiveParagraph()->getAlignment()->setMarginRight(convertInchToPixel(0.04));

		return $oCell;
	}

	public function createShapeSeries($seriesData, $name, $color)
	{
		$series = new Series($name, $seriesData);
		$series->setShowSeriesName(false);
		$series->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($color));
		$series->getFont()->getColor()->setRGB('00FF00');
		$series->setShowValue(false);
		$series->setShowPercentage(false);

		return $series;
	}

	public function createBaseRichTextShape($currentSlide, $width, $height, $offsetX, $offsetY, $horizontal = Alignment::HORIZONTAL_LEFT)
	{
		$shape = $currentSlide->createRichTextShape()
			->setHeight(convertInchToPixel($height))
			->setWidth(convertInchToPixel($width))
			->setOffsetX(convertInchToPixel($offsetX))
			->setOffsetY(convertInchToPixel($offsetY));
		$shape->getActiveParagraph()->getAlignment()
			->setHorizontal($horizontal);
		
		return $shape;
	}

	public function createBaseTextRun($shape, $value, $size = 11, $bold = false, $color = Color::COLOR_WHITE, $font = 'Yu Gothic')
	{
		$textRun = $shape->createTextRun($value);
		$textRun->getFont()
			->setName($font)
			->setBold($bold)
			->setSize($size)
			->setColor(new Color($color));

		return $textRun;
	}

	public function generateIntroductionSlide(PhpPresentation $objPHPPresentation)
	{
		$objPHPPresentation->getDocumentProperties()->setCreator('PHPOffice')
			->setLastModifiedBy('PHPPresentation Team')
			->setTitle('Sample 01 Title')
			->setSubject('Sample 01 Subject')
			->setDescription('Sample 01 Description')
			->setKeywords('office 2007 openxml libreoffice odt php')
			->setCategory('Sample Category');

		$currentSlide = $objPHPPresentation->getActiveSlide();

		$oBkgImage = new Image();
		$oBkgImage->setPath(public_path() . '/reports/images/cover_bg.png');
		$currentSlide->setBackground($oBkgImage);

		// パフォーマンス分析レポート
		$shape = $currentSlide->createRichTextShape()
			->setHeight(convertInchToPixel(0.57))
			->setWidth(convertInchToPixel(4.71))
			->setOffsetX(convertInchToPixel(2.58))
			->setOffsetY(convertInchToPixel(2.25));
		$shape->getActiveParagraph()->getAlignment()
			->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$oFill = new Fill();
		$oFill->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('FF203864'));
		$shape->setFill($oFill);
		$textRun = $shape->createTextRun('パフォーマンス分析レポート');
		$textRun->getFont()
			->setName('Yu Gothic')
			->setBold(false)
			->setSize(18)
			->setColor(new Color(Color::COLOR_WHITE));

		// レポート出力期間
		$shape = $this->createBaseRichTextShape($currentSlide, 1.85, 0.33, 3.20, 3.06);
		$this->createBaseTextRun($shape, 'レポート出力期間');

		$shape = $this->createBaseRichTextShape($currentSlide, 2.15, 0.24, 4.64, 3.11);
		$this->createBaseTextRun($shape, '2021.02.01〜2022.10.01', 12);

		$shape = $this->createBaseRichTextShape($currentSlide, 1.85, 0.34, 3.20, 3.42);
		$this->createBaseTextRun($shape, 'レポート出力期間');

		$shape = $this->createBaseRichTextShape($currentSlide, 2.15, 0.24, 4.64, 3.48);
		$this->createBaseTextRun($shape, '2021.02.01〜2022.10.01', 12);

		// Add a file drawing (PNG transparent) to the slide
		$shape = new DrawingFile();
		$shape->setName('Image File PNG')
			->setDescription('Image File PNG')
			->setPath(public_path() . '/reports/images/cover_image.png')
			->setHeight(convertInchToPixel(3.39))
			->setWidth(convertInchToPixel(6.65))
			->setOffsetX(convertInchToPixel(1.67))
			->setOffsetY(convertInchToPixel(4.10));
		$currentSlide->addShape($shape);
	}

	public function generateMenuSlide(PhpPresentation $objPHPPresentation)
	{
		$currentSlide = $this->createTemplatedSlide($objPHPPresentation, 'menu');

		$shape = $this->createBaseRichTextShape($currentSlide, 1.20, 0.45, 3.49, 1.28);
		$this->createBaseTextRun($shape, '目次', 24, false, 'FF1d39c4');
		
		// gach ngang
		$currentSlide->createLineShape(convertInchToPixel(4.80), convertInchToPixel(1.64), convertInchToPixel(8.84), convertInchToPixel(1.64))
			->getBorder()
			->setColor(new Color('FF808080'))
			->setLineWidth(2);
		
		// gach ngang
		$currentSlide->createLineShape(convertInchToPixel(4.80), convertInchToPixel(2.11), convertInchToPixel(8.84), convertInchToPixel(2.11))
			->getBorder()
			->setColor(new Color('FF808080'))
			->setLineWidth(2);
	
		// gach ngang
		$currentSlide->createLineShape(convertInchToPixel(4.80), convertInchToPixel(3.39), convertInchToPixel(8.84), convertInchToPixel(3.39))
			->getBorder()
			->setColor(new Color('FF808080'))
			->setLineWidth(2);

		// text lv1
		$shape = $this->createBaseRichTextShape($currentSlide, 4.90, 0.57, 4.70, 1.07);
		$shape->getActiveParagraph()->setLineSpacing(250);
		$this->createBaseTextRun($shape, '出力ビジネス情報一覧', 11, false, Color::COLOR_BLACK);
		
		// number page
		$shape = $this->createBaseRichTextShape($currentSlide, 3.22, 0.28, 5.70, 1.31, Alignment::HORIZONTAL_RIGHT);
		$this->createBaseTextRun($shape, '3', 11, true, Color::COLOR_BLACK);
	}

	public function generateBusinessInformationSlide(PhpPresentation $objPHPPresentation)
	{
		// {date_at: "2021/09/01", 神戸の看板制作 HORASIGN株式会社: 12}
		$data = [
			[
				'metric_id' => 'BUSINESS_IMPRESSIONS_DESKTOP_MAPS',
				'metric_name' => 'マップ経由の表示(パソコン)',
				'data' => [
					'date' => '2021/09/01',
					'total' => 200,
					'count' => [
						'account' => 123,
						'value' => 11
					],
				],
			],
			[
				'metric_id' => 'BUSINESS_IMPRESSIONS_MOBILE_MAPS',
				'metric_name' => 'マップ経由の表示(モバイル)',
				'data' => [
					'date' => '2021/09/01',
					'total' => 200,
					'count' => [
						'account' => 123,
						'value' => 11
					],
				],
			],
			[
				'metric_id' => 'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH',
				'metric_name' => '検索経由の表示(パソコン)',
				'data' => [
					'date' => '2021/09/01',
					'total' => 200,
					'count' => [
						'account' => 123,
						'value' => 11
					],
				],
			],
			[
				'metric_id' => 'BUSINESS_IMPRESSIONS_MOBILE_SEARCH',
				'metric_name' => '検索経由の表示(モバイル)',
				'data' => [
					'date' => '2021/09/01',
					'total' => 200,
					'count' => [
						'account' => 123,
						'value' => 11
					],
				],
			],
		];

		// Create templated slide
		$currentSlide = $this->createTemplatedSlide($objPHPPresentation); // local function
		$this->createTopTitle($currentSlide, 'ビジネス情報 表示(ユニークユーザー数)分析');
		$seriesData = [
			'BUSINESS_IMPRESSIONS_DESKTOP_MAPS' => [
				'metric_id' => 'BUSINESS_IMPRESSIONS_DESKTOP_MAPS',
				'metric_name' => 'Googleマップ経由',
				'data' => [
					[
						'date' => '2023/01',
						'total' => 131,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
					[
						'date' => '2023/02',
						'total' => 222,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
				],
			],
			'BUSINESS_IMPRESSIONS_MOBILE_MAPS' => [
				'metric_id' => 'BUSINESS_IMPRESSIONS_MOBILE_MAPS',
				'metric_name' => 'Googleマップ経由',
				'data' => [
					[
						'date' => '2023/01',
						'total' => 131,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
					[
						'date' => '2023/02',
						'total' => 222,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
				],
			],
			'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH' => [
				'metric_id' => 'BUSINESS_IMPRESSIONS_DESKTOP_SEARCH',
				'metric_name' => 'Googleマップ経由',
				'data' => [
					[
						'date' => '2023/01',
						'total' => 131,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
					[
						'date' => '2023/02',
						'total' => 222,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
				],
			],
			'BUSINESS_IMPRESSIONS_MOBILE_SEARCH' => [
				'metric_id' => 'BUSINESS_IMPRESSIONS_MOBILE_SEARCH',
				'metric_name' => 'Googleマップ経由',
				'data' => [
					[
						'date' => '2023/01',
						'total' => 131,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
					[
						'date' => '2023/02',
						'total' => 222,
						'count' => [
							[
								'account' => '"就労移行支援事業所 ソース堺東',
								'value' => 26,
							],
						],
					],
				],
			],

		];

		// Create a bar chart (that should be inserted in a shape)
		$StackedBarChart = new Bar();
		foreach ($seriesData as $series) {
			$dataTest[$series['metric_id']] = [];
			// dump('$series');
			// dump($series);
			foreach ($series['data'] as $data) {
				// $d = [
				//     $data['date'] => $data['total']
				// ];
				$series['metric_id'][$data['date']] = $data['total'];
				// array_push($dataTest[$series['metric_id']], $d);

			}

			dump($dataTest[$series['metric_id']]);
			// dump($series);
			// $series['metric_id'] = $this->createShapeSeries($dataTest[$series['metric_id']], config('common.metric_display.BUSINESS_IMPRESSIONS_DESKTOP_MAPS'), config('common.metric_display_color.BUSINESS_IMPRESSIONS_DESKTOP_MAPS'));
			// $StackedBarChart->addSeries($series['metric_id']);
		}
		die;

		// $series1 = $this->createShapeSeries($series1Data, config('common.metric_display.BUSINESS_IMPRESSIONS_DESKTOP_MAPS'), config('common.metric_display_color.BUSINESS_IMPRESSIONS_DESKTOP_MAPS'));
		// $series2 = $this->createShapeSeries($series2Data, config('common.metric_display.BUSINESS_IMPRESSIONS_MOBILE_MAPS'), config('common.metric_display_color.BUSINESS_IMPRESSIONS_MOBILE_MAPS'));
		// $series3 = $this->createShapeSeries($series3Data, config('common.metric_display.BUSINESS_IMPRESSIONS_DESKTOP_SEARCH'), config('common.metric_display_color.BUSINESS_IMPRESSIONS_DESKTOP_SEARCH'));
		// $series4 = $this->createShapeSeries($series4Data, config('common.metric_display.BUSINESS_IMPRESSIONS_MOBILE_SEARCH'), config('common.metric_display_color.BUSINESS_IMPRESSIONS_MOBILE_SEARCH'));

		// $StackedBarChart->addSeries($series4);
		// $StackedBarChart->addSeries($series3);
		// $StackedBarChart->addSeries($series2);
		// $StackedBarChart->addSeries($series1);
		$StackedBarChart->setBarGrouping(Bar::GROUPING_STACKED);
		// Create a shape (chart)
		$shape = $currentSlide->createChartShape();
		$shape->setName('ビジネス情報 表示(ユニークユーザー数)分析')
			->setResizeProportional(false)
			->setOffsetX(convertInchToPixel(0.73))
			->setOffsetY(convertInchToPixel(0.95))
			->setWidth(convertInchToPixel(8.54))
			->setHeight(convertInchToPixel(3.31));
		$shape->getBorder()->setLineStyle(Border::LINE_SINGLE);
		$shape->getTitle()->setText('');
		$shape->getTitle()->setVisible(false);

		$line = new Line();
		$shape->getPlotArea()->setType($line);

		$oOutlineAxisY = new Outline();
		$oOutlineAxisY->setWidth(0.012);
		$oOutlineAxisY->getFill()->setFillType(Fill::FILL_SOLID);
		$oOutlineAxisY->getFill()->getStartColor()->setRGB('000000');

		$gridlines = new Gridlines();
		$gridlines->getOutline()
			->setWidth(5)
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->setStartColor(new Color(Color::COLOR_BLACK));

		$shape->getPlotArea()->getAxisY()->setMajorGridlines($gridlines);
		$shape->getPlotArea()->getAxisX()->setTitleRotation(135)->setTitle('');
		$shape->getPlotArea()->getAxisY()->setOutline($oOutlineAxisY);
		$shape->getPlotArea()->getAxisY()->setTitleRotation(135)->setTitle('');

		$shape->getPlotArea()->getAxisX()->setTitle('');
		$shape->getPlotArea()->getAxisY()->setTitle('');
		$shape->getPlotArea()->getAxisY()->setFormatCode('#,#');
		$shape->getPlotArea()->setType($StackedBarChart)->setWidth(960);
		$shape->getLegend()->setVisible(false);

		// Create table
		$shape = $currentSlide->createTableShape(2)
			->setOffsetX(convertInchToPixel(0.27))
			->setOffsetY(convertInchToPixel(4.61))
			->setWidth(convertInchToPixel(9.45))
			->setHeight(convertInchToPixel(2.43));

		// Add row
		$row = $this->createTableRow($shape, '年月');
		$this->createTableCell($row, '2022.10', true);

		$row = $this->createTableRow($shape, '全て');
		$this->createTableCell($row, '123.456');

		$row = $this->createTableRow($shape, 'マップ経由(モバイル)');
		$this->createTableCell($row, '123.456');

		$row = $this->createTableRow($shape, '検索経由(モバイル)');
		$this->createTableCell($row, '123.456');

		$row = $this->createTableRow($shape, 'マップ経由(PC)');
		$this->createTableCell($row, '123.456');

		$row = $this->createTableRow($shape, '検索経由(PC)');
		$this->createTableCell($row, '123.456');

		// Add row
	}

	public function generateReports()
	{
		$objPHPPresentation = new PhpPresentation();
		$objPHPPresentation->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_4X3);
		$this->generateIntroductionSlide($objPHPPresentation);
		$this->generateMenuSlide($objPHPPresentation);
		// $this->generateBusinessInformationSlide($objPHPPresentation);

		return $this->write($objPHPPresentation, 'report' . time(), $this->writers);
	}
}
