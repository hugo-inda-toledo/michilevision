<?php
App::import('Vendor','tcpdf/tcpdf');

$tcpdf = new TCPDF();
$textfont = 'freesans';

$tcpdf->SetAuthor("Generado automaticamente por michilevision.cl para ".$data['Details']['name'].' '.$data['Details']['first_lastname']);
$tcpdf->SetAutoPageBreak( false );
$tcpdf->setHeaderFont(array($textfont,'',10));
$tcpdf->xheadercolor = array(255,255,255);
$tcpdf->xheadertext = '';
$tcpdf->xfootertext = date('Y').' Desarrollado por el departamento de Tecnologias de informacion, Red Televisiva Chilevision.';

$html.='';

foreach($data['Budget'] as $budget)
{
	if($budget['selected'] == 1)
	{
		$tcpdf->SetTitle("Orden de compra para el proveedor ".$budget['provider_name']);
	}
}



 
 /*NUEVA HOJA*/
 
$tcpdf->AddPage();
			
// Ahora imprimimos el contenido de la pagina en una posición determinada
// estos datos son un ejemplo, y en mi ejemplo hay un pequeño texto y una imagen.
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'B',10);

$fontSignOpen = "<font size=\"6\">";
$fontSignClose = "</font>";
$fecha = date('d-m-Y');

$html .= 	"<br><br><table>
					<tr>
						<td>&nbsp;</td>
						<td>Orden De Compra</td>
						<td><table><tr><td>Fecha</td></tr><tr><td>".$fecha."</td></tr></table></td>
					</tr>
				</table>";

if($data['PurchaseOrder']['invoice_to'] == 'Turner')
{
	$html.='<br><br><table><tr><td>'.$fontSignOpen.'<strong>Inversiones Turner<br>International I Limitada</strong><br>Ines Matte Urrejola #0890 - Providencia<br>Fono: (562) 461 5100<br>R.U.T: 76.109.207-7'.$fontSignClose.'</td><td>&nbsp;</td><td>N° '.$data['PurchaseOrder']['order_number'].'</td></tr></table><br><br>';
}

if($data['PurchaseOrder']['invoice_to'] == 'Chilevision')
{
	$html.='<br><br><table><tr><td>'.$fontSignOpen.'<strong>Red De Television Chilevision S.A</strong><br>Ines Matte Urrejola #0890 - Providencia<br>Fono: (562) 461 5100<br>Casilla 16547 - CORREO 9<br>R.U.T: 96.669.520-K'.$fontSignClose.'</td><td>&nbsp;</td><td>N° '.$data['PurchaseOrder']['order_number'].'</td></tr></table><br><br>';
}

foreach($data['Budget'] as $budget)
{
	if($budget['selected'] == 1)
	{
		$fontDataOpen = "<font size=\"8\">";
		$fontDataClose = "</font>";
		
		$html .= 	$fontDataOpen."<table>
							<tr>
								<td>Señor(es) : </td>
								<td>".$budget['ProviderSelected']['provider_name']."</td>
								<td>Rut : </td>
								<td>".$budget['ProviderSelected']['provider_dni']."</td>
							</tr>
							<tr>
								<td>Direccion: </td>
								<td colspan=\"3\">".$budget['ProviderSelected']['provider_address']."</td>
							</tr>
							<tr>
								<td>Telefono: </td>";
		if($budget['ProviderSelected']['provider_phone'] != '')
		{	
			$html.= "<td colspan=\"3\">".$budget['ProviderSelected']['provider_phone']."</td>";
		}
		else
		{
			$html.= "<td colspan=\"3\">N/A</td>";
		}
		
		$html .= "</tr></table>".$fontDataClose;
	}
}

$fontInfoOpen = "<font size=\"6\">";
$fontInfoClose = "</font>";

$html .= "<br><br><p align=\"center\">".$fontInfoOpen."Tenemos el agrado de comunicarle que, conforme a la cotización en referencia, hemos decidido adquirir lo siguiente:</p>".$fontInfoClose."<br><br>";

$fontTableOpen = "<font size=\"9\">";
$fontTableClose = "</font>";

$html .= 	"<table border=\"0.5\">
					<tr>
						<th>".$fontTableOpen."Item".$fontTableClose."</th>
						<th>".$fontTableOpen."Detalle".$fontTableClose."<br><font size=\"5\">(nombre, artículo, marca, característica, etc.)</font></th>
						<th>".$fontTableOpen."Cantidad".$fontTableClose."</th>
						<th>".$fontTableOpen."Precio Unitario".$fontTableClose."</th>
						<th>".$fontTableOpen."Total".$fontTableClose."</th>
					</tr>";

$x=1;

$fontContentOpen = "<font size=\"7\">";
$fontContentClose = "</font>";

foreach($data['PurchaseOrderRequest'] as $request)
{
	$total = $request['net_price'] * $request['quantity'];
	$html .= "<tr>
					<td>".$fontContentOpen." ".$x." ".$fontContentClose."</td>
					<td>".$fontContentOpen." ".$request['description']." ".$fontContentClose."</td>
					<td>".$fontContentOpen." ".$request['quantity']." ".$fontContentClose."</td>
					<td>".$fontContentOpen." ".$data['Badge']['symbol']." ".number_format($request['net_price'], 0, null, '.')." ".$fontContentClose."</td>
					<td>".$fontContentOpen." ".$data['Badge']['symbol']." ".number_format($total, 0, null, '.')." ".$fontContentClose."</td>
				</tr>";
}					

