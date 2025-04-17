<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pdf
{

    public $param;
    public $pdf;

    public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    {
        $this->param = $param;
        $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        // $this->pdf = new mPDF($this->param);
    }

    public function load()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        // return $this->pdf = new mPDF('c', 'A4-L');
    }
    public function certificateEmp() {
        return $this->pdf = new \Mpdf\Mpdf([
            'tempDir' => 'public/pdf/temp/',
			'mode' => 'c',
			'margin_left' => 0,
			'margin_right' => 0,
			'margin_top' => 0,
			'orientation' => 'portrait',
			'margin_bottom' => 0,
			'margin_top' => 0,
			'margin_header' => 0,
			'margin_footer' => 0
        ]);
    }
    public function ptwload()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        // return $this->pdf = new mPDF('utf-8','A4','','','8','8','32','18');
    }
    public function incUpload()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);;
        //  return $this->pdf = new mPDF('utf-8','A4','','','8','8','32','18');
    }

    public function ptwUpload()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        //  return $this->pdf = new mPDF('utf-8','A4','','','8','8','32','18');
    }

    public function gtsUpload()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        //  return $this->pdf = new mPDF('utf-8','A4','','','8','8','32','18');
    }

    public function QrUpload()
    {
        return $this->pdf = new \Mpdf\Mpdf(['tempDir' => 'public/pdf/temp/']);
        //  return $this->pdf = new mPDF('utf-8','A4','','','8','8','32','18');
    }
}