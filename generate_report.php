<?php
session_start();
require 'db.php'; 
require 'fpdf/fpdf.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access");
}


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'UMed Voting Session Report', 0, 1, 'C');
$pdf->Ln(10);

$tables = ['votes', 'requests', 'speaking_map', 'voting_sessions'];
foreach ($tables as $table) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, strtoupper($table), 0, 1);
    $pdf->SetFont('Arial', '', 10);

    $stmt = $pdo->query("SELECT * FROM $table");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            $pdf->Cell(50, 10, ucfirst($key) . ': ' . $value, 0, 1);
        }
        $pdf->Ln(5);
    }
    $pdf->Ln(10);
}

$pdf->Output('D', 'Voting_Report.pdf'); 
?>
