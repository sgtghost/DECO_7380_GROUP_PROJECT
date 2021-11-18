<?php
class Login extends CI_Controller {
    public function index() {
		$this->load->model('user_model');		//load user model
		$this->load->model('file_model');
		$username = $this->input->post('loginUsername'); //getting username from login form
		$password = $this->input->post('loginPassword'); //getting password from login form
		$remember = $this->input->post('remember'); //getting remember checkbox from login form

		$userInfo = $this->user_model->login($username, $password);
		if ($userInfo && $userInfo->active == "1") {//Check username and password
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

			if($remember) { // if remember me is activated create cookie
				set_cookie("username", $username, '300'); //set cookie username
				set_cookie("password", $password, '300'); //set cookie password
				set_cookie("remember", $remember, '300'); //set cookie remember
			}	
			$this->session->set_userdata($user_data); //set user status to login in session
			echo json_encode(array("success"=> true));
			
		}else {	
			if ($userInfo) {
				echo json_encode(array("success"=> false, "active"=> 0)); //User not active
			} else {
				echo json_encode(array("success"=> false, "active"=> false));
			}
		}

	}

	public function register() {
		$this->load->model('user_model');
		$username = $this->input->post('registerName');
		$password = $this->input->post('registerPassword');
		$email = $this->input->post('registerEmail');
		$userType = $this->input->post('userType');
		
		$result = $this->user_model->register($username, $password, $email, $userType);
		if ($result) {
			//Register succefully and send activation email
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
			$this->email->to($email);
			$this->email->subject("Register confirmation on VOLUNTEERING AND YOU");
			$this->email->message("[VOLUNTEERING AND YOU] Please click here to activate your account: 
				<a href='https://productlander.uqcloud.net/static/login/activate?username=" . $username . "'> Activate </a>");
			$this->email->send();
		}
		echo $result;
	}

	public function activate() {
        $this->load->model("user_model");
        $username = $this->input->get("username");
        $this->user_model->setActive($username);
        redirect("home");
    }
	
	public function logout() {
		$this->session->sess_destroy();
		set_cookie("username", "", time() - 3600);
		set_cookie("password", "", time() - 3600);
		set_cookie("remember", "", time() - 3600);
		redirect('home'); // redirect user back to home
	}

    
}
?>