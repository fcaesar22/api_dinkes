<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class a004_tv_adr_v3 extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper("/v1080/vpar");
		$this->load->helper("fglobal");
		$this->lang->load("resmsg", "english");
	}
#Last Update  : 25-07-2017
	function request_POST(){
		$_POST['genid']='1';
$_POST['token']='5NBZXXQUPU3O425ZR70ODTHSM6INLZ12';
$_POST['position']='1';
$_POST['devid']='30';
$_POST['limit']='50';
$_POST['userid']='independent.ukey@gmail.com';
		  #$_POST['userid']='r83309v3wh';#'r83309v3wh';
		  #$_POST['devid']='40';
		  #$_POST['genid']='2';
		  #$_POST['contentid']='0';
		  #$_POST['position']='1';
		  #$_POST['limit']='30';
		  #$_POST['token'] ='';# 'Fao1WSux4rjs6k25oWNw3SVjmRtqUY2B';
		 $this->index();
	}
	function index(){
		header('Content-Type: application/json');
		$adp2aaa=$this->config->item('adp2aaa');
		$rid='adr004';
		$userid=$this->input->post('userid');
		$devid=$this->input->post('devid');
		$genid=$this->input->post('genid');
		$contentid=$this->input->post('contentid');
		$position=$this->input->post('position');
		$limit=$this->input->post('limit');
		$token=$this->input->post('token');
		$tor=$this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$vdevid=ISNUM($devid, "devid", $this);
		$vposition=ISNUM($position, "position", $this);
		$vlimit=ISNUM($limit, "limit", $this);
		$vtoken=TOKEN($token, "token", $this);
		if($vdevid['rs']<>0){
			$response=$vdevid;
		}elseif(($vposition['rs']<>0)&&($contentid=='')&&($genid=='')){
			$response=$vposition;
		}elseif(($vlimit['rs']<>0)&&($contentid=='')&&($genid=='')){
			$response=$vlimit;
		}elseif($vtoken['rs']<>0){
			$response=$vtoken;
		}else{
			// Get token
			$postoken=array('userid'=>$userid, 'devid'=>$devid, 'token'=>$token);
			$urltoken=$adp2aaa.'/all_in_alpha/c1002_ctoken_v5';
			#$urltoken=$adp2aaa.'/v1035/c1002_ctokenstb_v2';
			$ctoken=$this->libadapter->execurl($urltoken, $postoken);#print_r($ctoken['data']);exit();
			$data_token=json_decode($ctoken['data']);
			// Post to AAA server
			//$funct='c004_tv_adr_v6/index';
			$funct='c004_tv_adr_v8/index';
			if($userid=='independent.ukey@gmail.com'){$funct='c004_tv_adr_v7/index';}
			$ver='adr1035';
			$ToURL=$adp2aaa.'/'.$ver.'/'.$funct;
			$apigen=$this->libadapter->execurl($ToURL, $_POST);
			$jdcode=json_decode($apigen['data'], TRUE);
			$vcurl=CURL($ctoken['info']['http_code'], "token", $this);
			$vjson=JSON($apigen['data'], "JsonAPI", $this);
			if($vcurl['rs']<>0){
				$response=$vcurl;
			}elseif((string) $data_token->response<>0){
				$response=array(
					'cd'=>(string)$data_token->response,
					'rs'=>(string) $data_token->response,
					'ms'=>(string) $data_token->msg,
					'dt'=>$data_token->data
				);
			}elseif($vjson['rs']<>0){
				$response=$vjson;
			}else{
				$response=array(
					'cd'=>$jdcode['response'],
					'rs'=>$jdcode['response'],
					'ms'=>$jdcode['msg'],
					'dt'=>$jdcode['data']
				);
			}
		}
		$resu=json_encode(response_v2($response, $rid, $tor));
		echo $resu;
		$this->olap_log($resu,$_SERVER['REQUEST_URI']);
	}
	function olap_log($httpresponse="",$function=""){
		$respon=json_decode($httpresponse);
		if(isset($respon->response)){$_POST['response']=$respon->response;}
		if(isset($respon->msg)){$_POST['msg']=$respon->msg;}
		if(isset($respon->tor)){$_POST['tor']=$respon->tor;}
		$pst="";
		if(count($_POST)>0){
			$jp=array();
			$j=0;
			foreach ($_POST as $key=>$value){
				$jp[$j]=$key.'='.$value;
				$j++;
			}
			$pst=implode("/", $jp);
		}		
		$ctraaa='http://olap.dens.tv/'.$function.'/'.$pst;
		$this->libadapter->execurl($ctraaa, array());
	}
}
