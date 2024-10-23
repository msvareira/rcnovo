<?php

namespace App\Http\Relatorios;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Preventivas;
use App\Models\AnexosPreventiva;



class PreventivaPDF extends Fpdf
{
    private $preventiva;

    public function setPreventiva($preventiva)
    {
        $this->preventiva = $preventiva;
    }

    function Header()
    {
        // Add logo
        $this->Image(storage_path('app/assets/logo.png'), 10, 10, 60); // Adjust the path and size as needed

        // Add contact information
        $this->SetFont('Arial', '', 10);
        $this->SetY(12);
        $this->SetX(140); // Adjust X position to align to the right side of the page
        $this->Cell(50, 5, utf8_decode('Fone: (51) 9550-5816 / 3028-7873'), 0, 1, 'L'); // Align text to the left within the cell
        $this->SetX(140); // Adjust X position to align to the right side of the page
        $this->Cell(50, 5, utf8_decode('Email: rcrefrig@hotmail.com'), 0, 1, 'L'); // Align text to the left within the cell
        $this->SetX(140); // Maintain the same X position for the next line
        $this->Cell(50, 5, utf8_decode('CNPJ: 00.317.682/0001-60'), 0, 1, 'L'); // Align text to the left within the cell
        // Draw border for header
        $this->Rect(10, 10, 190, 20);
    }

    function Footer()
    {
        // Add footer text
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function PreventivaTable()
    {
        $preventiva = $this->preventiva;

        $this->setY(30);
        // Draw border for the page
        $this->Rect(10, 10, 190, 277);

        // Draw border for client data
        $this->Rect(10, 30, 190, 32); // Adjust height as needed

        $this->SetFont('Arial', '', 12);
        $this->SetY(32); // Adjust Y position to fit within the border
        $this->Cell(0, 7, utf8_decode('Cliente: ') . utf8_decode($preventiva->cliente->razao), 0, 1);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, utf8_decode('CNPJ: ') . utf8_decode($preventiva->cliente->cpf_cnpj), 0, 1);

        $this->Cell(0, 7, utf8_decode('Logradouro: ') . utf8_decode($preventiva->cliente->rua), 0, 1);

        $this->Cell(0, 7, utf8_decode('Número: ') . utf8_decode($preventiva->cliente->numero) . utf8_decode('       Complemento: ') . utf8_decode($preventiva->cliente->complemento) . utf8_decode('       Estado: ') . utf8_decode($preventiva->cliente->estado) . utf8_decode('       Bairro: ') . utf8_decode($preventiva->cliente->bairro), 0, 1);

        $this->Ln(2);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, utf8_decode('Funcionario: ') . utf8_decode($preventiva->funcionario->nome), 0, 1);
        $this->Cell(0, 7, utf8_decode('Data de Execução: ') . date('d/m/Y', strtotime($preventiva->data_execucao)), 0, 1);

        $descricao = explode('-', $preventiva->descricao);


        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Descrição da Preventiva: '), 0, 1);

        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 7, utf8_decode($descricao[0]), 0, 1);

        $this->SetY($this->GetY() + 10);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Serviço Executado: '), 0, 1);

        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 7, utf8_decode(isset($descricao[1])?$descricao[1]:''), 0, 1);


        // Add space before the observation box
        $this->Ln(10);

        // Draw border for observation box
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Observações:'), 0, 1);

        // Draw borders for each line within the observation box
        $currentY = $this->GetY();
        for ($i = 0; $i < 3; $i++) { // Adjust the number of lines as needed
            $this->Rect(10, $currentY + ($i * 7), 190, 7); // Adjust height of each line as needed
        }
        $this->Ln(21); // Adjust to leave space for manual writing

        // Draw border for order number and signatures
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Número Preventiva: ') . utf8_decode($preventiva->id), 0, 1);
        $this->Ln(7); // Adjust to leave space for signatures

        $this->setY($this->GetY() + 10);
        // Add signature fields
        $this->SetFont('Arial', '', 12);
        $this->Cell(90, 7, utf8_decode('Assinatura do Prestador de Serviço'), 0, 0, 'C');
        $this->Cell(105, 7, utf8_decode('Assinatura do Cliente'), 0, 1, 'C');

        // Draw lines above each signature
        $this->Line(15, $this->GetY() - 7, 95, $this->GetY() - 7); // Line for service provider signature
        $this->Line(110, $this->GetY() - 7, 195, $this->GetY() - 7); // Line for client signature

        $this->Ln(10); // Adjust to leave space for signatures

        // Check if there are any attachments
        $anexos = AnexosPreventiva::where('preventiva_id', $preventiva->id)->get();

        if ($anexos->isNotEmpty()) {
            $this->AddPage();
            $this->setY(30);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode('Anexos da Preventiva'), 0, 1, 'C');

            foreach ($anexos as $anexo) {
                // Get image dimensions
                list($width, $height) = getimagesize(storage_path('app/public/' . $anexo->file_path));
                
                // Calculate the new dimensions to fit within the page
                $maxWidth = 170;
                $maxHeight = 257;
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = $width * $ratio;
                $newHeight = $height * $ratio;

                // Check if adding the image would overflow the page
                if ($this->GetY() + $newHeight > $this->PageBreakTrigger) {
                    $this->AddPage();
                    $this->setY(35);
                }

                $this->Image(storage_path('app/public/' . $anexo->file_path), null, null, $newWidth, $newHeight);
                $this->Ln(10);
            }
        }
    }
}
?>