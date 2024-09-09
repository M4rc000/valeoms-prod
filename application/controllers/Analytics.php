<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Analytics extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->model('Admin_model','AModel');
        $this->load->model('Warehouse_model', 'WModel');
        $this->load->model('Production_model', 'PModel');
        $this->load->model('Master_model', 'MModel');
    }
    
    public function chart(){
        $data['title'] = 'Chart';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['user'] = $this->AModel->getAllUsers();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('analytics/chart', $data);
        $this->load->view('templates/footer');
	}

    public function getChartDataBox() {
        // If no year is provided, use the current year.
        $year = $this->input->post('year') ? $this->input->post('year') : date('Y');
    
        // Get data for the chart
        $highData = $this->AModel->getBoxData('HIGH', $year);
        $mediumData = $this->AModel->getBoxData('MEDIUM', $year);
    
        // Return data as JSON
        echo json_encode([
            'highData' => $highData,
            'mediumData' => $mediumData,
            'year' => $year
        ]);
    }

    public function getChartDataKanban() {
        // If no year is provided, use the current year.
        $year = $this->input->post('year') ? $this->input->post('year') : date('Y');
    
        // Get data for the chart
        $result = $this->AModel->getKanbanData($year);
    
        // Return data as JSON
        echo json_encode([
            'kanbanData' => $result,
            'year' => $year
        ]);
    }

    public function getChartDataProductionRequest() {
        // If no year is provided, use the current year.
        $year = $this->input->post('year') ? $this->input->post('year') : date('Y');
    
        // Get data for the chart
        $result = $this->AModel->getProductionRequestData($year);
    
        // Return data as JSON
        echo json_encode([
            'productionRequest' => $result,
            'year' => $year
        ]);
    }

    public function getChartDataQualityRequest() {
        // If no year is provided, use the current year.
        $year = $this->input->post('year') ? $this->input->post('year') : date('Y');
    
        // Get data for the chart
        $result = $this->AModel->getQualityRequestData($year);
    
        // Return data as JSON
        echo json_encode([
            'qualityRequest' => $result,
            'year' => $year
        ]);
    }
}