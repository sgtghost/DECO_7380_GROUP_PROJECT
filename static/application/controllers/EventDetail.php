<?php
class EventDetail extends CI_Controller {
    public function index() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->get("eventID");
        if (!$eventID || !$this->file_model->getEventByID($eventID)) {
            $eventID = 2;
        }

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
            $event = $this->file_model->getEventByID($eventID);
            //Update clickCount
            $this->file_model->updateClickCount($eventID);
            $organiser = $this->file_model->getOrganiserByEvent($eventID);
            $applied = $this->user_model->getParticipations($this->session->userdata('username'), $eventID);
            $comments = $this->file_model->getCommentsByEvent($eventID);
            $replies = $this->file_model->getRepliesByEvent($eventID);
            if ($applied) {
                $data['applied'] = $applied;
            }

            if ($comments) {
                $data['comments'] = $comments;
            }

            if ($replies) {
                $data['replies'] = $replies;
            }
            $data['event'] = $event[0];
            $data['organiser'] = $organiser[0];

            $this->load->view('template/header');
            $this->load->view('eventDetail', $data);
            $this->load->view('aside');
		}
    }

    public function edit() {
        $this->load->model('user_model');
        $this->load->model('file_model');
        $eventID = $this->input->get("eventID");

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
					redirect("eventDetail/edit?eventID=".$eventID);
				} else {
                    redirect("home");
                }
			}else{
				redirect("home");
			}
		} else {
            $username = $this->session->userdata('username');
            if ($this->user_model->getResume($username)) {
                if ($this->session->userdata('organiser')) {
                    if (isset($eventID)) {
                        //Update an already exist event
                        $eventInfo = $this->file_model->getEventByID($eventID);

                        if ($eventInfo && $eventInfo[0]->organiserName == $username) {
                            //This event must owned by the user
                            $event = $this->file_model->getEventByID($eventID);
                            $data['event'] = $event[0];
                
                            $this->load->view('template/header');
                            $this->load->view('editEvent', $data);
                            $this->load->view('aside');
                        } else {
                            //Don't have this event
                            redirect('home');
                        }
                        
                    } else {
                        //Insert a new event
                        $this->load->view('template/header');
                        $this->load->view('editEvent');
                        $this->load->view('aside');
                    }
                } else {
                    //Not an organiser
                    redirect('home');
                }
            } else {
                redirect("home");
            }
		}
    }

    public function update() {
        $this->load->model('user_model');
        $this->load->model('file_model');

        $username = $this->session->userdata('username');
        $eventID = $this->input->post('eventID');
        $organiser = $this->user_model->getUserInfo($username)->id;
        $status = $this->input->post('eventStatus');
        if ($status && isset($status)) {
            if ($status == "Private") {
                $status = "private";
            } else {
                $status = "ongoing";
            }
        }
        $address = $this->input->post('editEventAddress');
        $latitude = $this->input->post('eventLatitude');
        $longitude = $this->input->post('eventLongtitude');
        $date = $this->input->post('editEventDate');
        $eventSlot = $this->input->post('eventSlot');
        $eventName = $this->input->post('editEventName');
        $eventDesc = $this->input->post('editEventDescVal');
        $base64url = $this->input->post('editEventAvatar');
        $eventImage = "[]";
        $eventPersonas = $this->input->post('editEventPersonas');
        $eventSkills = $this->input->post('eventSkills');
        $tags = $this->input->post('eventAreas');

        //Upload avatar image
        $path = "assets/images/";
        $start = strpos($base64url,','); 
        $base64url = substr($base64url,$start+1); 
        $base64url = str_replace(' ', '+', $base64url);
        $data = base64_decode($base64url);

        if ($eventID) {
            $eventInfo = $this->file_model->getEventByID($eventID);
            if ($eventInfo) {
                $fileName = json_decode($eventInfo[0]->descriptionImageLink)->avatar;
            }else {
                $fileName = uniqid() . '.jpg';
            }
        }else {
            $fileName = uniqid() . '.jpg';
        }
        $avatarUploadSuccess = file_put_contents($path . $fileName, $data);

        if ($avatarUploadSuccess) {
            $coordinates = $latitude . ", " . $longitude;
            $descriptionText = '{"name":"' . $eventName . '","description":"' . $eventDesc . '"}';
            $descriptionText = str_replace("\"", "\\\"", $descriptionText);
            $descriptionText = str_replace('\\\"', '\\\\\"', $descriptionText);
            $descriptionText = str_replace('\'', '\\\'', $descriptionText);
            $descriptionImageLink = '{"avatar":"' . $fileName . '","image":' . $eventImage . '}';
            $descriptionImageLink = str_replace("\"", "\\\"", $descriptionImageLink);
            $descriptionImageLink = str_replace('\'', '\\\'', $descriptionImageLink);
            $requirement = '{"personas":"' . $eventPersonas . '","skills":' . $eventSkills . ',"tags":' . $tags . '}';
            $requirement = str_replace("\"", "\\\"", $requirement);
            $requirement = str_replace('\'', '\\\'', $requirement);
            if ($eventID) {
                //Update
                $result = $this->file_model->updateEvent($eventID, $organiser, $address, $coordinates, $date, $descriptionText, $descriptionImageLink, $requirement);

                if ($result) {
                    echo $eventID;
                } else {
                    echo false;
                }
            } else {
                //Insert
                $result = $this->file_model->insertEvent($organiser, $status, $address, $coordinates, $date, $eventSlot, $descriptionText, $descriptionImageLink, $requirement);

                if ($result) {
                    echo $result->eventID;
                } else {
                    echo false;
                }
            }
        }

        // echo $eventID . "_____" . $organiser . "_____" . $address . "_____" . $coordinates  . "_____" . $date . "_____" . $descriptionText . "_____" . $descriptionImageLink . "_____" . $requirement;
    }
}

?>