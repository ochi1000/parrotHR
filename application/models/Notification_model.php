<?php

	class Notification_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
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

}

?>