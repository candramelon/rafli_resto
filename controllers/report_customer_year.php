<?php
include '../lib/config.php';
include '../lib/function.php';
include '../models/report_customer_year_model.php';
$page = null;
$page = (isset($_GET['page'])) ? $_GET['page'] : "list";
$title = ucfirst("Report Customer Tahunan");

$_SESSION['menu_active'] = 10;

switch ($page) {
	
	case 'list':
		get_header();

		$id = (isset($_GET['id'])) ? $_GET['id'] : null;
		
		$date_default = "";
		$date_url = "";

		$button_download = "";
		
		if(isset($_GET['preview'])){
			$i_year = get_isset($_GET['year']);
		}
		
		$action = "report_customer_year.php?page=form_result&preview=1";
		
		include '../views/report_customer_year/form.php';
		
		if(isset($_GET['preview'])){
			
				if(isset($_GET['year'])){
					$i_year = $_GET['year'];
				}else{
					extract($_POST);
					$i_year = get_isset($i_year);
				}
			
			$query_customer = select_customer();
			$year = $i_year;
			
            include '../views/report_customer_year/list_detail.php'; 
			//include '../views/report_customer_year/list_item.php';
			//include '../views/report_customer_year/list_partner.php';
			//include '../views/report_customer_year/list_transaction.php';
		}
		
		
		get_footer();
	break;
	
	case 'form_result':
		

		$id = (isset($_GET['id'])) ? $_GET['id'] : null;
		
		$date_default = "";
		$date_url = "";
		
		//if(isset($_GET['preview'])){
			
			extract($_POST);
			$i_year = (isset($_POST['i_year'])) ? $_POST['i_year'] : null;
		//}
		
		header("Location: report_customer_year.php?page=list&preview=1&year=$i_year");
	break;
	

	
	case 'form_detail':
		$title = ucfirst("Report Event Detail");
		get_header();
		
		$close_button = "report_customer_year.php?page=form";

			$id = (isset($_GET['id'])) ? $_GET['id'] : null;
			
			$row = read_id($id);
			$row->transaction_date = format_date($row->transaction_date);
			$row->transaction_date2 = format_date($row->transaction_date2);
			$all_date = $row->transaction_date." - ".$row->transaction_date2;

			$query_trainer_view = read_trainer_view($id);
			$query_agent_view = read_agent_view($id);
		
			include '../views/report_customer_year/form_save.php';
			include '../views/report_customer_year/list_trainer_view.php';
			include '../views/report_customer_year/list_agent_view.php';
			include '../views/report_customer_year/form_comand3.php';
			
		get_footer();
	break;
	
	case 'download':
	
			$i_date = $_GET['date'];
			$i_date = str_replace(" ","", $i_date);
			$date_real = $_GET['date'];
			
			
			
			$date = explode("-", $i_date);
			$date1 = format_back_date($date[0]);
			$date2 = format_back_date($date[1]);
			
			$i_owner_id = get_isset($_GET['owner']);
			
			if($i_owner_id == 0){
				$supplier = "All Supplier";
			}else{
				$supplier = get_data_owner($i_owner_id);
			}
			
			$query_item = select_detail($date1, $date2, $i_owner_id);
			
			//fungsi backup
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($date2);
			$difference = $datetime1->diff($datetime2);
			//echo $difference->days;
			
			/*$sel = abs(strtotime($date2)-strtotime($date1));
			$selisih= $sel /(60*60*24);*/
			
			$jumlah_hari = $difference->days + 1;
			$jumlah_truk = get_jumlah_truk($date1, $date2, $i_owner_id);
			$jumlah_pengiriman = get_jumlah_pengiriman($date1, $date2, $i_owner_id);
			$jumlah_volume = (get_jumlah_volume($date1, $date2, $i_owner_id)) ? get_jumlah_volume($date1, $date2, $i_owner_id) : 0;
			$jumlah_volume = str_replace(".",",", $jumlah_volume);
			
			$total_jasa_angkut = get_total_jasa_angkut($date1, $date2, $i_owner_id);
			$total_jasa_angkut = str_replace(".",",", $total_jasa_angkut);
			$total_subsidi_tol = get_total_subsidi_tol($date1, $date2, $i_owner_id);
			$total_transport = $total_jasa_angkut + $total_subsidi_tol;
			$total_harga_urukan = get_total_harga_urukan($date1, $date2, $i_owner_id);
			$total_hpp = get_total_hpp($date1, $date2, $i_owner_id);
						
			$title = 'report_customer_year';
			$supplier_title = str_replace(" ","_", $supplier);
			$format = create_report($title."_".$supplier_title."_".$i_date);
			
			include '../views/report/report_customer_year.php';
			

	break;


	
	case 'download_pdf':
			$i_date = $_GET['date'];
			$date_view = $_GET['date'];
			$i_date = str_replace(" ","", $i_date);
			
			
			$date = explode("-", $i_date);
			$date1 = format_back_date($date[0]);
			$date2 = format_back_date($date[1]);
			
			$i_owner_id = get_isset($_GET['owner']);
			
			if($i_owner_id == 0){
				$supplier = "All Supplier";
			}else{
				$supplier = get_data_owner($i_owner_id);
			}
			
			$query_item = select_detail($date1, $date2, $i_owner_id);
			
			//fungsi backup
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($date2);
			$difference = $datetime1->diff($datetime2);
			//echo $difference->days;
			
			/*$sel = abs(strtotime($date2)-strtotime($date1));
			$selisih= $sel /(60*60*24);*/
			
			$jumlah_hari = $difference->days + 1;
			$jumlah_truk = get_jumlah_truk($date1, $date2, $i_owner_id);
			$jumlah_pengiriman = get_jumlah_pengiriman($date1, $date2, $i_owner_id);
			$jumlah_volume = (get_jumlah_volume($date1, $date2, $i_owner_id)) ? get_jumlah_volume($date1, $date2, $i_owner_id) : 0;
			$jumlah_volume = str_replace(".",",", $jumlah_volume);
			
			$total_jasa_angkut = get_total_jasa_angkut($date1, $date2, $i_owner_id);
			$total_jasa_angkut = intval($total_jasa_angkut);
			$total_jasa_angkut = str_replace(".",",", $total_jasa_angkut);
			$total_subsidi_tol = get_total_subsidi_tol($date1, $date2, $i_owner_id);
			$total_transport = $total_jasa_angkut + $total_subsidi_tol;
			$total_harga_urukan = get_total_harga_urukan($date1, $date2, $i_owner_id);
			$total_hpp = get_total_hpp($date1, $date2, $i_owner_id);
			
			include '../views/report/report_customer_year_pdf.php';
	
	break;
	
	case 'download_komulatif':
	
			$i_date = $_GET['date'];
			$i_date = str_replace(" ","", $i_date);
			$date_real = $_GET['date'];
			
			$date = explode("-", $i_date);
			$date1 = format_back_date($date[0]);
			$date2 = format_back_date($date[1]);
			
			$i_owner_id = get_isset($_GET['owner']);
			
			if($i_owner_id == 0){
				$supplier = "All Supplier";
			}else{
				$supplier = get_data_owner($i_owner_id);
			}
			
			$query_item = select_detail($date1, $date2, $i_owner_id);
			
			//fungsi backup
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($date2);
			$difference = $datetime1->diff($datetime2);
			
			$transport_service_komulatif = get_transport_service_komulatif();
			
			
			//echo $difference->days;
			
			/*$sel = abs(strtotime($date2)-strtotime($date1));
			$selisih= $sel /(60*60*24);*/
			
			
						
			$title = 'report_komulatif';
			$supplier_title = str_replace(" ","_", $supplier);
			$format = create_report($title."_".$supplier_title."_".$i_date);
			
			include '../views/report/report_komulatif.php';
			

	break;

        case 'download_tagihan':
	
			$i_date = $_GET['date'];
			$i_date = str_replace(" ","", $i_date);
			$date_real = $_GET['date'];
			
			
			
			$date = explode("-", $i_date);
			$date1 = format_back_date($date[0]);
			$date2 = format_back_date($date[1]);
			
			$i_owner_id = get_isset($_GET['owner']);
			
			if($i_owner_id == 0){
				$supplier = "All Supplier";
			}else{
				$supplier = get_data_owner($i_owner_id);
			}

                        $transport_service_komulatif = get_transport_service_komulatif();
			
			$query_item = select_detail($date1, $date2, $i_owner_id);
			
			//fungsi backup
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($date2);
			$difference = $datetime1->diff($datetime2);
			//echo $difference->days;
			
			/*$sel = abs(strtotime($date2)-strtotime($date1));
			$selisih= $sel /(60*60*24);*/
			
			$jumlah_hari = $difference->days + 1;
			$jumlah_truk = get_jumlah_truk($date1, $date2, $i_owner_id);
			$jumlah_pengiriman = get_jumlah_pengiriman($date1, $date2, $i_owner_id);
			$jumlah_volume = (get_jumlah_volume($date1, $date2, $i_owner_id)) ? get_jumlah_volume($date1, $date2, $i_owner_id) : 0;
			$jumlah_volume = str_replace(".",",", $jumlah_volume);
			
			$total_jasa_angkut = get_total_jasa_angkut($date1, $date2, $i_owner_id);
			$total_jasa_angkut = str_replace(".",",", $total_jasa_angkut);
			$total_subsidi_tol = get_total_subsidi_tol($date1, $date2, $i_owner_id);
			$total_transport = $total_jasa_angkut + $total_subsidi_tol;
			$total_harga_urukan = get_total_harga_urukan($date1, $date2, $i_owner_id);
			$total_hpp = get_total_hpp($date1, $date2, $i_owner_id);
						
			$title = 'report_customer_year_tagihan';
			$supplier_title = str_replace(" ","_", $supplier);
			$format = create_report($title."_".$supplier_title."_".$i_date);
			
			include '../views/report/report_customer_year_tagihan.php';
			

	break;
	
	case 'delete_transaction':
		

		$id = (isset($_GET['id'])) ? $_GET['id'] : null;
			
			extract($_POST);
			$i_date = get_isset($_GET['date']);
			$date_default = $i_date;
			
		
		delete_transaction($id);
		
		header("Location: report_customer_year.php?page=list&preview=1&date=$date_default");
	break;
	
}

?>