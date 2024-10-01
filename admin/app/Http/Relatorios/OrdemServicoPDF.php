<?php

namespace App\Http\Relatorios;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Helpers\Utils;

class OrdemServicoPDF extends Fpdf
{
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
        // Draw border for footer
        $this->Rect(10, -25, 190, 15);
        
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo(), 0, 0, 'C');
    }

    function OrdemServicoTable($ordem_servico)
    {
        $this->setY(30);
        // Draw border for the page
        $this->Rect(10, 10, 190, 277);
        
        // Draw border for client data
        $this->Rect(10, 30, 190, 32); // Adjust height as needed

        $this->SetFont('Arial', '', 12);
        $this->SetY(32); // Adjust Y position to fit within the border
        $this->Cell(0, 7, utf8_decode('Razão Social: ') . utf8_decode($ordem_servico->cliente->razao), 0, 1);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, utf8_decode('CNPJ: ') . utf8_decode($ordem_servico->cliente->cpf_cnpj) . utf8_decode('       Fantasia: ') . utf8_decode($ordem_servico->cliente->fantasia), 0, 1);

        $this->Cell(0, 7, utf8_decode('Logradouro: ') . utf8_decode($ordem_servico->cliente->rua), 0, 1);

        $this->Cell(0, 7, utf8_decode('Número: ') . utf8_decode($ordem_servico->cliente->numero) . utf8_decode('       Complemento: ') . utf8_decode($ordem_servico->cliente->complemento) . utf8_decode('       Estado: ') . utf8_decode($ordem_servico->cliente->estado) . utf8_decode('       Bairro: ') . utf8_decode($ordem_servico->cliente->bairro), 0, 1);

        $this->Ln(2);        

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 7, utf8_decode('Funcionario: ') . utf8_decode($ordem_servico->funcionario->nome), 0, 1);
        $this->Cell(0, 7, utf8_decode('Data: ') . date('d/m/Y', strtotime($ordem_servico->data)), 0, 1);
        $this->Cell(0, 7, utf8_decode('Solicitante: ') . utf8_decode($ordem_servico->solicitante), 0, 1);

        $this->SetFont('Arial', 'B', 12);


        //Serviços

        $this->Rect(10, 83, 190, 10); // Adjust height as needed

        $this->SetY(83); // Adjust Y position to fit within the border
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, utf8_decode('Serviço'), 1, 0, 'L');
        $this->Cell(30, 10, utf8_decode('Duração'), 1, 0, 'C');
        $this->Cell(65, 10, utf8_decode('Valor'), 1, 1, 'R');

        foreach ($ordem_servico->servicos as $servico) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(95, 10, utf8_decode($servico->descricao), 1, 0, 'L');
            $this->Cell(30, 10, utf8_decode($servico->pivot->duracao . 'H'), 1, 0, 'C');
            $this->Cell(65, 10, utf8_decode(Utils::formatarnumero($servico->pivot->valor)), 1, 1, 'R');

            //$this->MultiCell(190, 10, $servico->pivot->descricao_execucao, 1, 'L');

            // Add a test text with 250 characters
            $testText = str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 5);
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(190, 5, utf8_decode($testText), 1, 'L');
        }

        // Calculate total hours and total value
        $totalHoras = 0;
        $totalValor = 0;

        foreach ($ordem_servico->servicos as $servico) {
            $totalHoras += $servico->pivot->duracao;
            $totalValor += $servico->pivot->valor;
        }

        // Add total hours and total value to the PDF
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, utf8_decode('Total'), 1, 0, 'L');
        $this->Cell(30, 10, utf8_decode($totalHoras . 'H'), 1, 0, 'C');
        $this->Cell(65, 10, utf8_decode(Utils::formatarnumero($totalValor)), 1, 1, 'R');

        // Add space before the observation box
        $this->Ln(10);

        // Calculate the Y position to place the observation box at the bottom of the page
        $bottomY = 277 - 28 - 20 - 7 - 7 - 10; // Adjust the values as needed to fit the content


        if ($this->GetY() > $bottomY) {
            $this->AddPage();
            // Draw border for client data
            $this->Rect(10, 30, 190, 175); // Adjust height as needed

        }

        // Draw border for observation box
        $this->SetY($bottomY);
        $this->Rect(10, $this->GetY(), 190, 28); // Adjust height as needed
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Observações:'), 0, 1);

        // Draw borders for each line within the observation box
        $currentY = $this->GetY();
        for ($i = 0; $i < 3; $i++) { // Adjust the number of lines as needed
            $this->Rect(10, $currentY + ($i * 7), 190, 7); // Adjust height of each line as needed
        }
        $this->Ln(21); // Adjust to leave space for manual writing

        // Draw border for order number and signatures
        $this->Rect(10, $this->GetY(), 190, 20); // Adjust height as needed
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Número OS: ') . utf8_decode($ordem_servico->numero), 0, 1);
        $this->Ln(7); // Adjust to leave space for signatures

        // Add signature fields
        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 7, utf8_decode('Assinatura do Prestador de Serviço'), 0, 0, 'C');
        $this->Cell(95, 7, utf8_decode('Assinatura do Cliente'), 0, 1, 'C');
        $this->Ln(10); // Adjust to leave space for signatures
    }
}
