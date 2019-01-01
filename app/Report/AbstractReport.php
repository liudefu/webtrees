<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees\Report;

use DomainException;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\MediaFile;
use Fisharebest\Webtrees\Webtrees;

/**
 * Class AbstractReport - base for PDF and HTML reports
 */
abstract class AbstractReport
{
    /** User measure unit. */
    const UNITS = 'pt';

    /** @var float Left Margin (expressed in points) Default: 17.99 mm, 0.7083 inch */
    public $left_margin = 51.0;

    /** @var float Right Margin (expressed in points) Default: 9.87 mm, 0.389 inch */
    public $right_margin = 28.0;

    /** @var float Top Margin (expressed in points) Default: 26.81 mm */
    public $top_margin = 76.0;

    /** @var float Bottom Margin (expressed in points) Default: 21.6 mm */
    public $bottom_margin = 60.0;

    /** @var float Header Margin (expressed in points) Default: 4.93 mm */
    public $header_margin = 14.0;

    /** @var float Footer Margin (expressed in points) Default: 9.88 mm, 0.389 inch */
    public $footer_margin = 28.0;

    /** @var string Page orientation (portrait, landscape) */
    public $orientation = 'portrait';

    /** @var string Page format name */
    public $page_format = 'A4';

    /** @var float Height of page format in points */
    public $page_height = 0.0;

    /** @var float Width of page format in points */
    public $page_width = 0.0;

    /** @var string[][] An array of the Styles elements found in the document */
    public $styles = [];

    /** @var string The default Report font name */
    public $default_font = 'dejavusans';

    /** @var float The default Report font size */
    public $default_font_size = 12.0;

    /** @var string Header (H), Page header (PH), Body (B) or Footer (F) */
    public $processing = 'H';

    /** @var bool RTL Language (false=LTR, true=RTL) */
    public $rtl = false;

    /** @var bool Show the Generated by... (true=show the text) */
    public $show_generated_by = true;

    /** @var string Generated By... text */
    public $generated_by = '';

    /** @var string The report title */
    public $title = '';

    /** @var string Author of the report, the users full name */
    public $rauthor = Webtrees::NAME . ' ' . Webtrees::VERSION;

    /** @var string Keywords */
    public $rkeywords = '';

    /** @var string Report Description / Subject */
    public $rsubject = '';

    /**
     * Clear the Header.
     *
     * @return void
     */
    abstract public function clearHeader();

    /**
     * Create a new Page Header object
     *
     * @return ReportBasePageheader
     */
    abstract public function createPageHeader(): ReportBasePageheader;

    /**
     * Add an element.
     *
     * @param ReportBaseElement|string $element
     *
     * @return void
     */
    abstract public function addElement($element);

    /**
     * Run the report.
     *
     * @return void
     */
    abstract public function run();

    /**
     * Create a new Cell object.
     *
     * @param int    $width   cell width (expressed in points)
     * @param int    $height  cell height (expressed in points)
     * @param mixed  $border  Border style
     * @param string $align   Text alignement
     * @param string $bgcolor Background color code
     * @param string $style   The name of the text style
     * @param int    $ln      Indicates where the current position should go after the call
     * @param mixed  $top     Y-position
     * @param mixed  $left    X-position
     * @param int    $fill    Indicates if the cell background must be painted (1) or transparent (0). Default value: 1
     * @param int    $stretch Stretch carachter mode
     * @param string $bocolor Border color
     * @param string $tcolor  Text color
     * @param bool   $reseth
     *
     * @return ReportBaseCell
     */
    abstract public function createCell($width, $height, $border, $align, $bgcolor, $style, $ln, $top, $left, $fill, $stretch, $bocolor, $tcolor, $reseth): ReportBaseCell;

    /**
     * Create a new TextBox object.
     *
     * @param float  $width   Text box width
     * @param float  $height  Text box height
     * @param bool   $border
     * @param string $bgcolor Background color code in HTML
     * @param bool   $newline
     * @param float  $left
     * @param float  $top
     * @param bool   $pagecheck
     * @param string $style
     * @param bool   $fill
     * @param bool   $padding
     * @param bool   $reseth
     *
     * @return ReportBaseTextbox
     */
    abstract public function createTextBox(
        float $width,
        float $height,
        bool $border,
        string $bgcolor,
        bool $newline,
        float $left,
        float $top,
        bool $pagecheck,
        string $style,
        bool $fill,
        bool $padding,
        bool $reseth
    ): ReportBaseTextbox;

