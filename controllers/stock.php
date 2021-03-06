<?php
include '../lib/config.php';
include '../lib/function.php';
include '../models/stock_model.php';
$page = null;
$page = (isset($_GET['page'])) ? $_GET['page'] : "list";
$title = ucfirst("Item Stock");

$_SESSION['menu_active'] = 8;

switch ($page) {
	case 'list':
		get_header($title);
		
		if($_SESSION['user_type_id']==1 || $_SESSION['user_type_id']==2){
			$where_branch = "";
		}else{
			$where_branch = " where branch_id = '".$_SESSION['branch_id']."' or branch_type_id = '1'";
		}
		
		$query = select();
		$q_branch = select_branch($where_branch);
		$q_branch2 = select_branch($where_branch);
		
		$add_button = "stock.php?page=form";

		include '../views/stock/list.php';
		get_footer();
	break;
	
	case 'form':
		get_header();

		$close_button = "stock.php?page=list";
		
		$query_unit = select_unit();
		

		$id = (isset($_GET['id'])) ? $_GET['id'] : null;
		if($id){

			$row = read_id($id);
			
			$action = "stock.php?page=edit&id=$id";
		} else{
			
			//inisialisasi
			$row = new stdClass();
	
			$row->item_name = false;
			$row->unit_id = false;
			$row->item_limit = false;
			
			
			$action = "stock.php?page=save";
		}
		
		$query_resep = select_resep($id);
		$query_resep2 = select_resep($id);

		include '../views/stock/form.php';
		//include '../views/stock/list_resep.php';
		get_footer();
	break;
	
	case 'add_menu';
		$item_id = get_isset($_GET['item_id']);	
		$i_id = get_isset($_GET['i_id']);	
		
		$data = "'',
					'$i_id',
					'$item_id',
					'0', 
					'".$_SESSION['user_id']."'
					
			";
		create_config("resep_details", $data);
			
		header("Location: stock.php?page=form&id=$i_id");
		
	break;

	case 'save':
	
		extract($_POST);

		$i_name = get_isset($i_name);
		$i_unit_id = get_isset($i_unit_id);
		$i_item_limit = get_isset($i_item_limit);
		
		
		$data = "'',
					'$i_name',
					'$i_unit_id',
					'$i_item_limit'
			";
			
			//echo $data;

			$id = create($data);
			create_stock($id);
			
			header("Location: stock.php?page=form&id=$id");
		
		
	break;

	case 'edit':

		extract($_POST);
		
		$id = get_isset($_GET['id']);
		
		$i_name = get_isset($i_name);
		$i_unit_id = get_isset($i_unit_id);
		$i_item_limit = get_isset($i_item_limit);
	
		
					$data = "
					item_name = '$i_name',
					unit_id = '$i_unit_id',
					item_limit = '$i_item_limit'

					";
			
			update($data, $id);
			
			header('Location: stock.php?page=list&did=2');

		

	break;

	case 'delete':

		$id = get_isset($_GET['id']);	

		delete($id);

		header('Location: stock.php?page=list&did=3');

	break;
	
	case 'delete_item':
	
		$id = get_isset($_GET['id']);
		$i_id = get_isset($_GET['i_id']);
			
		
		delete_item($id);

		header("Location: stock.php?page=form&id=$i_id");

	break;
}

?>