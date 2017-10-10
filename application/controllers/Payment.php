<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Visa
 *
 * @author user
 */
class Payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("visa");
    }

    public function index() {
        $params['fname'] = "Gilbert";
        $params['lname'] = "Sibajene";
        $params['email'] = "gilbertsibajene@gmail.com";
        $params['address'] = "8801 Woodlands, Lusaka";
        $params['town'] = "Lusaka";
        $params['phone'] = "260950003910";
        $params['amount'] = "20";
        
        $data['url'] = $this->config->item('url');
        
        $parameters = $this->visa->test($params);
        $data['params'] = $parameters;
        $this->load->view("confirm", $data);
    }

}
