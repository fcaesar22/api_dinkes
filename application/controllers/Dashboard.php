<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('libadapter');
    }

    public function index()
    {
        return show_404();
    }

	public function join_api()
	{
        $data = null;
        $endpoint_all_rsud = 'https://dinkes.jakarta.go.id/apps/jp-2024/all-rsud.json';
        $endpoint_satu_sehat = 'https://dinkes.jakarta.go.id/apps/jp-2024/all-rs-terkoneksi.json';
        
        $response_all_rsud = $this->libadapter->httpGet($endpoint_all_rsud);
        $result_all_rsud = json_decode($response_all_rsud,true);
        if (count($result_all_rsud) > 0)
        {
            $response_satu_sehat = $this->libadapter->httpGet($endpoint_satu_sehat);
            $result_satu_sehat = json_decode($response_satu_sehat,true);
            if (count($result_satu_sehat) > 0)
            {
                $arrayTemp = array();
                foreach ($result_all_rsud as $key => $value){
                    $arrayTemp[] = array_merge((array)$result_satu_sehat[$key], (array)$value);
                }                

                foreach ($arrayTemp as $keys => $values) {
                    $data[] = array(
                        'nama' => $values['nama'],
                        'organisasi_id' => $values['organisasi_id'],
                        'kelas_rs' => $values['kelas_rs'],
                        'status' => $values['status'],
                        'alamat' => $values['alamat'],
                        'kota_kab' => $values['kota_kab'],
                        'email' => $values['email']
                    );
                }
            }
        }
        return $this->output->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
	}

    public function summary_api()
    {
        $data = null;
        $endpoint_all_rsud = 'https://dinkes.jakarta.go.id/apps/jp-2024/all-rsud.json';
        $endpoint_satu_sehat = 'https://dinkes.jakarta.go.id/apps/jp-2024/all-rs-terkoneksi.json';
        $endpoint_pengiriman = 'https://dinkes.jakarta.go.id/apps/jp-2024/transaksi-data-satusehat.json';
        
        $response_all_rsud = $this->libadapter->httpGet($endpoint_all_rsud);
        $result_all_rsud = json_decode($response_all_rsud,true);
        if (count($result_all_rsud) > 0)
        {
            $response_satu_sehat = $this->libadapter->httpGet($endpoint_satu_sehat);
            $result_satu_sehat = json_decode($response_satu_sehat,true);
            if (count($result_satu_sehat) > 0)
            {
                $arrayTemp = array();
                foreach ($result_all_rsud as $key => $value){
                    $arrayTemp[] = array_merge((array)$result_satu_sehat[$key], (array)$value);
                }                

                foreach ($arrayTemp as $keys => $values) {
                    $data_rs[] = array(
                        'nama' => $values['nama'],
                        'organisasi_id' => $values['organisasi_id'],
                        'kelas_rs' => $values['kelas_rs'],
                        'status' => $values['status'],
                        'alamat' => $values['alamat'],
                        'kota_kab' => $values['kota_kab'],
                        'email' => $values['email']
                    );
                }
                $response_pengiriman = $this->libadapter->httpGet($endpoint_pengiriman);
                $result_pengiriman = json_decode($response_pengiriman,true);
                if (count($result_pengiriman) > 0)
                {
                    $arrayTemppengiriman = array();
                    foreach ($data_rs as $key => $value){
                        $arrayTemppengiriman[] = array_merge((array)$result_pengiriman[$key], (array)$value);
                    }

                    foreach ($arrayTemppengiriman as $key => $value) {
                        $data[] = array(
                            'nama' => $value['nama'],
                            'organisasi_id' => $value['organisasi_id'],
                            'kelas_rs' => $value['kelas_rs'],
                            'status' => $value['status'],
                            'jumlah_pengiriman_data' => $value['jumlah_pengiriman_data'],
                            'alamat' => $value['alamat'],
                            'kota_kab' => $value['kota_kab'],
                            'email' => $value['email']
                        );
                    }
                }
            }
        }
        return $this->output->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
