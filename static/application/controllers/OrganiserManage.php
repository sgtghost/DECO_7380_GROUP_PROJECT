<?php
class OrganiserManage extends CI_Controller {
    public function index() {
        $this->load->model('user_model');
        $this->load->model('file_model');

        if (!$this->session->userdata('logged_in')) {	
			if (get_cookie('remember')) { // check if user activate the "remember me" feature  
				$username = get_cookie('username'); //get the username from cookie
				$password = get_cookie('password'); //get the username from cookie
                $userInfo = $this->user_model->login($username, $password);
				if ($userInfo && $userInfo->active == "1")//check username and password correct
				{
                    $path = $this->file_model->getAvatar($username);

                    if ($path && isset(json_decode($path[0]->descriptionImageLink)->avatar)) {
                        $avatar = json_decode($path[0]->descriptionImageLink)->avatar;
                    } else {
                        $avatar = "userMale.png";
                    }
					$user_data = array(
						'username' => $username,
						'logged_in' => true, 	//create session variable
						'avatar' => $avatar,
                        'organiser' => $userInfo->organiser
					);
					$this->session->set_userdata($user_data); //set user status to login in session
					redirect("eventDetail?eventID=".$eventID);
				} else {
                    redirect("home");
                }
			}else{
				redirect("home");
			}
		} else {
            $username = $this->session->userdata('username');
            if ($this->user_model->getResume($username)) {
                //Has resume
                $privateEvents = $this->file_model->getOrganiserManageEvents($username, "private");
                $ongoingEvents = $this->file_model->getOrganiserManageEvents($username, "ongoing");
                $endedEvents = $this->file_model->getOrganiserManageEvents($username, "ended");
                if ($privateEvents || $ongoingEvents|| $endedEvents) {
                    if ($privateEvents) {
                        $data["privateEvents"] = $privateEvents;
                    }
    
                    if ($ongoingEvents) {
                        $data["ongoingEvents"] = $ongoingEvents;
                    }
    
                    if ($endedEvents) {
                        $data["endedEvents"] = $endedEvents;
                    }
                    
                    $this->load->view('template/header');
                    $this->load->view('organiserManage', $data);
                    $this->load->view('aside');
                } else {
                    $this->load->view('template/header');
                    $this->load->view('noEvent');
                    $this->load->view('aside');
                } 
            } else {
                //No resume
                redirect('resume');
            }
      
		}
    }

    public function getApplication() {
        $this->load->model('user_model');
        $this->load->model('file_model');

        $eventID = $this->input->post("eventID");
        $result = $this->user_model->getNotDeniedParticipations($eventID);

        if ($result) {
            echo json_encode($result);
        } else {
            echo false;
        }
    }

    public function getEvent() {
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $event = $this->file_model->getEventByID($eventID);

        echo json_encode($event[0]);
    }

    public function toPublic() {
        $this->load->model('file_model');
        $eventID = $this->input->post("eventID");
        $this->file_model->eventToPublic($eventID);
    }

}

?>