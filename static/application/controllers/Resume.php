<?php
class Resume extends CI_Controller {
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
					redirect("resume");
				} else {
                    redirect("home");
                }
			}else{
				redirect("home");
			}
		} else {
            $username = $this->input->get("username");
            if (!$username) {
                $username = $this->session->userdata('username');
            }
            $result = $this->user_model->getResume($username);
            $this->load->view('template/header');

            if ($result) {
                $data['resume'] = $result;
                $this->load->view('resume', $data);
            } else {
                $this->load->view('noResume');
            }
            $this->load->view('aside');
		}
    }
}

?>