$html .= "</table><br><br><br>";

$html .= 	"<table>
						<tr>
							<td colspan=\"5\"></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>Total Neto: </td>
							<td>".$data['Badge']['symbol']." ".number_format($data['PurchaseOrder']['grand_net_total_price'], 0, null, '.')."</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>";
				if($data['ApprovedOrder']['tax_id'] != 0)
				{
					$html .= "<td>".$data['Tax'] ['tax_name'].": </td>";
					$html .= "<td>".number_format($data['Tax']['value'], 0, null, '.')."%</td>";
					
					$porc = ($data['Tax']['value']/100)*$data['PurchaseOrder']['grand_net_total_price'];
					$total = $data['PurchaseOrder']['grand_net_total_price'] + $porc;
					
					$total = number_format($total, 0, null, '.');
				}
				else
				{
					$html .= '<td>'.$data['ApprovedOrder']['import_tax_name'].': </td>';
					$html .= '<td>'.number_format($data['ApprovedOrder']['import_tax_value'], 1).'%</td>';
					
					$porc = ($data['ApprovedOrder']['import_tax_value']/100)*$data['PurchaseOrder']['grand_net_total_price'];
					$total = $data['PurchaseOrder']['grand_net_total_price'] + $porc;
					
					$total = number_format($total, 0, null, '.');
				}
							
		$html .= "</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>Total: </td>
							<td>".$data['Badge']['symbol']." ".$total."</td>
						</tr>
					</table>";
					
$html .= "<br><br>
					<p align=\"center\">
						<ul>
							<li>".$fontInfoOpen."Se deberá adjuntar una copia de la presente Orden de Compra a la Factura o Guia de Despacho en el momento de la entrega. ".$fontInfoClose."</li>
							<li>".$fontInfoOpen."Servicios, hacer llegar la factura al Depto. de proveedores, ubicado en Inés Matte Urrejola #0890 Providencia. ".$fontInfoClose."</li>
							<li>".$fontInfoOpen."NO se aceptarán entregas parciales, a menos que se indique expresamente en la Orden de Compra.".$fontInfoClose."</li>
						</ul>
					</p><br><br>";
					
if($data['PurchaseOrder']['invoice_to'] == 'Turner')
{
	$chilevisionOturner ='Inversiones Turner International I Limitada';
	$elRut = '76.109.205-7';
	$telefono = '461 5100';
	$direccion = "Ines Matte Urrejola #0890 - Providencia - Santiago";
}

if($data['PurchaseOrder']['invoice_to'] == 'Chilevision')
{
	$chilevisionOturner ='Red de Television Chilevision S.A';
	$elRut = '96.669.520-K';
	$telefono = '461 5100';
	$direccion = "Ines Matte Urrejola #0890 - Providencia";
}

$fontEndOpen = "<font size=\"7\">";
$fontEndClose = "</font>";

$html .= $fontEndOpen."<table border=\"0.5\">
					<tr>
						<td>
							<table>
								<tr>
									<td>Fecha de Entrega: </td>
									<td>Inmediata</td>
								</tr>
								<tr>
									<td>Forma de Pago: </td>
									<td>".$data['ApprovedOrder']['pay_type']."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>Facturar a: </td>
									<td colspan =\"3\">".$chilevisionOturner."</td>
								</tr>
								<tr>
									<td>Rut: </td>
									<td>".$elRut."</td>
									<td>Fono: </td>
									<td>".$telefono."</td>
								</tr>
								<tr>
									<td>Dirección: </td>
									<td colspan =\"3\">".$direccion."</td>
								</tr>
								<tr>
									<td>Giro: </td>
									<td colspan =\"3\">El Giro</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td>Pedido de Materiales: </td>
									<td>".$data['PurchaseOrder']['request_number']."</td>
									<td>Nombre Item: </td>
									<td></td>
								</tr>
								<tr>
									<td>Area</td>
									<td colspan =\"3\">".$data['Management']['management_name']."</td>
								</tr>
								<tr>
									<td>Nombre del programa</td>
									<td colspan =\"3\">".$data['CostCenter']['cost_center_name']." (".$data['CostCenter']['cost_center_code'].")</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>".$fontEndClose."<br><br><br><br><br><br>";

$html .= "<table align=\"center\">
					<tr>
						<td><br><br><br><br><br><img src=\"img/signs/firma_adquisiciones.gif\"></td>
						<td><br><br><img src=\"img/signs/firma_administracion.gif\"></td>
					</tr>
				</table>";


// configuramos la calidad de JPEG
if($data['PurchaseOrder']['invoice_to'] == 'Chilevision')
{
	$tcpdf->Image('img/logo_example.png', -5, 4, 80, 0, '', '', '', false, 200); 
}

if($data['PurchaseOrder']['invoice_to'] == 'Turner')
{
	$tcpdf->Image('img/logo_turner.png', 2, 4, 50, 0, '', '', '', false, 150); 
}

$tcpdf->setJPEGQuality(100);

$html .= "<br><br><br><br><br><br><br>";

$tcpdf->WriteHTML($html, true, false, true, false, '');


//$tcpdf->fixHTMLCode($html, 'table tr td { border:1px solid #FF0000;}', '', '');


// se pueden asignar mas datos, ver la documentación de TCPDF

echo $tcpdf->Output('files/purchase_orders/orders/'.$data['PurchaseOrder']['order_number'].'.pdf', 'F');
//echo $tcpdf->Output('mi_archivo.pdf', 'I'); //el pdf se descarga
?>