<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Bullet;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\IOFactory;

class ReportController extends Controller
{
    protected $writers;
    public function __construct() {
        // Set writers
        $this->writers = ['PowerPoint2007' => 'pptx'];
    }
    public function index(Request $request)
    {
        $presentation = new PhpPresentation();

        // Create slide
        $currentSlide = $presentation->getActiveSlide();

        // Create a shape (drawing)
        $shape = $currentSlide->createDrawingShape();
      
        // Create a shape (text)
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(300)
                ->setWidth(600)
                ->setOffsetX(170)
                ->setOffsetY(180);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $shape->createTextRun('Thank you for using PHPPresentation!');
        $textRun->getFont()->setBold(true)
                            ->setSize(60)
                            ->setColor(new Color('FFE06B20'));

        $writerPPTX = IOFactory::createWriter($presentation, 'PowerPoint2007');
        $writerPPTX->save(__DIR__ . '/sample.pptx');
    }

    public function custom()
    {
        // Create new PHPPresentation object
        $objPHPPresentation = new PhpPresentation();

        // Set properties
        $objPHPPresentation->getDocumentProperties()->setCreator('PHPOffice')
            ->setLastModifiedBy('PHPPresentation Team')
            ->setTitle('Sample 01 Title')
            ->setSubject('Sample 01 Subject')
            ->setDescription('Sample 01 Description')
            ->setKeywords('office 2007 openxml libreoffice odt php')
            ->setCategory('Sample Category');

        // Create slide
        $currentSlide = $objPHPPresentation->getActiveSlide();

        // Create a shape (text)
        $shape = $currentSlide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(100);
        $shape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $shape->createTextRun('Thank you for using PHPPresentation!');
        $textRun->getFont()->setBold(true)
            ->setSize(60)
            ->setColor(new Color('FFE06B20'));

        // Create a shape (text)
        $shape = $currentSlide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(550);
        $shape->getActiveParagraph()->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setIsRTL(true);
        $textRun = $shape->createTextRun('تست فونت فارسی');
        $textRun->getFont()
            ->setBold(true)
            ->setSize(60)
            ->setColor(new Color('FFE06B20'))
            ->setFormat(Font::FORMAT_COMPLEX_SCRIPT)
            ->setName('B Nazanin');

        // Save file
        $this->write($objPHPPresentation, 'report'.time(), $this->writers);

        return response()->json(['message' => 'done']);
    }

    function write($phpPresentation, $filename, $writers)
    {
        // Write documents
        foreach ($writers as $writer => $extension) {
            if (!is_null($extension)) {
                $xmlWriter = IOFactory::createWriter($phpPresentation, $writer);
                $xmlWriter->save(__DIR__ . "/{$filename}.{$extension}");
                // rename(__DIR__ . "/{$filename}.{$extension}", __DIR__ . "/results/{$filename}.{$extension}");
            } else {
                return false;
            }
        }

        return true;
    }

    function createTemplatedSlide(PhpPresentation $objPHPPresentation)
    {
        // Create slide
        $slide = $objPHPPresentation->createSlide();

        // Add logo
        $shape = $slide->createDrawingShape();
        $shape->setName('PHPPresentation logo')
            ->setDescription('PHPPresentation logo')
            ->setPath(__DIR__ . '/resources/phppowerpoint_logo.gif')
            ->setHeight(36)
            ->setOffsetX(10)
            ->setOffsetY(10);
        $shape->getShadow()->setVisible(true)
            ->setDirection(45)
            ->setDistance(10);

        // Return slide
        return $slide;
    }
}
