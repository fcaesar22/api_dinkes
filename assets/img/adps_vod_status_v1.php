<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Adapter web vod content
	2019
	g
*/
class Adps_list_playlist_v1 extends CI_Controller{

	public function __construct(){
		parent::__construct();
		/*use library from existing*/
		$this->load->config('oconfig');
		$this->load->helper('universal/request');
		$this->load->helper('universal/customstring');
		$this->load->helper('v2020/validstring_helper');
		$this->load->helper('v2020/validresponse_helper');
		$this->lang->load("v2020_errormsg", "english");
		$this->load->library('Libadapter');
		$this->load->library('v2020/outparam_h');
	}

	public function index(){
		$rs->rs='0';
		$rs->cd='0';
		$rs->ms='SUCCESS';
		$rs->trace=0;
		$rs->api_id=$this->router->fetch_class();;
		$data=array();

		$this->benchmark->mark('reg_start');
		$toURL="http://10.0.1.41/v2019/aaa_check_account_v1/checkListPlaylist";

		$post=$this->input->post();
		$user_id=$this->input->post('user_id');

		$vstremail=EMT($user_id, 'user_id');
			
		if($vstremail->rs!='0')
		{
			$rs=$vstremail; $rs->trace=1;
		}
		else
		{
			// Get data email
			$get = $this->libadapter->execurl($toURL, $post);
			if (isset($get['data']))
			{
				$_get = json_decode($get['data'],true);
				if ($_get==null)
				{
					$rs->rs='1';
					$rs->ms='ERROR';
					$rs->trace=2;
					$data = array();
				}
				else
				{
					$data = $_get;
				}
			}
		}

		$_return = array(
			'error'=>$rs->rs>0?1:0,
			'message'=>$rs->ms,
			'api_id'=>$this->router->fetch_class(),
			'tor'=>$this->benchmark->elapsed_time('reg_start', 'reg_end'),
			'data'=>$data
		);
		// echo json_encode($_return);
		return $this->output->set_status_header(200)
			->set_content_type('application/json')
			->set_output(json_encode($_return));
	}
}
