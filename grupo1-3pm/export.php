<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// === Autoload de Composer ===
require_once __DIR__ . '/vendor/autoload.php';

// === Modelos ===
require_once 'models/Book.php';

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$book = new Book();

// === Recibir filtros desde GET ===
$searchTerm = $_GET['buscar'] ?? '';
$genre      = $_GET['genre'] ?? '';
$year       = $_GET['year'] ?? '';
$order      = $_GET['order'] ?? 'desc';
$type       = $_GET['type'] ?? 'pdf'; // tipo de exportación

$data = $book->getBooks($searchTerm, $genre, $year, $order);

if (!$data) {
    die("No hay datos para exportar según los filtros actuales.");
}

// === Exportar a PDF (usando mPDF) ===
if ($type === 'pdf') {
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_left' => 15,
        'margin_right' => 15
    ]);

    $stylesheet = file_get_contents(__DIR__ . '/css/export.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // === Contenido HTML ===
    ob_start();
    ?>
    <div class="header">
        <h2>Biblioteca Nocturna</h2>
        <div class="subtitle">Reporte de Libros</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Año</th>
                <th>Género</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Biblioteca Nocturna © <?= date('Y') ?> — Reporte generado automáticamente
    </div>
    <?php
    $html = ob_get_clean();

    // === Renderizar HTML en el PDF ===
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->Output('biblioteca_nocturna.pdf', 'D');
    exit;
}

// === Exportar a Excel ===
if ($type === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Libros');

    // Encabezados
    $sheet->fromArray(['ID', 'Título', 'Autor', 'Año', 'Género'], null, 'A1');

    // Datos
    $rowNum = 2;
    foreach ($data as $row) {
        $sheet->fromArray([
            $row['id'],
            $row['title'],
            $row['author'],
            $row['year'],
            $row['genre']
        ], null, "A$rowNum");
        $rowNum++;
    }

    // Estilo básico
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    $sheet->getColumnDimension('B')->setWidth(40);
    $sheet->getColumnDimension('C')->setWidth(30);

    // Salida al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="biblioteca_nocturna.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

die("Tipo de exportación no válido.");
