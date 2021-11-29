 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model');
        $this->load->model('settings_model');    
        $this->load->model('notice_model');    
        $this->load->model('project_model');    
        $this->load->model('leave_model');    
        $this->load->model('notification_model');    
        $this->load->library('email');
        $this->load->config('email');   
    }
    
	public function index()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
			redirect('dashboard/Dashboard');
		// $data=array();
		// $data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
		$this->load->view('login');
	}
    function Dashboard(){

		$data=array();
		$data['settingsvalue'] = $this->settings_model->GetSettingsValue();

		if($data['settingsvalue']){
			if($this->session->userdata('user_login_access') != False) {
				$this->load->view('backend/dashboard');
			}else{
				redirect(base_url() , 'refresh');
			}  
		}else{
			redirect(base_url() , 'refresh');
		}  
		
		// echo var_dump($data['settingsvalue']);
        // if($this->session->userdata('user_login_access') != False) {
        // 	$this->load->view('backend/dashboard');
        // }
		// else{
		// 	redirect(base_url() , 'refresh');
		// }            
    }
    public function add_todo(){
        $userid = $this->input->post('userid');
        $tododata = $this->input->post('todo_data');
        $date = date("Y-m-d h:i:sa");
        $this->load->library('form_validation');
        //validating to do list data
        $this->form_validation->set_rules('todo_data', 'To-do Data', 'trim|required|min_length[5]|max_length[150]|xss_clean');        
        if($this->form_validation->run() == FALSE){
            echo validation_errors();
        } else {
        $data=array();
        $data = array(
        'user_id' => $userid,
        'to_dodata' =>$tododata,
        'value' =>'1',
        'date' =>$date    
        );
        $success = $this->dashboard_model->insert_tododata($data);
            #echo "successfully added";
            if($this->db->affected_rows()){
                echo "successfully added";
            } else {
                echo "validation Error";
            }
        }        
    }
	public function Update_Todo(){
        $id = $this->input->post('toid');
		$value = $this->input->post('tovalue');
			$data = array();
			$data = array(
				'value'=> $value
			);
        $update= $this->dashboard_model->UpdateTododata($id,$data);
        $inserted = $this->db->affected_rows();
		if($inserted){
			$message="Successfully Added";
			echo $message;
		} else {
			$message="Something went wrong";
			echo $message;			
		}
	}    

    
    public function getNotifications(){
        $id = $this->session->userdata('user_login_id');
        $notifications = $this->notification_model->getNotification($id);
        // echo $id; exit;
        echo json_encode($notifications);
    }

    public function seenNotifications(){
        // $id = $this->session->userdata('user_login_id');
        // $this->notification_model->seenNotification($id);
        
        // Send Email Notification
        $this->email->initialize();
        $from = $this->config->item('smtp_user');
        $this->email->from($from);
        $this->email->subject('Notice From ParrotHR');
        $this->email->set_newline("\r\n");  
        $this->email->message('
        <p>Hello,</p>
        </br>
        <p>You have a Notice from ParrotHR<p>
        ');
        $this->email->to('ochiabuto@zercomsystems.com');

        // echo $from;exit;
        $this->email->send();
        $err = $this->email->print_debugger();
        echo $err;exit;
    }

}
