<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dokumen;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {
        require_once('../vendor/tecnickcom/tcpdf/examples/tcpdf_include.php');
   
        require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
        require_once('../vendor/setasign/fpdi/src/autoload.php');
        $dokumen = Dokumen::find($id);

        $pdf = new Fpdi('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        $file_pdf = $pdf->setSourceFile(public_path('storage\app\public\Upload\\').$upload->file);
       
        for ($i = 1; $i <= $file_pdf; $i++)
			{
				$pdf->AddPage();
				$page = $pdf->importPage($i);
				$pdf->useTemplate($page, 0, 0);
			}

            $style = array(
                'border' => 3,
                'padding' => 2,
             
                'fgcolor' => array(50,50,50),
                'bgcolor' => array(255,255,255), 
                
            );

            $file_name = 'Dokumen_Sudah_di_Tanda_Tangan'. '-'. rand(11111, 99999) . '.pdf';
            


        $pdf->SetAutoPageBreak(0, PDF_MARGIN_BOTTOM);

   
        
      
        
        $pdf->SetFont('times', '', 8);
        $text = "Dokumen ini  ditandatangani:".auth()->user()->name;
       

       
            
 
        


        $pdf->write2DBarcode($encryptedString, 'QRCODE,M', 20, 275, 15, 15, $style, 'N');
        $pdf->Ln();
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $pdf->SetFillColor(0,0,0);
        $pdf->SetTextColor(0,0,0);
        $pdf->MultiCell(70, 300, $text, 0, 'J', false, 1, 125, 249, true, 0, false, true, 0, 'T', false);
        $pdf->setSignatureAppearance(180, 60, 15, 15);
        
        
       
       
        
        $detail = array(
            "countryName" => "ID",
			"stateOrProvinceName" => "Bandung",
			"localityName" => "Bandung",
			"organizationName" => "Taris Monica",
			"commonName" => auth()->user()->name
        );
        $privKey = openssl_pkey_new(array(
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        ));
        $certificate = openssl_csr_new($detail, $privKey, array('digest_alg' => 'sha512'));
		openssl_csr_export($certificate, $certificateout);
		openssl_pkey_export($privKey, $pkeyout);
        

        $x509 = openssl_csr_sign($certificate,null,$privKey, $day=365, array('digest_alg' => 'sha512'));
		openssl_x509_export($x509, $crtout);
        $info = array('Location' => 'Bandung', 'Name' => auth()->user()->name, 'Organization' => 'Taris Monica');
        $pdf->setSignature($crtout, $pkeyout, 'pdf', '', 1, $info);
        ob_end_clean();
        $file_end = $pdf->Output($file_name, 'I');
    }
}
