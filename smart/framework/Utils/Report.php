<?php

namespace Smart\Utils;

require_once ("../../Vendor/FPDF/fpdf.php");

class Report extends FPDF
{	
    public function configStyleHeader($sizeFont = 10){
        $this->SetDrawColor(23,45,58);
        $this->SetLineWidth(0.1);
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial', 'B', $sizeFont);
    }

    public function configStyleParameterHeader($sizeFont = 10){
        $this->SetTextColor(100,100,100);
        $this->SetDrawColor(23,45,58);
        $this->SetLineWidth(0.1);
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial', 'B', $sizeFont);
    }

    public function configStyleLabelHeader($sizeFont = 8){
        $this->SetTextColor(23,45,58);
        $this->SetDrawColor(23,45,58);
        $this->SetLineWidth(0.1);
        $this->SetFillColor(230, 230, 230);		
        $this->SetFont('Arial','',$sizeFont);
    }

    public function configStyleDetail($sizeFont = 6){
        $this->SetDrawColor(23,45,58);
        $this->SetLineWidth(0.1);
        $this->SetTextColor(36,62,62); 
        $this->SetFillColor(233, 240, 233);
        $this->SetFont('Arial','',$sizeFont);
    }

    public function configStyleFooter($sizeFont = 6){
        $this->SetY(-15);
        $this->SetDrawColor(200,200,200);
        $this->SetTextColor(128);
        $this->SetFont('Arial','',$sizeFont);		
    }

    public function configStyleTitleHeaderGroup($sizeFont = 9){
        $this->SetTextColor(23,45,58);
        $this->SetFont('Arial','B',$sizeFont);		
    }

    public function configStyleDescriptionHeaderGroup($sizeFont = 9){
        $this->SetTextColor(0,0,128);
        $this->SetFont('Arial','B',$sizeFont);		
    }

    public function configStyleFooterGroup($sizeFont = 9){
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',$sizeFont);
    }

    public function loadLabel($columns, $sizeFont = 9){
        $x = $this->GetX();
        $y = $this->GetY();
        $ln = 1;

        $this->SetFont('Arial','',$sizeFont);

        foreach($columns as $c){
            $l = $this->_countLine($c[1], $c[0])+1;
            if($l > $ln) { 
                $ln = $l;
            }
        }
        foreach($columns as $c){
            if($y+4*$ln>$this->PageBreakTrigger&&!$this->InFooter){
                $this->addPage($this->CurOrientation);
                $y=$this->GetY();
            }
            $this->SetY($y);
            $this->SetX($x);
            $x=$this->GetX()+$c[0];
            //$y=$this->GetY();

            $c[1] .= str_repeat("\n",$ln-$this->_countLine($c[1], $c[0]));

            $this->MultiCell($c[0],4,$c[1],'B',$c[2],1);

        }
        $this->Cell(15,5,'','',1);
    }

    public function loadHeader($title){

        $left_margin = 25;

        $this->Cell($left_margin);
        $this->Cell(1,4, $title,0,1,'L',false);
        $this->Image("../app.reportclass/iasd.jpg",15,7,18,18,"JPG");		
    }

    public function loadHeaderParams($param, $buttonLineSize = 1000){

        $this->ln(2);

        foreach($param as $p){
            if(empty($p[3])){	
                $this->SetX(35);
                $o = 'L';
            } else {
                $this->SetX($p[3]);
                $o = 'R';
            }

            $nl = empty($p[2]) ? 1 : 0;

            $this->Cell(1,4, $p[0].': '.$p[1] , 0, $nl, $o, false);
        }

        $this->ln(1);
        $this->Cell($buttonLineSize,3, '','T',1,'C');		
        $this->ln(5);		
    }

    public function loadFooter($buttonLineSize = 1000){
        global $passport;
        $issuedOn   = "impresso em ";
        $date       = date("m/d/Y");
        $by         = ", por ";
        $page       = "pagina ";
        $of         = " de ";

        $this->Cell($buttonLineSize,3, '','B',1,'C');
        $this->Cell(0,4, $issuedOn . $date . $by . $passport,0,0,'L');
        $this->Cell(0,4, $page . $this->PageNo() . $of . '{nb}',0,0,'R');		
    }

    public static function scaleCalc($w1, $w2, $arrValues){
        for($i=0; $i < count($arrValues); $i++){
            $xw = $arrValues[$i]*$w2/$w1;
            $arrValues[$i] = $xw;
        }
        return $arrValues;
    }

    public function _countLine($s, $w){	
        $s=explode(' ',$s);
        $l = 0;
        $a = 0;
        for($i=0;$i<count($s);$i++){
            $b = count($s)-1==$i ? '': ' ';
            $c = $this->GetStringWidth($s[$i].$b);
            if($c > $w){
                $n=ceil($c/$w);				
                $l+=$n;
                $r=ceil( ($c+$n*2)-$n*$w );
                $a+=$r;
                $c=$r+$this->GetStringWidth(' ');
                if( count($s) == 1 ) { 
                    $l--;
                }
            }	
            $a += $c;
            if($a + 2 > $w){
                $l++;
                $a=$c;
            }
        }		
        return $l;		
    }

}