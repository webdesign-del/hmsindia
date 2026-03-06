<?php

class Embryology_model extends CI_Model
{
    public function get_records($limit, $start, $filters = [])
    {
        $this->db->from('embryology_discharge_summary');

        // Filters
        if (!empty($filters['center'])) {
            $this->db->where('center', $filters['center']);
        }

        if (!empty($filters['doctor_id'])) {
            $this->db->where('doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['iic_id'])) {
            $this->db->where('iic_id', $filters['iic_id']);
        }

        if (!empty($filters['from_date'])) {
            $this->db->where('date_of_addmission >=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $this->db->where('date_of_addmission <=', $filters['to_date']);
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');

        return $this->db->get()->result();
    }

    public function count_records($filters = [])
    {
        $this->db->from('embryology_discharge_summary');

        if (!empty($filters['center'])) {
            $this->db->where('center', $filters['center']);
        }

        if (!empty($filters['doctor_id'])) {
            $this->db->where('doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['iic_id'])) {
            $this->db->where('iic_id', $filters['iic_id']);
        }

        if (!empty($filters['from_date'])) {
            $this->db->where('date_of_addmission >=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $this->db->where('date_of_addmission <=', $filters['to_date']);
        }

        return $this->db->count_all_results();
    }
}