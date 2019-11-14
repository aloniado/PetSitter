<?php

require_once ("../dbClass.php");
require_once ("fPDF.php");

class mypdf extends FPDF
{
    function createPageHeader()
    {
        $this->Ln(20);
        $this->SetFont("Arial","B",20);
        $this->Cell(276,5,"petSitter",0,0,'C');
        $this->Ln();
        $this->SetFont("Times",'',12);
        $this->Cell(276,10, "Registered Users Details",0,0,'C');
        $this->Ln(10);
        $this->SetFont("Times",'',8);
        $this->Cell(276,10, "Createn on: " . date("Y-m-d h:i:sa"),0,0,'C');
        $this->Ln(20);

    }

    function footer()
    {
        $this->SetY(-15);
        $this->SetFont("Arial",'',8);
        $this->Cell(0,10,'page'.$this->PageNo().'/{nb}',0,0,'C');
    }

    function createTableHeader()
    {

        $this->SetFont('Times',"B",11);
        $this->Cell(15,10,"#",1,0,"C");
        $this->Cell(40,10,"Name",1,0,"C");
        $this->Cell(60,10,"Email",1,0,"C");
        $this->Cell(40,10,"Phone",1,0,"C");
        $this->Cell(40,10,"Register Time",1,0,"C");
        $this->Cell(40,10,"Status",1,0,"C");
        $this->Ln();
    }


    function createTableDetails($users)
    {
        $this->SetFont('Times','',11);
        $count=0;
        foreach ($users as $k)
        {
            $count++;
            $this->Cell(15,10,$count,1,0,"C");
            $this->Cell(40,10,$k->getUserFName()." ".$k->getUserLName(),1,0,"L");
            $this->Cell(60,10,$k->getUserEmail(),1,0,"L");
            $this->Cell(40,10,$k->getUserPhone(),1,0,"L");
            $this->Cell(40,10,$k->getUserRegisterTime(),1,0,"L");
            $this->Cell(40,10,$k->getUserStatus(),1,0,"L");
            $this->Ln();

        }
    }
}

//--------------------------------------------------------------------------------------------------
$db = new dbClass();

$users_arr_report = $db->getObjectsGeneral("users", " WHERE 1 ORDER BY userStatus", "User");

$pdf=new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4',0);
$pdf->createPageHeader();
$pdf->createTableHeader();
//$persons=$db->getPersons();
$pdf->createTableDetails($users_arr_report);
$pdf->Output();
$pdf->Close();
