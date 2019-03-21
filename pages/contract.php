<?php

$show = explode("0987340295870912835365213524110247489564320234",$_GET['show']);

require_once('../libraries/TCPDF-master/tcpdf.php');
include "../database/database_operations.php";
include "../libraries/cost_calculation.php";
$db = connect_to_database();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// add a page
$pdf->AddPage();

$pdf->SetAutoPageBreak(false, 0);
$pdf->Image("../pdf_files/contract.jpg", 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

$pdf->setPageMark();

//organization
$html = "<p>{$show[9]}</p>";
$pdf->writeHTMLCell(0, 0, '51', '50', $html, 0, 1, 0, true, '', true);

//event name
$html = "<p>{$show[1]}</p>";
$pdf->writeHTMLCell(0, 0, '51', '55', $html, 0, 1, 0, true, '', true);

//event venue
$html = "<p>{$show[2]}</p>";
$pdf->writeHTMLCell(0, 0, '51', '61', $html, 0, 1, 0, true, '', true);

//union funded
$union_funded = $show[10] == 0 ? "No" : "Yes";
$html = "<p>{$union_funded}</p>";
$pdf->writeHTMLCell(0, 0, '180', '50', $html, 0, 1, 0, true, '', true);

//attendance
$html = "<p>{$show[3]}</p>";
$pdf->writeHTMLCell(0, 0, '180', '55', $html, 0, 1, 0, true, '', true);

//print out id for some reason
$html = "<p>{$show[0]}</p>";
$pdf->writeHTMLCell(0, 0, '180', '61', $html, 0, 1, 0, true, '', true);

//start date
$start_date = new DateTime($show[13]);
$html = "<p>{$start_date->format("m-d-Y")}</p>";
$pdf->writeHTMLCell(0, 0, '128', '73', $html, 0, 1, 0, true, '', true);



//load start
$time = calcuate_load_start($show[16],calculate_load_hours($show[4],$show[2],$show[5],true));
//$html = "<p>" .calculate_load_hours($show[4],$show[2],$show[5],true). "</p>";
$html = "<p>{$time->format("h:i a")}</p>";
$pdf->writeHTMLCell(0, 0, '176', '73', $html, 0, 1, 0, true, '', true);

//soundcheck
$soundcheck = new DateTime($show[16]);
$html = "<p>{$soundcheck->format("h:i a")}</p>";
$pdf->writeHTMLCell(0, 0, '176', '78', $html, 0, 1, 0, true, '', true);

//start time
$time = new DateTime($show[7]);
$html = "<p>{$time->format("h:i a")}</p>";
$pdf->writeHTMLCell(0, 0, '176', '83', $html, 0, 1, 0, true, '', true);

//end time
$end_time = new DateTime($show[18]);
$html = "<p>{$end_time->format("h:i a")}</p>";
$pdf->writeHTMLCell(0, 0, '176', '89', $html, 0, 1, 0, true, '', true);

//load end
$time = calculate_load_end($show[18],calculate_load_hours($show[4],$show[2],$show[5],false),$show[14]);
$html = "<p>{$time->format("h:i a")}</p>";
$pdf->writeHTMLCell(0, 0, '176', '95', $html, 0, 1, 0, true, '', true);

//load date end
$html = "<p>{$time->format("m-d-Y")}</p>";
$pdf->writeHTMLCell(0, 0, '128', '95', $html, 0, 1, 0, true, '', true);

$contact = $db->query("SELECT * FROM users WHERE rcs='{$show[8]}'")->fetch_assoc();
//requester name
$html = "<p>{$contact['first_name']} {$contact['last_name']}</p>";
$pdf->writeHTMLCell(0, 0, '30', '82', $html, 0, 1, 0, true, '', true);

//requester email
$html = "<p>{$contact['email']}</p>";
$pdf->writeHTMLCell(0, 0, '30', '87', $html, 0, 1, 0, true, '', true);

//requester phone
$html = "<p>{$contact['phone']}</p>";
$pdf->writeHTMLCell(0, 0, '30', '93', $html, 0, 1, 0, true, '', true);

//equipment fees
$equipment = get_equipment_array($show[5]);
$base = 118;
$count_ = 0;
$x_name = 20;
$x_cost = 93;
$count = 0;

$equipment_cost = 0;
$tech_cost = 0;
$late_fee = 0;
for ($i = 0;$i < count($equipment)-1;++$i) {
    $cost = $equipment[$i][1] * $equipment[$i][2];
    if ($cost == 0)
        continue;

    $html = "<p>{$equipment[$i][0]}</p>";
    $pdf->writeHTMLCell(0, 0, $x_name, $base+(6*$count_), $html, 0, 1, 0, true, '', true);

    $equipment_cost += $cost;
    $html = "<p>$ {$cost}</p>";
    $pdf->writeHTMLCell(0, 0, $x_cost, $base+(6*$count_), $html, 0, 1, 0, true, '', true);

    if ($count == 2) {
        $x_name += 90;
        $x_cost += 90;
        $count_ = -1;
    }
    ++$count;
    ++$count_;
}
$equipment_cost = round($equipment_cost,2,PHP_ROUND_HALF_UP);

//equipment subtotal
$html = "<p>$ {$equipment_cost}</p>";
$pdf->writeHTMLCell(0, 0, '180', '135', $html, 0, 1, 0, true, '', true);



//tech fees

//load in techs
$load_in_info = calculate_staff($show[4],$show[2],$show[5],0,calculate_load_hours($show[4],$show[2],$show[5],true),calculate_show_hours($soundcheck,$end_time),calculate_load_hours($show[4],$show[2],$show[5],false));
$html = "<p>{$load_in_info[0]}</p>";
$pdf->writeHTMLCell(0, 0, '43', '155', $html, 0, 1, 0, true, '', true);

//load in reg hours
$html = "<p>{$load_in_info[1]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '153', $html, 0, 1, 0, true, '', true);
//load in ot hours
$html = "<p>{$load_in_info[2]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '159', $html, 0, 1, 0, true, '', true);
//load in reg cost
$tmp = $load_in_info[1] * 12.5;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '153', $html, 0, 1, 0, true, '', true);
//load in ot cost
$tmp = $load_in_info[2] * 18.75;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '159', $html, 0, 1, 0, true, '', true);





