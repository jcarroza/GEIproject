
	
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
	
	$PAGE->navbar->add ( 'funcion_pdf', 'reservar.php' );
	
	echo $OUTPUT->header (); // Imprime el header
	
	//variable que relaciona la pagina edit.php con la cantidad requerida de preguntas
	$rsimple = $_POST['rsimple'];
	$rextendida = $_POST['rextendida'];
	$rmultiple = $_POST['rmultiple'];
	$rvof = $_POST['rvof'];
	
	//variable para limitar la cantidad de preguntas por categoria 
	$cantsimple=1;
	$cantextendida=1;
	$cantmultiple=1;
	$cantvof=1;
	
	TCPDF_FONTS::addTTFfont('/full_path_to/ARIALUNI.TTF', 'TrueTypeUnicode');
	
	$idcurso = optional_param('id', 2, PARAM_INT);
	
	$contextocurso = context_course::instance($idcurso);
	
	$categorias = get_categories_for_contexts($contextocurso->id,  'name ASC');
	
	$cant=1;
	$i=1;
	$imp=0;
	
//arreglo que agrupa las categorias, preguntas y respuestas
	
	foreach($categorias as $categoria) {
		echo "<H1 align='center'>  $categoria->name </H1> <br>";
		$imp="$categoria->name <br>";
		$preguntas = $DB->get_records('question', array('category'=>$categoria->id));
		shuffle($preguntas);
		foreach($preguntas as $pregunta) {
			
//funcion de cantidad de preguntas por tipo de pregunta	
			if ($cantsimple <= $rsimple && $pregunta->qtype=="numerical" )		{
						echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
								$imp="$imp
								 La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
									$i++;
									$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
											echo "<blockquote>";
											foreach($respuestas as $respuesta) {
											echo "$respuesta->answer <br>";
										$imp="$imp <br> $respuesta->answer  ";
							
									}
									echo "</blockquote>";
									echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
									$imp="$imp <br><hr><br>";
			$cant++;
			$cantsimple++;							}
			
			
			if ($cantextendida <= $rextendida && $pregunta->qtype=="tests")		{
				echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
				$imp="$imp
				La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
				$i++;
				$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
				echo "<blockquote>";
				foreach($respuestas as $respuesta) {
					echo "$respuesta->answer <br>";
					$imp="$imp <br> $respuesta->answer  ";
						
				}
				echo "</blockquote>";
				echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
				$imp="$imp <br><hr><br>";
				$cant++;						
			$cantextendida++;	}
				
				
				if ($cantmultiple <= $rmultiple && $pregunta->qtype=="multiplechoice")		{
					echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
					$imp="$imp
					La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
					$i++;
					$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
					echo "<blockquote>";
					foreach($respuestas as $respuesta) {
						echo "$respuesta->answer <br>";
						$imp="$imp <br> $respuesta->answer  ";
							
					}
					echo "</blockquote>";
					echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
					$imp="$imp <br><hr><br>";
					$cant++;							
				$cantmultiple++;}
					
					
					if ($cantvof <= $rvof && $pregunta->qtype=="truefalse")		{
						echo "<h4>Pregunta $cant $pregunta->questiontext </h4>";
						$imp="$imp
						La pregunta $i es $pregunta->questiontext <br> las respuestas son:";
						$i++;
						$respuestas = $DB->get_records('question_answers', array('question'=>$pregunta->id));
						echo "<blockquote>";
						foreach($respuestas as $respuesta) {
							echo "$respuesta->answer <br>";
							$imp="$imp <br> $respuesta->answer  ";
								
						}
						echo "</blockquote>";
						echo "<HR align='CENTER' size='2' width='400' color='black' noshade>";
						$imp="$imp <br><hr><br>";
						$cant++;
					$cantvof++;							}
		}
	
	}
	
			echo"	<html>
			<form action='funcion_pdf_exportar.php' method='post'>";
	
			$nombre="guia $categoria->name .pdf";

//funcion de impresion de pdf 	
		echo "<br><input type='hidden' name='texto_pdf' value='$imp'>
			<input type='hidden' name='nombre_pdf' value='$nombre'>
			<input type='submit' value='Exportar PDF'>";
	
	
			echo"</form>
			</html>";
	
	echo $OUTPUT->footer (); // imprime el footer
	
	
			echo "</div>\n";	