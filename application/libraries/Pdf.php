<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
	public $sub_title="";
	public function __construct( $orientation = 'L', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false ) {
		parent::__construct( $orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa );
	}

	//Page header
	public function Header() {
		// Logo
		$image_file = DOC_ROOT_FRONT.'/uploads/logo_image/logo_81.jpg';
		$this->Image($image_file, 2, 2, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//		 Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, $this->sub_title, 0, true, 'C', 0, '', 0, false, 'T', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}