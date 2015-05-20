
	
	<?php 
	
	require_once ('../../config.php');
	require_once ($CFG->dirroot . "/lib/questionlib.php");
	require('config.php');
	require_once($CFG->libdir . '/pdflib.php');
	

	global $DB, $USER, $CFG;
	
	require_login (); // Requiere estar log in
	
	$baseurl = new moodle_url ( '/question/UAI/funcion_pdf.php' ); // importante para crear la clase pagina
	$context = context_system::instance (); // context_system::instance();
	$PAGE->set_context ( $context );
	$PAGE->set_url ( $baseurl );
	$PAGE->set_pagelayout ( 'standard' );
	$PAGE->set_title ( get_string ( 'title', 'preguntas_export' ) );
	$PAGE->set_heading ( get_string ( 'title', 'preguntas_export' ) );
	$PAGE->navbar->add ( get_string ( 'quuestion', 'preguntas_export' ) );
	$PAGE->navbar->add ( 'funcion_pdf', 'reservar.php' );
	
	echo $OUTPUT->header (); // Imprime el header
	echo $OUTPUT->heading ( get_string ( 'title', 'preguntas_export' ) );
	
	
	
	
	
	
	TCPDF_FONTS::addTTFfont('/full_path_to/ARIALUNI.TTF', 'TrueTypeUnicode');
	
	$idcurso = optional_param('id', 2, PARAM_INT);
	
	$contextocurso = context_course::instance($idcurso);
	
	$categorias = get_categories_for_contexts($contextocurso->id,  'name ASC');
	
	$i=1;
	$imp=0;
	foreach($categorias as $categoria) {
		echo $categoria->name;
		$imp="$categoria->name <br>";
		$preguntas = $DB->get_records('question', array('category'=>$categoria->id));
		foreach($preguntas as $pregunta) {
			echo $pregunta->questiontext;
			$imp="$imp
			La pregunta $i es $pregunta->questiontext las respuestas son:";
			$i++;
			$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
					foreach($respuestas as $respuesta) {
					echo "$respuesta->answer <br>";
				$imp="$imp <br> $respuesta->answer  ";
	
			}
			$imp="$imp <br><hr><br>";
			}
			}
	
			echo"	<html>
			<form action='funcion_pdf_exportar.php' method='post'>";
	
	
	
			$nombre="guia $categoria->name .pdf";
	echo "LA VARIABLE IMP SE IMPRIME AQUI: <br> $imp <hr>";
	
		//echo "Vista previa de la exportacion!:<br>$texto";
		echo "<br><input type='hidden' name='texto_pdf' value='$imp'>
			<input type='hidden' name='nombre_pdf' value='$nombre'>
			<input type='submit' value='Exportar PDF'>";
	
	
			echo"</form>
			</html>";
	
	
	
	echo $OUTPUT->footer (); // imprime el footer
	
	
	
	
	
	
	echo '<hr>';
	echo "AYUDA PARA GUIARSE EN LOS ARRAY: <hr>";
	echo $categoria->name;//&'<br>';
	
	echo "<hr>";
	echo $pregunta->name;
	echo " (Tipo de pregunta: ";
	echo $pregunta->qtype;
	echo ")<br>";
	echo $pregunta->questiontext;
	echo "<br>";
	echo $pregunta->generalfeedback;
	echo "<br>";
	echo $respuesta->answer;
	echo "<hr>";
	echo "VAR_DUMP DE RESPUESTA<br>";
	var_dump($respuesta);
	echo "<hr>";
	echo "VAR_DUMP DE PREGUNTA<br>";
	echo var_dump($pregunta);
	echo "<hr>";
	echo "VAR_DUMP DE CATEGORIA<br>";
	var_dump($categoria);
	echo "<hr>";
	echo "VAR_DUMP DE PREGUNTAS<br>";
	var_dump($preguntas);
	echo "<hr>";
	
	
	echo "$pregunta->name (Tipo de pregunta: $pregunta->qtype )<br>
	$pregunta->questiontext <br>
	$pregunta->generalfeedback <br>
	$respuesta->answer <br>
	   ";
	