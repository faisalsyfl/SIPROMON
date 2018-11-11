<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monev extends CI_Controller {
	private $head;
	private $side;
	private $data;
	private $foot;

	public function __construct(){
		parent::__construct();
		$this->head = array();
		$this->side = array();
		$this->data = array();
		$this->foot = array();
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('Monev/grafik');
		$this->load->view('templates/footer');
	}
	
	public function grafik($id = NULL){
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($id)->row_array();
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('Monev/grafik',$this->data);
		$this->load->view('templates/footer');
	}
	
	public function tabel($idk = NULL){
		$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();

		$this->data['allAct'] = $this->ModelRMPAct->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['allStages'] = $this->ModelRMPStages->selectAll()->result_array();		
		$this->data['moneys'] = $this->ModelRMPSDK->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['sdktitles'] = $this->ModelRMPSDKTitle->selectAll()->result_array();		
		/*Counting Total Every Step*/
		$convert = array('A'=>0,'B'=>1,'C'=>2);
		for($i=0;$i<3;$i++){
			$this->data['total'][$i] = 0;
			foreach($this->data['allAct'] as $act){
				if($convert[$act['parent']] == $i){
					foreach($this->data['allStages'] as $stage){
						if($stage['act_id'] == $act['id']){
							$this->data['total'][$i] += $stage['anggaran'];
						}
					}
					if($act['anggaran'] != 0){
						$this->data['total'][$i] += $act['anggaran'];
					}
				}
			}
		}		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('Monev/tabel',$this->data);
		$this->load->view('templates/footer');
	}

	public function tabel_progres($idk = NULL){
		$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();
		$this->data['sisaFisik'] = 100;
		$this->data['sisaMoneysP'] = 100;
		$this->data['sisa'] = $this->data['row']['pagu'];

		$this->data['allAct'] = $this->ModelRMPAct->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['allStages'] = $this->ModelRMPStages->selectByRMP($this->data['row']['id'])->result_array();			
		$this->data['moneys'] = $this->ModelRMPSDK->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['sdktitles'] = $this->ModelRMPSDKTitle->selectAll()->result_array();
		$this->data['idk'] = $idk;	//add ahmad 
		$this->data['actTotal'] = array();
		$this->data['stageTotal'] = array();
		$this->data['moneysTotalP'] = 0;
		$this->data['moneysTotalK'] = 0;

		$this->data['moneysMonths'] = array();
		$this->data['moneysMonthsKend'] = array();
		$this->data['moneysMonthsId'] = array();
		$this->data['moneysMonthsP'] = array();
		$this->data['moneysMonthsKumulatif'] = array();
		$this->data['moneysMonthsKumulatifP'] = array();
		$this->data['actMonths'] = array();
		$this->data['fisikMonths'] = array();
		$this->data['fisikMonthsR'] = array();
		$this->data['fisikMonthsKumulatif'] = array();
		$this->data['stageMonths'] = array();
		$this->data['moneysKumulatif'] = 0;
		$this->data['moneysP'] = 0;
		$this->data['fisikP'] = 0;
		$fisik_kumulatif = 0;
		$moneys_kumulatif = 0;
		$moneys_kumulatifP = 0;

		$this->data['actMonthsR'] = array();
		$this->data['stageMonthsR'] = array();
		$this->data['moneysMonthsR'] = array();
		$this->data['moneysMonthsPR'] = array();
		$this->data['moneysMonthsKumulatifR'] = array();
		$this->data['moneysMonthsKumulatifPR'] = array();
		$this->data['fisikMonthsKumulatifR'] = array();
		$fisik_kumulatifR = 0;
		$moneys_kumulatifR = 0;
		$moneys_kumulatifPR = 0;

		$month = (int)date("m");
		$this->data['this_month'] = ($month-1);

		for ($i=0; $i < 12; $i++) {
			$temp = $this->ModelMnvKeuangan->getByMonthRMP($idk,($i+1));
			if($temp->num_rows() != 0){
				$temp = $temp->result_array();
				$this->data['moneysMonths'][$i] = $temp[0]['jml_uang'];
				$this->data['moneysTotalK'] += $temp[0]['jml_uang'];
				$this->data['moneysTotalP'] += $temp[0]['jml_uangP'];
				$this->data['moneysMonthsP'][$i] = $temp[0]['jml_uangP'];
				$this->data['moneysMonthsKend'][$i] = $temp[0]['kendala'];
				$this->data['moneysMonthsId'][$i] = $temp[0]['id_mnv_keuangan'];
			}else{
				$this->data['moneysMonths'][$i] = 'x';
				$this->data['moneysMonthsP'][$i] = 0;
				$this->data['moneysMonthsKend'][$i] = '-';
			}

			$this->data['fisikMonths'][$i] = 0;

			$this->data['fisikMonthsR'][$i] = 0;
			$this->data['moneysMonthsR'][$i] = 0;
			$this->data['moneysMonthsPR'][$i] = 0;
			foreach($this->data['allAct'] as $act){
				$temp = $this->ModelRMPSDKPlanAct->getByMonthRMP($act['id'],($i+1)); //Model Edited
				if($temp->num_rows() == 0){
					$this->data['actMonthsR'][$act['id']][$i]['biaya'] = 0;
					$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
				}else{
					$temp = $temp->result_array();
					$this->data['actMonthsR'][$act['id']][$i]['biaya'] = 0;
					$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['actMonthsR'][$act['id']][$i]['biaya'] += $value['biaya'];
						$this->data['actMonthsR'][$act['id']][$i]['fisik'] += $value['fisik'];
						$this->data['moneysMonthsR'][$i] += $value['biaya'];
						$this->data['moneysMonthsPR'][$i] += $value['biayaP'];
						$this->data['fisikMonthsR'][$i] += $value['fisik'];
						$fisik_kumulatifR+= $value['fisik'];
						$moneys_kumulatifR+= $value['biaya'];
						$moneys_kumulatifPR+= $value['biayaP'];
					}
					
				}

				$temp = $this->ModelMnvFisik->getByMonthRMP($act['id'],($i+1),'act'); //Model Edited
				if($temp->num_rows() == 0){
					$this->data['actMonths'][$act['id']][$i]['biaya'] = "x";
				}else{
					$temp = $temp->result_array();
					$this->data['actMonths'][$act['id']][$i]['biaya'] = 0;
					$this->data['actMonths'][$act['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['actMonths'][$act['id']][$i]['biaya'] += $value['biaya'];
						$this->data['actMonths'][$act['id']][$i]['fisik'] += $value['fisik_real'];
						$this->data['moneysMonths'][$i] += $value['biaya'];
						$this->data['moneysMonthsP'][$i] += $value['biayaP'];
						$this->data['fisikMonths'][$i] += $value['fisik_real'];
						$fisik_kumulatif+= $value['fisik'];
						$moneys_kumulatif+= $value['biaya'];
						$moneys_kumulatifP+= $value['biayaP'];
					}
					
				}

			}
			foreach($this->data['allStages'] as $stage){

				$temp = $this->ModelRMPSDKPlanStage->getByMonthRMP($stage['id'],($i+1)); //Model Edited
				
				if($temp->num_rows() != 0){
					$temp = $temp->result_array();
					$this->data['stageMonthsR'][$stage['id']][$i]['biaya'] = 0;
					$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['stageMonthsR'][$stage['id']][$i]['biaya'] += $value['biaya'];
						$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] += $value['fisik'];
						$this->data['moneysMonthsR'][$i] += $value['biaya'];
						$this->data['moneysMonthsPR'][$i] += $value['biayaP'];
						$this->data['fisikMonthsR'][$i] += $value['fisik'];
						$fisik_kumulatifR+= $value['fisik'];
						$moneys_kumulatifR+= $value['biaya'];
						$moneys_kumulatifPR+= $value['biayaP'];
					}
				}else{
					$this->data['stageMonthsR'][$stage['id']][$i]['biaya'] = 0;
					$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
				}

				$temp = $this->ModelMnvFisik->getByMonthRMP($stage['id'],($i+1),'stage'); //Model Edited
				
				if($temp->num_rows() != 0){
					$temp = $temp->result_array();
					$this->data['stageMonths'][$stage['id']][$i]['biaya'] = 0;
					$this->data['stageMonths'][$stage['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['stageMonths'][$stage['id']][$i]['biaya'] += $value['biaya'];
						$this->data['stageMonths'][$stage['id']][$i]['fisik'] += $value['fisik_real'];
						$this->data['moneysMonths'][$i] += $value['biaya'];
						$this->data['moneysMonthsP'][$i] += $value['biayaP'];
						$this->data['fisikMonths'][$i] += $value['fisik_real'];
						$fisik_kumulatif+= $value['fisik'];
						$moneys_kumulatif+= $value['biaya'];
						$moneys_kumulatifP+= $value['biayaP'];
					}
				}else{
					$this->data['stageMonths'][$stage['id']][$i]['biaya'] = "x";
				}
			}

			$this->data['moneysMonthsKumulatif'][$i] = $moneys_kumulatif;
			$this->data['fisikMonthsKumulatif'][$i] = $fisik_kumulatif;
			$this->data['moneysMonthsKumulatifP'][$i] = $moneys_kumulatifP;

			$this->data['moneysMonthsKumulatifR'][$i] = $moneys_kumulatifR;
			$this->data['fisikMonthsKumulatifR'][$i] = $fisik_kumulatifR;
			$this->data['moneysMonthsKumulatifPR'][$i] = $moneys_kumulatifPR;
		}
		$this->data['moneysP'] = $moneys_kumulatifP;
		$this->data['sisaMoneysP'] -= $moneys_kumulatif;
		$this->data['fisikP'] = $fisik_kumulatif;
		$this->data['sisaFisik'] -= $fisik_kumulatif;
		$this->data['moneysKumulatif'] = $moneys_kumulatif;
		$this->data['sisa'] -= $moneys_kumulatif;
		$convert = array('A'=>0,'B'=>1,'C'=>2);
		for($i=0;$i<3;$i++){
			$this->data['total'][$i] = 0;
			$this->data['totalP'][$i] = 0;
			$this->data['totalF'][$i] = 0;
			$kk=0;
			foreach($this->data['allAct'] as $act){
				
				if($convert[$act['parent']] == $i){
					$temp = $this->ModelMnvFisik->getByReferTotal($act['id'],'act'); //edited model
					if($temp->num_rows() != 0){
						$temp = $temp->result_array();
						$this->data['actTotal'][$act['id']]['biaya'] = $temp[0]['biaya'];
						$this->data['actTotal'][$act['id']]['biayaP'] = $temp[0]['biayaP'];
						$this->data['actTotal'][$act['id']]['fisik'] = $temp[0]['fisik'];
						$this->data['actTotal'][$act['id']]['hchild'] = 0;
						$this->data['total'][$i] += $temp[0]['biaya'];
						$this->data['totalP'][$i] += $temp[0]['biayaP'];
						$this->data['totalF'][$i] += $temp[0]['fisik'];
					}
					foreach($this->data['allStages'] as $stage){
						if($stage['act_id'] == $act['id']){
							$this->data['actTotal'][$act['id']]['hchild'] = 1;
							$temp = $this->ModelMnvFisik->getByReferTotal($stage['id'],'stage'); //edited model
							if($temp->num_rows() != 0){

								$temp = $temp->result_array();
								$this->data['actTotal'][$stage['act_id']]['biaya'] += $temp[0]['biaya'];
								$this->data['actTotal'][$stage['act_id']]['biayaP'] += $temp[0]['biayaP'];
								$this->data['actTotal'][$stage['act_id']]['fisik'] += $temp[0]['fisik'];
								$this->data['stageTotal'][$stage['id']]['biaya'] = $temp[0]['biaya'];
								$this->data['stageTotal'][$stage['id']]['biayaP'] = $temp[0]['biayaP'];
								$this->data['stageTotal'][$stage['id']]['fisik'] = $temp[0]['fisik'];
								$this->data['total'][$i] += $temp[0]['biaya'];
								$this->data['totalP'][$i] += $temp[0]['biayaP'];
								$this->data['totalF'][$i] += $temp[0]['fisik'];
							}
						}
					}
				}
				$kk++;
			}
		}			
		// echo "<pre>";
		// print_r($this->data);
		// echo "</pre>";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('Monev/tabel_progres',$this->data);
		$this->load->view('templates/footer');
	}


	public function view_progres($idk = NULL){
		$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();
		$this->data['id_kegiatan'] = $idk;
		$this->data['allAct'] = $this->ModelRMPAct->selectByIdRMP($this->data['row']['id'])->result_array();
		// $this->data['allStages'] = $this->ModelRMPStages->selectAll()->result_array();	

		$this->data['allStages'] = $this->ModelRMPStages->selectByRMP($this->data['row']['id'])->result_array();	
		$this->data['moneys'] = $this->ModelRMPSDK->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['sdktitles'] = $this->ModelRMPSDKTitle->selectAll()->result_array();		
		$this->data['moneysWeeks'] = array();
		$this->data['moneysWeeksKumulatif'] = array();
		$this->data['moneysWeeksKumulatifP'] = array();
		$this->data['actWeeks'] = array();
		$this->data['fisikWeeks'] = array();
		$this->data['fisikWeeksKumulatif'] = array();
		$this->data['stageWeeks'] = array();
		$fisik_kumulatif = 0;
		
		
		$month = (int)date("m");
		
		$this->data['this_months'] = ($month-1);
		
		for ($i=0; $i < 12; $i++) {
			$temp2 = 0;
			$temp = $this->ModelMnvFisik->getByWeeksRMPKumulatif($this->data['row']['id'],($i+1));
			if($temp->num_rows() != 0){
				$temp = $temp->result_array()[0]['persentase_real'];
				if($temp != 0){
					$fisik_kumulatif += $temp;
					$temp2 = $fisik_kumulatif;
				}
			}else{
				$temp = 0;
			}
			array_push($this->data['fisikWeeks'],round($temp,3));
			array_push($this->data['fisikWeeksKumulatif'],round($temp2,3));
			
			$temp = $this->ModelMnvKeuanganTotal->getByWeeksRMP($this->data['row']['id'],($i+1));
			$temp2 = 0;
			$temp3 = 0;
			if($temp->num_rows() != 0){
				$temp = $temp->result_array();
				$temp2 = $temp[0]['jml_kumulatif'];
				$temp3 = $temp[0]['persentase_kumulatif'];
				$temp = $temp[0]['jml'];
			}else{
				$temp = 0;
			}

			array_push($this->data['moneysWeeks'],$temp);
			array_push($this->data['moneysWeeksKumulatif'],$temp2);
			array_push($this->data['moneysWeeksKumulatifP'],round($temp3,3));
			foreach($this->data['allAct'] as $act){
				$this->data['actWeeks'][$act['id']][$i] = $this->ModelMnvFisik->getByWeeksRMP($act['id'],($i+1),"act");

				if($this->data['actWeeks'][$act['id']][$i]->num_rows() != 0){
					$tempArr = $this->data['actWeeks'][$act['id']][$i]->result_array();
					$tempWeeks = array(
						'persentase' => $tempArr[0]['persentase_real'],
						'tingkat_kendala' => $tempArr[0]['tingkat_kendala'],
						);
					$this->data['actWeeks'][$act['id']][$i] = $tempWeeks;
					// $this->data['actWeeks'][$act['id']][$i] = $this->data['actWeeks'][$act['id']][$i]->result_array()[0]['tingkat_kendala'];
				}else{
					$this->data['actWeeks'][$act['id']][$i] = 0;
				}
			}
			foreach($this->data['allStages'] as $stage){
				$this->data['stageWeeks'][$stage['id']][$i] = $this->ModelMnvFisik->getByWeeksRMP($stage['id'],($i+1),"stage");
				
				if($this->data['stageWeeks'][$stage['id']][$i]->num_rows() != 0){
					$tempArr = $this->data['stageWeeks'][$stage['id']][$i]->result_array();
					$tempWeeks = array(
						'persentase' => $tempArr[0]['persentase_real'],
						'tingkat_kendala' => $tempArr[0]['tingkat_kendala'],
						);
					$this->data['stageWeeks'][$stage['id']][$i] = $tempWeeks;
					// $this->data['stageWeeks'][$stage['id']][$i]['tingkat_kendala'] = $this->data['stageWeeks'][$stage['id']][$i]->result_array()[0]['tingkat_kendala'];
				}else{
					$this->data['stageWeeks'][$stage['id']][$i] = 0;
				}
				// $temp = array(
				// 	array( $stage['id'] => array(
				// 			$i => $this->ModelMnvFisik->cekByWeeksRMP($stage['id'],($i+1),"act"),
				// 			) 
				// 		)
				// 	);
				// array_push($this->data['stageWeeks'],$temp);
			}
		}
		// echo "<pre>";
		// print_r($this->data['moneysWeeks']);
		// echo "</pre>";

		/*Counting Total Every Step*/
		$convert = array('A'=>0,'B'=>1,'C'=>2);
		for($i=0;$i<3;$i++){
			$this->data['total'][$i] = 0;
			foreach($this->data['allAct'] as $act){
				if($convert[$act['parent']] == $i){
					foreach($this->data['allStages'] as $stage){
						if($stage['act_id'] == $act['id']){
							$this->data['total'][$i] += $stage['anggaran'];
						}
					}
					if($act['anggaran'] != 0){
						$this->data['total'][$i] += $act['anggaran'];
					}
				}
			}
		}		
		// echo "<pre>";
		// print_r($this->data['moneysWeeks']);
		// echo "</pre>";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('Monev/tabel_progres_view',$this->data);
		$this->load->view('templates/footer');
	}

	public function submit_keuangan(){
		// print_r($_POST['jml']);

		if(isset($_POST['submit'])){

			
			$jml_uangP = ($_POST['jml_uang']/$_POST['pagu'])*100;
			

			$data = array(
				'month' => $_POST['month'], 
				'id_kegiatan' => $_POST['id_kegiatan'], 
				'kendala' => $_POST['kendala'], 
				'jml_uang' => $_POST['jml_uang'], 
				'jml_uangP' => $jml_uangP, 
				);
			// print_r($data);
			
			$kumulatif = $this->ModelMnvKeuangan->insert($data);
			// print_r($pagu);
			redirect('Monev/tabel_progres/'.$_POST['id_kegiatan']);
		}else if(isset($_POST['update'])){
			
			$jml_uangP = ($_POST['jml_uang']/$_POST['pagu'])*100;
			

			$data = array(
				'month' => $_POST['month'], 
				'id_kegiatan' => $_POST['id_kegiatan'], 
				'kendala' => $_POST['kendala'], 
				'jml_uang' => $_POST['jml_uang'], 
				'jml_uangP' => $jml_uangP, 
				);

			$kumulatif = $this->ModelMnvKeuangan->update($_POST['id_mnv_keuangan'],$data);

			redirect('Monev/tabel_progres/'.$_POST['id_kegiatan']);
		}else{
			redirect('Home');
		}
		
	}

	public function submit_fisik(){
		if(isset($_POST['submit'])){
			// echo '<pre>' . print_r($_POST, TRUE) . '</pre>';
			$persentase_kumulatif = $_POST['fisik_real']*$_POST['persentase']/100;
			$persentase_real = $persentase_kumulatif - $this->ModelMnvFisik->jmlFisikByActRMP($_POST['id_rmp'],$_POST['id_refer'], $_POST['type'],$_POST['minggu'])[0]['persen_total'];
			
			if(isset($_POST['kendala'])){

				$data = array(
					'minggu' => $_POST['minggu'],
					'persentase' => $_POST['persentase'],
					'persentase_real' => $persentase_real,
					'output' => $_POST['output'],
					'deskripsi' => $_POST['deskripsi'],
					'id_rmp' => $_POST['id_rmp'],
					'id_refer' => $_POST['id_refer'],
					'kendala' => $_POST['kendala'],
					'tingkat_kendala' => $_POST['tingkat_kendala'],
					'type' => $_POST['type'],
					);
			}else{
				$data = array(
					'minggu' => $_POST['minggu'],
					'persentase' => $_POST['persentase'],
					'persentase_real' => $persentase_real,
					'output' => $_POST['output'],
					'deskripsi' => $_POST['deskripsi'],
					'id_rmp' => $_POST['id_rmp'],
					'id_refer' => $_POST['id_refer'],
					'type' => $_POST['type'],
					);
			}
			$id_new = $this->ModelMnvFisik->insert($data);
			redirect('Monev/tabel_progres/'.$_POST['id_kegiatan']);
			// echo '<pre>' . print_r($data) . '</pre>';
			// echo '<pre>' . $id_new. '</pre>';
		}else if(isset($_POST['update'])){
			$persentase_kumulatif = $_POST['fisik_realF']*$_POST['persentaseF']/100;
			$persentase_real = $persentase_kumulatif - $this->ModelMnvFisik->jmlFisikByActRMP($_POST['id_rmp'],$_POST['id_referF'], $_POST['typeF'],$_POST['minggu'])[0]['persen_total'];
			$data = array(
				'persentase' => $_POST['persentaseF'],
				'persentase_real' => $persentase_real,
				'output' => $_POST['outputF'],
				'deskripsi' => $_POST['deskripsiF'],
				'kendala' => $_POST['kendalaF'],
				'tingkat_kendala' => $_POST['tingkat_kendalaF'],
				);
			// echo '<pre>' . print_r($data) . '</pre>';
			$result = $this->ModelMnvFisik->update($_POST['id_mnv_fisik'],$data);
			redirect('Monev/tabel_progres/'.$_POST['id_kegiatan']);
		}else{
			redirect('Home');
		}
	}

	public function getJmlRmpSdkWeeks($minggu, $id_rmp_sdk){
		$x = $this->ModelMnvKeuangan->selectByRmpSdkWeeks($minggu, $id_rmp_sdk)->result_array();
		echo json_encode($x);
	}

	public function getFisikDetail($id_refer,$minggu, $type){
		$x = $this->ModelMnvFisik->getDetail($id_refer,$minggu, $type)->result_array();
		echo json_encode($x);
	}
}
