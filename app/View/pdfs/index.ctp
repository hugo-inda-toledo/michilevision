<?php
$pdf->core->addPage('', 'USLETTER');
$pdf->core->setFont('helvetica', '', 12);
$pdf->core->cell(30, 0, 'Hola mundo');
$pdf->core->Output('example_001.pdf', 'I');
?>