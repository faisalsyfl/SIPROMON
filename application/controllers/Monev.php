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
		$this->load->view('monev/grafik');
		$this->load->view('templates/footer');
	}
	
	public function grafik($id = NULL){
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($id)->row_array();
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('monev/grafik',$this->data);
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
		$this->load->view('monev/tabel',$this->data);
		$this->load->view('templates/footer');
	}

	public function tabel_progres($idk = NULL){
		$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
		$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();
		$this->data['sisaFisik'] = 100;
		$this->data['sisaMoneysP'] = 100;
		$this->data['row']['pagu'] = $this->ModelMnvKeuanganTotal->getPaguEmon($this->data['row']['kdsatker'],$this->data['row']['kdpaket'],$this->data['row']['tahun_anggaran'])->row_array()['pagu'];
		$this->data['sisa'] = $this->data['row']['pagu'];

		$this->data['allAct'] = $this->ModelRMPAct->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['allStages'] = $this->ModelRMPStages->selectByRMP($this->data['row']['id'])->result_array();			
		$this->data['moneys'] = $this->ModelRMPSDK->selectByIdRMP($this->data['row']['id'])->result_array();
		$this->data['sdktitles'] = $this->ModelRMPSDKTitle->selectAll()->result_array();
		$this->data['idk'] = $idk;	//add ahmad
		$this->data['akun'] = $this->ModelMnvKeuanganTotal->getAkunEmon($this->data['row']['kdsatker'],$this->data['row']['kdpaket'],$this->data['row']['tahun_anggaran'])->result_array();

		echo "<pre>";
		print_r($this->data['akun']);
		echo "</pre>";
		exit();
		$this->data['actTotal'] = array();
		$this->data['stageTotal'] = array();
		$this->data['moneysTotalP'] = 0;
		$this->data['moneysTotalK'] = 0;

		$this->data['moneysMonths'] = array();
		$this->data['moneysMonthsMin'] = array();
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
		$this->data['moneysMonthsRK'] = array();
		$this->data['moneysMonthsPR'] = array();
		$this->data['moneysMonthsKumulatifR'] = array();
		$this->data['moneysMonthsKumulatifPR'] = array();
		$this->data['fisikMonthsKumulatifR'] = array();
		$fisik_kumulatifR = 0;
		$moneys_kumulatifR = 0;
		$moneys_kumulatifPR = 0;

		$month = (int)date("m");
		$this->data['this_month'] = ($month+1);

		for ($i=0; $i < 12; $i++) {
			foreach ($this->data['akun'] as $key => $value) {
				$data_akun = $this->ModelMnvKeuangan->selectByRmpSdkMonth($i,$this->data['akun'][$key]['nmmak'])->row_array();
				if(!empty($data_akun)){
					$this->data['akun'][$key][$i] = 0;
				}else if($i == 0){
					$this->data['akun'][$key][$i] = 0;
				}else{
					$this->data['akun'][$key][$i] = $this->data['akun'][$key][($i-1)];
				}
			}
			$temp = $this->ModelMnvKeuangan->getByMonthRMP($idk,($i+1));
			if($temp->num_rows() != 0){
				$temp = $temp->result_array();
				$this->data['moneysMonthsR'][$i] = $temp[0]['jml_uang'];
				if($i == 0){
					$this->data['moneysMonthsMin'][$i] = 0;
				}else{
					$this->data['moneysMonthsMin'][$i] = $this->data['moneysMonthsR'][($i-1)];
				}
				$this->data['moneysTotalK'] = $temp[0]['jml_uang'];
				$this->data['moneysTotalP'] += $temp[0]['jml_uangP'];
				$this->data['moneysMonthsRP'][$i] = $temp[0]['jml_uangP'];
				$this->data['moneysMonthsKend'][$i] = $temp[0]['kendala'];
				$this->data['moneysMonthsId'][$i] = $temp[0]['id_mnv_keuangan'];
				$moneys_kumulatifR = $this->data['moneysMonthsR'][$i];
				$moneys_kumulatifPR = $this->data['moneysMonthsR'][$i]/$this->data['row']['pagu'];
				
				$this->data['moneysMonthsKumulatifR'][$i] = $moneys_kumulatifR;
				$this->data['moneysMonthsKumulatifPR'][$i] = $moneys_kumulatifPR;
			}else{
				if($i == 0){
					$this->data['moneysMonthsMin'][$i] = 0;
				}else{
					$this->data['moneysMonthsMin'][$i] = $this->data['moneysMonthsMin'][($i-1)];
				}
				$this->data['moneysMonthsR'][$i] = 'x';
				$this->data['moneysMonthsRP'][$i] = 0;
				$this->data['moneysMonthsKend'][$i] = '-';
			}
			
			$this->data['fisikMonths'][$i] = 0;
			$this->data['fisikMonthsR'][$i] = 0;
			$this->data['moneysMonths'][$i] = 0;
			$this->data['moneysMonthsP'][$i] = 0;
			foreach($this->data['allAct'] as $act){
				$temp = $this->ModelRMPSDKPlanAct->getByMonthRMP($act['id'],($i+1)); //Model Edited
				if($temp->num_rows() == 0){
					$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
				}else{
					$temp = $temp->result_array();
					$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['actMonthsR'][$act['id']][$i]['fisik'] += $value['fisik'];
						$this->data['fisikMonthsR'][$i] += $value['fisik'];
						$fisik_kumulatifR+= $value['fisik'];
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
						
						$this->data['actMonths'][$act['id']][$i]['fisik'] += $value['fisik_real'];
						$this->data['actMonths'][$act['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
						$this->data['actMonths'][$act['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
						$this->data['actMonths'][$act['id']][$i]['fisik_P'] = $value['fisik'];
						$this->data['actMonths'][$act['id']][$i]['output'] = $value['output'];
						$this->data['actMonths'][$act['id']][$i]['deskripsi'] = $value['deskripsi'];
						$this->data['actMonths'][$act['id']][$i]['kendala'] = $value['kendala'];
						
						$this->data['fisikMonths'][$i] += $value['fisik_real'];
						$fisik_kumulatif+= $value['fisik_real'];
					}
					
				}

			}
			foreach($this->data['allStages'] as $stage){

				$temp = $this->ModelRMPSDKPlanStage->getByMonthRMP($stage['id'],($i+1)); //Model Edited
				
				if($temp->num_rows() != 0){
					$temp = $temp->result_array();
					$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] += $value['fisik'];
						$this->data['fisikMonthsR'][$i] += $value['fisik'];
						$fisik_kumulatifR+= $value['fisik'];
					}
				}else{
					$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
				}

				$temp = $this->ModelMnvFisik->getByMonthRMP($stage['id'],($i+1),'stage'); //Model Edited
				
				if($temp->num_rows() != 0){
					$temp = $temp->result_array();
					$this->data['stageMonths'][$stage['id']][$i]['fisik'] = 0;
					foreach ($temp as $value) {
						$this->data['stageMonths'][$stage['id']][$i]['fisik'] += $value['fisik_real'];
						$this->data['stageMonths'][$stage['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
						$this->data['stageMonths'][$stage['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
						$this->data['stageMonths'][$stage['id']][$i]['fisik_P'] = $value['fisik'];
						$this->data['stageMonths'][$stage['id']][$i]['output'] = $value['output'];
						$this->data['stageMonths'][$stage['id']][$i]['deskripsi'] = $value['deskripsi'];
						$this->data['stageMonths'][$stage['id']][$i]['kendala'] = $value['kendala'];
						
						$this->data['fisikMonths'][$i] += $value['fisik_real'];
						$fisik_kumulatif+= $value['fisik_real'];
					}
				}else{
					$this->data['stageMonths'][$stage['id']][$i]['biaya'] = "x";
					
				}
			}

			$this->data['moneysMonthsKumulatif'][$i] = $moneys_kumulatif;
			$this->data['fisikMonthsKumulatif'][$i] = $fisik_kumulatif;
			$this->data['moneysMonthsKumulatifP'][$i] = $moneys_kumulatifP;

			$this->data['fisikMonthsKumulatifR'][$i] = $fisik_kumulatifR;
			if($i == 0){
				$this->data['moneysMonthsRK'][$i] = $this->data['moneysMonthsR'][$i];
			}else{
				$this->data['moneysMonthsRK'][$i] = $this->data['moneysMonthsR'][$i] + $this->data['moneysMonthsRK'][($i-1)];
			}
		}
		$this->data['moneysP'] = $moneys_kumulatifP;
		$this->data['fisikP'] = $fisik_kumulatif;
		$this->data['sisaMoneysP'] -= $moneys_kumulatifP;
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
		$temp = $this->ModelKegiatan->selectProgres($idk);

		if($temp[0]['r_keuangan'] != $this->data['moneysP'] || $temp[0]['r_fisik'] != $this->data['fisikP']){
			$data_temp = array(
				'r_keuangan' => $this->data['moneysP'], 
				'r_fisik' => $this->data['fisikP'], 
				);
			$this->ModelKegiatan->update($idk,$data_temp);
		}
		// echo "<pre>";
		// print_r($this->data);
		// echo "</pre>";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('monev/tabel_progres',$this->data);
		$this->load->view('templates/footer_mnv_progres');
	}

	// public function tabel_progres($idk = NULL){
	// 	$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
	// 	$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();
	// 	$this->data['sisaFisik'] = 100;
	// 	$this->data['sisaMoneysP'] = 100;
	// 	$this->data['row']['pagu'] = $this->ModelMnvKeuanganTotal->getPaguEmon($this->data['row']['kdsatker'],$this->data['row']['kdpaket'],$this->data['row']['tahun_anggaran'])->row_array()['pagu'];
	// 	$this->data['sisa'] = $this->data['row']['pagu'];

	// 	$this->data['allAct'] = $this->ModelRMPAct->selectByIdRMP($this->data['row']['id'])->result_array();
	// 	$this->data['allStages'] = $this->ModelRMPStages->selectByRMP($this->data['row']['id'])->result_array();			
	// 	$this->data['moneys'] = $this->ModelRMPSDK->selectByIdRMP($this->data['row']['id'])->result_array();
	// 	$this->data['sdktitles'] = $this->ModelRMPSDKTitle->selectAll()->result_array();
	// 	$this->data['idk'] = $idk;	//add ahmad 
	// 	$this->data['actTotal'] = array();
	// 	$this->data['stageTotal'] = array();
	// 	$this->data['moneysTotalP'] = 0;
	// 	$this->data['moneysTotalK'] = 0;

	// 	$this->data['moneysMonths'] = array();
	// 	$this->data['moneysMonthsMin'] = array();
	// 	$this->data['moneysMonthsKend'] = array();
	// 	$this->data['moneysMonthsId'] = array();
	// 	$this->data['moneysMonthsP'] = array();
	// 	$this->data['moneysMonthsKumulatif'] = array();
	// 	$this->data['moneysMonthsKumulatifP'] = array();
	// 	$this->data['actMonths'] = array();
	// 	$this->data['fisikMonths'] = array();
	// 	$this->data['fisikMonthsR'] = array();
	// 	$this->data['fisikMonthsKumulatif'] = array();
	// 	$this->data['stageMonths'] = array();
	// 	$this->data['moneysKumulatif'] = 0;
	// 	$this->data['moneysP'] = 0;
	// 	$this->data['fisikP'] = 0;
	// 	$fisik_kumulatif = 0;
	// 	$moneys_kumulatif = 0;
	// 	$moneys_kumulatifP = 0;

	// 	$this->data['actMonthsR'] = array();
	// 	$this->data['stageMonthsR'] = array();
	// 	$this->data['moneysMonthsR'] = array();
	// 	$this->data['moneysMonthsRK'] = array();
	// 	$this->data['moneysMonthsPR'] = array();
	// 	$this->data['moneysMonthsKumulatifR'] = array();
	// 	$this->data['moneysMonthsKumulatifPR'] = array();
	// 	$this->data['fisikMonthsKumulatifR'] = array();
	// 	$fisik_kumulatifR = 0;
	// 	$moneys_kumulatifR = 0;
	// 	$moneys_kumulatifPR = 0;

	// 	$month = (int)date("m");
	// 	$this->data['this_month'] = ($month+1);

	// 	for ($i=0; $i < 12; $i++) {
	// 		$temp = $this->ModelMnvKeuangan->getByMonthRMP($idk,($i+1));
	// 		if($temp->num_rows() != 0){
	// 			$temp = $temp->result_array();
	// 			$this->data['moneysMonthsR'][$i] = $temp[0]['jml_uang'];
	// 			if($i == 0){
	// 				$this->data['moneysMonthsMin'][$i] = 0;
	// 			}else{
	// 				$this->data['moneysMonthsMin'][$i] = $this->data['moneysMonthsR'][($i-1)];
	// 			}
	// 			$this->data['moneysTotalK'] = $temp[0]['jml_uang'];
	// 			$this->data['moneysTotalP'] += $temp[0]['jml_uangP'];
	// 			$this->data['moneysMonthsRP'][$i] = $temp[0]['jml_uangP'];
	// 			$this->data['moneysMonthsKend'][$i] = $temp[0]['kendala'];
	// 			$this->data['moneysMonthsId'][$i] = $temp[0]['id_mnv_keuangan'];
	// 			$moneys_kumulatifR = $this->data['moneysMonthsR'][$i];
	// 			$moneys_kumulatifPR = $this->data['moneysMonthsR'][$i]/$this->data['row']['pagu'];
				
	// 			$this->data['moneysMonthsKumulatifR'][$i] = $moneys_kumulatifR;
	// 			$this->data['moneysMonthsKumulatifPR'][$i] = $moneys_kumulatifPR;
	// 		}else{
	// 			if($i == 0){
	// 				$this->data['moneysMonthsMin'][$i] = 0;
	// 			}else{
	// 				$this->data['moneysMonthsMin'][$i] = $this->data['moneysMonthsMin'][($i-1)];
	// 			}
	// 			$this->data['moneysMonthsR'][$i] = 'x';
	// 			$this->data['moneysMonthsRP'][$i] = 0;
	// 			$this->data['moneysMonthsKend'][$i] = '-';
	// 		}
			
	// 		$this->data['fisikMonths'][$i] = 0;
	// 		$this->data['fisikMonthsR'][$i] = 0;
	// 		$this->data['moneysMonths'][$i] = 0;
	// 		$this->data['moneysMonthsP'][$i] = 0;
	// 		foreach($this->data['allAct'] as $act){
	// 			$temp = $this->ModelRMPSDKPlanAct->getByMonthRMP($act['id'],($i+1)); //Model Edited
	// 			if($temp->num_rows() == 0){
	// 				$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
	// 			}else{
	// 				$temp = $temp->result_array();
	// 				$this->data['actMonthsR'][$act['id']][$i]['fisik'] = 0;
	// 				foreach ($temp as $value) {
	// 					$this->data['actMonthsR'][$act['id']][$i]['fisik'] += $value['fisik'];
	// 					$this->data['fisikMonthsR'][$i] += $value['fisik'];
	// 					$fisik_kumulatifR+= $value['fisik'];
	// 				}
					
	// 			}

	// 			$temp = $this->ModelMnvFisik->getByMonthRMP($act['id'],($i+1),'act'); //Model Edited
	// 			if($temp->num_rows() == 0){
	// 				$this->data['actMonths'][$act['id']][$i]['biaya'] = "x";
					
	// 			}else{
	// 				$temp = $temp->result_array();
	// 				$this->data['actMonths'][$act['id']][$i]['biaya'] = 0;
	// 				$this->data['actMonths'][$act['id']][$i]['fisik'] = 0;
	// 				foreach ($temp as $value) {
						
	// 					$this->data['actMonths'][$act['id']][$i]['fisik'] += $value['fisik_real'];
	// 					$this->data['actMonths'][$act['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
	// 					$this->data['actMonths'][$act['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
	// 					$this->data['actMonths'][$act['id']][$i]['fisik_P'] = $value['fisik'];
	// 					$this->data['actMonths'][$act['id']][$i]['output'] = $value['output'];
	// 					$this->data['actMonths'][$act['id']][$i]['deskripsi'] = $value['deskripsi'];
	// 					$this->data['actMonths'][$act['id']][$i]['kendala'] = $value['kendala'];
						
	// 					$this->data['fisikMonths'][$i] += $value['fisik_real'];
	// 					$fisik_kumulatif+= $value['fisik_real'];
	// 				}
					
	// 			}

	// 		}
	// 		foreach($this->data['allStages'] as $stage){

	// 			$temp = $this->ModelRMPSDKPlanStage->getByMonthRMP($stage['id'],($i+1)); //Model Edited
				
	// 			if($temp->num_rows() != 0){
	// 				$temp = $temp->result_array();
	// 				$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
	// 				foreach ($temp as $value) {
	// 					$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] += $value['fisik'];
	// 					$this->data['fisikMonthsR'][$i] += $value['fisik'];
	// 					$fisik_kumulatifR+= $value['fisik'];
	// 				}
	// 			}else{
	// 				$this->data['stageMonthsR'][$stage['id']][$i]['fisik'] = 0;
	// 			}

	// 			$temp = $this->ModelMnvFisik->getByMonthRMP($stage['id'],($i+1),'stage'); //Model Edited
				
	// 			if($temp->num_rows() != 0){
	// 				$temp = $temp->result_array();
	// 				$this->data['stageMonths'][$stage['id']][$i]['fisik'] = 0;
	// 				foreach ($temp as $value) {
	// 					$this->data['stageMonths'][$stage['id']][$i]['fisik'] += $value['fisik_real'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['fisik_P'] = $value['fisik'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['output'] = $value['output'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['deskripsi'] = $value['deskripsi'];
	// 					$this->data['stageMonths'][$stage['id']][$i]['kendala'] = $value['kendala'];
						
	// 					$this->data['fisikMonths'][$i] += $value['fisik_real'];
	// 					$fisik_kumulatif+= $value['fisik_real'];
	// 				}
	// 			}else{
	// 				$this->data['stageMonths'][$stage['id']][$i]['biaya'] = "x";
					
	// 			}
	// 		}

	// 		$this->data['moneysMonthsKumulatif'][$i] = $moneys_kumulatif;
	// 		$this->data['fisikMonthsKumulatif'][$i] = $fisik_kumulatif;
	// 		$this->data['moneysMonthsKumulatifP'][$i] = $moneys_kumulatifP;

	// 		$this->data['fisikMonthsKumulatifR'][$i] = $fisik_kumulatifR;
	// 		if($i == 0){
	// 			$this->data['moneysMonthsRK'][$i] = $this->data['moneysMonthsR'][$i];
	// 		}else{
	// 			$this->data['moneysMonthsRK'][$i] = $this->data['moneysMonthsR'][$i] + $this->data['moneysMonthsRK'][($i-1)];
	// 		}
	// 	}
	// 	$this->data['moneysP'] = $moneys_kumulatifP;
	// 	$this->data['fisikP'] = $fisik_kumulatif;
	// 	$this->data['sisaMoneysP'] -= $moneys_kumulatifP;
	// 	$this->data['sisaFisik'] -= $fisik_kumulatif;
	// 	$this->data['moneysKumulatif'] = $moneys_kumulatif;
	// 	$this->data['sisa'] -= $moneys_kumulatif;
	// 	$convert = array('A'=>0,'B'=>1,'C'=>2);
	// 	for($i=0;$i<3;$i++){
	// 		$this->data['total'][$i] = 0;
	// 		$this->data['totalP'][$i] = 0;
	// 		$this->data['totalF'][$i] = 0;
	// 		$kk=0;
	// 		foreach($this->data['allAct'] as $act){
				
	// 			if($convert[$act['parent']] == $i){
	// 				$temp = $this->ModelMnvFisik->getByReferTotal($act['id'],'act'); //edited model
	// 				if($temp->num_rows() != 0){
	// 					$temp = $temp->result_array();
	// 					$this->data['actTotal'][$act['id']]['biaya'] = $temp[0]['biaya'];
	// 					$this->data['actTotal'][$act['id']]['biayaP'] = $temp[0]['biayaP'];
	// 					$this->data['actTotal'][$act['id']]['fisik'] = $temp[0]['fisik'];
	// 					$this->data['actTotal'][$act['id']]['hchild'] = 0;
	// 					$this->data['total'][$i] += $temp[0]['biaya'];
	// 					$this->data['totalP'][$i] += $temp[0]['biayaP'];
	// 					$this->data['totalF'][$i] += $temp[0]['fisik'];
	// 				}
	// 				foreach($this->data['allStages'] as $stage){
	// 					if($stage['act_id'] == $act['id']){
	// 						$this->data['actTotal'][$act['id']]['hchild'] = 1;
	// 						$temp = $this->ModelMnvFisik->getByReferTotal($stage['id'],'stage'); //edited model
	// 						if($temp->num_rows() != 0){

	// 							$temp = $temp->result_array();
	// 							$this->data['actTotal'][$stage['act_id']]['biaya'] += $temp[0]['biaya'];
	// 							$this->data['actTotal'][$stage['act_id']]['biayaP'] += $temp[0]['biayaP'];
	// 							$this->data['actTotal'][$stage['act_id']]['fisik'] += $temp[0]['fisik'];
	// 							$this->data['stageTotal'][$stage['id']]['biaya'] = $temp[0]['biaya'];
	// 							$this->data['stageTotal'][$stage['id']]['biayaP'] = $temp[0]['biayaP'];
	// 							$this->data['stageTotal'][$stage['id']]['fisik'] = $temp[0]['fisik'];
	// 							$this->data['total'][$i] += $temp[0]['biaya'];
	// 							$this->data['totalP'][$i] += $temp[0]['biayaP'];
	// 							$this->data['totalF'][$i] += $temp[0]['fisik'];
	// 						}
	// 					}
	// 				}
	// 			}
	// 			$kk++;
	// 		}
	// 	}
	// 	$temp = $this->ModelKegiatan->selectProgres($idk);

	// 	if($temp[0]['r_keuangan'] != $this->data['moneysP'] || $temp[0]['r_fisik'] != $this->data['fisikP']){
	// 		$data_temp = array(
	// 			'r_keuangan' => $this->data['moneysP'], 
	// 			'r_fisik' => $this->data['fisikP'], 
	// 			);
	// 		$this->ModelKegiatan->update($idk,$data_temp);
	// 	}
	// 	// echo "<pre>";
	// 	// print_r($this->data);
	// 	// echo "</pre>";
	// 	$this->load->view('templates/header');
	// 	$this->load->view('templates/sidebar');
	// 	$this->load->view('monev/tabel_progres',$this->data);
	// 	$this->load->view('templates/footer_mnv_progres');
	// }


	public function view_progres($idk = NULL){
		// if (strpos($idk, '.csv') !== false) {
		//     // echo $idk;
		// 	$url = 'http://emonitoring.pu.go.id/pusair/';
		// 	$url.=$idk;

		// 	$_SESSION['url_api'] = $url;
		// 	$_SESSION['file_api'] = $idk;

		// 	$csvData = file_get_contents($url);

		// 	$rangekeg = count($csvData);
			

		// 	$lines = explode("\n", $csvData);
		// 	$array = [];
		// 	$header = null;
		// 	$separator = ';';
		// 	foreach ($lines as $line) {
		// 		if ($header === null) {
		// 			$header = str_getcsv($line,$separator);
		// 			continue;
		// 		}
				
				
		// 		$array[] = array_combine(array_intersect_key($header, str_getcsv($line,$separator)), array_intersect_key(str_getcsv($line,$separator),$header));
		// 		// print_r(array_intersect_key($header, str_getcsv($line,$separator)));
		// 	}


		// 	$this->view_progres($_SESSION['idk']);
		// 	// for ($i=0; $i < 12 ; $i++) { 
		// 	// 	# code...

		// 	// 	echo $array[(sizeof($array) - 4)]['rf'.($i+1)]."<br>";
		// 	// }
		// 	// 	echo $array[(sizeof($array) - 10)]['kode_rkakl']."<br>";
		// 	// echo "<pre>";
		// 	// print_r($array);
		// 	// print_r($array[(sizeof($array) - 10)]);
		// 	// echo "</pre>";
		// }else{
			$_SESSION['idk'] = $idk;
			$this->data['satker'] = $this->ModelSatker->selectByIdUser($this->session->userdata('id'))->row_array();
			$this->data['row'] = $this->ModelRMP->selectByIdKegiatan($idk)->row_array();
			$this->data['sisaFisik'] = 100;
			$this->data['sisaMoneysP'] = 100;
			$this->data['row']['pagu'] = $this->ModelMnvKeuanganTotal->getPaguEmon($this->data['row']['kdsatker'],$this->data['row']['kdpaket'],$this->data['row']['tahun_anggaran'])->row_array()['pagu'];
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
			$this->data['this_month'] = ($month+1);

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
							$this->data['actMonths'][$act['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
							$this->data['actMonths'][$act['id']][$i]['kendala'] = $value['kendala'];
							$this->data['actMonths'][$act['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
							$this->data['actMonths'][$act['id']][$i]['biayaP'] = $value['biayaP'];
							$this->data['actMonths'][$act['id']][$i]['fisik_P'] = $value['fisik'];
							$this->data['actMonths'][$act['id']][$i]['output'] = $value['output'];
							$this->data['actMonths'][$act['id']][$i]['deskripsi'] = $value['deskripsi'];
							$this->data['actMonths'][$act['id']][$i]['kendala'] = $value['kendala'];
							
							$this->data['fisikMonths'][$i] += $value['fisik_real'];
							$fisik_kumulatif+= $value['fisik_real'];
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
							$this->data['stageMonths'][$stage['id']][$i]['tingkat_kendala'] = $value['tingkat_kendala'];
							$this->data['stageMonths'][$stage['id']][$i]['kendala'] = $value['kendala'];
							$this->data['stageMonths'][$stage['id']][$i]['id_mnv_fisik'] = $value['id_mnv_fisik'];
							$this->data['stageMonths'][$stage['id']][$i]['biayaP'] = $value['biayaP'];
							$this->data['stageMonths'][$stage['id']][$i]['fisik_P'] = $value['fisik'];
							$this->data['stageMonths'][$stage['id']][$i]['output'] = $value['output'];
							$this->data['stageMonths'][$stage['id']][$i]['deskripsi'] = $value['deskripsi'];
							$this->data['stageMonths'][$stage['id']][$i]['kendala'] = $value['kendala'];
							
							$this->data['fisikMonths'][$i] += $value['fisik_real'];
							$fisik_kumulatif+= $value['fisik_real'];
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
			$this->data['fisikP'] = $fisik_kumulatif;
			$this->data['sisaMoneysP'] -= $moneys_kumulatifP;
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
			// print_r($temp);
			// echo "</pre>";
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('monev/tabel_progres_view',$this->data);
			$this->load->view('templates/footer');
		// }
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
			redirect('monev/tabel_progres/'.$_POST['id_kegiatan']);
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

			redirect('monev/tabel_progres/'.$_POST['id_kegiatan']);
		}else{
			redirect('Home');
		}
		
	}

	public function submit_fisik(){
		if(isset($_POST['submit'])){
			// echo '<pre>' . print_r($_POST, TRUE) . '</pre>';
			$data = array(
					'month' => $_POST['month'],
					'biaya' => $_POST['biaya'],
					'biayaP' => $_POST['biayaP'],
					'fisik' => $_POST['fisik'],
					'fisik_real' => $_POST['fisik_real'],
					'output' => $_POST['output'],
					'deskripsi' => $_POST['deskripsi'],
					'id_kegiatan' => $_POST['id_kegiatan'],
					'id_refer' => $_POST['id_refer'],
					'kendala' => $_POST['kendala'],
					'tingkat_kendala' => $_POST['tingkat_kendala'],
					'type' => $_POST['type'],
					);
			$id_new = $this->ModelMnvFisik->insert($data);
			redirect('monev/tabel_progres/'.$_POST['id_kegiatan']);
			// echo '<pre>' . print_r($data) . '</pre>';
			// echo '<pre>' . $id_new. '</pre>';
		}else if(isset($_POST['update'])){
			$data = array(
					'biaya' => $_POST['biaya'],
					'biayaP' => $_POST['biayaP'],
					'fisik' => $_POST['fisik'],
					'fisik_real' => $_POST['fisik_real'],
					'output' => $_POST['output'],
					'deskripsi' => $_POST['deskripsi'],
					'kendala' => $_POST['kendala'],
					'tingkat_kendala' => $_POST['tingkat_kendala'],
					);
			$result = $this->ModelMnvFisik->update($_POST['id_mnv_fisik'],$data);
			redirect('monev/tabel_progres/'.$_POST['id_kegiatan']);
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

	public function getSisaOther($id_refer,$month, $type, $id_kegiatan){
		$x = $this->ModelMnvKeuangan->getSisaOther($id_refer,$month, $type, $id_kegiatan);
		echo json_encode($x);
	}
}