//show techs
$show_info = calculate_staff($show[4],$show[2],$show[5],1,calculate_load_hours($show[4],$show[2],$show[5],true),calculate_show_hours($soundcheck,$end_time),calculate_load_hours($show[4],$show[2],$show[5],false));
$html = "<p>{$show_info[0]}</p>";
$pdf->writeHTMLCell(0, 0, '43', '167', $html, 0, 1, 0, true, '', true);
//show reg hours
$html = "<p>{$show_info[1]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '164', $html, 0, 1, 0, true, '', true);
//show ot hours
$html = "<p>{$show_info[2]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '170', $html, 0, 1, 0, true, '', true);
//total cost show reg
$tmp = $show_info[1] * 12.5;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '164', $html, 0, 1, 0, true, '', true);
//total cost ot reg
$tmp = $show_info[2] * 18.75;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '170', $html, 0, 1, 0, true, '', true);



//load out techs
$show_info = calculate_staff($show[4],$show[2],$show[5],2,calculate_load_hours($show[4],$show[2],$show[5],true),calculate_show_hours($soundcheck,$end_time),calculate_load_hours($show[4],$show[2],$show[5],false));
$html = "<p>{$show_info[0]}</p>";
$pdf->writeHTMLCell(0, 0, '43', '179', $html, 0, 1, 0, true, '', true);
//show reg hours
$html = "<p>{$show_info[1]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '175', $html, 0, 1, 0, true, '', true);
//show ot hours
$html = "<p>{$show_info[2]}</p>";
$pdf->writeHTMLCell(0, 0, '58', '181', $html, 0, 1, 0, true, '', true);
//load out reg total cost
$tmp = $show_info[1] * 12.5;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '175', $html, 0, 1, 0, true, '', true);
//load out ot cost
$tmp = $show_info[2] * 18.75;
$tech_cost += $tmp;
$html = "<p>$ {$tmp}</p>";
$pdf->writeHTMLCell(0, 0, '97', '181', $html, 0, 1, 0, true, '', true);
$tech_cost = round($tech_cost,2,PHP_ROUND_HALF_UP);
//tech subtotal
$html = "<p>$ {$tech_cost}</p>";
$pdf->writeHTMLCell(0, 0, '97', '187', $html, 0, 1, 0, true, '', true);



//total cost

//equipment cost
$html = "<p>$ {$equipment_cost}</p>";
$pdf->writeHTMLCell(0, 0, '176', '153', $html, 0, 1, 0, true, '', true);
//tech cost
$html = "<p>$ {$tech_cost}</p>";
$pdf->writeHTMLCell(0, 0, '176', '159', $html, 0, 1, 0, true, '', true);

//grand total
$grand_total = '$'.($equipment_cost + $tech_cost);
$html = "<p>{$grand_total}</p>";
$pdf->writeHTMLCell(0, 0, '175', '187', $html, 0, 1, 0, true, '', true);


// ---------------------------------------------------------

//Close and output PDF document
echo $pdf->Output('contract.pdf', 'i');
?>

