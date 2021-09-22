<?php

// ==================== //
//   [DEV] EyeTracker   //
//     Lolsecs#6289     //
// ==================== //

defined('BASEPATH') or exit('No direct script access allowed');

Class Quest extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->allprotect->AdminDashboard_Protection();
        $this->load->model('admin/eventsquest_model', 'eventsquest');
    }

    function index()
    {
        $data['title'] = 'Events Quest';
        $data['header'] = 'Events Quest';

        $data['events'] = $this->eventsquest->GetEvents();

        $data['content'] = 'admin/content/eventsmanagement/quest/content_quest';
        $this->load->view('admin/layout/wrapper', $data, FALSE);
    }

    function do_delete()
    {
        $this->eventsquest->DeleteEvents();
    }

    function do_update()
    {
        $response = array();

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules(
            'start_date',
            'Start Date',
            'required',
            array('required' => '%s Cannot Be Empty.')
        );
        $this->form_validation->set_rules(
            'end_date',
            'End Date',
            'required',
            array('required' => '%s Cannot Be Empty.')
        );
        if ($this->form_validation->run())
        {
            $this->eventsquest->UpdateEvents();
        }
        else
        {
            $response['response'] = 'false';
            $response['token'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            
            echo json_encode($response);
        }
    }
}

// This Code Generated Automatically By EyeTracker Snippets. //

?>