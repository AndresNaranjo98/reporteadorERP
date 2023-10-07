<?php

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit();
}

require('../db/connection.php');

require('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$fechaInicial = $_POST['fechaInicial'];
$fechaFinal = $_POST['fechaFinal'];
$empresa = $_POST['empresa'];

try {

    $stmt = $conexion->prepare("SELECT Ventas.idVentas,
                                        Ventas.folio,
                                        Clientes.nombre AS cliente,
                                        Ventas.folioCFDI,
                                        Productos.nombre AS producto,
                                        DetallesVentas.cantidad AS cantidad,
                                        DetallesVentas.precio AS precio,
                                        (DetallesVentas.cantidad * DetallesVentas.precio) AS importeProducto,
                                        DetallesVentas.iva AS ivaProducto,
                                        Empresa.nombre AS empresa,
                                        Ventas.fechaEntrega,
                                        Ventas.fechaCobro,
                                        EstadoVentas.nombre AS estadoVenta,
                                        (DetallesVentas.cantidad * DetallesVentas.precio) + (DetallesVentas.iva) AS total,
                                        Moneda.descripcion AS moneda,
                                        Ventas.paridad,
                                        ((DetallesVentas.cantidad * DetallesVentas.precio) + (DetallesVentas.iva)) * (Ventas.paridad) AS totalNacional
                                        FROM Ventas
                                        JOIN Clientes Clientes ON Ventas.Clientes_idClientes = Clientes.idClientes
                                        JOIN EstadoVentas EstadoVentas ON Ventas.EstadoVentas_idEstadoVentas = EstadoVentas.idEstadoVentas
                                        JOIN Moneda Moneda ON Ventas.Moneda_idMoneda = Moneda.idMoneda
                                        JOIN DetallesVentas DetallesVentas ON DetallesVentas.Ventas_idVentas = Ventas.idVentas
                                        JOIN Empresa Empresa ON Clientes.Empresa_idEmpresa = Empresa.idEmpresa
                                        JOIN Productos Productos ON DetallesVentas.Productos_idProductos = Productos.idProductos
                                        WHERE Ventas.fechaEntrega BETWEEN :fechaInicial AND :fechaFinal
                                        AND Empresa.nombre = :empresa
                                        ORDER BY idVentas;");
    $stmt->bindParam('fechaInicial', $fechaInicial);
    $stmt->bindParam('fechaFinal', $fechaFinal);
    $stmt->bindParam('empresa', $empresa);
    $stmt->execute();

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'idVenta');
    $sheet->setCellValue('B1', 'Folio');
    $sheet->setCellValue('C1', 'Cliente');
    $sheet->setCellValue('D1', 'Folio CFDI');
    $sheet->setCellValue('E1', 'Producto');
    $sheet->setCellValue('F1', 'Cantidad');
    $sheet->setCellValue('G1', 'Precio');
    $sheet->setCellValue('H1', 'Importe');
    $sheet->setCellValue('I1', 'I.V.A Producto');
    $sheet->setCellValue('J1', 'Empresa');
    $sheet->setCellValue('K1', 'Fecha de Entrega');
    $sheet->setCellValue('L1', 'Fecha de Cobro');
    $sheet->setCellValue('M1', 'Estado de Venta');
    $sheet->setCellValue('N1', 'Total');
    $sheet->setCellValue('O1', 'Moneda');
    $sheet->setCellValue('P1', 'Paridad');
    $sheet->setCellValue('Q1', 'Total Nacional');

    
    $fila = 2;

    while ($filaDatos = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $fila, $filaDatos['idVentas']);
        $sheet->setCellValue('B' . $fila, $filaDatos['folio']);
        $sheet->setCellValue('C' . $fila, $filaDatos['cliente']);
        $sheet->setCellValue('D' . $fila, $filaDatos['folioCFDI']);
        $sheet->setCellValue('E' . $fila, $filaDatos['producto']);
        $sheet->setCellValue('F' . $fila, $filaDatos['cantidad']);
        $sheet->setCellValue('G' . $fila, $filaDatos['precio']);
        $sheet->setCellValue('H' . $fila, $filaDatos['importeProducto']);
        $sheet->setCellValue('I' . $fila, $filaDatos['ivaProducto']);
        $sheet->setCellValue('J' . $fila, $filaDatos['empresa']);
        $sheet->setCellValue('K' . $fila, $filaDatos['fechaEntrega']);
        $sheet->setCellValue('L' . $fila, $filaDatos['fechaCobro']);
        $sheet->setCellValue('M' . $fila, $filaDatos['estadoVenta']);
        $sheet->setCellValue('N' . $fila, $filaDatos['total']);
        $sheet->setCellValue('O' . $fila, $filaDatos['moneda']);
        $sheet->setCellValue('P' . $fila, $filaDatos['paridad']);
        $sheet->setCellValue('Q' . $fila, $filaDatos['totalNacional']);
        $fila++;
        $sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(70);
        $sheet->getColumnDimension('D')->setWidth(60);
        $sheet->getColumnDimension('E')->setWidth(60);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(70);
        $sheet->getColumnDimension('K')->setWidth(30);
        $sheet->getColumnDimension('L')->setWidth(30);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(30);
        $style = $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1');
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff48c9b0');
    }

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte Ventas '.$empresa.'.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
