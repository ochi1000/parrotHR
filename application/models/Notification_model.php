<?php

	class Notification_model extends CI_Model{


	function __construct(){
	parent::__construct();
    $this->load->library('email');
    $this->load->config('email');   
    $this->load->model('employee_model');
}

    public function addNotification($data){
        $this->db->insert('notifications', $data);
    }

    public function getNotification($id){
        $sql = "SELECT * FROM `notifications` WHERE `receiver_id`='$id'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result; 
    }

    public function seenNotification($id){

        $this->db->set('seen', 'Seen');
        $this->db->where('receiver_id', $id);
        $this->db->update('notifications'); 
    }

    public function sendNotification($sender, $receiver, $subject, $message){
        
        $notificationData = array(
            'title' => $subject,
            'sender_id' => $sender->em_id,
            'sender_name' => $sender->first_name.' '.$sender->last_name,
            'receiver_id' => $receiver->em_id,
            'receiver_name' => $receiver->first_name.' '.$receiver->last_name,
        );

        $this->addNotification($notificationData);

        // Send EMail Notifications
        $from = $this->config->item('smtp_user');
        $this->email->from($from);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->to($receiver->em_email);
        $this->email->send();
    }

    public function sendNotificationToAdmins($sender, $subject, $message){
        $admins = $this->employee_model->getAdmins();
        foreach ($admins as $admin) {
            $notificationData = array(
                'title' => $subject,
                'sender_id' => $sender->em_id,
                'sender_name' => $sender->first_name.' '.$sender->last_name,
                'receiver_id' => $admin->em_id,
                'receiver_name' => $admin->first_name.' '.$admin->last_name,
            );
            
            $this->addNotification($notificationData);
    
            // Send EMail Notifications
            $from = $this->config->item('smtp_user');
            $this->email->from($from);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->to($admin->em_email);
            $this->email->send();
        }

    }
}

?>