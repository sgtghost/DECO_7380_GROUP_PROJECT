<?php
class Home extends CI_Controller {
    public function index() {
        $this->load->model('file_model');
        $searchVal = $this->input->get("searchVal");
        $areaVal = $this->input->get("areaVal");
        $searchVal = str_replace("+", " ", $searchVal);

        if ($areaVal) {
            $areaVal = str_replace("!amp;", "&", $areaVal);
        } else {
            $areaVal = "";
        }
        $data['events'] = $this->file_model->getAllEvent($searchVal, $areaVal);
        $data['topEvents'] = $this->file_model->getTopEvent();
        
        //Get My Events
        if ($this->session->userdata('logged_in')) {
            $username = $this->session->userdata('username');
            if ($this->session->userdata('organiser')) {
                $data['myEvents'] = $this->file_model->getOrganiserMyEvents($username);
            } else {
                $data['myEvents'] = $this->file_model->getUserMyEvents($username);
            }
        }

        $this->load->view('template/header');
        $this->load->view('home', $data);
        $this->load->view('aside');
        
    }

    public function test() {
        
    }

}

?>