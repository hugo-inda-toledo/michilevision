<?php
App::import('Vendor','tcpdf/tcpdf');

$tcpdf = new TCPDF();
$textfont = 'freesans';
//$textfont = 'tahoma';

$tcpdf->SetAuthor("Generado automaticamente por michilevision.cl para ".$data['Details']['generated_by']);
$tcpdf->SetTitle("Cuantificador de gastos generales desde el ".$data['Details']['start_date']." hasta ".$data['Details']['end_date']);
$tcpdf->SetAutoPageBreak( false );
$tcpdf->setHeaderFont(array($textfont,'',10));
$tcpdf->xheadercolor = array(255,255,255);
$tcpdf->xheadertext = '';
$tcpdf->xfootertext = date('Y').' Desarrollado por el departamento de Tecnologias de informacion, Red Televisiva Chilevision.';


/*********************************************************************************************************************/
/*********************************************Calculo de deuda por divisa****************************************/
/*********************************************************************************************************************/
$clp_deft = $data['Details']['clp_total'] - $data['Details']['clp_total_render'];
$usd_deft = $data['Details']['usd_total'] - $data['Details']['usd_total_render'];
$euro_deft = $data['Details']['euro_total'] - $data['Details']['euro_total_render'];
$uf_deft = $data['Details']['uf_total'] - $data['Details']['uf_total_render'];
$utm_deft = $data['Details']['utm_total'] - $data['Details']['utm_total_render'];

$html='';
 
 /*NUEVA HOJA*/
 
$tcpdf->AddPage();
			
// Ahora imprimimos el contenido de la pagina en una posición determinada
// estos datos son un ejemplo, y en mi ejemplo hay un pequeño texto y una imagen.
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'B',10);
$tcpdf->Cell(10,30,'Cuantificador de gastos (Generañ)', 0, 0);
// configuramos la calidad de JPEG
$tcpdf->Image('img/logo_example.png', -5, 4, 80, 0, '', '', '', false, 200); 
$tcpdf->setJPEGQuality(100);

$html .= "<br><br><br><br><br><br><br>";
$html .= "Desde el <font color=\"#800E0E\">".$data['Details']['start_date']."</font>";
$html .= " hasta <font color=\"#800E0E\">".$data['Details']['end_date']."</font><br>";
$html .= "<p align=\"center\"><u><font color=\"#800E0E\">Estadistica Total De Fondos</font></u></p><br>";
$html .="<table border=\"1\" align=\"center\">
				<tr>
					<td>Total de fondos</td>
					<td>".$data['Details']['total_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Aprobados</td>
					<td>".$data['Details']['approved_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Rechazados</td>
					<td>".$data['Details']['declined_funds']."</td>
				</tr>
				<tr>
					<td>Fondos por aprobar</td>
					<td>".$data['Details']['to_sign_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Rendidos</td>
					<td>".$data['Details']['render_funds']."</td>
				</tr>
				<tr>
					<td>Fondos por Rendir</td>
					<td>".$data['Details']['to_render_funds']."</td>
				</tr>
				<tr>
					<td>Fondos Expirados</td>";
					if($data['Details']['expired_funds'] > 0)
						$html .= "<td><font color=\"#FF0000\">".$data['Details']['expired_funds']."</font></td>";
					else
						$html .= "<td>".$data['Details']['expired_funds']."</td>";
				$html .= 
				"</tr>
			</table><br><br>";

$html .= "<p align=\"center\"><u><font color=\"#800E0E\">Estadistica Monetaria De Fondos</font></u></p><br>";
$html .= "<table border=\"1\" align=\"center\">
				<tr>
					<td>Dinero total en pesos chilenos</td>
					<td>CLP <u>$". number_format($data['Details']['clp_total'], 0, null, '.')."</u></td>
					<td>Dinero rendido en pesos chilenos</td>
					<td>CLP <u>$".number_format($data['Details']['clp_total_render'], 0, null, '.')."</u></td>
					<td>Deuda de la gerencia/c.costo en pesos chilenos</td>";
					if($clp_deft > 0)
						$html .= "<td>CLP <u><font color=\"#FF0000\">$".number_format($clp_deft, 0, null, '.')."</font></u></td>";
					else
						$html .= "<td>CLP <u>$".number_format($clp_deft, 0, null, '.')."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Dinero total en dolares americanos</td>
					<td>USD <u>$".number_format($data['Details']['usd_total'], 0, null, '.')."</u></td>
					<td>Dinero rendido en dolares americanos</td>
					<td>USD <u>$".number_format($data['Details']['usd_total_render'], 0, null, '.')."</u></td>
					<td>Deuda de la gerencia/c.costo en dolares americanos</td>";
					if($usd_deft > 0)
						$html .= "<td>USD <u><font color=\"#FF0000\">$".number_format($usd_deft, 0, null, '.')."</font></u></td>";
					else
						$html .= "<td>USD <u>$".number_format($usd_deft, 0, null, '.')."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Dinero total en euros</td>
					<td>EUR <u>$".$data['Details']['euro_total']."</u></td>
					<td>Dinero rendido en euros</td>
					<td>EUR <u>$".$data['Details']['euro_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en euros</td>";
					if($euro_deft > 0)
						$html .="<td>EUR <u><font color=\"#FF0000\">$".$euro_deft."</font></u></td>";
					else
						$html .="<td>EUR <u>$".$euro_deft."</u></td>";
				$html .= 
				"</tr>
				<tr>
					<td>Monto total en UF</td>
					<td>UF <u>".$data['Details']['uf_total']."</u></td>
					<td>Monto rendido en UF</td>
					<td>UF <u>".$data['Details']['uf_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en UF</td>";
					if($uf_deft > 0)
						$html .= "<td>UF <u><font color=\"#FF0000\">".$uf_deft."</font></u></td>";
					else
						$html .= "<td>UF <u>".$uf_deft."</u></td>";
				$html .=
				"</tr>
				<tr>
					<td>Monto total en UTM </td>
					<td>UTM <u>".$data['Details']['utm_total']."</u></td>
					<td>Monto rendido en UTM</td>
					<td>UTM <u>".$data['Details']['utm_total_render']."</u></td>
					<td>Deuda de la gerencia/c.costo en UTM</td>";
					if($utm_deft > 0)
						$html .= "<td>UTM <u><font color=\"#FF0000\">".$utm_deft."</font></u></td>";
					else
						$html .= "<td>UTM <u>".$utm_deft."</u></td>";
				$html .= 
				"</tr>
			</table>";

