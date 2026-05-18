<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_pdo.php';

class PdfReporte
{
    private array $pages = [];
    private string $content = '';
    private float $y = 548;
    private int $fontSize = 8;

    public function __construct()
    {
        $this->addPage();
    }

    public function addPage(): void
    {
        if ($this->content !== '') {
            $this->pages[] = $this->content;
        }
        $this->content = '';
        $this->y = 548;
    }

    public function title(string $text): void
    {
        $this->line($text, 16, 35, 18);
        $this->line(str_repeat('=', min(80, strlen($this->clean($text)) + 10)), 8, 35, 14);
    }

    public function section(string $text): void
    {
        $this->line('');
        $this->line($text, 12, 35, 16);
        $this->line(str_repeat('-', min(100, strlen($this->clean($text)) + 20)), 8, 35, 12);
    }

    public function line(string $text = '', ?int $size = null, int $x = 35, int $gap = 11): void
    {
        if ($this->y < 45) {
            $this->addPage();
        }

        $size = $size ?? $this->fontSize;
        $safeText = $this->escape($this->clean($text));
        $this->content .= "BT /F1 {$size} Tf {$x} {$this->y} Td ({$safeText}) Tj ET\n";
        $this->y -= $gap;
    }

    public function output(): string
    {
        if ($this->content !== '') {
            $this->pages[] = $this->content;
            $this->content = '';
        }

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';

        $kids = [];
        for ($i = 0; $i < count($this->pages); $i++) {
            $kids[] = (4 + ($i * 2)) . ' 0 R';
        }
        $objects[] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($this->pages) . ' >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>';

        foreach ($this->pages as $index => $pageContent) {
            $pageObject = 4 + ($index * 2);
            $contentObject = $pageObject + 1;
            $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentObject} 0 R >>";
            $objects[] = "<< /Length " . strlen($pageContent) . " >>\nstream\n{$pageContent}endstream";
        }

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $number => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($number + 1) . " 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function clean(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $converted = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        return $converted !== false ? $converted : $text;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}

function valor($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }
    return (string) $value;
}

function ajustar(string $text, int $width): string
{
    $text = preg_replace('/\s+/', ' ', valor($text));
    if (strlen($text) > $width) {
        return substr($text, 0, max(0, $width - 3)) . '...';
    }
    return str_pad($text, $width);
}

function fila(array $values, array $widths): string
{
    $parts = [];
    foreach ($values as $index => $value) {
        $parts[] = ajustar((string) $value, $widths[$index]);
    }
    return implode(' | ', $parts);
}

function tabla(PdfReporte $pdf, string $titulo, array $cabeceras, array $filas, array $campos, array $anchos): void
{
    $pdf->section($titulo . ' (' . count($filas) . ')');
    $pdf->line(fila($cabeceras, $anchos));
    $pdf->line(fila(array_fill(0, count($cabeceras), str_repeat('-', 20)), $anchos));

    if (empty($filas)) {
        $pdf->line('No hay datos.');
        return;
    }

    foreach ($filas as $fila) {
        $valores = [];
        foreach ($campos as $campo) {
            $valores[] = valor($fila[$campo] ?? '');
        }
        $pdf->line(fila($valores, $anchos));
    }
}

$usuarios = $pdo->query("SELECT nombre_usuario, nombre, apellidos, correo_electronico, telefono, domicilio, rol FROM usuarios ORDER BY nombre_usuario ASC")->fetchAll(PDO::FETCH_ASSOC);
$taxistas = $pdo->query("SELECT numero_licencia, nombre, apellidos, telefono, horario FROM taxistas ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$reservas = $pdo->query("SELECT id, nombre_usuario, numero_licencia, fecha_reserva, fecha_recogida, hora_recogida, direccion_recogida, estado FROM reservas ORDER BY fecha_recogida DESC, hora_recogida DESC")->fetchAll(PDO::FETCH_ASSOC);

try {
    $mensajes = $pdo->query("SELECT id, nombre_usuario, opinion, mensaje, fecha_creacion FROM mensajes_contacto ORDER BY fecha_creacion DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensajes = [];
}

$pdf = new PdfReporte();
$pdf->title('Mobility Alliance - Exportacion de datos');
$pdf->line('Generado: ' . date('d/m/Y H:i'));
$pdf->line('Administrador: ' . $_SESSION['usuario']);

tabla($pdf, 'Usuarios', ['Usuario', 'Nombre', 'Apellidos', 'Correo', 'Telefono', 'Rol'], $usuarios, ['nombre_usuario', 'nombre', 'apellidos', 'correo_electronico', 'telefono', 'rol'], [16, 16, 20, 36, 14, 10]);
tabla($pdf, 'Taxistas', ['Licencia', 'Nombre', 'Apellidos', 'Telefono', 'Horario'], $taxistas, ['numero_licencia', 'nombre', 'apellidos', 'telefono', 'horario'], [14, 18, 26, 16, 16]);
tabla($pdf, 'Reservas', ['ID', 'Usuario', 'Licencia', 'Fecha', 'Hora', 'Direccion', 'Estado'], $reservas, ['id', 'nombre_usuario', 'numero_licencia', 'fecha_recogida', 'hora_recogida', 'direccion_recogida', 'estado'], [5, 16, 12, 12, 10, 44, 12]);
tabla($pdf, 'Mensajes de contacto', ['ID', 'Usuario', 'Opinion', 'Mensaje', 'Fecha'], $mensajes, ['id', 'nombre_usuario', 'opinion', 'mensaje', 'fecha_creacion'], [5, 16, 14, 58, 20]);

$pdfContent = $pdf->output();
$filename = 'mobility_alliance_datos_' . date('Ymd_His') . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($pdfContent));
echo $pdfContent;
exit;
