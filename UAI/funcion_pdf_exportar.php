<?php 


use core_question\bank\preview_action_column;
require_once ('../../config.php');

require_once ($CFG->dirroot . "/lib/questionlib.php");


require('config.php');

require_once($CFG->libdir . '/pdflib.php');
TCPDF_FONTS::addTTFfont('/full_path_to/ARIALUNI.TTF', 'TrueTypeUnicode');

    
	$doc = new pdf();
	$doc->setPrintHeader(false);
	$doc->setPrintFooter(false);
	$doc->AddPage();
	$doc->writeHTML($_POST['titulo_pdf'],1,$_POST['titulo_pdf'],0,0,'C');
	$doc->writeHTML($_POST['texto_pdf'],1,$_POST['texto_pdf'],1,1,'L');
		
	
	$doc->Output($_POST['nombre_pdf']);
	$doc=null;
  
?>