$tcpdf->WriteHTML($html, true, false, true, false, '');

$html2 = '';

/* NUEVA HOJA */

$tcpdf->AddPage();

// Atributos de la hoja
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'',10);
//$tcpdf->Cell(10,30,'Detalle del Informe', 0, 0);

// configuramos la calidad de JPEG
$tcpdf->Image('img/logo_example.png', -5, 4, 80, 0, '', '', '', false, 200); 
$tcpdf->setJPEGQuality(100);

$size = "<font size=\"6\">";
$sizeHeader = "size=\"6\"";

$html2 .= "<br><br><p align=\"center\"><u><font color=\"#800E0E\"><strong>Detalle del Informe</strong></font></u></p><br>";

$html2 .= "<table border=\"1\" align=\"center\">
					<tr>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Id</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Fecha de emision</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Titulo del fondo</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Solicitante</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Utilizante</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Gerencia</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Centro de costo</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Monto Total</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Estado</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Numero de fondo</strong></font></td>
						<td><font color=\"green\"  ".$sizeHeader."><strong>Fecha de tope a rendir</strong></font></td>
					</tr>";

if(isset($data['ApprovedFunds']['RenderFunds']))
{
	foreach($data['ApprovedFunds']['RenderFunds'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

if(isset($data['ApprovedFunds']['ToRenderFunds']))
{
	foreach($data['ApprovedFunds']['ToRenderFunds'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

if(isset($data['ApprovedFunds']['Approved']))
{
	foreach($data['ApprovedFunds']['Approved'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

if(isset($data['ApprovedFunds']['ExpiredFunds']))
{
	foreach($data['ApprovedFunds']['ExpiredFunds'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

if(isset($data['ToSignFunds']))
{
	foreach($data['ToSignFunds'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

if(isset($data['DeclinedFunds']))
{
	foreach($data['DeclinedFunds'] as $value)
	{
		$html2 .= "<tr>
							<td>".$size.$value['RenderFund']['id']."</font></td>
							<td>".$size.$value['RenderFund']['created']."</font></td>
							<td>".$size.$value['RenderFund']['render_fund_title']."</font></td>
							<td>".$size.$value['User']['name']." ".$value['User']['first_lastname']."</font></td>
							<td>".$size.$value['RenderFund']['used_by_name']."</font></td>
							<td>".$size.$value['Management']['management_name']."</font></td>
							<td>".$size.$value['CostCenter']['cost_center_name']." (".$value['CostCenter']['cost_center_code'].")</font></td>
							<td>".$size.$value['Badge']['symbol'].number_format($value['RenderFund']['total_price'], 0, null, '.')."</font></td>
							<td>".$size.$value['State']['state']."</font></td>
							<td>".$size.$value['RenderFund']['fund_number']."</font></td>
							<td>".$size.$value['RenderFund']['render_date_end']."</font></td>
					</tr>";
	}
}

$html2 .= "</table><br><br>";
			
$html2 .= 'Informe generado por <font color="#07714B">'.$data['Details']['generated_by'].'</font>';
$html2 .= '<br>';
$html2 .= "Fecha de emisi".utf8_encode("ó")."n: <font color=\"#07714B\">".$data['Details']['date_today']."</font>";

$tcpdf->WriteHTML($html2, true, false, true, false, '');

//$tcpdf->fixHTMLCode($html, 'table tr td { border:1px solid #FF0000;}', '', '');


// se pueden asignar mas datos, ver la documentación de TCPDF

echo $tcpdf->Output('general_report_from_'.$data['Details']['start_date'].'_to_'.$data['Details']['end_date'].'.pdf', 'D');
//echo $tcpdf->Output('mi_archivo.pdf', 'I'); //el pdf se descarga
?>