    /**
     * Create a text element.
     *
     * @param string $style
     * @param string $color
     *
     * @return ReportBaseText
     */
    abstract public function createText(string $style, string $color): ReportBaseText;

    /**
     * Create an HTML element.
     *
     * @param string   $tag
     * @param string[] $attrs
     *
     * @return ReportBaseHtml
     */
    abstract public function createHTML(string $tag, array $attrs): ReportBaseHtml;

    /**
     * Create a line.
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     *
     * @return ReportBaseLine
     */
    abstract public function createLine(float $x1, float $y1, float $x2, float $y2): ReportBaseLine;

    /**
     * Create a new image object.
     *
     * @param string $file  Filename
     * @param float  $x
     * @param float  $y
     * @param float  $w     Image width
     * @param float  $h     Image height
     * @param string $align L:left, C:center, R:right or empty to use x/y
     * @param string $ln    T:same line, N:next line
     *
     * @return ReportBaseImage
     */
    abstract public function createImage(string $file, float $x, float $y, float $w, float $h, string $align, string $ln): ReportBaseImage;

    /**
     * Create a new image object from Media Object.
     *
     * @param MediaFile $media_file
     * @param float     $x
     * @param float     $y
     * @param float     $w     Image width
     * @param float     $h     Image height
     * @param string    $align L:left, C:center, R:right or empty to use x/y
     * @param string    $ln    T:same line, N:next line
     *
     * @return ReportBaseImage
     */
    abstract public function createImageFromObject(MediaFile $media_file, float $x, float $y, float $w, float $h, string $align, string $ln): ReportBaseImage;

    /**
     * Create a new Footnote object.
     *
     * @param string $style Style name
     *
     * @return ReportBaseFootnote
     */
    abstract public function createFootnote($style): ReportBaseFootnote;

