<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Visa {

    public $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('session');
        $this->CI->config->item('base_url');
    }

    public function test($data) {

        $params = array();
        $params['access_key'] = $this->CI->config->item('ACCESS_KEY');
        $params['profile_id'] = $this->CI->config->item('PROFILE_ID');
        $params['transaction_uuid'] = uniqid();
        $params['signed_field_names'] = 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,currency,payment_method,amount';
        $params['unsigned_field_names'] = 'bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_city,bill_to_address_country,bill_to_phone,bill_to_address_state,bill_to_address_postal_code';
        $params['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $params['locale'] = 'en';
        $params['transaction_type'] = "sale";


        $params['reference_number'] = $this->get_reference_number();

        $params['bill_to_forename'] = $data['fname'];
        $params['bill_to_surname'] = $data['lname'];
        $params['bill_to_email'] = $data['email'];
        $params['bill_to_address_line1'] = $data['address'];
        $params['bill_to_address_city'] = $data['town'];
        $params['bill_to_address_country'] = 'ZM';
        $params['bill_to_address_postal_code'] = '10101';
        $params['bill_to_address_state'] = $data['town'];
        $params['bill_to_phone'] = $data['phone'];

        $params['payment_method'] = 'card';
        $params['currency'] = "USD";
        $params['amount'] = $data['amount'];

        $params['signature'] = $this->sign($params);
        var_dump($params);
        return $params;
    }

    function sign($params) {
        return $this->signData($this->buildDataToSign($params), $this->CI->config->item('SECRET_KEY'));
    }

    function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    function buildDataToSign($params) {
        $signedFieldNames = explode(",", $params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
            $dataToSign[] = $field . "=" . $params[$field];
        }
        return $this->commaSeparate($dataToSign);
    }

    function commaSeparate($dataToSign) {
        return implode(",", $dataToSign);
    }

    public function get_reference_number() {
        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
        return substr($d->format("YmdHisu"), 0, 17);
    }

    public function sale() {
        $account = $this->CI->session->userdata('account');

        $params = array();
        $params['access_key'] = $this->CI->config->item('ACCESS_KEY');
        $params['profile_id'] = $this->CI->config->item('PROFILE_ID');
        $params['transaction_uuid'] = uniqid();
        $params['signed_field_names'] = 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,currency,payment_method,amount';
        $params['unsigned_field_names'] = 'bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_city,bill_to_address_country,bill_to_phone,bill_to_address_state,bill_to_address_postal_code,merchant_defined_data1,merchant_defined_data2,item_1_name';

        $params['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");


        $params['locale'] = 'en';
        $params['transaction_type'] = "sale";


        $params['reference_number'] = $this->get_reference_number();

        $params['bill_to_forename'] = $account['fname'];
        $params['bill_to_surname'] = $account['lname'];
        $params['bill_to_email'] = $account['email'];
        $params['bill_to_address_line1'] = $account['address'];
        $params['bill_to_address_city'] = $account['town']['name'];
        $params['bill_to_address_country'] = 'ZM';
        $params['bill_to_address_postal_code'] = '10101';

        $params['bill_to_address_state'] = $account['town']['province']['name'];
        $params['bill_to_phone'] = $account['phone'];

        $params['payment_method'] = 'card';
        $params['currency'] = "ZMW";
        $params['amount'] = $this->CI->input->post('txt_amount');
        // $params['card_type'] = $this->CI->input->post('rbx_card_type');
        // $params['card_number'] = $this->CI->input->post('txt_card_number');
        // $params['card_cvn'] = $this->CI->input->post('txt_cvn');
        // $params['card_expiry_date'] = "{$this->CI->input->post('cbx_month')}-{$this->CI->input->post('cbx_year')}";

        $params['signature'] = $this->sign($params);
        return $params;
    }

    public function token_sale($token, $amount) {
        $params = array();
        $params['access_key'] = $this->CI->config->item('ACCESS_KEY');
        $params['profile_id'] = $this->CI->config->item('PROFILE_ID');
        $params['transaction_uuid'] = uniqid();
        $params['signed_field_names'] = 'access_key,profile_id,transaction_uuid,signed_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,payment_token';
        //$params['unsigned_field_names'] = 'payment_token';
        $params['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $params['locale'] = 'en';
        $params['transaction_type'] = "sale";
        $params['reference_number'] = getDate()[0];

        $params['payment_token'] = $token;
        $params['amount'] = $amount;
        $params['currency'] = "ZMW";

        $params['signature'] = $this->sign($params);
        $secret_key = $params['signature'];

        return $params;
    }

}
