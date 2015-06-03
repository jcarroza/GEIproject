<?php 
	//requerimientos de funcion_pdf.php
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
	$PAGE->navbar->add ( 'funcion_pdf', 'reservar.php' );
	echo $OUTPUT->header (); // Imprime el header
	
	//cantidad de preguntas por tipo que quiere el usuario obtenidas de la pagina "edit.php"
	$rsimple = $_POST['rsimple']; 
	$rextendida = $_POST['rextendida'];
	$rmultiple = $_POST['rmultiple'];
	$rvof = $_POST['rvof'];
	
	//Contador de cantidad de preguntas
	$cantsimple=1;
	$cantextendida=1;
	$cantmultiple=1;
	$cantvof=1;
	
	//obtiene el id del curso desde la página edit.php
	$idcurse = $_POST['idcurse'];
	TCPDF_FONTS::addTTFfont('/full_path_to/ARIALUNI.TTF', 'TrueTypeUnicode');
	
	//variable que lee el id de curso seleccionado en la página edit.php
	$idcurso = optional_param('id',$idcurse, PARAM_INT);
	
	//relación del curso con sus correspondientes categorias.
	$contextocurso = context_course::instance($idcurso);
	$categorias = get_categories_for_contexts($contextocurso->id,  'name ASC');
	
	//contador de preguntas en total
	$cant=1;
	$i=1;

	//forma para obtener las preguntas y respuestas según la categoría 
	foreach($categorias as $categoria) {
		//Formato del título de pregunta según la clase (linea 56 "vista previa", linea 57 impresión PDF)
		echo "<H1 align='center'>  $categoria->name </H1> <br>";
		$titulo_pdf="$categoria->name <br>";
		
		//relacion categoria con pregunta.
		$preguntas = $DB->get_records('question', array('category'=>$categoria->id));
		shuffle($preguntas);
		
		echo "<h5>*Marcar respuesta correcta</h5>";
		$imp="<h5>*Marcar respuesta correcta</h5>";
		
		//forma de obtener las preguntas de la categoría
		foreach($preguntas as $pregunta) {

			//condición de cantidad de preguntas solicitadas del tipo "Respuesta numerica"
			if ($cantsimple <= $rsimple && $pregunta->qtype=="numerical" )		{
						echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
								$imp="$imp
								 La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
									$i++;
									$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
											foreach($respuestas as $respuesta) {
											echo "<input type='checkbox' name='condiciones' />";
											echo "$respuesta->answer <br>";
										$imp="$imp <br>[_] $respuesta->answer  ";
							
									}
									echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
									$imp="$imp <br><hr><br>";
			$cant++;
			$cantsimple++;							}
			
			//condición de cantidad de preguntas solicitadas del tipo "Ensayo"
			if ($cantextendida <= $rextendida && $pregunta->qtype=="tests")		{
				echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
				$imp="$imp
				La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
				$i++;
				$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
				foreach($respuestas as $respuesta) {
					echo "<input type='checkbox' name='condiciones' />";
					echo "$respuesta->answer <br>";
					
					$imp="$imp <br>[_] $respuesta->answer  ";
						
				}
				echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
				$imp="$imp <br><hr><br>";
				$cant++;						
			$cantextendida++;	}
				
			//condición de cantidad de preguntas solicitadas del tipo "Respuesta múltiple"
				if ($cantmultiple <= $rmultiple && $pregunta->qtype=="multiplechoice")		{
					echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
					$imp="$imp
					La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
					$i++;
					$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
					foreach($respuestas as $respuesta) {
						echo "<input type='checkbox' name='condiciones' />";
						echo "$respuesta->answer <br>";
						$imp="$imp <br> [_] $respuesta->answer  ";			
					}
				
					echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
					$imp="$imp <br><hr><br>";
					$cant++;							
				$cantmultiple++;}
					
				//condición de cantidad de preguntas solicitadas del tipo "verdadero o falso"
					if ($cantvof <= $rvof && $pregunta->qtype=="truefalse")		{
						echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
						$imp="$imp
						La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
						$i++;
						$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
					
						foreach($respuestas as $respuesta) {
							echo "<input type='checkbox' name='condiciones' />";
							echo "$respuesta->answer <br>";
						$imp="$imp <br>[_] $respuesta->answer  ";	}
						
						echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
						$imp="$imp <br><hr><br>";
						$cant++;
					$cantvof++;							}}}
	
			echo"	<html>
			<form action='funcion_pdf_exportar.php' method='post'>";
			$nombre="guia $categoria->name .pdf";
		//echo "Vista previa de la exportacion!:<br>$texto";
		echo "<br><input type='hidden' name='texto_pdf' value='$imp'>
			<input type='hidden' name='titulo_pdf' value='$titulo_pdf'>
			<input type='hidden' name='nombre_pdf' value='$nombre'>
			<input type='submit' value='Exportar PDF'>";
	
	
			echo"</form>
			</html>";
			
	echo $OUTPUT->footer (); // imprime el footer
	
	echo '<hr>';
	