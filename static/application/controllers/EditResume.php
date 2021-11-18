<?php
class EditResume extends CI_Controller {
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
					redirect("editResume");
				} else {
                    redirect("home");
                }
			}else{
				redirect("home");
			}
		} else {
            $username = $this->session->userdata('username');
            $result = $this->user_model->getResume($username);
            $this->load->view('template/header');

            if (!$result) {
                //Don't have a resume, get basic info
                $result = $this->user_model->getUserInfo($username);
            }

            $data['resume'] = $result;
            $this->load->view('editResume', $data);
            $this->load->view('aside');
		}
    }

    public function update() {
        $this->load->model('user_model');
        $this->load->model('file_model');

        $username = $this->session->userdata('username');
        $ownerID = $this->input->post('ownerID');
        $isNew = $this->input->post('isNew');
        $firstName = $this->input->post('firstName');
        $lastName = $this->input->post('lastName'); 
        if($this->input->post('orgName') != null) {
            $name = $this->input->post('orgName');
        } else {
            $name = $firstName . " " . $lastName;
        }
        // $emailAddress = $this->input->post('emailAddress'); 
        $phoneNo = $this->input->post('phoneNo'); 
        $gender = $this->input->post('gender'); 
        $age = $this->input->post('age'); 
        $base64url = $this->input->post('editAvatar');
        $times = $this->input->post('times'); 
        $areas = $this->input->post('areas'); 
        $description = $this->input->post('userDesc');
        $skills = $this->input->post('skills');

        //Upload avatar image
        $path = "assets/images/";
        $start = strpos($base64url,','); 
        $base64url = substr($base64url,$start+1); 
        $base64url = str_replace(' ', '+', $base64url);
        $data = base64_decode($base64url);

        $avatarInfo = $this->file_model->getAvatar($username);
        if ($avatarInfo && isset(json_decode($avatarInfo[0]->descriptionImageLink)->avatar)) {
            $fileName = json_decode($avatarInfo[0]->descriptionImageLink)->avatar;
        }else {
            $fileName = uniqid() . '.png';
        }
        
        $avatarUploadSuccess = file_put_contents($path . $fileName, $data);

        if ($avatarUploadSuccess) {
            $this->session->set_userdata("avatar", $fileName);
            $descriptionText = '{"name":"' . $name . '","description":"' . $description . '","gender":"' . $gender . '","age":' . $age . ',"time":' . $times . ',"skills":' . $skills . '}';
            $descriptionImageLink = '{"avatar":"' . $fileName . '"}';
            $tags = '{"tags":' . $areas . '}';
            $descriptionText = str_replace("\"", "\\\"", $descriptionText);
            $descriptionText = str_replace('\\\"', '\\\\\"', $descriptionText);
            $descriptionText = str_replace('\'', '\\\'', $descriptionText);
            $descriptionImageLink = str_replace("\"", "\\\"", $descriptionImageLink);
            $descriptionImageLink = str_replace('\'', '\\\'', $descriptionImageLink);
            $tags = str_replace("\"", "\\\"", $tags);
            $tags = str_replace('\'', '\\\'', $tags);
            if ($isNew == "false") {
                $result = $this->user_model->updateResume($ownerID, $firstName, $lastName, $phoneNo, $descriptionText, $descriptionImageLink, $tags);
            } else {
                $result = $this->user_model->insertResume($ownerID, $firstName, $lastName, $phoneNo, $descriptionText, $descriptionImageLink, $tags);
            }
            
            echo true;
        } else {
            echo false;
        }

    }
}

?>