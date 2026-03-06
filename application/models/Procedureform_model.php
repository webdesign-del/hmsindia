<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Asia/Calcutta');




class Procedureform_model extends CI_Model
{

    public function count_records($patient_id = null)
{
    if($patient_id){
        $this->db->like('patient_id',$patient_id);
    }

    return $this->db->count_all_results('embryo_record');
}


   public function get_records($limit,$start,$patient_id=null)
{
    if($patient_id){
        $this->db->like('patient_id',$patient_id);
    }

    $this->db->limit($limit,$start);

    return $this->db->get('embryo_record')->result_array();
}


    public function update_status($id,$status)
    {

        $this->db->where('id',$id);

        return $this->db->update('embryo_record',[
            'status'=>$status,
            'updated_at'=>date('Y-m-d H:i:s')
        ]);

    }

}