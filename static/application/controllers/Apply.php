<?php
class Apply extends CI_Controller {
    public function index() {
        $this->load->model('file_model');
        $this->load->model('user_model');
        $eventID = $this->input->post("eventID");
        $username = $this->session->userdata('username');
        
        if ($eventID) {
            if ($this->user_model->getParticipations($username, $eventID)) {
                //Already applied this event
                echo json_encode(array("success" => false));
            } else {
                $result = $this->user_model->insertParticipations($username, $eventID);
                echo json_encode(array("success" => true));
            }
        } else {
            echo false;
        }
    }

    public function hasResume() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $username = $this->session->userdata('username');

        if ($this->user_model->getResume($username)) {
            echo true;
        } else {
            echo false;
        }
    }
    public function rejectOne() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $uid = $this->input->post("uid");
        $rejectUserInfo = $this->user_model->getUserInfoById($uid);

        if ($rejectUserInfo) {
            //Set denied
            $this->user_model->setDenied($uid, $eventID);
            $eventInfo = $this->file_model->getEventByID($eventID)[0];
			//Reject email to the user
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mailhub.eait.uq.edu.au',
				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE ,
				'mailtype' => 'html',
				'starttls' => true,
				'newline' => "\r\n"
			);
			   
			$this->email->initialize($config);
			$this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
			$this->email->to($rejectUserInfo->emailAddress);
			$this->email->subject("Rejected Infromation on VOLUNTEERING AND YOU");
			$this->email->message("Dear $rejectUserInfo->username,<br><br>
                                   Sorry to tell you that<br>
                                   your application on Event <strong>'" . json_decode($eventInfo->descriptionText)->name . "'</strong> has been <strong style='color:red;'>Rejected</strong>.<br>
                                   Looking forward to your next apply.<br><br>
                                   Regards,<br>VOLUNTEERING AND YOU");
			$this->email->send();
		}
    }

    public function acceptOne() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $uid = $this->input->post("uid");
        $acceptUserInfo = $this->user_model->getUserInfoById($uid);

        if ($acceptUserInfo) {
            //Set passed
            $this->user_model->setPassed($uid, $eventID);
            $eventInfo = $this->file_model->getEventByID($eventID)[0];
			//Accept email to the user
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mailhub.eait.uq.edu.au',
				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE ,
				'mailtype' => 'html',
				'starttls' => true,
				'newline' => "\r\n"
			);
			   
			$this->email->initialize($config);
			$this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
			$this->email->to($acceptUserInfo->emailAddress);
			$this->email->subject("Approved Infromation on VOLUNTEERING AND YOU");
			$this->email->message("Dear $acceptUserInfo->username,<br><br>
                                   <h4 style='color:green;'>Congratulations!!!</h4>
                                   your application on Event <strong>'" . json_decode($eventInfo->descriptionText)->name . "'</strong> has been <strong style='color:green;'>Accepted</strong>.<br>
                                   Looking forward to your coming.<br><br>
                                   Regards,<br>VOLUNTEERING AND YOU");
			$this->email->send();
		}
    }

    public function acceptOneFull() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $uid = $this->input->post("uid");
        $acceptUserInfo = $this->user_model->getUserInfoById($uid);
        //First set the user to passed
        if ($acceptUserInfo) {
            //Set passed
            $this->user_model->setPassed($uid, $eventID);
            $eventInfo = $this->file_model->getEventByID($eventID)[0];
			//Accept email to the user
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mailhub.eait.uq.edu.au',
				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE ,
				'mailtype' => 'html',
				'starttls' => true,
				'newline' => "\r\n"
			);
			   
			$this->email->initialize($config);
			$this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
			$this->email->to($acceptUserInfo->emailAddress);
			$this->email->subject("Approved Infromation on VOLUNTEERING AND YOU");
			$this->email->message("Dear $acceptUserInfo->username,<br><br>
                                   <h4 style='color:green;'>Congratulations!!!</h4>
                                   your application on Event <strong>'" . json_decode($eventInfo->descriptionText)->name . "'</strong> has been <strong style='color:green;'>Accepted</strong>.<br>
                                   Looking forward to your coming.<br><br>
                                   Regards,<br>VOLUNTEERING AND YOU");
			$this->email->send();
		}
        
        //Then reject all other applicants
        //Send email to each denied user
        $result = $this->user_model->getProcessingByEventID($eventID);
        if ($result) {
            foreach ($result as $key => $userInfo) {
                $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'mailhub.eait.uq.edu.au',
                    'smtp_port' => 25,
                    'mailtype' => 'html',
                    'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE ,
                    'mailtype' => 'html',
                    'starttls' => true,
                    'newline' => "\r\n"
                );
                   
                $this->email->initialize($config);
                $this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
                $this->email->to($userInfo->emailAddress);
                $this->email->subject("Rejected Infromation on VOLUNTEERING AND YOU");
                $this->email->message("Dear $userInfo->username,<br><br>
                                   Sorry to tell you that<br>
                                   your application on Event <strong>'" . json_decode($eventInfo->descriptionText)->name . "'</strong> has been <strong style='color:red;'>Rejected</strong>.<br>
                                   Looking forward to your next apply.<br><br>
                                   Regards,<br>VOLUNTEERING AND YOU");
                $this->email->send();
            }
        }
        //Set each denied
        $this->user_model->setAllDenied($eventID);
    }

    public function rejectAll() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $eventInfo = $this->file_model->getEventByID($eventID)[0];

        //Reject all applicants
        //Send email to each denied user
        $result = $this->user_model->getProcessingByEventID($eventID);
        if ($result) {
            foreach ($result as $key => $userInfo) {
                $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'mailhub.eait.uq.edu.au',
                    'smtp_port' => 25,
                    'mailtype' => 'html',
                    'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE ,
                    'mailtype' => 'html',
                    'starttls' => true,
                    'newline' => "\r\n"
                );
                   
                $this->email->initialize($config);
                $this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
                $this->email->to($userInfo->emailAddress);
                $this->email->subject("Rejected Infromation on VOLUNTEERING AND YOU");
                $this->email->message("Dear $userInfo->username,<br><br>
                                   Sorry to tell you that<br>
                                   your application on Event <strong>'" . json_decode($eventInfo->descriptionText)->name . "'</strong> has been <strong style='color:red;'>Rejected</strong>.<br>
                                   Looking forward to your next apply.<br><br>
                                   Regards,<br>VOLUNTEERING AND YOU");
                $this->email->send();
            }
        }
        //Set each denied
        $this->user_model->setAllDenied($eventID);
        //Set event ended
        $this->file_model->setEventEnded($eventID);
    }

    public function deleteOne() {
        $this->load->model('user_model');
        $eventID = $this->input->post("eventID");
        $uid = $this->input->post("uid");
        $this->user_model->deleteOneParticipation($eventID, $uid);
    }
}

?>