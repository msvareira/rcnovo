<?php

namespace App\Http\Relatorios;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Helpers\Utils;
use App\Models\OrdemServico;
use App\Models\ServicosOS;
use App\Models\ServicosOSAnexos;

class OrdemServicoPDF extends Fpdf
{
    var $angle=0;

    private $ordem_servico;

    public function setOrdemServico($ordem_servico)
    {
        $this->ordem_servico = $ordem_servico;
    }

    protected $extgstates = array();

    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_put('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }    

    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
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

    function Footer() {
        // Add watermark if the order status is not "Aberta"
        if ($this->ordem_servico->status !== 'Aberta') {            
            $this->setAlpha(0.1);
            $this->SetFont('Arial', 'B', 100);
            $this->SetTextColor(100, 100, 100); // Light pink color
            $this->Rotate(45, 50, 170);
            $this->Text(35, 190, utf8_decode($this->ordem_servico->status), 0, 1, 'C');
            $this->Rotate(0); // Reset rotation
            $this->setAlpha(1);
            $this->SetTextColor(0, 0, 0); // Reset text color to black
        }


    }

    function OrdemServicoTable()
    {
        $ordem_servico = $this->ordem_servico;

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

            $this->MultiCell(190, 10, $servico->pivot->descricao_execucao, 1, 'L');
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
        //$this->Rect(10, $this->GetY(), 190, 20); // Adjust height as needed
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 7, utf8_decode('Número OS: ') . utf8_decode($ordem_servico->id), 0, 1);
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
        $anexos = ServicosOSAnexos::selectRaw('servicos_os_anexos.*, servicos_os.descricao_execucao, servicos.descricao as servico')
            ->join('servicos_os', 'servicos_os.id', 'servicos_os_anexos.servico_os_id')
            ->join('servicos', 'servicos.id', 'servicos_os.servico_id')
            ->whereIn('servico_os_id', $ordem_servico->servicos->pluck('pivot.id'))
            ->get();

        if ($anexos->isNotEmpty()) {
            $this->AddPage();
            $this->setY(30);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode('Anexos dos Serviços'), 0, 1, 'C');

            $count = 0;
            $initialY = $this->GetY(); // Store the initial Y position
            $y = $initialY;

            foreach ($anexos as $anexo) {
                // Get the MIME type of the attachment file
                $mimeType = mime_content_type(storage_path('app/public/' . $anexo->arquivo));

                // Validate the MIME type
                if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {

                    // Add description and service above the image
                    $this->setY($y);
                    $this->SetFont('Arial', '', 12);
                    $this->Image(storage_path('app/public/' . $anexo->arquivo), null, null, 90, 50);
                    
                    $this->setY($y);
                    $this->setX(105);
                    $this->Cell(0, 10, utf8_decode('Serviço: ') . utf8_decode($anexo->servico), 0, 1);
                    $this->setX(105);
                    $this->MultiCell(0, 10, utf8_decode('Descrição: ') . utf8_decode($anexo->descricao_execucao), 0, 1);

                    $count++;
                    $y += 55;
                    
                    

                    // Add a new page after 4 images (2 rows)
                    if ($count % 4 == 0) {
                        $this->AddPage();
                        $this->setY(30);
                        $y = $initialY;
                        $this->SetFont('Arial', 'B', 12);                        
                        $this->Cell(0, 10, utf8_decode('Anexos dos Serviços'), 0, 1, 'C');
                        $initialY = $this->GetY(); // Reset the initial Y position for the new page
                    }
                }
            }
        }
    }
}