    /**
     * Initial Setup
     *
     * Setting up document wide defaults that will be inherited of the report modules
     * As DEFAULT A4 and Portrait will be used if not set
     *
     * @return void
     */
    public function setup()
    {
        // Set RTL direction
        if (I18N::direction() === 'rtl') {
            $this->rtl = true;
        }
        // Set the Keywords
        $this->rkeywords = '';
        // Generated By...text
        // I18N: This is a report footer. %s is the name of the application.
        $this->generated_by = I18N::translate('Generated by %s', Webtrees::NAME . ' ' . Webtrees::VERSION);

        // For known size pages
        if ($this->page_width == 0 && $this->page_height == 0) {
            /**
             * 1 inch = 72 points
             * 1 mm = 2.8346457 points
             * 1 inch = 25.4 mm
             * 1 point = 0,35278 mm
             */
            switch ($this->page_format) {
                // ISO A series
                case '4A0': // ISO 216, 1682 mm x 2378 mm
                    $sizes = [
                        4767.86,
                        6740.79,
                    ];
                    break;
                case '2A0': // ISO 216, 1189 mm x 1682 mm
                    $sizes = [
                        3370.39,
                        4767.86,
                    ];
                    break;
                case 'A0': // ISO 216, 841 mm x 1189mm
                    $sizes = [
                        2383.94,
                        3370.39,
                    ];
                    break;
                case 'A1': // ISO 216, 594 mm x 841 mm
                    $sizes = [
                        1683.78,
                        2383.94,
                    ];
                    break;
                case 'A2': // ISO 216, 420 mm x 594 mm
                    $sizes = [
                        1190.55,
                        1683.78,
                    ];
                    break;
                case 'A3': // ISO 216, 297 mm x 420 mm
                    $sizes = [
                        841.89,
                        1190.55,
                    ];
                    break;
                case 'A4': // ISO 216, 210 mm 297 mm
                    $sizes = [
                        595.28,
                        841.89,
                    ];
                    break;
                case 'A5': // ISO 216, 148 mm x 210 mm
                    $sizes = [
                        419.53,
                        595.28,
                    ];
                    break;
                case 'A6': // ISO 216, 105 mm x 148 mm
                    $sizes = [
                        297.64,
                        419.53,
                    ];
                    break;
                case 'A7': // ISO 216, 74 mm x 105 mm
                    $sizes = [
                        209.76,
                        297.64,
                    ];
                    break;
                case 'A8': // ISO 216, 52 mm x 74 mm
                    $sizes = [
                        147.40,
                        209.76,
                    ];
                    break;
                case 'A9': // ISO 216, 37 mm x 52 mm
                    $sizes = [
                        104.88,
                        147.40,
                    ];
                    break;
                case 'A10': // ISO 216, 26 mm x 37 mm
                    $sizes = [
                        73.70,
                        104.88,
                    ];
                    break;

                case 'DL': // Original DIN 678 but ISO 269 now has this C6/5 , 110 mm x 220 mm, For A4 sheet folded in thirds, A5 in half
                    $sizes = [
                        311.81,
                        623.62,
                    ];
                    break;

                // US pages
                case 'EXECUTIVE': // 7.25 in x 10.5 in
                    $sizes = [
                        522.00,
                        756.00,
                    ];
                    break;
                case 'FOLIO': // 8.5 in x 13 in
                    $sizes = [
                        612.00,
                        936.00,
                    ];
                    break;
                case 'FOOLSCAP': // 13.5 in x 17 in
                    $sizes = [
                        972.00,
                        1224.00,
                    ];
                    break;
                case 'LEDGER': // 11 in x 17 in
                    $sizes = [
                        792.00,
                        1224.00,
                    ];
                    break;
                case 'LEGAL': // 8.5 in x 14 in
                    $sizes = [
                        612.00,
                        1008.00,
                    ];
                    break;
                case 'LETTER': // 8.5 in x 11 in
                    $sizes = [
                        612.00,
                        792.00,
                    ];
                    break;
                case 'QUARTO': // 8.46 in x 10.8 in
                    $sizes = [
                        609.12,
                        777.50,
                    ];
                    break;
                case 'STATEMENT': // 5.5 in x 8.5 in
                    $sizes = [
                        396.00,
                        612.00,
                    ];
                    break;
                case 'USGOVT': // 8 in x 11 in
                    $sizes = [
                        576.00,
                        792.00,
                    ];
                    break;
                default:
                    $this->page_format = 'A4';
                    $sizes             = [
                        595.28,
                        841.89,
                    ];
                    break;
            }
            $this->page_width  = $sizes[0];
            $this->page_height = $sizes[1];
        } else {
            if ($this->page_width < 10) {
                throw new DomainException('REPORT ERROR AbstractReport::setup(): For custom size pages you must set "customwidth" larger then this in the XML file');
            }
            if ($this->page_height < 10) {
                throw new DomainException('REPORT ERROR AbstractReport::setup(): For custom size pages you must set "customheight" larger then this in the XML file');
            }
        }
    }

    /**
     * Process the Header , Page header, Body or Footer
     *
     * @param string $p Header (H), Page header (PH), Body (B) or Footer (F)
     *
     * @return void
     */
    public function setProcessing(string $p)
    {
        $this->processing = $p;
    }

    /**
     * Add the Title when raw character data is used in Title
     *
     * @param string $data
     *
     * @return void
     */
    public function addTitle(string $data)
    {
        $this->title .= $data;
    }

    /**
     * Add the Description when raw character data is used in Description
     *
     * @param string $data
     *
     * @return void
     */
    public function addDescription(string $data)
    {
        $this->rsubject .= $data;
    }

    /**
     * Add Style to Styles array
     *
     * @param string[] $style
     *
     * @return void
     */
    public function addStyle(array $style)
    {
        $this->styles[$style['name']] = $style;
    }

    /**
     * Get a style from the Styles array
     *
     * @param string $s Style name
     *
     * @return array
     */
    public function getStyle(string $s): array
    {
        if (!isset($this->styles[$s])) {
            return current($this->styles);
        }

        return $this->styles[$s];
    }
}
