 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('notice_model');
        $this->load->model('settings_model');
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
            $data=array();
            #$data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
			$this->load->view('login');
	}
    public function All_notice(){
        if($this->session->userdata('user_login_access') != False) {
        $data['notice'] = $this->notice_model->GetNotice();
        $this->load->view('backend/notice',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Published_Notice(){
        if($this->session->userdata('user_login_access') != False) {    
            $filetitle = $this->input->post('title');    		
            $ndate = $this->input->post('nodate');    		
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters();
            $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[25]|max_length[150]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                #redirect("notice/All_notice");
            } else {
                if($_FILES['file_url']['name']){
                    $file_name = $_FILES['file_url']['name'];
                    $fileSize = $_FILES["file_url"]["size"]/1024;
                    $fileType = $_FILES["file_url"]["type"];
                    $new_file_name='';
                    $new_file_name .= $file_name;

                    $config = array(
                        'file_name' => $new_file_name,
                        'upload_path' => "./assets/images/notice",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf|doc|docx",
                        'overwrite' => False,
                        'max_size' => "50720000"
                    );
            
                    $this->load->library('Upload', $config);
                    $this->upload->initialize($config);                
                    // if (!$this->upload->do_upload('file_url')) {
                    //     echo $this->upload->display_errors();
                    //     #redirect("notice/All_notice");
                    // }

                    $path = $this->upload->data();
                    $img_url = $path['file_name'];
                    $data = array();
                    $data = array(
                        'title' => $filetitle,
                        'file_url' => $img_url,
                        'date' => $ndate
                    );
                    $success = $this->notice_model->Published_Notice($data); 

                    // Send Notification
                    $emid = $this->session->userdata('user_login_id');
                    $sender = $this->employee_model->emselectByID($emid);

                    $employees =  $this->employee_model->emselect();
                    $employeeEmails = array();
                    $notificationData = array();
                    foreach ($employees as $employee) {
                        if ($employee->em_id != $emid) {
                            $notificationData = array(
                                'title' => 'Notice',
                                'sender_id' => $emid,
                                'sender_name' => $sender->first_name.' '.$sender->last_name,
                                'receiver_id' => $employee->em_id,
                                'receiver_name' => $employee->first_name.' '.$employee->last_name
                            );
                            $this->notification_model->addNotification($notificationData);
                            array_push($employeeEmails,$employee->em_email);
                        }
                    }
                    #$this->session->set_flashdata('feedback','Successfully Updated');
                    #redirect("notice/All_notice");
                    echo "Successfully Added";

                    // Send Email Notification
                    $from = $this->config->item('smtp_user');
                    $this->email->from($from);
                    $this->email->subject('Notice From ParrotHR');
                    $this->email->message('
                        <p>Hello,</p>
                        </br>
                        <p>You have a Notice from ParrotHR<p>
                    ');
                    foreach($employeeEmails as $employeeEmail){
                        $this->email->to('chukajide@gmail.com');
                        $this->email->send();
                    }
                    
                }else{
                    $data = array();
                    $data = array(
                        'title' => $filetitle,
                        'file_url' => null,
                        'date' => $ndate
                    );
                    $success = $this->notice_model->Published_Notice($data); 

                    $emid = $this->session->userdata('user_login_id');
                    $sender = $this->employee_model->emselectByID($emid);

                    $employees =  $this->employee_model->emselect();
                    $employeeEmails = array();
                    // Send Notification
                    foreach ($employees as $employee) {
                        if ($employee->em_id != $emid) {
                            $notificationData = array(
                                'title' => 'Notice',
                                'sender_id' => $emid,
                                'sender_name' => $sender->first_name.' '.$sender->last_name,
                                'receiver_id' => $employee->em_id,
                                'receiver_name' => $employee->first_name.' '.$employee->last_name
                            );
                            $this->notification_model->addNotification($notificationData);
                            array_push($employeeEmails,$employee->em_email);
                        }
                    }

                    // Send Email Notification
                    $from = $this->config->item('smtp_user');
                    $this->email->from($from);
                    $this->email->subject('Notice From ParrotHR');
                    $this->email->message('
                        <p>Hello,</p>
                        </br>
                        <p>You have a Notice from ParrotHR<p>
                    ');
                    foreach($employeeEmails as $employeeEmail){
                        $this->email->to('chukajide@gmail.com');
                        $this->email->send();
                        // $err = $this->email->print_debugger();
                        // echo $err;exit;
                    }
                    #$this->session->set_flashdata('feedback','Successfully Updated');
                    #redirect("notice/All_notice");
                    echo "Successfully Added";
                }
            }
        }
        else{
            redirect(base_url() , 'refresh');
        }        
    }
    
}
