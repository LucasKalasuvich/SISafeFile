<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Dokumen\StoreRequest;
use App\Http\Requests\Dokumen\UpdateRequest;
use App\Models\Dokumen;
use setasign\Fpdi\Tcpdf\Fpdi;


class DokumenController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['dokumens'] = Dokumen::where('upload_by', \Auth::user()->id)->get();

        return view('dokumen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('file_dokumen')) {
            $File = 'file_dokumen_' . date('Ymdhis').'.pdf';
            $Path = base_path().'/public/uploads';
            $request->file('file_dokumen')->move($Path, $File);
            $input['file_dokumen'] = $File;
        }

        
        $input['upload_by'] = auth()->user()->id; 
        Dokumen::create($input);

        alert()->success('Data berhasil disimpan', 'Berhasil');
        return redirect('dokumen');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['dokumen'] = Dokumen::find($id);
        return view('dokumen.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $model = Dokumen::find($id);
        $input = $request->all();
        if($request->hasFile('file_dokumen')){
            $File = 'file_dokumen_' . date('Ymdhis').'.pdf';
            $Path = base_path().'/'.'public'.'/uploads';
            $request->file('file_dokumen')->move($Path, $File);

            $input['file_dokumen'] = $File;
        }
        $model->update($input);

        alert()->success('Data berhasil diubah', 'Berhasil');
        return redirect('dokumen');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Dokumen::find($id);
        $model->delete();

        alert()->success('Data berhasil dihapus', 'Berhasil');
        return redirect('dokumen');
    }

    public function delete(Request $request)
    {
        $category = Dokumen::find($request->id);
        $category->delete();

        return 'success';
    }

    public function showSign(Request $request, $id)
    {
        require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
        require_once('../vendor/setasign/fpdi/src/autoload.php');
        $dokumen = Dokumen::find($id);
        $pdf = new Fpdi('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $file_pdf = $pdf->setSourceFile(public_path('uploads\\').$dokumen->file_dokumen);
       
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
        $text = "Dokumen ini  ditandatangani oleh:".auth()->user()->name;
        $barout = 'Dokumen ini ditandatangani oleh '.' ' . auth()->user()->name . '.' . ' ' .'Pada ' . $dokumen->created_at;
        $pdf->write2DBarcode($barout, 'QRCODE,M', 107, 250, 15, 15, $style, 'N');
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
        $file_end = $pdf->Output($file_name, 'D');
    }
}
