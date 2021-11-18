<?php
class GetEvent extends CI_Controller {
    public function index() {
        $this->load->model('file_model');
        $count = $this->input->post('count');
        $searchVal = $this->input->post('searchVal');
        $areaVal = $this->input->post('areaVal');
        $result= $this->file_model->getMoreEvent($count, $searchVal, $areaVal);
        
        if (isset($result)) {
            echo json_encode($result);
        } else {
            echo 0;
        }
        
    }


}

?>