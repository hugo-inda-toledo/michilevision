<?php
App::import('Vendor','xtcpdf'); 
$tcpdf = new XTCPDF();
$textfont = 'freesans';
//$textfont = 'tahoma';

$tcpdf->SetAuthor("Generado automaticamente por michilevision.cl");
$tcpdf->SetTitle("Cuantificador de gastos general");
$tcpdf->SetAutoPageBreak( false );
$tcpdf->setHeaderFont(array($textfont,'',10));
$tcpdf->xheadercolor = array(255,255,255);
$tcpdf->xheadertext = '';
$tcpdf->xfootertext = '© '.date('Y').' Desarrollado por el departamento de Tecnologias de informacion, Red Televisiva Chilevision.';


/*********************************************************************************************************************/
/*********************************************Calculo de deuda por divisa****************************************/
/*********************************************************************************************************************/
$clp_deft = $datos_pdf['clp_total'] - $datos_pdf['clp_total_render'];
$usd_deft = $datos_pdf['usd_total'] - $datos_pdf['usd_total_render'];
$euro_deft = $datos_pdf['euro_total'] - $datos_pdf['euro_total_render'];
$uf_deft = $datos_pdf['uf_total'] - $datos_pdf['uf_total_render'];
$utm_deft = $datos_pdf['utm_total'] - $datos_pdf['utm_total_render'];

 
$html .= "<br><br><br><br><br><br><br>";
$html .= "Desde el <font color=\"#800E0E\">".$datos_pdf['start_date']."</font>";
$html .= " hasta <font color=\"#800E0E\">".$datos_pdf['end_date']."</font><br>";
$html .= "<br><br>Gerencia asociada al informe: <font color=\"#800E0E\">".$datos_pdf['management_name'].'</font><br>';
$html .= "Centro de costo asociado al informe: <font color=\"#800E0E\">".$datos_pdf['cost_center_name']."</font><br>";
$html .= "<p align='center'><u><font color=\"#800E0E\">Estadistica Total De Fondos</font></u></p><br>";
$html .="<table border='1' align='center'>
				<tr>
					<td>Total de fondos</td>
					<td>".$datos_pdf['total_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Aprobados</td>
					<td>".$datos_pdf['approved_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Rechazados</td>
					<td>".$datos_pdf['declined_funds']."</td>
				</tr>
				<tr>
					<td>Fondos por aprobar</td>
					<td>".$datos_pdf['to_sign_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Rendidos</td>
					<td>".$datos_pdf['render_funds']."</td>
				</tr>
				<tr>
					<td>Fondos por Rendir</td>
					<td>".$datos_pdf['to_render_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Expirados</td>";
					if($datos_pdf['expired_funds'] > 0)
						$html .= "<td><font color=\"#FF0000\">".$datos_pdf['expired_funds']."</font></td>";
					else
						$html .= "<td>".$datos_pdf['expired_funds']."</td>";
				$html .= 
				"</tr>
			</table><br><br>";

$html .= "<p align='center'><u><font color=\"#800E0E\">Estadistica Monet".utf8_encode('á')."ria De Fondos</font></u></p><br>";
$html .= "<table border='1' align='center'>
				<tr>
					<td>Dinero total en pesos chilenos</td>
					<td>CLP <u>$". number_format($datos_pdf['clp_total'], 0, null, '.')."</u></td>
					<td>Dinero rendido en pesos chilenos</td>
					<td>CLP <u>$".number_format($datos_pdf['clp_total_render'], 0, null, '.')."</u></td>
					<td>Deuda de la gerencia/c.costo en pesos chilenos</td>";
					if($clp_deft > 0)
						$html .= "<td>CLP <u><font color=\"#FF0000\">$".number_format($clp_deft, 0, null, '.')."</font></u></td>";
					else
						$html .= "<td>CLP <u>$".number_format($clp_deft, 0, null, '.')."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Dinero total en dolares americanos</td>
					<td>USD <u>$".number_format($datos_pdf['usd_total'], 0, null, '.')."</u></td>
					<td>Dinero rendido en dolares americanos</td>
					<td>USD <u>$".number_format($datos_pdf['usd_total_render'], 0, null, '.')."</u></td>
					<td>Deuda de la gerencia/c.costo en dolares americanos</td>";
					if($usd_deft > 0)
						$html .= "<td>USD <u><font color=\"#FF0000\">$".number_format($usd_deft, 0, null, '.')."</font></u></td>";
					else
						$html .= "<td>USD <u>$".number_format($usd_deft, 0, null, '.')."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Dinero total en euros</td>
					<td>EUR <u>$".$datos_pdf['euro_total']."</u></td>
					<td>Dinero rendido en euros</td>
					<td>EUR <u>$".$datos_pdf['euro_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en euros</td>";
					if($euro_deft > 0)
						$html .="<td>EUR <u><font color=\"#FF0000\">$".$euro_deft."</font></u></td>";
					else
						$html .="<td>EUR <u>$".$euro_deft."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Monto total en UF</td>
					<td>UF <u>".$datos_pdf['uf_total']."</u></td>
					<td>Monto rendido en UF</td>
					<td>UF <u>".$datos_pdf['uf_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en UF</td>";
					if($uf_deft > 0)
						$html .= "<td>UF <u><font color=\"#FF0000\">".$uf_deft."</font></u></td>";
					else
						$html .= "<td>UF <u>".$uf_deft."</u></td>";
				$html .=
				"</tr>
				<tr>
					<td>Monto total en UTM </td>
					<td>UTM <u>".$datos_pdf['utm_total']."</u></td>
					<td>Monto rendido en UTM</td>
					<td>UTM <u>".$datos_pdf['utm_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en UTM</td>";
					if($utm_deft > 0)
						$html .= "<td>UTM <u><font color=\"#FF0000\">".$utm_deft."</font></u></td>";
					else
						$html .= "<td>UTM <u>".$utm_deft."</u></td>";
				$html .= 
				"</tr>
			</table>
			
			<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
			
$html .= "Informe generado por <font color=\"#07714B\">".$datos_pdf['generated_by']."</font>";
$html .= '<br>';
$html .= "Fecha de emisi".utf8_encode("ó")."n: <font color=\"#07714B\">".$datos_pdf['date_today']."</font>";

// Ahora imprimimos el contenido de la pagina en una posición determinada
// estos datos son un ejemplo, y en mi ejemplo hay un pequeño texto y una imagen.
$tcpdf->AddPage();
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'B',10);
$tcpdf->Cell(10,30,'Cuantificador de gastos', 0, 0);
// configuramos la calidad de JPEG
$tcpdf->Image('img/logo_example.png', -5, 4, 80, 0, '', '', '', false, 200); 
$tcpdf->setJPEGQuality(100);


//$tcpdf->fixHTMLCode($html, 'table tr td { border:1px solid #FF0000;}', '', '');
$tcpdf->WriteHTML($html, true, false, true, false, '');

// se pueden asignar mas datos, ver la documentación de TCPDF

echo $tcpdf->Output('mi_archivo.pdf', 'I'); //el pdf se muestra en el navegador
//echo $tcpdf->Output('mi_archivo.pdf', 'I'); //el pdf se descarga

?>