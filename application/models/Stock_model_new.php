<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Stock_model_new extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

    /**
     * Calculate expiry days for a batch
     * @param string $expiry_date
     * @return int
     */
    public function calculate_expiry_days($expiry_date)
    {
        $expiry = new DateTime($expiry_date);
        $today = new DateTime();
        $diff = $today->diff($expiry);
        return $expiry > $today ? $diff->days : -$diff->days;
    }

    /**
     * Update expiry_days for all batches
     * Call this method periodically to keep expiry_days current
     */
    public function update_expiry_days()
    {
        $this->db->query("
            UPDATE medicine_batches
            SET expiry_days = DATEDIFF(expiry_date, CURDATE())
            WHERE batch_status = 'ACTIVE'
        ");
    }

    // ===============================================
    // DASHBOARD FUNCTIONS
    // ===============================================

    public function get_dashboard_summary()
    {
        // try {
            // Get total medicines
            $this->db->select("COUNT(*) as total_medicines");
            $this->db->from("medicines");
            $this->db->where("status", "active");
            $medicines = $this->db->get()->row();

            // Get total active batches
            $this->db->select("COUNT(*) as total_batches");
            $this->db->from("medicine_batches");
            $this->db->where("batch_status", "ACTIVE");
            $this->db->where("quantity_remaining >", 0);
            $batches = $this->db->get()->row();

            // Get low stock count
            $low_stock_query = $this->db->query("
                SELECT COUNT(*) as low_stock_count
                FROM (
                    SELECT m.id
                    FROM medicines m
                    LEFT JOIN medicine_batches mb ON m.id = mb.medicine_id AND mb.batch_status = 'ACTIVE' AND mb.quantity_remaining > 0
                    WHERE m.status = 'active' AND m.min_stock_level > 0
                    GROUP BY m.id, m.min_stock_level
                    HAVING COALESCE(SUM(mb.quantity_remaining), 0) <= m.min_stock_level
                ) as low_stock_medicines
            ");
            $low_stock = $low_stock_query ? $low_stock_query->row() : null;
            // $this->db->select("COUNT(*) as low_stock_count");
            // $this->db->from("medicines m");
            // $this->db->join("medicine_batches mb", "m.id = mb.medicine_id");
            // $this->db->where("m.status", "active");
            // $this->db->where("mb.batch_status", "ACTIVE");
            // $this->db->where(
            //     "mb.quantity_remaining <=",
            //     "m.min_stock_level",
            //     false,
            // );
            // $low_stock = $this->db->get()->row();

            // Get expiring soon count (within 30 days)
        $this->db->select("COUNT(*) as expiring_soon_items");
            $this->db->from("medicine_batches");
            $this->db->where("batch_status", "ACTIVE");
            $this->db->where("quantity_remaining >", 0);
            $this->db->where("DATEDIFF(expiry_date, CURDATE()) <=", 7);
            $this->db->where("DATEDIFF(expiry_date, CURDATE()) >", 0); // Changed from >= 0
            $expiring_soon = $this->db->get()->row();
            // $expiring_soon_count = $this->db->where("batch_status", "ACTIVE")
            //                                ->where("quantity_remaining >", 0)
            //                                ->where("DATEDIFF(expiry_date, CURDATE()) <=", 30)
            //                                ->where("DATEDIFF(expiry_date, CURDATE()) >=", 0)
            //                                ->count_all_results("medicine_batches");

            // Get expired count
            $this->db->select("COUNT(*) as expired_count");
            $this->db->from("medicine_batches");
            $this->db->where("batch_status", "ACTIVE");
            $this->db->where("quantity_remaining >", 0);
            $this->db->where("DATEDIFF(expiry_date, CURDATE()) <", 0);
            $expired = $this->db->get()->row();

            // Get total stock value
            $this->db->select('SUM(quantity_remaining * selling_price) as total_stock_value', FALSE);
            $this->db->from("medicine_batches");
            $this->db->where("batch_status", "ACTIVE");
            $this->db->where("quantity_remaining >", 0);
            $stock_value = $this->db->get()->row();
            // --- NEW: Get Today's Sales ---
            $this->db->select('COUNT(id) as todays_sales_count, SUM(total_amount) as todays_sales_value');
            $this->db->from('sales');
            $this->db->where('sale_date', date('Y-m-d')); // CURDATE()
            $this->db->where('status', 'CONFIRMED'); // Only confirmed sales
            
            $todays_sales = $this->db->get()->row();
            // --- NEW: Get Today's Transfers ---
            $this->db->select('COUNT(id) as todays_transfer_count, SUM(total_value) as todays_transfer_value');
            $this->db->from('stock_transfers');
            $this->db->where('transfer_date', date('Y-m-d')); // CURDATE()
            $this->db->where('status', 'COMPLETED'); // Only completed transfers
            $todays_transfers = $this->db->get()->row();

            // Get expiring soon items (for the second card)
            $this->db->select("COUNT(*) as expiring_soon_items");
            $this->db->from("medicine_batches");
            $this->db->where("batch_status", "ACTIVE");
            $this->db->where("quantity_remaining >", 0);
            $this->db->where("DATEDIFF(expiry_date, CURDATE()) <=", 30);
            $this->db->where("DATEDIFF(expiry_date, CURDATE()) >=", 0);
            $expiring_soon = $this->db->get()->row();
            return (object) [
                "total_medicines" => $medicines->total_medicines ?? 0,
                "total_batches" => $batches->total_batches ?? 0,
                "low_stock_count" => $low_stock->low_stock_count ?? 0,
                "expiring_soon_count" => $expired->expired_count ?? 0,
                "expired_count" => $expired->expired_count ?? 0,
                "total_stock_value" => $stock_value->total_stock_value ?? 0,
                "expiring_soon_items" =>$expiring_soon->expiring_soon_items ?? 0,
                "todays_sales_count"    => $todays_sales->todays_sales_count ?? 0,
                "todays_sales_value"    => $todays_sales->todays_sales_value ?? 0,
                "todays_transfer_count" => $todays_transfers->todays_transfer_count ?? 0,
                "todays_transfer_value" => $todays_transfers->todays_transfer_value ?? 0,
            ];
        // } catch (Exception $e) {
        //     return (object) [
        //         "total_medicines" => 0,
        //         "total_batches" => 0,
        //         "low_stock_count" => 0,
        //         "expiring_soon_count" => 0,
        //         "expired_count" => 0,
        //         "total_stock_value" => 0,
        //         "expiring_soon_items" => 0,
        //     ];
        // }
    }
    // public function get_dashboard_summary()
    // {
    //     try {
    //         // Get total active medicines
    //         $total_medicines = $this->db->where("status", "active")
    //                                     ->count_all_results("medicines");

    //         // Get total active batches that have stock
    //         $total_batches = $this->db->where("batch_status", "ACTIVE")
    //                                   ->where("quantity_remaining >", 0)
    //                                   ->count_all_results("medicine_batches");

    //         // --- Added fields from your v_stock_dashboard_summary ---
    //         $total_centers = $this->db->where('status', 1)->count_all_results('hms_centers');
    //         $total_vendors = $this->db->where('status', 1)->count_all_results('hms_vendors');
            
    //         $total_stock_qty_result = $this->db->select_sum('quantity_remaining')
    //                                           ->where('batch_status','ACTIVE')
    //                                           ->get('medicine_batches')->row();
    //         $total_stock_quantity = $total_stock_qty_result ? $total_stock_qty_result->quantity_remaining : 0;
    //         // --- End added fields ---


    //         // Get low stock count (by MEDICINE, not batch)
    //         $low_stock_query = $this->db->query("
    //             SELECT COUNT(*) as low_stock_count
    //             FROM (
    //                 SELECT m.id
    //                 FROM medicines m
    //                 LEFT JOIN medicine_batches mb ON m.id = mb.medicine_id AND mb.batch_status = 'ACTIVE' AND mb.quantity_remaining > 0
    //                 WHERE m.status = 'active' AND m.min_stock_level > 0
    //                 GROUP BY m.id, m.min_stock_level
    //                 HAVING COALESCE(SUM(mb.quantity_remaining), 0) <= m.min_stock_level
    //             ) as low_stock_medicines
    //         ");
    //         $low_stock = $low_stock_query ? $low_stock_query->row() : null;


    //         // Get expiring soon count (within 30 days, NOT expired)
    //         $expiring_30_days = $this->db->select("COUNT(*) as expiring_soon_count") // Renamed variable for clarity
    //                                    ->from("medicine_batches")
    //                                    ->where("batch_status", "ACTIVE")
    //                                    ->where("quantity_remaining >", 0)
    //                                    ->where("DATEDIFF(expiry_date, CURDATE()) <=", 30) // 30 days or less
    //                                    ->where("DATEDIFF(expiry_date, CURDATE()) >=", 0) // Not yet expired
    //                                    ->get()->row();

    //         // Get expired count
    //         $expired = $this->db->select("COUNT(*) as expired_count")
    //                             ->from("medicine_batches")
    //                             ->where("batch_status", "ACTIVE")
    //                             ->where("quantity_remaining >", 0)
    //                             ->where("DATEDIFF(expiry_date, CURDATE()) <", 0) // Already expired
    //                             ->get()->row();

    //         // Get total stock value
    //         $this->db->select('SUM(quantity_remaining * selling_price) as total_stock_value', FALSE);
    //         $this->db->from("medicine_batches");
    //         $this->db->where("batch_status", "ACTIVE");
    //         $this->db->where("quantity_remaining >", 0);
    //         $stock_value = $this->db->get()->row();

    //         // Get expiring critically soon (within 7 days)
    //         $expiring_7_days = $this->db->select("COUNT(*) as expiring_soon_items") // Renamed variable for clarity
    //                                   ->from("medicine_batches")
    //                                   ->where("batch_status", "ACTIVE")
    //                                   ->where("quantity_remaining >", 0)
    //                                   ->where("DATEDIFF(expiry_date, CURDATE()) <=", 7) // 7 days or less
    //                                   ->where("DATEDIFF(expiry_date, CURDATE()) >=", 0) // Not yet expired
    //                                   ->get()->row();

    //         return (object) [
    //             "total_medicines"       => $total_medicines ?? 0,
    //             "total_batches"         => $total_batches ?? 0,
    //             "total_centers"         => $total_centers ?? 0,
    //             "total_vendors"         => $total_vendors ?? 0,
    //             "total_stock_quantity"  => $total_stock_quantity ?? 0,
    //             "low_stock_count"       => $low_stock->low_stock_count ?? 0,
    //             "expiring_soon_count"   => $expiring_30_days->expiring_soon_count ?? 0, // <-- CORRECTED
    //             "expired_count"         => $expired->expired_count ?? 0,
    //             "total_stock_value"     => $stock_value->total_stock_value ?? 0,
    //             "expiring_soon_items"   => $expiring_7_days->expiring_soon_items ?? 0, // Renamed variable
    //         ];
            
    //     } catch (Exception $e) {
    //         log_message('error', 'Error in get_dashboard_summary: ' . $e->getMessage());
    //         // Return an object with 0 values on failure
    //         return (object) [
    //             "total_medicines" => 0,
    //             "total_batches" => 0,
    //             "total_centers" => 0,
    //             "total_vendors" => 0,
    //             "total_stock_quantity" => 0,
    //             "low_stock_count" => 0,
    //             "expiring_soon_count" => 0,
    //             "expired_count" => 0,
    //             "total_stock_value" => 0,
    //             "expiring_soon_items" => 0,
    //         ];
    //     }
    // }

    public function get_low_stock_alerts($filters = [])
    {
        try {
            // Determine what stock to calculate based on filters
            $calculate_central = true;
            $calculate_center = true;
            
            if (!empty($filters['center_id'])) {
                // Only calculate center stock for the specific center
                $calculate_central = false;
            }
            
            if (!empty($filters['central_only'])) {
                // Only calculate central stock
                $calculate_center = false;
            }
            
            // Build the SELECT clause based on filters
            $stock_calculation = '';
            if ($calculate_central && $calculate_center) {
                // Calculate total stock from both central and centers
                $stock_calculation = 'COALESCE(SUM(COALESCE(cs.quantity, 0) + COALESCE(ccs.quantity, 0)), 0)';
            } elseif ($calculate_central) {
                // Only central stock
                $stock_calculation = 'COALESCE(SUM(COALESCE(cs.quantity, 0)), 0)';
            } else {
                // Only center stock (for specific center)
                $stock_calculation = 'COALESCE(SUM(COALESCE(ccs.quantity, 0)), 0)';
            }
            
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                m.min_stock_level,
                m.max_stock_level,
                m.reorder_level,
                ' . $stock_calculation . ' as current_stock,
                COALESCE(SUM(COALESCE(cs.quantity, 0)), 0) as central_stock,
                COALESCE(SUM(COALESCE(ccs.quantity, 0)), 0) as center_stock,
                CASE
                    WHEN ' . $stock_calculation . ' = 0 THEN "OUT_OF_STOCK"
                    WHEN ' . $stock_calculation . ' <= m.min_stock_level THEN "LOW_STOCK"
                    ELSE "NORMAL"
                END as stock_status,
                b.brand_name as brand_name,
                c.id as center_id,
                c.center_name,
                GROUP_CONCAT(DISTINCT c.center_name SEPARATOR ", ") as center_names
            ', FALSE);
            
            $this->db->from("medicines m");
            $this->db->join("medicine_batches mb", "m.id = mb.medicine_id", "left");
            $this->db->join("central_stocks cs", "mb.id = cs.batch_id AND (cs.status = \"ACTIVE\" OR cs.status IS NULL)", "left");
            $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id AND (ccs.status = \"ACTIVE\" OR ccs.status IS NULL)", "left");
            $this->db->join("hms_centers c", "ccs.center_id = c.id", "left");
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            
            $this->db->where("m.status", "active");
            $this->db->where("m.min_stock_level >", 0);
            $this->db->where("(mb.batch_status = \"ACTIVE\" OR mb.batch_status IS NULL)");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // Apply filters
            if (!empty($filters['center_id'])) {
                // Filter by specific center - only show stock for this center
                $this->db->where("ccs.center_id", $filters['center_id']);
            }
            
            if (!empty($filters['central_only'])) {
                // Show only central stock (exclude center stocks)
                $this->db->where("(ccs.quantity IS NULL OR ccs.quantity = 0)");
                $this->db->where("(cs.quantity IS NOT NULL AND cs.quantity > 0)");
            }
            
            if (!empty($filters['department'])) {
                // Filter by department - filter directly on center_stocks department field
                $this->db->where("ccs.department", $filters['department']);
            }
            
            $this->db->group_by("m.id, m.medicine_name, m.medicine_code, m.generic_name, m.min_stock_level, m.max_stock_level, m.reorder_level, b.brand_name");
            $this->db->having("current_stock <= m.min_stock_level");
            $this->db->order_by("(current_stock - m.min_stock_level)", "ASC");
            
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_low_stock_alerts: ' . $e->getMessage());
            return [];
        }
    }
    // public function get_low_stock_alerts()
    // {
    //     // try {
    //         $this->db->select('
    //             m.id as medicine_id,
    //             m.medicine_name,
    //             m.medicine_code,
    //             m.generic_name,
    //             m.min_stock_level,
    //             m.max_stock_level,
    //             m.reorder_level,
    //             b.brand_name as brand_name,
    //             -- This subquery calculates the total stock from all locations
    //             (
    //                 COALESCE((SELECT SUM(cs.quantity) 
    //                           FROM central_stocks cs 
    //                           JOIN medicine_batches mb_cs ON cs.batch_id = mb_cs.id
    //                           WHERE mb_cs.medicine_id = m.id AND mb_cs.batch_status = "ACTIVE"
    //                          ), 0) +
    //                 COALESCE((SELECT SUM(ccs.quantity) 
    //                           FROM center_stocks ccs 
    //                           JOIN medicine_batches mb_ccs ON ccs.batch_id = mb_ccs.id
    //                           WHERE mb_ccs.medicine_id = m.id AND mb_ccs.batch_status = "ACTIVE"
    //                          ), 0)
    //             ) as current_stock
    //         ');
    //         $this->db->from("medicines m");
    //         $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            
    //         $this->db->where("m.status", "active");
    //         // Only select medicines that are actually tracking stock levels
    //         $this->db->where("m.min_stock_level >", 0); 
            
    //         // We group by medicine
    //         $this->db->group_by("m.id, b.brand_name"); 
            
    //         // Use HAVING to filter based on the calculated 'current_stock'
    //         $this->db->having("current_stock <= m.min_stock_level"); 
            
    //         // Order by the most urgent (most negative stock difference)
    //         $this->db->order_by("(current_stock - m.min_stock_level)", "ASC"); 
            
    //         return $this->db->get()->result();

    //     // } catch (Exception $e) {
    //     //     log_message('error', 'Error in get_low_stock_alerts: ' . $e->getMessage());
    //     //     return [];
    //     // }
    // }


    // public function get_expiry_alerts()
    // {
    //     try {
    //         $this->db->select('
    //             mb.id as batch_id,
    //             m.medicine_name,
    //             m.medicine_code,
    //             m.generic_name,
    //             mb.batch_number,
    //             mb.expiry_date,
    //             mb.quantity_remaining as central_quantity,
    //             mb.quantity_remaining as center_quantity,
    //             "Central" as center_name,
    //             b.name as brand_name,
    //             DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry,
    //             CASE
    //                 WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
    //                 WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 7 THEN "CRITICAL"
    //                 WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "WARNING"
    //                 ELSE "OK"
    //             END as alert_level
    //         ');
    //         $this->db->from("medicine_batches mb");
    //         $this->db->join("medicines m", "mb.medicine_id = m.id");
    //         $this->db->join(
    //             $this->config->item("db_prefix") . "brands b",
    //             "m.brand_id = b.ID",
    //             "left",
    //         );
    //         $this->db->where("mb.batch_status", "ACTIVE");
    //         $this->db->where("mb.quantity_remaining >", 0);
    //         $this->db->where(
    //             "mb.expiry_date <=",
    //             date("Y-m-d", strtotime("+30 days")),
    //         );
    //         $this->db->order_by("mb.expiry_date", "ASC");
    //         return $this->db->get()->result();
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }
    public function get_expiry_alerts($filters = [])
    {
        try {
            $this->db->select('
                mb.id as batch_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                mb.batch_number,
                mb.expiry_date,
                
                cs.quantity as central_quantity,
                ccs.quantity as center_quantity,
                
                c.center_name,
                c.id as center_id,
                
                b.brand_name as brand_name,
                DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry,
                CASE
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 7 THEN "CRITICAL"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "WARNING"
                    ELSE "OK"
                END as alert_level
            ', FALSE); // FALSE to prevent CodeIgniter from escaping the query
            
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id", "inner");
            
            // Use LEFT JOINs to check both stock tables
            $this->db->join("central_stocks cs", "mb.id = cs.batch_id", "left");
            $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id", "left");
            $this->db->join("hms_centers c", "ccs.center_id = c.id", "left"); // Join to centers via center_stocks
            
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands b",
            //     "m.brand_id = b.ID",
            //     "left"
            // );
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            
            // --- Base conditions ---
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where(
                "mb.expiry_date <=",
                date("Y-m-d", strtotime("+30 days"))
            );
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // Check for quantity in EITHER table
            $this->db->where("( (ccs.quantity IS NOT NULL AND ccs.quantity > 0) OR (cs.quantity IS NOT NULL AND cs.quantity > 0) )", NULL, FALSE);

            // --- Apply filters ---
            if (!empty($filters['center_id'])) {
                // Find items specific to the selected center
                // OR items that are only in central stock (where ccs.center_id is NULL)
                $this->db->group_start();
                $this->db->where('ccs.center_id', $filters['center_id']);
                $this->db->or_where('ccs.center_id IS NULL');
                $this->db->group_end();
            }

            // Must use HAVING for 'alert_level' because it is a calculated alias
            if (!empty($filters['alert_level'])) {
                $this->db->having('alert_level', $filters['alert_level']);
            }
            
            $this->db->order_by("mb.expiry_date", "ASC");
            
            $query = $this->db->get();
            return $query->result();

        } catch (Exception $e) {
            log_message('error', 'Error in get_expiry_alerts: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Utility function to get all centers (used by controllers)
     */
    // public function get_all_centers()
    // {
    //     try {
    //         $this->db->select('id as ID, center_name'); // Use 'id' alias as 'ID' to match other queries
    //         $this->db->from('hms_centers');
    //         $this->db->where('status', 'ACTIVE');
    //         $this->db->order_by('center_name', 'ASC');
    //         $query = $this->db->get();
    //         return $query->result();
    //     } catch (Exception $e) {
    //         log_message('error', 'Error in get_all_centers: ' . $e->getMessage());
    //         return [];
    //     }
    // }

    public function get_recent_sales($limit = 10)
    {
        try {
            $this->db->select("s.*, c.center_name");
            $this->db->from("sales s");
            $this->db->join("hms_centers c", "s.center_id = c.ID", "left");
            $this->db->where("s.status", "CONFIRMED");
            $this->db->order_by("s.created_at", "DESC");
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_recent_transfers($limit = 10)
    {
        try {
            $this->db->select(
                "st.*, fc.center_name as from_center, tc.center_name as to_center",
            );
            $this->db->from("stock_transfers st");
            $this->db->join(
                "hms_centers fc",
                "st.from_center_id = fc.ID",
                "left",
            );
            $this->db->join(
                "hms_centers tc",
                "st.to_center_id = tc.ID",
                "left",
            );
            $this->db->where("st.status", "COMPLETED");
            $this->db->order_by("st.created_at", "DESC");
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get sales list with filters
     */
    public function get_sales($filters = [])
    {
        $this->db->select('
            s.*,
            c.center_name,
            e.name as salesperson_name,
            COALESCE(COUNT(si.id), 0) as total_items,
            COALESCE(SUM(si.quantity_sold), 0) as total_quantity,
            COALESCE(SUM(si.subtotal), 0) as subtotal,
            COALESCE(SUM(si.discount_amount), 0) as discount_amount,
            COALESCE(SUM(si.tax_amount), 0) as tax_amount,
            COALESCE(SUM(si.total), 0) as total_amount,
            GROUP_CONCAT(DISTINCT m.gst_rate ORDER BY m.gst_rate SEPARATOR ", ") as gst_rates
        ', FALSE);
        $this->db->from('sales s');
        $this->db->join('hms_centers c', 's.center_id = c.ID', 'left');
        $this->db->join('hms_employees e', 's.created_by = e.ID', 'left');
        $this->db->join('sale_items si', 's.id = si.sale_id', 'left');
        $this->db->join('medicine_batches mb', 'si.batch_id = mb.id', 'left');
        $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $this->db->where("
            EXISTS (
                SELECT 1 FROM stock_movements sm
                WHERE sm.reference_id = s.id
                AND sm.movement_type = 'SALE'
                AND sm.to_location_type = 'SALE'
            )
        ", null, false);
        

        // $this->db->join("stock_movements sm", "sm.reference_id = s.id AND sm.movement_type = 'SALE' AND sm.to_location_type = 'SALE'", "left");
        
        // Apply filters
        if (!empty($filters['center_id'])) {
            $this->db->where('s.center_id', $filters['center_id']);
        }
        
        if (!empty($filters['patient_id'])) {
            $this->db->like('s.patient_id', $filters['patient_id']);
        }
        
        if (!empty($filters['patient_name'])) {
            $this->db->like('s.patient_name', $filters['patient_name']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('s.status', $filters['status']);
        }
        
        if (!empty($filters['approval_status'])) {
            $this->db->where('s.accountant_approval_status', $filters['approval_status']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(s.sale_date) >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(s.sale_date) <=', $filters['date_to']);
        }

        // Filter sales where stock movements exist
        // $this->db->where('sm.id IS NOT NULL');
        $this->db->group_by('s.id');
        // $this->db->group_by('s.id, c.center_name, e.name, sm.id');
        $this->db->order_by('s.sale_date', 'DESC');
        $this->db->order_by('s.id', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_sales_analytics($days = 30)
    {
        $this->db->reset_query();
        $this->db->select('
            DATE(sale_date) as sale_date,
            COUNT(*) as total_sales,
            SUM(subtotal) as total_revenue,
            AVG(subtotal) as avg_sale_amount
        ');
        $this->db->from('sales');
        $this->db->where('sale_date >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->where('status', 'CONFIRMED');
        $this->db->group_by('DATE(sale_date)');
        $this->db->order_by('sale_date', 'DESC');

        return $this->db->get()->result();
    }


    public function get_transfer_analytics($days = 30)
    {
        try {
            $this->db->select('
                DATE(transfer_date) as transfer_date,
                COUNT(*) as total_transfers,
                SUM(total_value) as total_value,
                AVG(total_value) as avg_transfer_value
            ');
            $this->db->from("stock_transfers");
            $this->db->where(
                "transfer_date >=",
                date("Y-m-d", strtotime("-{$days} days")),
            );
            $this->db->where("status", "COMPLETED");
            $this->db->group_by("DATE(transfer_date)");
            $this->db->order_by("transfer_date", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_top_selling_medicines($limit = 10)
    {
        // try {
            $this->db->select(
                "m.medicine_name, mb.brand_name as brand_name, SUM(si.quantity_sold) as total_sold, SUM(si.total) as total_revenue",
            );
            $this->db->from("sale_items si");
            $this->db->join("medicine_batches mb2", "si.batch_id = mb2.id");
            $this->db->join("medicines m", "mb2.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands mb",
            //     "m.brand_id = mb.ID",
            // );
            $this->db->join('medicine_brands mb', 'm.brand_id = mb.id');
            $this->db->join("sales s", "si.sale_id = s.id");
            $this->db->where("s.status", "CONFIRMED");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->group_by("m.id, m.medicine_name, mb.brand_name");
            $this->db->order_by("total_sold", "DESC");
            $this->db->limit($limit);
            return $this->db->get()->result();
        // } catch (Exception $e) {
        //     return [];
        // }
    }

    // public function get_center_stock_summary()
    // {
    //     // try {
    //         $this->db->select(
    //             "c.center_name, COUNT(DISTINCT ccs.batch_id) as total_batches, SUM(ccs.quantity) as total_quantity",
    //         );
    //         $this->db->from("centers c");
    //         $this->db->join(
    //             "center_stocks ccs",
    //             "c.id = ccs.center_id",
    //             "left",
    //         );
    //         $this->db->where("c.status", "active");
    //         $this->db->where("ccs.status", "ACTIVE");
    //         $this->db->group_by("c.id, c.center_name");
    //         return $this->db->get()->result();
    //     // } catch (Exception $e) {
    //     //     return [];
    //     // }
    // }
    public function get_center_stock_summary()
    {
        // try {
            $this->db->select(
                "c.center_name, 
                COUNT(DISTINCT ccs.batch_id) as total_batches, 
                COALESCE(SUM(ccs.quantity), 0) as total_quantity"
            );
            $this->db->from("hms_centers c"); 
            $join_condition = "c.ID = ccs.center_id AND ccs.status = 'ACTIVE'";
            $this->db->join(
                "center_stocks ccs",
                $join_condition,
                "left" // This is the LEFT JOIN
            );
            $this->db->where("c.status", 1); 
            $this->db->group_by("c.ID, c.center_name");
            $this->db->order_by("c.center_name", "ASC");
            return $this->db->get()->result();
        // } catch (Exception $e) {
        //     log_message('error', 'Error in get_center_stock_summary: ' . $e->getMessage());
        //     return [];
        // }
    }

    // ===============================================
    // MEDICINE BRANDS FUNCTIONS
    // ===============================================

    public function get_medicine_brands()
    {
        $this->db->order_by("ID", "ASC");
        return $this->db
            ->get('medicine_brands')
            ->result();

        // return $this->db
        //     ->get($this->config->item("db_prefix") . "brands")
        //     ->result();

    }

    public function add_medicine_brand($data)
    {
        
        
        // return $this->db->insert(
        //     $this->config->item("db_prefix") . "brands",
        //     $data,
        // );       
        return $this->db->insert(
            'medicine_brands',
            $data,
        );
    }

    public function update_medicine_brand($id, $data)
    {
        $this->db->where("ID", $id);
        return $this->db->update(
            'medicine_brands',
            $data,
        );

        // return $this->db->update(
        //     $this->config->item("db_prefix") . "brands",
        //     $data,
        // );
    }

    public function get_medicine_brand_by_id($id)
    {
        $this->db->where("ID", $id);
        return $this->db
            ->get('medicine_brands')
            ->row();
        // return $this->db
        //     ->get($this->config->item("db_prefix") . "brands")
        //     ->row();
    }

    // ===============================================
    // VENDORS FUNCTIONS
    // ===============================================

    public function get_vendors()
    {
        $this->db->order_by("ID", "ASC");
        return $this->db
            ->get($this->config->item("db_prefix") . "vendors")
            ->result();
    }
    
    public function get_vendor_by_number($vendor_number) {
        $table = $this->config->item('db_prefix') . 'vendors';
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('vendor_number', $vendor_number);
        $this->db->where('status', 1);
        return $this->db->get()->row();
    }


    public function add_vendor($data)
    {
        // Generate vendor number if not provided
        if (!isset($data["vendor_number"]) || empty($data["vendor_number"])) {
            $data["vendor_number"] = $this->generate_vendor_number();
        }

        // Set created date
        $data["created_at"] = date("Y-m-d H:i:s");
        $data["updated_at"] = date("Y-m-d H:i:s");

        return $this->db->insert(
            $this->config->item("db_prefix") . "vendors",
            $data,
        );
    }

    public function update_vendor($id, $data)
    {
        // Set updated date
        $data["updated_at"] = date("Y-m-d H:i:s");

        $this->db->where("ID", $id);
        return $this->db->update(
            $this->config->item("db_prefix") . "vendors",
            $data,
        );
    }

    public function generate_vendor_number()
    {
        // Get the last vendor number
        $this->db->select("vendor_number");
        $this->db->from($this->config->item("db_prefix") . "vendors");
        $this->db->order_by("ID", "DESC");
        $this->db->limit(1);
        $result = $this->db->get()->row();

        if ($result && !empty($result->vendor_number)) {
            // Extract number from existing vendor_number
            $last_number = intval(
                preg_replace("/[^0-9]/", "", $result->vendor_number),
            );
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return "VEND" . str_pad($new_number, 4, "0", STR_PAD_LEFT);
    }

    public function handle_vendor_file_upload(
        $file_input_name,
        $vendor_id = null,
    ) {
        if (
            !isset($_FILES[$file_input_name]) ||
            $_FILES[$file_input_name]["error"] !== UPLOAD_ERR_OK
        ) {
            return null;
        }

        $file = $_FILES[$file_input_name];
        $allowed_types = ["pdf", "jpg", "jpeg", "png"];
        $file_extension = strtolower(
            pathinfo($file["name"], PATHINFO_EXTENSION),
        );

        if (!in_array($file_extension, $allowed_types)) {
            return false; // Invalid file type
        }

        // Create upload directory if it doesn't exist
        $upload_dir = "uploads/vendors/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $filename =
            $file_input_name .
            "_" .
            ($vendor_id ? $vendor_id : "new") .
            "_" .
            time() .
            "." .
            $file_extension;
        $file_path = $upload_dir . $filename;

        if (move_uploaded_file($file["tmp_name"], $file_path)) {
            return $filename;
        }

        return false;
    }

    public function get_vendor_by_id($id)
    {
        $this->db->where("ID", $id);
        return $this->db
            ->get($this->config->item("db_prefix") . "vendors")
            ->row();
    }

    // ===============================================
    // MEDICINES FUNCTIONS
    // ===============================================

    public function get_all_medicines($medicine_name = null, $generic_name = null, $brand_id = null, $category = null, $selected_medicine_id = null)
    {
        $this->db->reset_query();
        $this->db->select("m.*, mb.brand_name as brand_name");
        $this->db->from("medicines m");
        $this->db->join("medicine_brands mb", "m.brand_id = mb.id", "left");

        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb",
        //     "m.brand_id = mb.ID"
        // );
        $this->db->where("m.status", "active");
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        if (!empty($selected_medicine_id)) {
            $this->db->where("m.ID", $selected_medicine_id);
        }

        // --- APPLY FILTERS ---
        if (!empty($medicine_name)) {
            $this->db->like("m.medicine_name", $medicine_name);
        }
        if (!empty($generic_name)) {
            $this->db->like("m.generic_name", $generic_name);
        }
        if (!empty($brand_id)) {
            $this->db->where("m.brand_id", $brand_id);
        }
        if (!empty($category)) {
            $this->db->like("m.category", $category);
        }
        // --- END FILTERS ---

        $this->db->order_by("m.medicine_name", "ASC");

        return $this->db->get()->result();
    }
    
     public function get_all_medicines_package($medicine_name = null, $generic_name = null, $brand_id = null, $category = null, $selected_medicine_id = null)
    {
        $this->db->reset_query();
        $this->db->select("
            m.*,
            mb.brand_name as brand_name,
            COALESCE(latest_batch.selling_price, 0) as selling_price,
            COALESCE(latest_batch.mrp, 0) as mrp,
            COALESCE(latest_batch.purchase_price, 0) as purchase_price
        ");
        $this->db->from("medicines m");
        $this->db->join("medicine_brands mb", "m.brand_id = mb.id", "left");

        // Join with latest batch to get pricing information
        $this->db->join("(
            SELECT
                mb_inner.medicine_id,
                mb_inner.selling_price,
                mb_inner.mrp,
                mb_inner.purchase_price
            FROM medicine_batches mb_inner
            INNER JOIN (
                SELECT medicine_id, MAX(created_at) as latest_date
                FROM medicine_batches
                GROUP BY medicine_id
            ) latest ON mb_inner.medicine_id = latest.medicine_id
                     AND mb_inner.created_at = latest.latest_date
        ) latest_batch", "m.id = latest_batch.medicine_id", "left");

        $this->db->where("m.status", "active");
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        if (!empty($selected_medicine_id)) {
            $this->db->where("m.ID", $selected_medicine_id);
        }

        // --- APPLY FILTERS ---
        if (!empty($medicine_name)) {
            $this->db->like("m.medicine_name", $medicine_name);
        }
        if (!empty($generic_name)) {
            $this->db->like("m.generic_name", $generic_name);
        }
        if (!empty($brand_id)) {
            $this->db->where("m.brand_id", $brand_id);
        }
        if (!empty($category)) {
            $this->db->like("m.category", $category);
        }
        // --- END FILTERS ---

        $this->db->order_by("m.medicine_name", "ASC");

        $result = $this->db->get()->result();

        // Ensure all objects have pricing properties
        foreach ($result as $medicine) {
            if (!isset($medicine->selling_price) || $medicine->selling_price === null) {
                $medicine->selling_price = 0;
            }
            if (!isset($medicine->mrp) || $medicine->mrp === null) {
                $medicine->mrp = 0;
            }
            if (!isset($medicine->purchase_price) || $medicine->purchase_price === null) {
                $medicine->purchase_price = 0;
            }
        }

        return $result;
    }

    public function search_medicines($search_term = "")
    {
        $this->db->select("m.*, mb.brand_name as brand_name");
        $this->db->from("medicines m");
        $this->db->join(
            "medicine_brands mb",
            "m.brand_id = mb.ID",
        );

        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb",
        //     "m.brand_id = mb.ID",
        // );
        $this->db->where("m.status", "active");
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        if (!empty($search_term)) {
            $this->db->group_start();
            $this->db->like("m.medicine_name", $search_term);
            $this->db->or_like("m.generic_name", $search_term);
            $this->db->or_like("m.medicine_code", $search_term);
            $this->db->or_like("mb.brand_name", $search_term);
            $this->db->group_end();
        }

        $this->db->order_by("m.medicine_name", "ASC");
        $this->db->limit(50); // Limit results for performance
        return $this->db->get()->result();
    }

    public function add_medicine($data)
    {
        return $this->db->insert("medicines", $data);
    }
    
    public function get_medicine_by_name_and_brand($medicine_name, $brand_name) {
        $this->db->select('m.*');
        $this->db->from('medicines m');
        $this->db->join('medicine_brands mb', 'm.brand_id = mb.id');
        $this->db->where('m.medicine_name', $medicine_name);
        $this->db->where('mb.brand_name', $brand_name);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $this->db->where('m.status', 'ACTIVE');
        return $this->db->get()->row();
    }

    public function update_medicine($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update("medicines", $data);
    }

    public function get_medicine_by_id_data($id)
    {
        $this->db->select("m.*, mb.brand_name as brand_name");
        $this->db->from("medicines m");
        $this->db->join(
            "medicine_brands mb",
            "m.brand_id = mb.ID",
        );
        $this->db->where("m.id", $id);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        return $this->db->get()->row();
    }
  /*  public function get_medicine_by_id($medicine_id, $center_id = null, $po_department = null,$po_center)
    {
        if ($po_center === 'CENTRAL_WAREHOUSE_NOIDA') {
                $this->db->select('COALESCE(SUM(cs.available_quantity), 0) AS current_stock');
                $this->db->from('central_stocks AS cs');
                $this->db->join(
                        'medicine_batches AS mb',
                        'cs.batch_id = mb.id
                        AND mb.medicine_id = ' . (int)$medicine_id,
                        'LEFT'   
                );
                $this->db->where('cs.status', 'ACTIVE');
                $this->db->where_in('mb.batch_status', ['ACTIVE', 'EXPIRED']);
                $stock_result = $this->db->get()->row();
                return $stock_result;
        } */
        /*if($po_department == 'Embryologist Basant Lok'){
            $po_department = 'Embryology Basant Lok';
        }*/
      /*  $this->db->select("mcs.*, med.unit,med.pack_size");
        $this->db->from("medicine_center_stocks mcs");
        $this->db->join("medicines med", "med.id = mcs.medicine_id", "left");
        $this->db->where("mcs.medicine_id", $medicine_id);
        $this->db->where("med.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("med.medicine_code NOT LIKE 'ST_%'");
        /*if (!empty($center_id)) {
            $this->db->where("mcs.center_id", $center_id);
        }
        
        if ($po_department) {
            $this->db->like('mcs.department', $po_department);
        }*/ /*
        return $this->db->get()->row();
    } */
    
public function get_medicine_by_id($medicine_id, $center_id = null, $po_department = null, $po_center = null)
{

    if ($po_center === 'CENTRAL_WAREHOUSE_NOIDA') {

        /*$this->db->select('
            COALESCE(SUM(cs.available_quantity),0) AS current_stock,
            cs.min_stock_level,
            cs.max_stock_level
        ');*/

       /* $this->db->from('central_stocks AS cs');

        $this->db->join(
            'medicine_batches AS mb',
            'cs.batch_id = mb.id AND mb.medicine_id = '.(int)$medicine_id,
            'LEFT'
        );

        $this->db->where('cs.status', 'ACTIVE');
        $this->db->where_in('mb.batch_status', ['ACTIVE','EXPIRED']); */

        $this->db->select('
            COALESCE(SUM(cs.available_quantity),0) AS current_stock,
            COALESCE(SUM(cs.min_stock_level),0) AS min_stock_level,
            COALESCE(SUM(cs.max_stock_level),0) AS max_stock_level
        ');

        $this->db->from('central_stocks AS cs');

        $this->db->join('medicine_batches AS mb', 'cs.batch_id = mb.id', 'LEFT');

        $this->db->where('mb.medicine_id', $medicine_id);
        $this->db->where('cs.status', 'ACTIVE');
        $this->db->where_in('mb.batch_status', ['ACTIVE','EXPIRED']);

        // ✅ PRINT QUERY
      //  echo $this->db->get_compiled_select();
      //  die();

        return $this->db->get()->row();
    }

    $this->db->select("mcs.*, med.unit, med.pack_size");

    $this->db->from("medicine_center_stocks mcs");

    $this->db->join("medicines med", "med.id = mcs.medicine_id", "left");

    $this->db->where("mcs.medicine_id", $medicine_id);

    $this->db->where("mcs.department", $po_department);

        // ✅ ADD THIS
    if (!empty($center_id)) {
        $this->db->where("mcs.center_id", $center_id);
    }

    $this->db->where("med.medicine_code NOT LIKE 'HK_%'");
    $this->db->where("med.medicine_code NOT LIKE 'ST_%'");

    // ✅ PRINT QUERY
    //echo $this->db->get_compiled_select();
    //die();

    return $this->db->get()->row();
}

    // ===============================================
    // BATCHES FUNCTIONS
    // ===============================================

    public function get_all_batches(
        $medicine_id = null,
        $vendor_id = null,
        $batch_number = null,
        $batch_status = null,
    ) {
        // Try to get batches with a simple query first
        try {
            $this->db->select(
                'mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, v.name as vendor_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days, COALESCE(mb.quality_status, "PENDING") as quality_status',
            );
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands mb2",
            //     "m.brand_id = mb2.ID",
            // );
            $this->db->join('medicine_brands mb2', 'm.brand_id = mb2.id');
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );

            if ($batch_status && $batch_status != "") {
                $this->db->where("mb.batch_status", $batch_status);
            } else {
                $this->db->where("mb.batch_status", "ACTIVE");
            }

            if ($medicine_id && $medicine_id != "") {
                $this->db->where("mb.medicine_id", $medicine_id);
            }

            if ($vendor_id && $vendor_id != "") {
                $this->db->where("mb.vendor_id", $vendor_id);
            }

            if ($batch_number && $batch_number != "") {
                $this->db->like("mb.batch_number", $batch_number);
            }

            $this->db->order_by("mb.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If table doesn't exist or has issues, return empty array
            return [];
        }
    }

    public function add_batch($data)
    {
        $this->db->trans_start();
        if (isset($data["expiry_date"])) {
            $data["expiry_days"] = $this->calculate_expiry_days(
                $data["expiry_date"],
            );
        }
        // Extract center_id and department if present (don't insert into medicine_batches)
        $center_id = isset($data["center_id"]) ? $data["center_id"] : null;
        $department = isset($data["department"]) ? $data["department"] : 'GENERAL';
        unset($data["center_id"]);
        unset($data["department"]);
        
        // Insert batch
        $this->db->insert("medicine_batches", $data);
        $batch_id = $this->db->insert_id();
        
        // Determine if adding to central or center stock
        if (!empty($center_id)) {
            // Add to center stock
            $center_stock_data = [
                "batch_id" => $batch_id,
                "center_id" => $center_id,
                "department" => $department,
                "quantity" => $data["quantity_purchased"],
                "last_movement_date" => date("Y-m-d H:i:s"),
                "status" => "ACTIVE"
            ];
            $this->db->insert("center_stocks", $center_stock_data);
            
            // Log stock movement for center
            $movement_data = [
                "batch_id" => $batch_id,
                "movement_type" => "PURCHASE",
                "from_location_type" => "VENDOR",
                "from_location_id" => $data["vendor_id"],
                "to_location_type" => "CENTER",
                "to_location_id" => $center_id,
                "quantity_change" => $data["quantity_purchased"],
                "quantity_after" => $data["quantity_purchased"],
                "unit_price" => $data["selling_price"],
                "total_value" =>
                    $data["quantity_purchased"] * $data["selling_price"],
                "reference_type" => "PURCHASE_RECEIPT",
                "reference_id" => $batch_id,
                "reference_number" => $data["invoice_number"],
                "created_by" => $data["created_by"],
            ];
        } else {
            // Add to central stock
            $central_stock_data = [
                "batch_id" => $batch_id,
                "quantity" => $data["quantity_purchased"],
                "last_movement_date" => date("Y-m-d H:i:s"),
            ];
            $this->db->insert("central_stocks", $central_stock_data);
            
            // Log stock movement for central
            $movement_data = [
                "batch_id" => $batch_id,
                "movement_type" => "PURCHASE",
                "from_location_type" => "VENDOR",
                "from_location_id" => $data["vendor_id"],
                "to_location_type" => "CENTRAL",
                "quantity_change" => $data["quantity_purchased"],
                "quantity_after" => $data["quantity_purchased"],
                "unit_price" => $data["selling_price"],
                "total_value" =>
                    $data["quantity_purchased"] * $data["selling_price"],
                "reference_type" => "PURCHASE_RECEIPT",
                "reference_id" => $batch_id,
                "reference_number" => $data["invoice_number"],
                "created_by" => $data["created_by"],
            ];
        }
        
        $this->db->insert("stock_movements", $movement_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    /**
     * Update batch quantity when duplicate batch is found during import
     * @param int $batch_id The existing batch ID
     * @param float $additional_quantity The quantity to add
     * @param int|null $center_id Center ID if updating center stock, null for central stock
     * @param string $department Department name (for center stock)
     * @param array $batch_data Additional batch data (vendor_id, selling_price, etc.)
     * @return bool Success status
     */
    public function update_batch_quantity($batch_id, $additional_quantity, $center_id = null, $department = 'GENERAL', $batch_data = []) {
        $this->db->trans_start();
        
        // Update medicine_batches quantity
        $this->db->set("quantity_purchased", "quantity_purchased + " . floatval($additional_quantity), false);
        $this->db->set("quantity_remaining", "quantity_remaining + " . floatval($additional_quantity), false);
        $this->db->where("id", $batch_id);
        $this->db->update("medicine_batches");
        
        // Get updated batch for logging
        $batch = $this->db->get_where("medicine_batches", ["id" => $batch_id])->row();
        
        if (!empty($center_id)) {
            // Update or insert center stock
            $this->db->where("batch_id", $batch_id);
            $this->db->where("center_id", $center_id);
            $this->db->where("department", $department);
            $existing_center_stock = $this->db->get("center_stocks")->row();
            
            if ($existing_center_stock) {
                // Update existing center stock
                $this->db->set("quantity", "quantity + " . floatval($additional_quantity), false);
                $this->db->set("last_movement_date", date("Y-m-d H:i:s"));
                $this->db->where("id", $existing_center_stock->id);
                $this->db->update("center_stocks");
                $new_quantity = $existing_center_stock->quantity + $additional_quantity;
            } else {
                // Insert new center stock entry
                $center_stock_data = [
                    "batch_id" => $batch_id,
                    "center_id" => $center_id,
                    "department" => $department,
                    "quantity" => $additional_quantity,
                    "last_movement_date" => date("Y-m-d H:i:s"),
                    "status" => "ACTIVE"
                ];
                $this->db->insert("center_stocks", $center_stock_data);
                $new_quantity = $additional_quantity;
            }
            
            // Log stock movement for center
            $movement_data = [
                "batch_id" => $batch_id,
                "movement_type" => "PURCHASE",
                "from_location_type" => "VENDOR",
                "from_location_id" => isset($batch_data["vendor_id"]) ? $batch_data["vendor_id"] : $batch->vendor_id,
                "to_location_type" => "CENTER",
                "to_location_id" => $center_id,
                "quantity_change" => $additional_quantity,
                "quantity_after" => $new_quantity,
                "unit_price" => isset($batch_data["selling_price"]) ? $batch_data["selling_price"] : $batch->selling_price,
                "total_value" => $additional_quantity * (isset($batch_data["selling_price"]) ? $batch_data["selling_price"] : $batch->selling_price),
                "reference_type" => "PURCHASE_RECEIPT",
                "reference_id" => $batch_id,
                "reference_number" => isset($batch_data["invoice_number"]) ? $batch_data["invoice_number"] : $batch->invoice_number,
                "created_by" => isset($batch_data["created_by"]) ? $batch_data["created_by"] : $batch->created_by,
            ];
        } else {
            // Update or insert central stock
            $this->db->where("batch_id", $batch_id);
            $existing_central_stock = $this->db->get("central_stocks")->row();
            
            if ($existing_central_stock) {
                // Update existing central stock
                $this->db->set("quantity", "quantity + " . floatval($additional_quantity), false);
                $this->db->set("last_movement_date", date("Y-m-d H:i:s"));
                $this->db->where("id", $existing_central_stock->id);
                $this->db->update("central_stocks");
                $new_quantity = $existing_central_stock->quantity + $additional_quantity;
            } else {
                // Insert new central stock entry
                $central_stock_data = [
                    "batch_id" => $batch_id,
                    "quantity" => $additional_quantity,
                    "last_movement_date" => date("Y-m-d H:i:s"),
                ];
                $this->db->insert("central_stocks", $central_stock_data);
                $new_quantity = $additional_quantity;
            }
            
            // Log stock movement for central
            $movement_data = [
                "batch_id" => $batch_id,
                "movement_type" => "PURCHASE",
                "from_location_type" => "VENDOR",
                "from_location_id" => isset($batch_data["vendor_id"]) ? $batch_data["vendor_id"] : $batch->vendor_id,
                "to_location_type" => "CENTRAL",
                "quantity_change" => $additional_quantity,
                "quantity_after" => $new_quantity,
                "unit_price" => isset($batch_data["selling_price"]) ? $batch_data["selling_price"] : $batch->selling_price,
                "total_value" => $additional_quantity * (isset($batch_data["selling_price"]) ? $batch_data["selling_price"] : $batch->selling_price),
                "reference_type" => "PURCHASE_RECEIPT",
                "reference_id" => $batch_id,
                "reference_number" => isset($batch_data["invoice_number"]) ? $batch_data["invoice_number"] : $batch->invoice_number,
                "created_by" => isset($batch_data["created_by"]) ? $batch_data["created_by"] : $batch->created_by,
            ];
        }
        
        $this->db->insert("stock_movements", $movement_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    /**
     * Add batch without adding to central stock (for direct center transfers)
     */
    public function add_batch_only($data) {
        $this->db->trans_start();
        
        // Calculate expiry days
        if (isset($data['expiry_date'])) {
            $data['expiry_days'] = $this->calculate_expiry_days($data['expiry_date']);
        }
        
        // Insert batch only
        $this->db->insert('medicine_batches', $data);
        $batch_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        return $this->db->trans_status() ? $batch_id : false;
    }

    public function get_batch_by_id($id)
    {
        $this->db->select(
            'mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, v.name as vendor_name, COALESCE(mb.quality_status, "PENDING") as quality_status, mb.quantity_remaining as current_stock',
        );
        $this->db->from("medicine_batches mb");
        $this->db->join("medicines m", "mb.medicine_id = m.id");
        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb2",
        //     "m.brand_id = mb2.ID",
        // );
        $this->db->join('medicine_brands mb2', 'm.brand_id = mb2.id');
        $this->db->join(
            $this->config->item("db_prefix") . "vendors v",
            "mb.vendor_id = v.ID",
        );
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $this->db->where("mb.id", $id);
        return $this->db->get()->row();
    }

    // ===============================================
    // CENTRAL STOCKS FUNCTIONS
    // ===============================================

    public function get_central_stocks(
        $medicine_id = null,
        $batch_number = null,
        $status = null,
    ) {
        // try {
            $this->db->select(
                "cs.*, mb.batch_number,m.pack_size, mb.expiry_date, mb.purchase_price, mb.selling_price, m.medicine_name, m.medicine_code, b.brand_name as brand_name, v.name as vendor_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days",
            );
            $this->db->from("central_stocks cs");
            $this->db->join("medicine_batches mb", "cs.batch_id = mb.id");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            if ($medicine_id && $medicine_id != "") {
                $this->db->where("mb.medicine_id", $medicine_id);
            }

            if ($batch_number && $batch_number != "") {
                $this->db->like("mb.batch_number", $batch_number);
            }

            if ($status && $status != "") {
                $this->db->where("cs.status", $status);
            }

            $this->db->order_by("mb.expiry_date", "ASC");
            return $this->db->get()->result();
        // } catch (Exception $e) {
        //     return [];
        // }
    }

    public function update_central_stock_status($stock_id, $status)
    {
        $this->db->where("id", $stock_id);
        return $this->db->update("central_stocks", ["status" => $status]);
    }

    // ===============================================
    // CENTER STOCKS FUNCTIONS
    // ===============================================

   public function get_center_id($data)
    {
        $data ="Select c.ID from hms_centers as c where c.center_number='".$data."'";
        $center = $this->db->query($data)->row();
        return $center ? $center->ID : null;
        // $this->db->select("c.id");
        // $this->db->from("hms_centers as c");
        // $this->db->where('c.center_number', $data);
        // $center = $this->db->get()->row();
        // return $center ? $center->id : null;
    }
    public function get_center_stock_quantity_for_po(
        $center_id = null,
        $medicine_id = null,
        $status = null,
        $department = null
    ) {
        $this->db->select('IFNULL(SUM(ccs.quantity), 0) AS total_quantity');
        $this->db->from('center_stocks ccs');
        $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id');

        if ($center_id) {
            $this->db->where('ccs.center_id', $center_id);
        }

        if ($medicine_id) {
            $this->db->where('mb.medicine_id', $medicine_id);
        }

        if ($status) {
            $this->db->where('ccs.status', $status);
        }

        if ($department) {
            $this->db->like('ccs.department', $department);
        }

        // role-based center restriction
        $center = null;
        if (!empty($_SESSION['logged_billing_manager']) &&
            ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
            $center = $_SESSION['logged_billing_manager']['center'];
        }

        if (!empty($_SESSION['logged_stock_manager']) &&
            ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
            $center = $_SESSION['logged_stock_manager']['center'];
        }

        if ($center !== null) {
            $this->db->where('ccs.center_id', $this->get_center_id($center));
        }

        $row = $this->db->get()->row();

      //  echo $this->db->last_query();
//die();
        return (int) ($row->total_quantity ?? 0);
    }

    public function get_center_stocks($center_id = null,$medicine_id = null,$batch_number = null,$status = null,$department = null) 
    {
        // try {
            // Select columns - ensure pack_size is explicitly included from medicines table
            // Using IFNULL (MySQL) to handle NULL pack_size values (defaults to 1)
            // This ensures pack_size is always returned, even if medicine doesn't have it set
            $this->db->select(
                "ccs.*, mb.batch_number, mb.medicine_id, IFNULL(m.pack_size, 1) as pack_size, mb.expiry_date, mb.purchase_price, mb.selling_price, mb.mrp, m.medicine_name, m.medicine_code, b.brand_name as brand_name, v.name as vendor_name, c.center_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days",
            );
            $this->db->from("center_stocks ccs");
            $this->db->join("medicine_batches mb", "ccs.batch_id = mb.id");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "ccs.center_id = c.ID");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            if ($center_id && $center_id != "") {
                $this->db->where("ccs.center_id", $center_id);
            }

            if ($medicine_id && $medicine_id != "") {
                $this->db->where("mb.medicine_id", $medicine_id);
            }

            if ($batch_number && $batch_number != "") {
                $this->db->like("mb.batch_number", $batch_number);
            }

            if ($status && $status != "") {
                $this->db->where("ccs.status", $status);
            }

            // if ($department && $department != "") {
            //     $this->db->where("ccs.department", $department);
            // }
            // if ((isset($_SESSION['logged_billing_manager']) && $_SESSION['logged_billing_manager']['role'] == 'billing_manager') || (isset($_SESSION['logged_stock_manager']) && $_SESSION['logged_stock_manager']['role'] == 'stock_manager')){
            // // if (isset($_SESSION['logged_billing_manager']) && $_SESSION['logged_billing_manager']['role'] == 'billing_manager') {
            //     $this->db->where('ccs.center_id', $this->get_center_id($_SESSION['logged_billing_manager']['center']));
            // }
            $center = null;
            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
                $department = $_SESSION['logged_billing_manager']['department'] ?? null;
            }
            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
                $department = $_SESSION['logged_stock_manager']['department'] ?? null;
            }
            if ($center !== null) {
                $this->db->where('ccs.center_id', $this->get_center_id($center));
            }
            // Filter by department if available
            if ($department !== null && $department !== '') {
                if ($department == 'billing') {
                    $this->db->like('ccs.department', 'CASH MEDICINE');
                }elseif($department == 'Embryologist Basant Lok')
                {
                    $this->db->like('ccs.department', 'Embryology Basant Lok');
                }
                 else {
                    $this->db->like('ccs.department', $department);
                }
            }
            $this->db->order_by("mb.expiry_date", "ASC");
            $results = $this->db->get()->result();
            
            // Post-process results to ensure pack_size is always set (fallback safety)
            foreach ($results as $result) {
                // Ensure pack_size is always set and valid
                if (!isset($result->pack_size) || $result->pack_size === null || $result->pack_size === '' || $result->pack_size == 0) {
                    $result->pack_size = 1;
                } else {
                    // Ensure pack_size is numeric
                    $result->pack_size = floatval($result->pack_size);
                    if ($result->pack_size <= 0) {
                        $result->pack_size = 1;
                    }
                }
            }
            
            return $results;
        // } catch (Exception $e) {
        //     return [];
        // }
    }
    

    public function update_center_stock_status($stock_id, $status)
    {
        $this->db->where("id", $stock_id);
        return $this->db->update("center_stocks", ["status" => $status]);
    }

    public function delete_center_stock($stock_id)
    {
        try {
            $this->db->trans_start();
            
            // Get stock details with batch and center information
            $this->db->select("ccs.*, mb.batch_number, mb.medicine_id, mb.selling_price, mb.purchase_price, c.center_name");
            $this->db->from("center_stocks ccs");
            $this->db->join("medicine_batches mb", "ccs.batch_id = mb.id");
            $this->db->join("hms_centers c", "ccs.center_id = c.ID");
            $this->db->where("ccs.id", $stock_id);
            $stock = $this->db->get()->row();
            
            if (!$stock) {
                $this->db->trans_rollback();
                return false;
            }

            // Get employee ID from session for audit trail
            $created_by = $this->get_employee_id_from_session();

            // Log stock movement before deletion (for audit trail)
            $movement_data = [
                "batch_id" => $stock->batch_id,
                "movement_type" => "DISPOSAL",
                "from_location_type" => "CENTER",
                "from_location_id" => $stock->center_id,
                "to_location_type" => "DISPOSAL",
                "to_location_id" => null,
                "quantity_change" => -$stock->quantity,
                "quantity_after" => 0,
                "unit_price" => $stock->selling_price ?? $stock->purchase_price ?? 0,
                "total_value" => ($stock->selling_price ?? $stock->purchase_price ?? 0) * $stock->quantity,
                "reference_type" => "STOCK_DELETION",
                "reference_id" => $stock_id,
                "reference_number" => "DEL-" . $stock_id,
                "created_by" => $created_by,
                "created_at" => date("Y-m-d H:i:s")
            ];
            $this->db->insert("stock_movements", $movement_data);

            // Delete the center stock record
            $this->db->where("id", $stock_id);
            $result = $this->db->delete("center_stocks");
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === false) {
                return false;
            }
            
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log("Error deleting center stock: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get employee ID from session
     * Helper method to get employee ID from various session types
     */
    private function get_employee_id_from_session()
    {
        $employee_number = null;
        
        // Check different session types
        if (!empty($_SESSION['logged_stock_manager']['employee_number'])) {
            $employee_number = $_SESSION['logged_stock_manager']['employee_number'];
        } elseif (!empty($_SESSION['logged_billing_manager']['employee_number'])) {
            $employee_number = $_SESSION['logged_billing_manager']['employee_number'];
        } elseif (!empty($_SESSION['logged_central_stock_manager']['employee_number'])) {
            $employee_number = $_SESSION['logged_central_stock_manager']['employee_number'];
        } elseif (!empty($_SESSION['logged_administrator']['employee_number'])) {
            $employee_number = $_SESSION['logged_administrator']['employee_number'];
        }
        
        if (empty($employee_number)) {
            return null;
        }
        
        // Get employee ID from employee number
        $this->db->select("ID");
        $this->db->from($this->config->item("db_prefix") . "employees");
        $this->db->where("employee_number", $employee_number);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->ID;
        }
        
        return null;
    }

    // ===============================================
    // BATCH STATUS MANAGEMENT FUNCTIONS
    // ===============================================

    public function update_batch_status($batch_id, $status)
    {
        $this->db->where("id", $batch_id);
        return $this->db->update("medicine_batches", [
            "batch_status" => $status,
        ]);
    }

    public function get_batch_status_options()
    {
        return [
            "ACTIVE" => "Active",
            "INACTIVE" => "Inactive",
            "EXPIRED" => "Expired",
            "DAMAGED" => "Damaged",
            "QUARANTINE" => "Quarantine",
            "RETURNED" => "Returned",
            "DISPOSED" => "Disposed",
        ];
    }

    // ===============================================
    // STOCK LEVELS FUNCTIONS
    // ===============================================

    public function get_current_stock_levels(
        $center_id = null,
        $medicine_name = null,
        $stock_status = null,
    ) {
        try {
            $this->db->select("*");
            $this->db->from("v_current_stock_levels");

            if ($center_id && $center_id != "") {
                $this->db->where("center_id", $center_id);
            }

            if ($medicine_name && $medicine_name != "") {
                $this->db->like("medicine_name", $medicine_name);
            }

            if ($stock_status && $stock_status != "") {
                $this->db->where("expiry_status", $stock_status);
            }
            // if (isset($_SESSION['logged_billing_manager']) && $_SESSION['logged_billing_manager']['role'] == 'billing_manager') {
            //     $this->db->where('center_id', $this->get_center_id($_SESSION['logged_billing_manager']['center']));
            // }
            $center = null;
            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
            }
            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
            }
            if ($center !== null) {
                $this->db->where('center_id', $this->get_center_id($center));
            }

            $this->db->order_by("medicine_name", "ASC");
            $result = $this->db->get()->result();
            // if (empty($result)) {
            //     return $this->get_stock_levels_from_tables(
            //         $center_id,
            //         $medicine_name,
            //         $stock_status,
            //     );
            // }

            return $result;
        } catch (Exception $e) {
            return $this->get_stock_levels_from_tables(
                $center_id,
                $medicine_name,
                $stock_status,
            );
        }
    }

    public function get_stock_levels_from_tables(
        $center_id = null,
        $medicine_name = null,
        $stock_status = null,
    ) {
        try {
            // Get aggregated stock data by medicine and batch
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                SUM(COALESCE(cs.quantity, 0)) as central_quantity,
                SUM(COALESCE(ccs.quantity, 0)) as center_quantity,
                SUM(COALESCE(cs.quantity, 0) + COALESCE(ccs.quantity, 0)) as total_quantity,
                GROUP_CONCAT(DISTINCT COALESCE(c.center_name, "Central") SEPARATOR ", ") as center_names,
                GROUP_CONCAT(DISTINCT ccs.center_id SEPARATOR ",") as center_ids,
                ROW_NUMBER() OVER (ORDER BY mb.expiry_date ASC, mb.created_at ASC) as fifo_rank,
                CASE
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                    ELSE "FRESH"
                END as expiry_status,
                mb.id as batch_id,
                COALESCE(b.brand_name, "Unknown") as brand_name,
                mb.purchase_price,
                mb.selling_price,
                mb.mrp,
                mb.quantity_remaining
            ');
            $this->db->from("medicines m");
            $this->db->join("medicine_batches mb", "m.id = mb.medicine_id");
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands b",
            //     "m.brand_id = b.ID",
            //     "left",
            // );
            $this->db->join("central_stocks cs", "mb.id = cs.batch_id", "left");
            $this->db->join(
                "center_stocks ccs",
                "mb.id = ccs.batch_id",
                "left",
            );
            $this->db->join(
                $this->config->item("db_prefix") . "centers c",
                "ccs.center_id = c.ID",
                "left",
            );
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where(
                "(COALESCE(cs.quantity, 0) > 0 OR COALESCE(ccs.quantity, 0) > 0)",
            );
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

            if ($center_id && $center_id != "") {
                $this->db->where(
                    "(ccs.center_id = " .
                        $center_id .
                        " OR ccs.center_id IS NULL)",
                );
            }

            if ($medicine_name && $medicine_name != "") {
                $this->db->like("m.medicine_name", $medicine_name);
            }

            if ($stock_status && $stock_status != "") {
                if ($stock_status == "EXPIRED") {
                    $this->db->where(
                        "DATEDIFF(mb.expiry_date, CURDATE()) <",
                        0,
                    );
                } elseif ($stock_status == "EXPIRING_SOON") {
                    $this->db->where(
                        "DATEDIFF(mb.expiry_date, CURDATE()) <=",
                        30,
                    );
                    $this->db->where(
                        "DATEDIFF(mb.expiry_date, CURDATE()) >=",
                        0,
                    );
                } elseif ($stock_status == "FRESH") {
                    $this->db->where(
                        "DATEDIFF(mb.expiry_date, CURDATE()) >",
                        30,
                    );
                }
            }

            $this->db->group_by("m.id, mb.id");
            $this->db->order_by("mb.expiry_date", "ASC");
            $this->db->order_by("m.medicine_name", "ASC");

            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return [];
        }
    }

    public function get_medicine_stock_summary()
    {
        // try {
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                b.brand_name as brand_name,
                COUNT(mb.id) as total_batches,
                SUM(mb.quantity_remaining) as total_quantity,
                AVG(mb.selling_price) as avg_price,
                SUM(mb.quantity_remaining * mb.selling_price) as total_value,
                MIN(mb.expiry_date) as earliest_expiry,
                MAX(mb.expiry_date) as latest_expiry,
                COUNT(CASE WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN 1 END) as expiring_soon_count,
                COUNT(CASE WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN 1 END) as expired_count
            ');
            $this->db->from("medicines m");
            $this->db->join("medicine_batches mb", "m.id = mb.medicine_id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands b",
            //     "m.brand_id = b.ID",
            //     "left",
            // );
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("mb.quantity_remaining >", 0);
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->group_by(
                "m.id, m.medicine_name, m.medicine_code, m.generic_name, b.brand_name",
            );
            $this->db->order_by("m.medicine_name", "ASC");
            return $this->db->get()->result();
        // } catch (Exception $e) {
        //     return [];
        // }
    }

    public function get_available_stock($batch_id, $center_id)
    {
        $this->db->select(
            "cs.quantity as central_stock, ccs.quantity as center_stock",
        );
        $this->db->from("central_stocks cs");
        $this->db->join(
            "center_stocks ccs",
            "cs.batch_id = ccs.batch_id AND ccs.center_id = " . $center_id,
            "left",
        );
        $this->db->where("cs.batch_id", $batch_id);
        $this->db->where("cs.status", "ACTIVE");
        $this->db->where("(ccs.status = 'ACTIVE' OR ccs.status IS NULL)", null, false);
        return $this->db->get()->row();
    }

    /**
     * Get current stock level and max stock level for a medicine
     * @param int $medicine_id
     * @return object|null Returns object with current_stock and max_stock_level
     */
    public function get_medicine_stock_info($medicine_id, $center_id, $department)
    {
        $department = strtoupper(trim($department));

        // 1️⃣ Stock configuration
        $this->db->select('min_stock_level, max_stock_level, reorder_level');
        $this->db->from('medicine_center_stocks');
        $this->db->where([
            'medicine_id' => $medicine_id,
            'center_id'   => $center_id,
            'department'  => $department
        ]);
        $mcs_config = $this->db->get()->row();

        // 2️⃣ Actual stock calculation
        $this->db->select('COALESCE(SUM(ccs.available_quantity), 0) AS current_stock');
        $this->db->from('center_stocks AS ccs');

        $this->db->join(
            'medicine_batches AS mb',
            'ccs.batch_id = mb.id
            AND mb.medicine_id = ' . (int)$medicine_id,
            'LEFT'   // 🔥 IMPORTANT
        );

        $this->db->where('ccs.center_id', $center_id);
        $this->db->where('UPPER(ccs.department)', $department);
        $this->db->where('ccs.status', 'ACTIVE');
        $this->db->where_in('mb.batch_status', ['ACTIVE', 'EXPIRED']); // optional safety

        $stock_result = $this->db->get()->row();

        // 3️⃣ Final object
        $result = new stdClass();
        $result->current_stock = (int) ($stock_result->current_stock ?? 0);
        $result->min_stock_level = (int) ($mcs_config->min_stock_level ?? 0);
        $result->max_stock_level = (int) ($mcs_config->max_stock_level ?? 0);
        $result->reorder_level   = (int) ($mcs_config->reorder_level ?? 0);
        return $result;
    }



    /**
     * Get medicine center stock configuration
     * @param int $center_id
     * @param int $medicine_id
     * @param string $department
     * @return object|null
     */
    public function get_medicine_center_stock_config($center_id, $medicine_id, $department = null) {
        $this->db->select('*');
        $this->db->from('medicine_center_stocks');
        $this->db->where('center_id', $center_id);
        $this->db->where('medicine_id', $medicine_id);
        if (!empty($department)) {
            $this->db->where('department', $department);
        }
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Insert or update medicine center stock configuration
     * @param array $data
     * @return bool
     */
    public function save_medicine_center_stock_config($data) {
        try {
            // Check if record exists
            $existing = $this->get_medicine_center_stock_config(
                $data['center_id'],
                $data['medicine_id'],
                isset($data['department']) ? $data['department'] : null
            );

            if ($existing) {
                // Update existing record
                $this->db->where('id', $existing->id);
                return $this->db->update('medicine_center_stocks', $data);
            } else {
                // Insert new record
                return $this->db->insert('medicine_center_stocks', $data);
            }
        } catch (Exception $e) {
            log_message('error', 'Error saving medicine center stock config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update medicine center stock levels
     * @param int $center_id
     * @param int $medicine_id
     * @param string $department
     * @param array $levels (min_stock_level, max_stock_level, reorder_level)
     * @return bool
     */
    public function update_medicine_center_stock_levels($center_id, $medicine_id, $department, $levels) {
        try {
            $this->db->where('center_id', $center_id);
            $this->db->where('medicine_id', $medicine_id);
            $this->db->where('department', $department);
            return $this->db->update('medicine_center_stocks', $levels);
        } catch (Exception $e) {
            log_message('error', 'Error updating medicine center stock levels: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get medicine center stock levels for management interface
     * @param int|null $center_id
     * @param int|null $medicine_id
     * @param string|null $department
     * @return array
     */
    public function get_medicine_center_stock_levels($center_id = null, $medicine_id = null, $department = null) {
        $this->db->select('
            mcs.id,
            mcs.center_id,
            mcs.medicine_id,
            mcs.department,
            mcs.min_stock_level,
            mcs.max_stock_level,
            mcs.reorder_level,
            mcs.created_at,
            mcs.updated_at,
            m.medicine_name,
            m.medicine_code,
            c.center_name,
            COALESCE(SUM(COALESCE(ccs.quantity, 0)), 0) as current_stock
        ');
        $this->db->from('medicine_center_stocks mcs');
        $this->db->join('medicines m', 'mcs.medicine_id = m.id', 'left');
        $this->db->join('hms_centers c', 'mcs.center_id = c.ID', 'left');
        $this->db->join('medicine_batches mb', 'mb.medicine_id = mcs.medicine_id', 'left');
        $this->db->join('center_stocks ccs', 'ccs.batch_id = mb.id AND ccs.center_id = mcs.center_id AND ccs.department = mcs.department AND (ccs.status = "ACTIVE" OR ccs.status IS NULL)', 'left');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        // Apply filters
        if (!empty($center_id)) {
            $this->db->where('mcs.center_id', $center_id);
        }
        if (!empty($medicine_id)) {
            $this->db->where('mcs.medicine_id', $medicine_id);
        }
        if (!empty($department)) {
            $this->db->where('mcs.department', $department);
        }

        $this->db->group_by('mcs.id, mcs.center_id, mcs.medicine_id, mcs.department, mcs.min_stock_level, mcs.max_stock_level, mcs.reorder_level, mcs.created_at, mcs.updated_at, m.medicine_name, m.medicine_code, c.center_name');
        $this->db->order_by('c.center_name, m.medicine_name, mcs.department');

        return $this->db->get()->result();
    }

    /**
     * Delete medicine center stock configuration
     * @param int $config_id
     * @return bool
     */
    public function delete_medicine_center_stock_config($config_id) {
        try {
            $this->db->where('id', $config_id);
            return $this->db->delete('medicine_center_stocks');
        } catch (Exception $e) {
            log_message('error', 'Error deleting medicine center stock config: ' . $e->getMessage());
            return false;
        }
    }

    // ===============================================
    // STOCK TRANSFERS FUNCTIONS
    // ===============================================
    public function get_all_transfers($transfer_type = null, $from_center_id = null, $to_center_id = null, $status = null)
    {
        try {
            $this->db->select('
                st.*,
                fc.center_name as from_center,
                tc.center_name as to_center,
                COUNT(sti.id) as total_items,
                COALESCE(SUM(sti.quantity_transferred), 0) as total_quantity,
                COALESCE(SUM(sti.total_price), 0) as total_value
            ');
            $this->db->from("stock_transfers st");
            $this->db->join(
                "hms_centers fc",
                "st.from_center_id = fc.ID",
                "left",
            );
            $this->db->join(
                "hms_centers tc",
                "st.to_center_id = tc.ID",
                "left",
            );
            $this->db->join(
                "stock_transfer_items sti",
                "st.id = sti.transfer_id",
                "left",
            );
            if ($transfer_type && $transfer_type != "") {
                $this->db->where("st.transfer_type", $transfer_type);
            }
            if ($from_center_id && $from_center_id != "") {
                $this->db->where("st.from_center_id", $from_center_id);
            }
            if ($to_center_id && $to_center_id != "") {
                $this->db->where("st.to_center_id", $to_center_id);
            }
            if ($status && $status != "") {
                $this->db->where("st.status", $status);
            }
            $this->db->group_by("st.id");
            $this->db->order_by("st.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return [];
        }
    }

    public function add_transfer($data)
    {
        // Generate transfer number
        $data["transfer_number"] =
            "TRF" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);

        if ($this->db->insert("stock_transfers", $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function get_transfer_by_id($id)
    {
        $this->db->select('
            st.*,
            fc.center_name as from_center,
            tc.center_name as to_center,
            COUNT(sti.id) as total_items,
            COALESCE(SUM(sti.quantity_transferred), 0) as total_quantity,
            COALESCE(SUM(sti.total_price), 0) as total_value
        ');
        $this->db->from("stock_transfers st");
        $this->db->join("hms_centers fc", "st.from_center_id = fc.ID", "left");
        $this->db->join("hms_centers tc", "st.to_center_id = tc.ID", "left");
        $this->db->join(
            "stock_transfer_items sti",
            "st.id = sti.transfer_id",
            "left",
        );
        $this->db->where("st.id", $id);
        $this->db->group_by("st.id");
        return $this->db->get()->row();
    }

    public function get_transfer_items($transfer_id)
    {
        $this->db->select(
            "sti.*, m.medicine_name, m.medicine_code, mb.brand_name as brand_name, mb2.batch_number, mb2.expiry_date",
        );
        $this->db->from("stock_transfer_items sti");
        $this->db->join("medicine_batches mb2", "sti.batch_id = mb2.id");
        $this->db->join("medicines m", "mb2.medicine_id = m.id");
        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb",
        //     "m.brand_id = mb.ID",
        // );
        $this->db->join("medicine_brands mb",
            "m.brand_id = mb.id",
        );
        $this->db->where("sti.transfer_id", $transfer_id);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        return $this->db->get()->result();
    }

    public function add_transfer_item($data)
    {
        if ($this->db->insert("stock_transfer_items", $data)) {
            $item_id = $this->db->insert_id();

            // Update transfer totals
            $this->update_transfer_totals($data["transfer_id"]);

            return $item_id;
        }
        return false;
    }

    public function remove_transfer_item($item_id)
    {
        // Get transfer_id before deleting
        $this->db->select("transfer_id");
        $this->db->from("stock_transfer_items");
        $this->db->where("id", $item_id);
        $item = $this->db->get()->row();

        if ($item) {
            // Delete the item
            $this->db->where("id", $item_id);
            $result = $this->db->delete("stock_transfer_items");

            if ($result) {
                // Update transfer totals
                $this->update_transfer_totals($item->transfer_id);
                return true;
            }
        }

        return false;
    }

    public function update_transfer_totals($transfer_id)
    {
        // Get totals from transfer items
        $this->db->select('
            COUNT(id) as total_items,
            SUM(quantity_transferred) as total_quantity,
            SUM(total_price) as total_value
        ');
        $this->db->from("stock_transfer_items");
        $this->db->where("transfer_id", $transfer_id);
        $totals = $this->db->get()->row();

        // Update transfer record with calculated totals
        $this->db->where("id", $transfer_id);
        $this->db->update("stock_transfers", [
            "total_items" => $totals->total_items ?: 0,
            "total_quantity" => $totals->total_quantity ?: 0,
            "total_value" => $totals->total_value ?: 0,
        ]);

        return true;
    }

    public function approve_transfer($id, $approved_by)
    {
        $this->db->trans_start();

        // Check if transfer has items before approval
        $items = $this->get_transfer_items($id);
        if (empty($items)) {
            $this->db->trans_rollback();
            return false; // Cannot approve transfer without items
        }

        // Update transfer status
        $this->db->where("id", $id);
        $this->db->update("stock_transfers", [
            "status" => "APPROVED",
            "approved_by" => $approved_by,
        ]);

        // Get transfer items
        $transfer = $this->get_transfer_by_id($id);
        foreach ($items as $item) {
            // Reduce from source location
            if ($transfer->transfer_type == "CENTRAL_TO_CENTER") {
                $this->db->where("batch_id", $item->batch_id);
                $this->db->set(
                    "quantity",
                    "quantity - " . $item->quantity_transferred,
                    false,
                );
                $this->db->update("central_stocks");
            } else {
                $this->db->where("batch_id", $item->batch_id);
                $this->db->where("center_id", $transfer->from_center_id);
                if (!empty($transfer->from_department)) {
                    $this->db->where("department", $transfer->from_department);
                }
                $this->db->set(
                    "quantity",
                    "quantity - " . $item->quantity_transferred,
                    false,
                );
                $this->db->update("center_stocks");
            }

            // Add to destination location
            if ($transfer->transfer_type == "CENTER_TO_CENTRAL") {
                // Destination is central warehouse
                $this->db->where("batch_id", $item->batch_id);
                $existing = $this->db->get("central_stocks")->row();

                if ($existing) {
                    $this->db->where("batch_id", $item->batch_id);
                    $this->db->set(
                        "quantity",
                        "quantity + " . $item->quantity_transferred,
                        false,
                    );
                    $this->db->update("central_stocks");
                } else {
                    $this->db->insert("central_stocks", [
                        "batch_id" => $item->batch_id,
                        "quantity" => $item->quantity_transferred,
                        "last_movement_date" => date("Y-m-d H:i:s"),
                    ]);
                }
            } else {
                // Destination is a center
                $this->db->where("batch_id", $item->batch_id);
                $this->db->where("center_id", $transfer->to_center_id);
                if (!empty($transfer->to_department)) {
                    $this->db->where("department", $transfer->to_department);
                }
                $existing = $this->db->get("center_stocks")->row();

                if ($existing) {
                    $this->db->where("batch_id", $item->batch_id);
                    $this->db->where("center_id", $transfer->to_center_id);
                    if (!empty($transfer->to_department)) {
                        $this->db->where("department", $transfer->to_department);
                    }
                    $this->db->set(
                        "quantity",
                        "quantity + " . $item->quantity_transferred,
                        false,
                    );
                    $this->db->update("center_stocks");
                } else {
                    $this->db->insert("center_stocks", [
                        "batch_id" => $item->batch_id,
                        "center_id" => $transfer->to_center_id,
                        "department" => $transfer->to_department,
                        "quantity" => $item->quantity_transferred,
                        "last_movement_date" => date("Y-m-d H:i:s"),
                    ]);
                }
            }

            // Log stock movement
            $movement_data = [
                "batch_id" => $item->batch_id,
                "movement_type" => "TRANSFER_OUT",
                "from_location_type" =>
                    $transfer->transfer_type == "CENTRAL_TO_CENTER"
                        ? "CENTRAL"
                        : "CENTER",
                "from_location_id" => $transfer->from_center_id,
                "to_location_type" =>
                    $transfer->transfer_type == "CENTER_TO_CENTRAL"
                        ? "CENTRAL"
                        : "CENTER",
                "to_location_id" => $transfer->to_center_id,
                "quantity_change" => -$item->quantity_transferred,
                "unit_price" => $item->unit_price,
                "total_value" => $item->total_price,
                "reference_type" => "STOCK_TRANSFER",
                "reference_id" => $id,
                "reference_number" => $transfer->transfer_number,
                "created_by" => $approved_by,
            ];
            $this->db->insert("stock_movements", $movement_data);
        }

        // Update transfer status to COMPLETED after successful stock movement
        $this->db->where("id", $id);
        $update_data = ["status" => "COMPLETED"];

        // Check if approved_date column exists before trying to update it
        $columns = $this->db->list_fields("stock_transfers");
        if (in_array("approved_date", $columns)) {
            $update_data["approved_date"] = date("Y-m-d H:i:s");
        }

        $this->db->update("stock_transfers", $update_data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function bulk_approve_transfers($transfer_ids, $approved_by)
    {
        $success_count = 0;
        $failed_count = 0;
        $results = [];

        foreach ($transfer_ids as $transfer_id) {
            $result = $this->approve_transfer($transfer_id, $approved_by);
            if ($result) {
                $success_count++;
                $results[] = ["id" => $transfer_id, "status" => "success"];
            } else {
                $failed_count++;
                $results[] = ["id" => $transfer_id, "status" => "failed"];
            }
        }

        return [
            "success_count" => $success_count,
            "failed_count" => $failed_count,
            "results" => $results,
        ];
    }

    public function get_available_batches_for_transfer(
        $center_id,
        $department = null,
        $employee_number = null,
    ) {
        if ($center_id) {
            // Center to center transfer
            $this->db->select(
                "mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, ccs.quantity as available_quantity",
            );
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands mb2",
            //     "m.brand_id = mb2.ID",
            // );
            $this->db->join("medicine_brands mb2",
                "m.brand_id = mb2.id",
            );
            $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id");
            $this->db->where("ccs.center_id", $center_id);
            $this->db->where("ccs.quantity >", 0);
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("m.status", "active");
            $this->db->where("ccs.status", "ACTIVE");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

            if ($department) {
                $this->db->where("mb.department", $department);
            }

            if ($employee_number) {
                $this->db->where("mb.employee_number", $employee_number);
            }
        } else {
            // Central to center transfer
            $this->db->select(
                "mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, cs.quantity as available_quantity",
            );
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands mb2",
            //     "m.brand_id = mb2.ID",
            // );
            $this->db->join("medicine_brands mb2",
                "m.brand_id = mb2.id",
            );
            $this->db->join("central_stocks cs", "mb.id = cs.batch_id");
            $this->db->where("cs.quantity >", 0);
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("m.status", "active");
            $this->db->where("cs.status", "ACTIVE");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        }

        // Order by FEFO (First Expiry First Out)
        $this->db->order_by("mb.expiry_date", "ASC");
        $this->db->order_by("m.medicine_name", "ASC");
        return $this->db->get()->result();
    }

    // ===============================================
    // MULTI-ITEM TRANSFER FUNCTIONS
    // ===============================================

    public function process_multi_transfer($data)
    {
        $this->db->trans_start();

        try {
            // Generate transfer number
            $transfer_number =
                "MTR" .
                date("Ymd") .
                str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);

            // 1. Create main transfer record
            $transfer_data = [
                "transfer_number" => $transfer_number,
                "transfer_type" => "CENTER_TO_CENTER",
                "from_center_id" => $data["from_center_id"],
                "to_center_id" => $data["to_center_id"],
                "transfer_date" => $data["transfer_date"],
                "remarks" => $data["remarks"],
                "created_by" => $data["transferred_by"],
                "status" => "COMPLETED",
                "total_value" => 0,
            ];

            $this->db->insert("stock_transfers", $transfer_data);
            $transfer_id = $this->db->insert_id();

            $total_value = 0;

            // 2. Process each transfer item
            foreach ($data["transfer_items"] as $item) {
                if (
                    empty($item["batch_id"]) ||
                    empty($item["quantity"]) ||
                    $item["quantity"] <= 0
                ) {
                    continue;
                }

                // Get batch details
                $batch = $this->get_batch_by_id($item["batch_id"]);
                if (!$batch) {
                    throw new Exception(
                        "Batch not found: " . $item["batch_id"],
                    );
                }

                // Check if sufficient quantity is available at source location (center + department + employee)
                $source_stock = $this->db
                    ->select("available_quantity")
                    ->from("medicine_batches")
                    ->where("id", $item["batch_id"])
                    ->where("center_id", $data["from_center_id"])
                    ->where("department", $data["from_department"])
                    ->where("employee_number", $data["from_employee_number"])
                    ->where("batch_status", "ACTIVE")
                    ->get()
                    ->row();

                if (
                    !$source_stock ||
                    $source_stock->available_quantity < $item["quantity"]
                ) {
                    throw new Exception(
                        "Insufficient quantity for batch: " .
                            $batch->batch_number .
                            " at source location (Center: " .
                            $data["from_center_id"] .
                            ", Dept: " .
                            $data["from_department"] .
                            ", Emp: " .
                            $data["from_employee_number"] .
                            ")",
                    );
                }

                // Calculate item value
                $item_value = $item["quantity"] * $batch->selling_price;
                $total_value += $item_value;

                // Record transfer item
                $item_data = [
                    "transfer_id" => $transfer_id,
                    "batch_id" => $item["batch_id"],
                    "quantity_transferred" => $item["quantity"],
                    "unit_price" => $batch->selling_price,
                    "total_price" => $item_value,
                    "remarks" => isset($item["remarks"])
                        ? $item["remarks"]
                        : "",
                ];

                $this->db->insert("stock_transfer_items", $item_data);

                // Reduce from source center stock
                $this->db->where("batch_id", $item["batch_id"]);
                $this->db->where("center_id", $data["from_center_id"]);
                $this->db->set(
                    "quantity",
                    "quantity - " . $item["quantity"],
                    false,
                );
                $this->db->update("center_stocks");

                // Add to destination center stock
                $this->db->where("batch_id", $item["batch_id"]);
                $this->db->where("center_id", $data["to_center_id"]);
                $existing_dest = $this->db->get("center_stocks")->row();

                if ($existing_dest) {
                    $this->db->where("batch_id", $item["batch_id"]);
                    $this->db->where("center_id", $data["to_center_id"]);
                    $this->db->set(
                        "quantity",
                        "quantity + " . $item["quantity"],
                        false,
                    );
                    $this->db->update("center_stocks");
                } else {
                    $this->db->insert("center_stocks", [
                        "batch_id" => $item["batch_id"],
                        "center_id" => $data["to_center_id"],
                        "quantity" => $item["quantity"],
                        "status" => "ACTIVE",
                        "last_movement_date" => date("Y-m-d H:i:s"),
                    ]);
                }

                // Log stock movement
                $movement_data = [
                    "batch_id" => $item["batch_id"],
                    "movement_type" => "TRANSFER_OUT",
                    "from_location_type" => "CENTER",
                    "from_location_id" => $data["from_center_id"],
                    "to_location_type" => "CENTER",
                    "to_location_id" => $data["to_center_id"],
                    "quantity_change" => -$item["quantity"],
                    "unit_price" => $batch->selling_price,
                    "total_value" => $item_value,
                    "reference_type" => "STOCK_TRANSFER",
                    "reference_id" => $transfer_id,
                    "reference_number" => $transfer_number,
                    "created_by" => $data["transferred_by"],
                ];
                $this->db->insert("stock_movements", $movement_data);
            }

            // Update transfer total value
            $this->db->where("id", $transfer_id);
            $this->db->update("stock_transfers", [
                "total_value" => $total_value,
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception("Transaction failed");
            }

            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log("Multi-transfer error: " . $e->getMessage());
            return false;
        }
    }

    public function get_employees_by_location($center_id, $department)
    {
        $result = [];
        $sql_condition = "";

        // First try to get employees for specific center and department
        $sql =
            "Select employee_number, name from " .
            $this->config->item("db_prefix") .
            "employees where center_id='" .
            $center_id .
            "' and department='" .
            $department .
            "' and status='1' ORDER BY name ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();

        // If no employees found for this center and department, get all employees for this center
        if (empty($result)) {
            $sql =
                "Select employee_number, name from " .
                $this->config->item("db_prefix") .
                "employees where center_id='" .
                $center_id .
                "' and status='1' ORDER BY name ASC";
            $q = $this->db->query($sql);
            $result = $q->result_array();
        }

        // If still no employees found, get all active employees
        if (empty($result)) {
            $sql =
                "Select employee_number, name from " .
                $this->config->item("db_prefix") .
                "employees where status='1' ORDER BY name ASC";
            $q = $this->db->query($sql);
            $result = $q->result_array();
        }

        return $result;
    }

    public function get_departments_by_center($center_id)
    {
        $result = [];
        $sql_condition = "";

        // Get all unique departments regardless of center
        $sql =
            "Select DISTINCT department from " .
            $this->config->item("db_prefix") .
            "employees where status='1' and department != '' ORDER BY department ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();

        return $result;
    }

    public function get_stocks_by_location(
        $center_id,
        $department,
        $employee_number,
    ) {
        $this->db->select(
            "mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, v.vendor_name",
        );
        $this->db->from("medicine_batches mb");
        $this->db->join("medicines m", "mb.medicine_id = m.id");
        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb2",
        //     "m.brand_id = mb2.ID",
        // );
        $this->db->join("medicine_brands mb2",
            "m.brand_id = mb2.id",
        );
        $this->db->join("vendors v", "mb.vendor_id = v.id", "left");
        $this->db->where("mb.center_id", $center_id);
        $this->db->where("mb.department", $department);
        $this->db->where("mb.employee_number", $employee_number);
        $this->db->where("mb.batch_status", "ACTIVE");
        $this->db->where("mb.available_quantity >", 0);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $this->db->order_by("m.medicine_name", "ASC");
        return $this->db->get()->result();
    }

    public function add_or_update_destination_batch(
        $source_batch,
        $transfer_data,
        $quantity,
    ) {
        // Check if batch already exists at destination
        $existing_batch = $this->get_batch_by_location(
            $source_batch->id,
            $transfer_data["to_center_id"],
            $transfer_data["to_department"],
            $transfer_data["to_employee_number"],
        );

        if ($existing_batch) {
            // Update existing batch
            $this->db->where("id", $existing_batch->id);
            $this->db->set(
                "available_quantity",
                "available_quantity + " . $quantity,
                false,
            );
            $this->db->set(
                "total_quantity",
                "total_quantity + " . $quantity,
                false,
            );
            $this->db->update("medicine_batches");
        } else {
            // Create new batch at destination
            $new_batch_data = [
                "medicine_id" => $source_batch->medicine_id,
                "batch_number" => $source_batch->batch_number,
                "vendor_id" => $source_batch->vendor_id,
                "purchase_price" => $source_batch->purchase_price,
                "selling_price" => $source_batch->selling_price,
                "available_quantity" => $quantity,
                "total_quantity" => $quantity,
                "expiry_date" => $source_batch->expiry_date,
                "center_id" => $transfer_data["to_center_id"],
                "department" => $transfer_data["to_department"],
                "employee_number" => $transfer_data["to_employee_number"],
                "batch_status" => "ACTIVE",
                "created_at" => date("Y-m-d H:i:s"),
            ];

            $this->db->insert("medicine_batches", $new_batch_data);
        }
    }

    public function get_batch_by_location(
        $original_batch_id,
        $center_id,
        $department,
        $employee_number,
    ) {
        $this->db->select("mb.*");
        $this->db->from("medicine_batches mb");
        $this->db->join(
            "medicine_batches mb2",
            "mb.medicine_id = mb2.medicine_id AND mb.batch_number = mb2.batch_number",
        );
        $this->db->where("mb2.id", $original_batch_id);
        $this->db->where("mb.center_id", $center_id);
        $this->db->where("mb.department", $department);
        $this->db->where("mb.employee_number", $employee_number);
        $this->db->where("mb.batch_status", "ACTIVE");
        return $this->db->get()->row();
    }

    public function update_center_stock(
        $medicine_id,
        $center_id,
        $department,
        $quantity,
        $operation,
    ) {
        // Check if record exists
        $this->db->where("medicine_id", $medicine_id);
        $this->db->where("center_id", $center_id);
        $this->db->where("department", $department);
        $existing = $this->db->get("center_stocks")->row();

        if ($existing) {
            // Update existing record
            if ($operation == "ADD") {
                $this->db->set("quantity", "quantity + " . $quantity, false);
            } else {
                $this->db->set("quantity", "quantity - " . $quantity, false);
            }
            $this->db->where("id", $existing->id);
            $this->db->update("center_stocks");
        } elseif ($operation == "ADD") {
            // Create new record
            $stock_data = [
                "medicine_id" => $medicine_id,
                "center_id" => $center_id,
                "department" => $department,
                "quantity" => $quantity,
                "updated_at" => date("Y-m-d H:i:s"),
            ];
            $this->db->insert("center_stocks", $stock_data);
        }
    }

    public function get_central_batches()
    {
        $this->db->select(
            "mb.*, m.medicine_name, m.medicine_code, mb2.brand_name as brand_name, v.vendor_name, cs.quantity as available_quantity, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days",
        );
        $this->db->from("medicine_batches mb");
        $this->db->join("central_stocks cs", "mb.id = cs.batch_id");
        $this->db->join("medicines m", "mb.medicine_id = m.id");
        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb2",
        //     "m.brand_id = mb2.ID",
        // );
        $this->db->join("medicine_brands mb2",
            "m.brand_id = mb2.id",
        );
        $this->db->join("vendors v", "mb.vendor_id = v.id", "left");
        $this->db->where("mb.batch_status", "ACTIVE");
        $this->db->where("cs.quantity >", 0);
        $this->db->where("cs.status", "ACTIVE");
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        // Order by FEFO (First Expiry First Out)
        $this->db->order_by("mb.expiry_date", "ASC");
        $this->db->order_by("m.medicine_name", "ASC");

        return $this->db->get()->result();
    }

    public function process_department_transfer($data)
    {
        $this->db->trans_start();

        try {
            // Generate transfer number
            $transfer_number =
                "DTR" .
                date("Ymd") .
                str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);

            // 1. Create main transfer record
            $transfer_data = [
                "transfer_number" => $transfer_number,
                "transfer_type" => "CENTER_TO_CENTER",
                "from_center_id" => $data["from_center_id"],
                "to_center_id" => $data["to_center_id"],
                "transfer_date" => $data["transfer_date"],
                "remarks" => $data["remarks"],
                "created_by" => $data["transferred_by"],
                "status" => "COMPLETED",
                "total_value" => 0,
            ];

            $this->db->insert("stock_transfers", $transfer_data);
            $transfer_id = $this->db->insert_id();

            $total_value = 0;

            // 2. Process each transfer item
            foreach ($data["transfer_items"] as $item) {
                if (
                    empty($item["batch_id"]) ||
                    empty($item["quantity"]) ||
                    $item["quantity"] <= 0
                ) {
                    continue;
                }

                // Get batch details
                $batch = $this->get_batch_by_id($item["batch_id"]);
                if (!$batch) {
                    throw new Exception(
                        "Batch not found: " . $item["batch_id"],
                    );
                }

                // Check if sufficient quantity is available at source location
                $source_batch = $this->get_batch_by_location(
                    $item["batch_id"],
                    $data["from_center_id"],
                    $data["from_department"],
                    $data["from_employee_number"],
                );
                if (
                    !$source_batch ||
                    $source_batch->available_quantity < $item["quantity"]
                ) {
                    throw new Exception(
                        "Insufficient quantity for batch: " .
                            $batch->batch_number,
                    );
                }

                // Calculate item value
                $item_value = $item["quantity"] * $batch->purchase_price;
                $total_value += $item_value;

                // Record transfer item
                $item_data = [
                    "transfer_id" => $transfer_id,
                    "batch_id" => $item["batch_id"],
                    "quantity_transferred" => $item["quantity"],
                    "unit_price" => $batch->purchase_price,
                    "total_price" => $item_value,
                    "remarks" => isset($item["remarks"])
                        ? $item["remarks"]
                        : "",
                ];

                $this->db->insert("stock_transfer_items", $item_data);

                // Deduct from source location
                $this->db->where("id", $item["batch_id"]);
                $this->db->where("center_id", $data["from_center_id"]);
                $this->db->where("department", $data["from_department"]);
                $this->db->where(
                    "employee_number",
                    $data["from_employee_number"],
                );
                $this->db->set(
                    "available_quantity",
                    "available_quantity - " . $item["quantity"],
                    false,
                );
                $this->db->update("medicine_batches");

                // Add to destination location
                $this->add_or_update_destination_batch(
                    $batch,
                    $data,
                    $item["quantity"],
                );

                // Update center stocks
                $this->update_center_stock(
                    $batch->medicine_id,
                    $data["from_center_id"],
                    $data["from_department"],
                    $item["quantity"],
                    "SUBTRACT",
                );
                $this->update_center_stock(
                    $batch->medicine_id,
                    $data["to_center_id"],
                    $data["to_department"],
                    $item["quantity"],
                    "ADD",
                );
            }

            // 3. Update transfer totals
            $this->db->where("id", $transfer_id);
            $this->db->update("stock_transfers", [
                "total_items" => count($data["transfer_items"]),
                "total_quantity" => array_sum(
                    array_column($data["transfer_items"], "quantity"),
                ),
                "total_value" => $total_value,
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception("Transaction failed");
            }

            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log("Department transfer error: " . $e->getMessage());
            return false;
        }
    }

    // ===============================================
    // SALES FUNCTIONS
    // ===============================================

    // public function get_all_sales()
    // {
    //     // try {
    //         $this->db->select('s.*, c.center_name,
    //             COALESCE(SUM(si.quantity_sold), 0) as total_quantity,
    //             COALESCE(COUNT(si.id), 0) as total_items,
    //             COALESCE(SUM(si.total), 0) as total_amount
    //         ');
    //         $this->db->from("sales s");
    //         $this->db->join("hms_centers c", "s.center_id = c.ID", "left");
    //         $this->db->join("sale_items si", "s.id = si.sale_id", "left");
    //         if (
    //             isset(
    //                 $_SESSION['logged_billing_manager']
    //                     ['center'],
    //             ) &&
    //             !empty(
    //                 $_SESSION['logged_billing_manager']
    //                     ['center']
    //             )
    //         ) {
    //             $this->db->where("s.center_id", $this->get_center_id($_SESSION['logged_billing_manager']['center']));
    //         }
    //         $this->db->group_by("s.id");
    //         $this->db->order_by("s.created_at", "DESC");
    //         return $this->db->get()->result();
    //     // } catch (Exception $e) {
    //     //     // If tables don't exist or have issues, return empty array
    //     //     return [];
    //     // }
    // }

   public function count_all_sales($filters = [])
{
    $this->db->select('COUNT(DISTINCT s.id) as total');
    $this->db->from('sales s');

    $this->db->join('sale_items si', 'si.sale_id = s.id', 'left');
    $this->db->join('medicine_batches mb', 'mb.id = si.batch_id', 'left');
    $this->db->join('medicines m', 'm.id = mb.medicine_id', 'left');

    $this->db->where('s.status !=', 'package');
    $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
    $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

    // COPY SAME FILTERS HERE 👇

    if (!empty($filters['center_id'])) {
        $this->db->where('s.center_id', $filters['center_id']);
    }

    if (!empty($filters['patient_id'])) {
        $this->db->like('s.patient_id', $filters['patient_id']);
    }

    if (!empty($filters['patient_name'])) {
        $this->db->like('s.patient_name', $filters['patient_name']);
    }

    if (!empty($filters['status'])) {
        $this->db->where('s.status', $filters['status']);
    }

    if (!empty($filters['approval_status'])) {
        $this->db->where('s.accountant_approval_status', $filters['approval_status']);
    }

    if (!empty($filters['date_from'])) {
        $this->db->where('s.sale_date >=', $filters['date_from']);
    }

    if (!empty($filters['date_to'])) {
        $this->db->where('s.sale_date <=', $filters['date_to']);
    }

    $query = $this->db->get();
    return $query->row()->total;
}

   public function get_all_sales($filters = [], $limit = null, $offset = null)
    {
        try {

            /* -------------------------------------------------
            *  SELECT COLUMNS
            * -------------------------------------------------*/
            $select = "
                s.id,
                s.sale_number,
                s.center_id,
                s.patient_id,
                s.patient_name,
                s.doctor_id,
                s.doctor_name,
                s.sale_date,
                s.sale_time,
                s.tally_status,
                s.payment_method,
                s.payment_status,
                s.utr_transaction_id,
                s.payment_image,
                s.status,
                s.remarks,
                s.created_by,
                s.created_at,
                s.updated_at,
                m.medicine_code,

                c.center_name,
                e.name AS salesperson_name,

                COUNT(DISTINCT si.id) AS total_items,
                COALESCE(SUM(si.quantity_sold), 0) AS total_quantity,
                COALESCE(SUM(si.subtotal), 0) AS subtotal,
                COALESCE(SUM(si.discount_amount), 0) AS discount_amount,
                COALESCE(SUM(si.tax_amount), 0) AS tax_amount,
                COALESCE(SUM(si.total), 0) AS total_amount,

                GROUP_CONCAT(DISTINCT m.gst_rate ORDER BY m.gst_rate SEPARATOR ', ') AS gst_rates
            ";

            /* -------------------------------------------------
            *  SAFE COLUMN CHECKS
            * -------------------------------------------------*/
            $fields = $this->db->list_fields('sales');

            if (in_array('payment_approved_by', $fields)) {
                $select .= ",
                    s.payment_approved_by,
                    s.payment_approved_by_name,
                    s.payment_approved_at
                ";
            } else {
                $select .= ",
                    NULL AS payment_approved_by,
                    NULL AS payment_approved_by_name,
                    NULL AS payment_approved_at
                ";
            }

            if (in_array('payment_rejected_by', $fields)) {
                $select .= ",
                    s.payment_rejected_by,
                    s.payment_rejected_by_name,
                    s.payment_rejected_at
                ";
            } else {
                $select .= ",
                    NULL AS payment_rejected_by,
                    NULL AS payment_rejected_by_name,
                    NULL AS payment_rejected_at
                ";
            }

            if (in_array('stock_restored', $fields)) {
                $select .= ",
                    s.stock_restored,
                    s.stock_restored_at,
                    s.stock_restored_by
                ";
            } else {
                $select .= ",
                    0 AS stock_restored,
                    NULL AS stock_restored_at,
                    NULL AS stock_restored_by
                ";
            }

            if (in_array('accountant_approval_status', $fields)) {
                $select .= ",
                    s.accountant_approval_status,
                    s.accountant_approved_by,
                    s.accountant_approved_by_name,
                    s.accountant_approved_at,
                    s.accountant_remarks
                ";
            } else {
                $select .= ",
                    'PENDING' AS accountant_approval_status,
                    NULL AS accountant_approved_by,
                    NULL AS accountant_approved_by_name,
                    NULL AS accountant_approved_at,
                    NULL AS accountant_remarks
                ";
            }

            /* -------------------------------------------------
            *  QUERY START
            * -------------------------------------------------*/
            $this->db->select($select, false);
            $this->db->from('sales s');

            $this->db->join('hms_centers c', 'c.ID = s.center_id', 'left');
            $this->db->join('hms_employees e', 'e.ID = s.created_by', 'left');

            /* Sale items (aggregation only) */
            $this->db->join('sale_items si', 'si.sale_id = s.id', 'left');
            $this->db->join('medicine_batches mb', 'mb.id = si.batch_id', 'left');
            $this->db->join('medicines m', 'm.id = mb.medicine_id', 'left');
            $this->db->where('s.status !=', 'package');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

            /* -------------------------------------------------
            *  STOCK MOVEMENT EXISTS OR DRAFT SALES
            * -------------------------------------------------*/
            $this->db->where("
                (
                    EXISTS (
                        SELECT 1
                        FROM stock_movements sm
                        WHERE sm.reference_id = s.id
                        AND sm.movement_type = 'SALE'
                        AND sm.to_location_type = 'SALE'
                    )
                    OR s.status = 'DRAFT'
                )
            ", null, false);

            /* -------------------------------------------------
            *  SESSION CENTER FILTER
            * -------------------------------------------------*/
            $center = null;

            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
            }

            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
            }

            if ($center !== null) {
                $this->db->where('s.center_id', $this->get_center_id($center));
            }

            /* -------------------------------------------------
            *  FILTERS
            * -------------------------------------------------*/
            if (!empty($filters['center_id'])) {
                $this->db->where('s.center_id', $filters['center_id']);
            }

            if (!empty($filters['patient_id'])) {
                $this->db->like('s.patient_id', $filters['patient_id']);
            }

            if (!empty($filters['patient_name'])) {
                $this->db->like('s.patient_name', $filters['patient_name']);
            }

            if (!empty($filters['status'])) {
                $this->db->where('s.status', $filters['status']);
            }

            if (!empty($filters['approval_status'])) {
                $this->db->where('s.accountant_approval_status', $filters['approval_status']);
            }

            if (!empty($filters['date_from'])) {
                $this->db->where('s.sale_date >=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $this->db->where('s.sale_date <=', $filters['date_to']);
            }

            /* -------------------------------------------------
            *  FINAL GROUP + ORDER
            * -------------------------------------------------*/
            $this->db->group_by('s.id');
            $this->db->order_by('s.created_at', 'DESC');

            if ($limit !== null) {
                $this->db->limit($limit, $offset);
            }

            return $this->db->get()->result();

        } catch (Exception $e) {
            log_message('error', 'get_all_sales error: ' . $e->getMessage());
            return [];
        }
    }
    public function add_sale($data)
    {
        $this->db->trans_start(); 
        $year  = date('Y');
        $month = date('n'); // 1–12
        if ($month >= 4) {
            $financial_year = $year . '-' . ($year + 1);
        } else {
            $financial_year = ($year - 1) . '-' . $year;
        }
        $this->db->select('sale_number');
        $this->db->from('sales');
        $this->db->like('sale_number', "Inv/$financial_year/", 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $lastSale = $this->db->get()->row();
        if ($lastSale) {
            $lastNumber = (int) substr(
                $lastSale->sale_number,
                strrpos($lastSale->sale_number, '/') + 1
            );
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; 
        }
        $data['sale_number'] = "Inv/$financial_year/" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $this->db->insert('sales', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete(); 
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $insert_id;
    }


    public function get_sale_by_id($id)
    {
        $this->db->select(
            's.*, c.center_name,
            COALESCE(SUM(si.quantity_sold), 0) as total_quantity,
            COALESCE(COUNT(si.id), 0) as total_items,
            
            -- RECALCULATED TOTALS --
            COALESCE(SUM(si.subtotal), 0) as subtotal,
            COALESCE(SUM(si.discount_amount), 0) as discount_amount,
            COALESCE(SUM(si.tax_amount), 0) as tax_amount,
            COALESCE(SUM(si.total), 0) as total_amount
            '
        );
        $this->db->from("sales s");
        $this->db->join("hms_centers c", "s.center_id = c.ID", "left");
        $this->db->join("sale_items si", "s.id = si.sale_id", "left");
        $this->db->where("s.id", $id);
        // You must GROUP BY all non-aggregated columns in the SELECT list
        $this->db->group_by("s.id, c.center_name"); 
        
        return $this->db->get()->row();
    }

    public function get_sale_items($sale_id)
    {
        $this->db->select(
            // si.* already includes discount_amount and tax_amount
            "si.*, m.medicine_name, m.medicine_code, b.brand_name, mb.batch_number, mb.expiry_date"
        );
        $this->db->from("sale_items si");
        $this->db->join("medicine_batches mb", "si.batch_id = mb.id", "left"); // Alias 'mb'
        $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
        // --- CORRECTED JOIN ---
        // Join 'medicine_brands' with alias 'b'
        $this->db->join("medicine_brands b", "m.brand_id = b.id", "left"); 
        // --- END CORRECTION ---
        $this->db->where("si.sale_id", $sale_id);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");   
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        return $this->db->get()->result();
    }

    public function add_sale_item($data)
    {
        return $this->db->insert("sale_items", $data);
    }

    public function remove_sale_item($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("sale_items");
    }

    public function confirm_sale($id, $user_id = null) // Added user_id
    {
        $this->db->trans_start();
        $sale = $this->get_sale_by_id($id);
        if (!$sale) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Sale not found.');
            return ['status' => 'error', 'message' => 'Sale not found.'];
        }
        if ($sale->status != 'DRAFT') {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Sale is already processed.');
            return ['status' => 'error', 'message' => 'Sale is already processed.'];
        }
        $items = $this->get_sale_items($id);
        if (empty($items)) {
             $this->db->trans_rollback();
             $this->session->set_flashdata('error', 'Cannot confirm a sale with no items.');
             return ['status' => 'error', 'message' => 'Cannot confirm a sale with no items.'];
        }
        foreach ($items as $item) {
            $stock = $this->db->select('available_quantity')
                              ->from('center_stocks')
                              ->where('batch_id', $item->batch_id)
                              ->where('center_id', $sale->center_id)
                              ->get()->row();
            if (!$stock || $stock->available_quantity < $item->quantity_sold) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Not enough stock for ' . $item->medicine_name . ' (Batch: ' . $item->batch_number . ').');
                return ['status' => 'error', 'message' => 'Not enough stock for ' . $item->medicine_name];
            }
        }
        $update_data = [
            "status"            => "CONFIRMED",
            "total_items"       => $sale->total_items,         // From recalculated value
            "total_quantity"    => $sale->total_quantity,    // From recalculated value
            "subtotal"          => $sale->subtotal,          // From recalculated value
            "discount_amount"   => $sale->discount_amount,   // From recalculated value
            "tax_amount"        => $sale->tax_amount,        // From recalculated value
            "total_amount"      => $sale->total_amount,      // From recalculated value
            "payment_status"    => "PAID", // Or 'PENDING' - set payment status on confirm
            "updated_at"        => date('Y-m-d H:i:s')
        ];
        $this->db->where("id", $id);
        $this->db->update("sales", $update_data);
        foreach ($items as $item) {
             // Get current stock quantity for the log
            $stock_before = $this->db->select('quantity')->from('center_stocks')->where('batch_id', $item->batch_id)->where('center_id', $sale->center_id)->get()->row();
            $quantity_before = $stock_before ? (int)$stock_before->quantity : 0;
            $quantity_after = $quantity_before - (float)$item->quantity_sold;
            // Reduce center stock
            $this->db->where("batch_id", $item->batch_id);
            $this->db->where("center_id", $sale->center_id);
            $this->db->set("quantity", "GREATEST(0, quantity - " . (float)$item->quantity_sold . ")", false);
            $this->db->set("last_movement_date", "NOW()", false);
            $this->db->update("center_stocks");
            // Reduce master batch stock
            $this->db->where("id", $item->batch_id);
            $this->db->set("quantity_remaining", "GREATEST(0, quantity_remaining - " . (float)$item->quantity_sold . ")", false);
            $this->db->update("medicine_batches");
            // Log stock movement
            $movement_data = [
                "batch_id"          => $item->batch_id,
                "movement_type"     => "SALE",
                "from_location_type"=> "CENTER",
                "from_location_id"  => $sale->center_id,
                "to_location_type"  => "SALE",
                "to_location_id"    => $id,
                "quantity_before"   => $quantity_before,
                "quantity_change"   => -(int)$item->quantity_sold,
                "quantity_after"    => $quantity_after,
                "unit_price"        => $item->unit_price,
                "total_value"       => $item->total,
                "reference_type"    => "SALES_BILL",
                "reference_id"      => $id,
                "reference_number"  => $sale->sale_number,
                "patient_id"        => $sale->patient_id,
                "patient_name"      => $sale->patient_name,
                "created_by"        => $user_id ?? $sale->created_by, // Use ID of user confirming sale
                "created_at"        => date('Y-m-d H:i:s')
            ];
            $this->db->insert("stock_movements", $movement_data);
        }
        $this->db->trans_complete(); // Commit transaction
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Database transaction failed during confirmation.');
            return ['status' => 'error', 'message' => 'Database transaction failed.'];
        }

        return ['status' => 'success']; // Success
    }
    public function get_available_batches_for_sale($center_id)
    {
        $this->db->select(
            "mb.*, m.medicine_name,m.pack_size, m.medicine_code, mb2.brand_name as brand_name,m.gst_rate as gst_rate, ccs.quantity as available_quantity",
        );
        $this->db->from("medicine_batches mb");
        $this->db->join("medicines m", "mb.medicine_id = m.id");
        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb2",
        //     "m.brand_id = mb2.ID",
        // );
        $this->db->join("medicine_brands mb2", "m.brand_id = mb2.id");
        $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id");
        $this->db->where("ccs.center_id", $center_id);
        // Filter by department if available
        $department =null;
        if(!empty($_SESSION['logged_stock_manager']['employee_number'])) {
            $department = $_SESSION['logged_stock_manager']['department'] ?? null;
        } elseif (isset($_SESSION['billing_manager']['employee_number']) && !empty($_SESSION['billing_manager']['employee_number'])) {
            $department = $_SESSION['billing_manager']['department'] ?? null;
        }elseif (isset($_SESSION['logged_billing_manager']['employee_number']) && !empty($_SESSION['logged_billing_manager']['employee_number'])) {
            $department = $_SESSION['logged_billing_manager']['department'] ?? null;
        }
        if ($department !== null && $department !== '') {
            if ($department == 'billing') {
                $this->db->like('ccs.department', 'CASH MEDICINE');
            }elseif($department == 'Embryologist Basant Lok')
            {
                $this->db->like('ccs.department', 'Embryology Basant Lok');
            }else {
                $this->db->like('ccs.department', $department);
            }
        }

        $this->db->where("ccs.quantity >", 0);
        $this->db->where("mb.batch_status", "ACTIVE");
        $this->db->where("m.status", "active");
        $this->db->where("ccs.status", "ACTIVE");
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $center = null;
        // if (!empty($_SESSION['logged_billing_manager']) &&
        //     ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
        //     $center = $_SESSION['logged_billing_manager']['center'];
        // }
        // if (!empty($_SESSION['logged_stock_manager']) &&
        //     ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
        //     $center = $_SESSION['logged_stock_manager']['center'];
        // }
        // if ($center !== null) {
        //     $this->db->where('s.center_id', $this->get_center_id($center));
        // }
        $this->db->where("mb.expiry_date >", date("Y-m-d"));
        $this->db->order_by("mb.expiry_date", "ASC");
        $this->db->order_by("m.medicine_name", "ASC");
        return $this->db->get()->result();
    }

    // ===============================================
    // REPORTS FUNCTIONS
    // ===============================================

    public function get_sales_report($start_date, $end_date, $center_id = null)
    {
        try {
            $this->db->select("s.*, c.center_name");
            $this->db->from("sales s");
            $this->db->join("hms_centers c", "s.center_id = c.ID");
            $this->db->where("s.sale_date >=", $start_date);
            $this->db->where("s.sale_date <=", $end_date);
            $this->db->where("s.status", "CONFIRMED");

            if ($center_id) {
                $this->db->where("s.center_id", $center_id);
            }

            $this->db->order_by("s.sale_date", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return [];
        }
    }

    public function get_transfer_report(
        $start_date,
        $end_date,
        $transfer_type = null,
        $from_center_id = null,
        $to_center_id = null,
        ) {
        try {
            $this->db->select(
                "st.*, fc.center_name as from_center, tc.center_name as to_center",
            );
            $this->db->from("stock_transfers st");
            $this->db->join(
                "hms_centers fc",
                "st.from_center_id = fc.ID",
                "left",
            );
            $this->db->join("hms_centers tc", "st.to_center_id = tc.ID");
            $this->db->where("st.transfer_date >=", $start_date);
            $this->db->where("st.transfer_date <=", $end_date);
            $this->db->where("st.status", "COMPLETED");

            if ($transfer_type) {
                $this->db->where("st.transfer_type", $transfer_type);
            }

            if ($from_center_id) {
                $this->db->where("st.from_center_id", $from_center_id);
            }

            if ($to_center_id) {
                $this->db->where("st.to_center_id", $to_center_id);
            }

            $this->db->order_by("st.transfer_date", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return [];
        }
    }

    public function get_available_batches_for_return($receipt_number = null)
    {
            $this->db->select('
                si.batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining as available_quantity,
                mb.selling_price,
                mb.purchase_price,
                m.medicine_name,
                m.medicine_code,
                s.center_id,
                m.pack_size,
                COALESCE(b.brand_name, "Unknown Brand") as brand_name,
                COALESCE(v.name, "Unknown Vendor") as vendor_name,
                si.quantity_sold as quantity_sold,
                (si.quantity_sold - COALESCE(si.quantity_returned, 0)) as available_for_return,
                s.patient_id,
                s.patient_name,
                s.sale_number
            ');
            $this->db->from("sale_items si");
            $this->db->join(
                "medicine_batches mb",
                "si.batch_id = mb.id",
                "left",
            );
            $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
            // $this->db->join("hms_brands b", "m.brand_id = b.ID", "left");
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->join("hms_vendors v", "mb.vendor_id = v.ID", "left");
            $this->db->join("sales s", "si.sale_id = s.id", "left");
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("m.status", "active");
            $this->db->where("s.status", "CONFIRMED");
            $this->db->where("
                (
                    EXISTS (
                        SELECT 1
                        FROM stock_movements sm
                        WHERE sm.reference_id = s.id
                        AND sm.movement_type = 'SALE'
                        AND sm.to_location_type = 'SALE'
                    )
                )
            ", null, false);
            if (!empty($receipt_number)) {
                $this->db->where("s.sale_number", $receipt_number);
            } else {
                $this->db->where(
                    "s.sale_date >=",
                    date("Y-m-d", strtotime("-30 days")),
                );
            }
            $center = null;
            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
            }
            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
            }
            if ($center !== null) {
                $this->db->where('s.center_id', $this->get_center_id($center));
            }
            $this->db->where("s.status", "CONFIRMED");
            $this->db->where("(si.quantity_sold - COALESCE(si.quantity_returned, 0)) >", 0); 
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->group_by("si.id");
            $this->db->order_by("mb.expiry_date", "ASC");
            $this->db->order_by("m.medicine_name", "ASC");

            $result = $this->db->get()->result();
            return $result;
    }

    public function get_available_batches_for_audit($location_id= null ,$selected_department = null)
    {
        // try {
            // $location_id can be a center ID (e.g., 5) or the string 'central'
            if ($location_id == 'central' || $location_id == 0) {
                // --- Query 1: Get stock from CENTRAL WAREHOUSE ---
                $this->db->select([
                    'cst.batch_id',
                    'cst.quantity as system_quantity',
                    'mb.batch_number', 
                    'm.medicine_name',
                    "'CENTRAL' as location_type", // Identifier
                    "0 as location_id" // Use 0 or NULL
                ]);
                $this->db->from('central_stocks cst');
                $this->db->join('medicine_batches mb', 'cst.batch_id = mb.id', 'inner');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
                $this->db->where('cst.quantity >', 0);
                $this->db->where('mb.batch_status', 'ACTIVE');
                $this->db->where('m.status', 'active');
                $this->db->where('cst.status', 'ACTIVE');
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $query = $this->db->get();

            } else {
                // --- Query 2: Get stock from a specific CENTER ---
                $this->db->select([
                    'cs.batch_id',
                    'cs.quantity as system_quantity',
                    'mb.batch_number', 
                    'm.medicine_name',
                    "'CENTER' as location_type",
                    'cs.center_id as location_id',
                    'cs.department'
                ]);
                $this->db->from('center_stocks cs');
                $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
                $this->db->where('cs.center_id', (int)$location_id);
                if ($selected_department != null) {
                    $this->db->where('cs.department', $selected_department);
                }
                $center = null;
                if (!empty($_SESSION['logged_billing_manager']) &&
                    ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                    $center = $_SESSION['logged_billing_manager']['center'];
                }
                if (!empty($_SESSION['logged_stock_manager']) &&
                    ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                    $center = $_SESSION['logged_stock_manager']['center'];
                }
                if ($center !== null) {
                    $this->db->where('cs.center_id', $this->get_center_id($center));
                }
                $this->db->where('cs.quantity >', 0);
                $this->db->where('mb.batch_status', 'ACTIVE');
                $this->db->where('m.status', 'active');
                $this->db->where('cs.status', 'ACTIVE');
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $query = $this->db->get();
            }
            return $query->result();
        // } catch (Exception $e) {
        //     log_message('error', 'Error in get_available_batches_for_audit: ' . $e->getMessage());
        //     return []; // Return empty array on any database error
        // }
    }
    public function get_all_batches_list()
    {
         try {
            $this->db->select([
                'mb.id as batch_id',
                'mb.batch_number', 
                'm.medicine_name'
            ]);
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->order_by('m.medicine_name', 'ASC');
            $query = $this->db->get();
            return $query->result();
         } catch (Exception $e) {
             log_message('error', 'Error in get_all_batches_list: ' . $e->getMessage());
             return [];
         }
    }

    private function get_batch_purchase_price($batch_id)
    {
        $batch = $this->db->select('purchase_price')->get_where('medicine_batches', ['id' => $batch_id])->row();
        return $batch ? (float)$batch->purchase_price : 0;
    }

    private function get_stock_quantity_for_batch($batch_id, $location_type, $center_id, $department = null)
    {
        $this->db->select('quantity');
        if ($location_type == 'CENTRAL') {
            $this->db->from('central_stocks');
            $this->db->where('batch_id', $batch_id);
        } else {
            $this->db->from('center_stocks');
            $this->db->where('batch_id', $batch_id);
            $this->db->where('center_id', $center_id);
            // Filter by department if specified
            if (!empty($department)) {
                $this->db->where('department', $department);
            }
        }
        $result = $this->db->get()->row();
        return $result ? (int)$result->quantity : 0;
    }

    public function get_available_batches_for_disposal()
    {
        try {
            // Get batches from center_stocks
            $this->db->select([
                'cs.batch_id',           // The actual batch ID from medicine_batches
                'mb.batch_number',
                'mb.expiry_date',
                'DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days',
                'cs.quantity as available_quantity', // Stock available AT THIS CENTER
                'mb.purchase_price',     // Use purchase price for disposal value
                'm.medicine_name',
                'm.medicine_code',
                'b.brand_name',          // Correct table: medicine_brands
                'c.center_name',         // The center where the stock is located
                'c.ID as center_id'      // The ID of the center
            ]);
            $this->db->from('center_stocks cs'); // START HERE: This table tracks stock location
            // Join to get batch details (number, expiry, price)
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner'); 
            // Join to get medicine details (name, code)
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');      
            // Join to get brand name (Correct Table: medicine_brands)
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');     
            // Join to get center name (Correct Table: hms_centers)
            $this->db->join('hms_centers c', 'cs.center_id = c.ID', 'inner');      
            // --- Filters ---
            // Only show batches that actually have stock at the center
            $this->db->where('cs.quantity >', 0); 
            // Only show batches that are considered 'ACTIVE' (you might adjust this)
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('m.status', 'active');
            $this->db->where('cs.status', 'ACTIVE'); 
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // Optional: You could add filters here, e.g., only show expired batches
            // $this->db->where('mb.expiry_date <', date('Y-m-d')); 
            // --- Ordering ---
            $this->db->order_by('c.center_name', 'ASC'); // Group by center first
            $this->db->order_by('mb.expiry_date', 'ASC'); // Show soonest to expire first
            $this->db->order_by('m.medicine_name', 'ASC');
            $center_batches = $this->db->get()->result();
            
            // Get batches from central_stocks
            $this->db->select('cs.batch_id, mb.batch_number, mb.expiry_date, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days, cs.quantity as available_quantity, mb.purchase_price, m.medicine_name, m.medicine_code, b.brand_name, "Central warehouse Noida" as center_name, NULL as center_id', FALSE);
            $this->db->from('central_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where('cs.quantity >', 0);
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('m.status', 'active');
            $this->db->where('cs.status', 'ACTIVE');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->order_by('mb.expiry_date', 'ASC');
            $this->db->order_by('m.medicine_name', 'ASC');
            $central_batches = $this->db->get()->result();
            
            // Combine both results
            $result = array_merge($center_batches, $central_batches);
            
            // Sort combined results
            usort($result, function($a, $b) {
                $center_compare = strcmp($a->center_name ?? '', $b->center_name ?? '');
                if ($center_compare !== 0) return $center_compare;
                $expiry_compare = strcmp($a->expiry_date ?? '', $b->expiry_date ?? '');
                if ($expiry_compare !== 0) return $expiry_compare;
                return strcmp($a->medicine_name ?? '', $b->medicine_name ?? '');
            });
            
            return $result;
        } catch (Exception $e) {
            // Log error if needed for debugging
            // error_log("Error in get_available_batches_for_disposal: " . $e->getMessage());
            return []; // Return empty array on any database error
        }
    }
    public function get_audit_reports($filters = [])
    {
        $center = null;
        $created_by = null;

        if (!empty($_SESSION['logged_billing_manager']) &&
            ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {

            $center = $_SESSION['logged_billing_manager']['center'];
            $created_by = $this->get_employee_id_from_number(
                $_SESSION['logged_billing_manager']['employee_number']
            );
        }
        if (!empty($_SESSION['logged_stock_manager']) &&
            ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {

            $center = $_SESSION['logged_stock_manager']['center'];
            $created_by = $this->get_employee_id_from_number(
                $_SESSION['logged_stock_manager']['employee_number']
            );
        }
        /* 🔑 NOW start building the audit query */
        $this->db->select("ar.*, c.center_name");
        $this->db->from("audit_reports ar");
        $this->db->join("hms_centers c", "ar.center_id = c.ID", "left");

        if ($center !== null) {
            $this->db->where('ar.center_id', $this->get_center_id($center));
        }

        if ($created_by !== null) {
            $this->db->where('ar.created_by', $created_by);
        }

        // Filters
        if (!empty($filters['center_id'])) {
            if ($filters['center_id'] === 'central' || $filters['center_id'] === '0') {
                $this->db->where('ar.center_id IS NULL', null, false);
            } else {
                $this->db->where('ar.center_id', $filters['center_id']);
            }
        }

        if (!empty($filters['audit_type'])) {
            $this->db->where('ar.audit_type', $filters['audit_type']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('ar.status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $this->db->where('DATE(ar.audit_date) >=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $this->db->where('DATE(ar.audit_date) <=', $filters['to_date']);
        }

        $this->db->order_by("ar.created_at", "DESC");

        $results = $this->db->get()->result();

        foreach ($results as $result) {
            if ($result->center_id === null || $result->center_id == 0) {
                $result->center_name = 'Central Warehouse';
            }
        }

        return $results;
    }

    public function get_disposal_reports($filters = [])
    {
        try {
            $this->db->select("dr.*, COALESCE(c.center_name, 'Central warehouse Noida') as center_name");
            $this->db->from("disposal_reports dr");
            $this->db->join("hms_centers c", "dr.center_id = c.ID", "left");
            if (!empty($filters['center_id'])) {
                // Handle filtering for central warehouse
                if ($filters['center_id'] === 'CENTRAL_WAREHOUSE_NOIDA' || $filters['center_id'] === 'NULL') {
                    $this->db->where('dr.center_id IS NULL');
                } else {
                    $this->db->where('dr.center_id', $filters['center_id']);
                }
            }
            if (!empty($filters['status'])) {
                $this->db->where('dr.status', $filters['status']);
            }
            if (!empty($filters['from_date'])) {
                $this->db->where('DATE(dr.created_at) >=', $filters['from_date']);
            }
            if (!empty($filters['to_date'])) {
                $this->db->where('DATE(dr.created_at) <=', $filters['to_date']);
            }
            $this->db->order_by("dr.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }


    public function get_invoices()
    {
        try {
            $this->db->select("i.*, v.name as vendor_name, c.center_name");
            $this->db->from("invoices i");
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "i.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "i.center_id = c.ID", "left");
            $this->db->order_by("i.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_available_batches_for_invoice()
    {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.brand_name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands b",
            //     "m.brand_id = b.ID",
            // );
            $this->db->join('medicine_brands b', 'm.brand_id = b.id');
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "mb.center_id = c.ID", "left");
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("m.status", "active");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->where("mb.quantity_remaining >", 0);
            $this->db->order_by("mb.expiry_date", "ASC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_categories()
    {
        try {
            $this->db->where("status", "active");
            $this->db->order_by("category_name", "ASC");
            return $this->db->get("medicine_categories")->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_generic_names()
    {
        try {
            $this->db->select('
                gn.id,
                gn.generic_name,
                gn.generic_code,
                gn.description,
                gn.therapeutic_class,
                gn.status,
                gn.created_at,
                gn.updated_at,
                mc.category_name,
                COALESCE(med_count.medicines_count, 0) as medicines_count
            ');
            $this->db->from("generic_names gn");
            $this->db->join(
                "medicine_categories mc",
                "gn.category_id = mc.id",
                "left",
            );
            $this->db->join(
                '(
                SELECT generic_id, COUNT(*) as medicines_count
                FROM medicines
                WHERE status = "active"
                GROUP BY generic_id
            ) med_count',
                "gn.id = med_count.generic_id",
                "left",
            );
            $this->db->order_by("gn.generic_name", "ASC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    // public function get_vendor_returns()
    // {
    //     try {
    //         $this->db->select("vr.*, v.name as vendor_name, c.center_name");
    //         $this->db->from("vendor_returns vr");
    //         $this->db->join(
    //             $this->config->item("db_prefix") . "vendors v",
    //             "vr.vendor_id = v.ID",
    //         );
    //         $this->db->join("hms_centers c", "vr.center_id = c.ID", "left");
    //         $this->db->order_by("vr.created_at", "DESC");
    //         return $this->db->get()->result();
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }

    /**
     * MODIFIED: Function now accepts a $filters array
     */
    public function get_vendor_returns($filters = [])
    {
        try {
            $this->db->select("vr.*, v.name as vendor_name, COALESCE(c.center_name, 'Central warehouse Noida') as center_name");
            $this->db->from("vendor_returns vr");
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "vr.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "vr.center_id = c.ID", "left");
            // --- NEW: Add conditional WHERE clauses from filters ---
            // Filter by Vendor ID
            if (!empty($filters['vendor_id'])) {
                $this->db->where('vr.vendor_id', $filters['vendor_id']);
            }
            // Filter by Status
            if (!empty($filters['status'])) {
                $this->db->where('vr.status', $filters['status']);
            }
            // Filter by Date From (assuming 'vr.created_at' is your date column)
            if (!empty($filters['from_date'])) {
                $this->db->where('DATE(vr.created_at) >=', $filters['from_date']);
            }
            // Filter by Date To
            if (!empty($filters['to_date'])) {
                $this->db->where('DATE(vr.created_at) <=', $filters['to_date']);
            }
            // --- End of new code ---
            $this->db->order_by("vr.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }
    public function get_vendor_return_reports(
        $vendor_id = null,
        $status = null,
        $from_date = null,
        $to_date = null,
    ) {
        try {
            $this->db->select("vr.*, v.name as vendor_name, COALESCE(c.center_name, 'Central warehouse Noida') as center_name");
            $this->db->from("vendor_returns vr");
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "vr.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "vr.center_id = c.ID", "left");

            // Apply filters
            if ($vendor_id) {
                $this->db->where("vr.vendor_id", $vendor_id);
            }
            if ($status) {
                $this->db->where("vr.status", $status);
            }
            if ($from_date) {
                $this->db->where("vr.return_date >=", $from_date);
            }
            if ($to_date) {
                $this->db->where("vr.return_date <=", $to_date);
            }

            $this->db->order_by("vr.created_at", "DESC");
            return $this->db->get()->result();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_vendor_return_summary_stats(
        $vendor_id = null,
        $status = null,
        $from_date = null,
        $to_date = null,
    ) {
        try {
            $this->db->select('
                COUNT(*) as total_returns,
                SUM(CASE WHEN status = "PENDING" THEN 1 ELSE 0 END) as pending_returns,
                SUM(CASE WHEN status = "APPROVED" THEN 1 ELSE 0 END) as approved_returns,
                SUM(CASE WHEN status = "COMPLETED" THEN 1 ELSE 0 END) as completed_returns,
                SUM(CASE WHEN status = "REJECTED" THEN 1 ELSE 0 END) as rejected_returns,
                SUM(total_items) as total_items_returned,
                SUM(total_quantity) as total_quantity_returned,
                SUM(total_value) as total_value_returned,
                AVG(total_value) as avg_return_value
            ');
            $this->db->from("vendor_returns");

            // Apply filters
            if ($vendor_id) {
                $this->db->where("vendor_id", $vendor_id);
            }
            if ($status) {
                $this->db->where("status", $status);
            }
            if ($from_date) {
                $this->db->where("return_date >=", $from_date);
            }
            if ($to_date) {
                $this->db->where("return_date <=", $to_date);
            }

            return $this->db->get()->row();
        } catch (Exception $e) {
            return (object) [
                "total_returns" => 0,
                "pending_returns" => 0,
                "approved_returns" => 0,
                "completed_returns" => 0,
                "rejected_returns" => 0,
                "total_items_returned" => 0,
                "total_quantity_returned" => 0,
                "total_value_returned" => 0,
                "avg_return_value" => 0,
            ];
        }
    }
    public function export_vendor_return_data($filters)
    {
        try {
            // --- 1. Fetch Data ---
            // This query logic matches your `get_vendor_return_reports` function
            $this->db->select([
                "vr.*", 
                "v.name as vendor_name", 
                "COALESCE(c.center_name, 'Central warehouse Noida') as center_name"
            ]);
            $this->db->from("vendor_returns vr");
            // Join with hms_vendors to get the name
            $this->db->join("hms_vendors v", "vr.vendor_id = v.ID", "left"); 
            // Join with hms_centers to get the name
            $this->db->join("hms_centers c", "vr.center_id = c.ID", "left"); 
            // --- 2. Apply Filters ---
            if (!empty($filters['vendor_id'])) {
                $this->db->where("vr.vendor_id", $filters['vendor_id']);
            }
            if (!empty($filters['status'])) {
                $this->db->where("vr.status", $filters['status']);
            }
            if (!empty($filters['from_date'])) {
                $this->db->where("vr.return_date >=", $filters['from_date']);
            }
            if (!empty($filters['to_date'])) {
                $this->db->where("vr.return_date <=", $filters['to_date']);
            }
            $this->db->order_by("vr.return_date", "DESC");
            $query = $this->db->get();
            $reports = $query->result();
            // --- 3. Generate CSV File ---
            $filename = "vendor_return_report_" . date("Y-m-d") . ".csv";
            // Set headers to force download
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            
            $output = fopen("php://output", "w");
            
            // 4. Write CSV Header Row
            fputcsv($output, [
                "Return Number",
                "Return Date",
                "Vendor",
                "Center",
                "Total Items",
                "Total Quantity",
                "Total Value (Cost)",
                "Status",
                "Return Reason",
                "Remarks"
            ]);
            // 5. Write Data Rows
            if (!empty($reports)) {
                foreach ($reports as $report) {
                    fputcsv($output, [
                        $report->return_number,
                        $report->return_date,
                        $report->vendor_name ?? 'N/A', // Use vendor_name from join
                        $report->center_name ?? 'N/A', // Use center_name from join
                        $report->total_items,
                        $report->total_quantity,
                        $report->total_value,
                        $report->status,
                        $report->return_reason,
                        $report->remarks
                    ]);
                }
            }
            // 6. Close stream and stop script execution
            fclose($output);
            exit; 
        } catch (Exception $e) {
            log_message('error', "Error exporting vendor return report: " . $e->getMessage());
            // If an error happens, show an error message
            echo "Error: Could not generate report. Please check system logs.";
            exit;
        }
    }


    // public function get_available_batches_for_vendor_return()
    // {
    //     try {
    //         $this->db->select('
    //             mb.id as batch_id,
    //             mb.batch_number,
    //             mb.expiry_date,
    //             DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
    //             mb.quantity_remaining,
    //             mb.selling_price,
    //             m.medicine_name,
    //             m.medicine_code,
    //             b.name as brand_name,
    //             v.name as vendor_name,
    //             c.center_name
    //         ');
    //         $this->db->from("medicine_batches mb");
    //         $this->db->join("medicines m", "mb.medicine_id = m.id");
    //         $this->db->join(
    //             $this->config->item("db_prefix") . "brands b",
    //             "m.brand_id = b.ID",
    //         );
    //         $this->db->join(
    //             $this->config->item("db_prefix") . "vendors v",
    //             "mb.vendor_id = v.ID",
    //         );
    //         $this->db->join("hms_centers c", "mb.center_id = c.ID", "left");
    //         $this->db->where("mb.batch_status", "ACTIVE");
    //         $this->db->where("mb.quantity_remaining >", 0);
    //         $this->db->order_by("mb.expiry_date", "ASC");
    //         return $this->db->get()->result();
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }
        public function get_available_batches_for_vendor_return($vendor_id, $center_id)
        {
            try {
                $this->db->select([
                    'cs.batch_id',
                    'cs.center_id',
                    'c.center_name',
                    'cs.quantity as available_quantity', // Stock available AT THIS CENTER
                    'm.medicine_name',
                    'mb.batch_number',
                    'mb.purchase_price', 
                    'mb.expiry_date' 
                ]);
                $this->db->from('center_stocks cs');
                $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
                $this->db->join('hms_centers c', 'cs.center_id = c.ID', 'inner');

                // --- Filters ---
                $this->db->where('cs.quantity >', 0); // Must have stock at the center
                $this->db->where('cs.center_id', $center_id); // Filter by selected center
                $this->db->where('mb.vendor_id', $vendor_id); // Filter by selected vendor
                $this->db->where('mb.batch_status', 'ACTIVE'); // Only active batches
                $this->db->where('m.status', 'active'); // Only active medicines
                $this->db->where('cs.status', 'ACTIVE'); // Only active center stocks
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                // --- Ordering ---
                $this->db->order_by('m.medicine_name', 'ASC');
                $this->db->order_by('mb.expiry_date', 'ASC');

                $result = $this->db->get()->result();
                return $result;

            } catch (Exception $e) {
                log_message('error', "Error in get_batches_by_vendor_center: " . $e->getMessage());
                return []; // Return empty array on any database error
            }
        }
        public function get_batches_by_vendor_center($vendor_id, $center_id)
        {
            // try {
                // Check if it's Central Warehouse Noida
                if ($center_id === 'CENTRAL_WAREHOUSE_NOIDA') {
                    // Use select with FALSE to allow raw SQL expressions
                    $this->db->select('cs.batch_id, NULL as center_id, NULL as department, "Central warehouse Noida" as center_name, cs.quantity as available_quantity, m.medicine_name, mb.batch_number, mb.purchase_price, mb.expiry_date', FALSE);
                    $this->db->from('central_stocks cs');
                    $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
                    $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');

                    // --- Filters ---
                    $this->db->where('cs.quantity >', 0); // Must have stock at central warehouse
                    $this->db->where('mb.vendor_id', $vendor_id); // Filter by selected vendor
                    $this->db->where('mb.batch_status', 'ACTIVE'); // Only active batches
                    $this->db->where('m.status', 'active'); // Only active medicines
                    $this->db->where('cs.status', 'ACTIVE'); // Only active central stocks
                    $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                    $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                    // --- Ordering ---
                    $this->db->order_by('m.medicine_name', 'ASC');
                    $this->db->order_by('mb.expiry_date', 'ASC');

                    $result = $this->db->get()->result();
                    return $result;
                } else {
                    // Regular center query
                    $this->db->select([
                        'cs.batch_id',
                        'cs.center_id',
                        'cs.department',
                        'c.center_name',
                        'cs.quantity as available_quantity', 
                        'm.medicine_name',
                        'mb.batch_number',
                        'mb.purchase_price', 
                        'mb.expiry_date'   
                    ]);
                    $this->db->from('center_stocks cs');
                    $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
                    $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
                    $this->db->join('hms_centers c', 'cs.center_id = c.ID', 'inner');

                    // --- Filters ---
                    $this->db->where('cs.quantity >', 0); // Must have stock at the center
                    $this->db->where('cs.center_id', $center_id); // Filter by selected center
                    $this->db->where('mb.vendor_id', $vendor_id); // Filter by selected vendor
                    $this->db->where('mb.batch_status', 'ACTIVE'); // Only active batches
                    $this->db->where('m.status', 'active'); // Only active medicines
                    $this->db->where('cs.status', 'ACTIVE'); // Only active center stocks
                    $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                    $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                    // --- Ordering ---
                    $this->db->order_by('m.medicine_name', 'ASC');
                    $this->db->order_by('mb.expiry_date', 'ASC');

                    $result = $this->db->get()->result();
                    return $result;
                }
            // } catch (Exception $e) {
            //     log_message('error', "Error in get_batches_by_vendor_center: " . $e->getMessage());
            //     return []; // Return empty array on any database error
            // }
        }
        // ===============================================
        // PURCHASE ORDER BATCH TRACKING METHODS
        // ===============================================

        public function get_purchase_order_details($po_id)
        {
            try {
                $this->db->select("*");
                $this->db->from("purchase_orders");
                $this->db->where("id", $po_id);
                return $this->db->get()->row();
            } catch (Exception $e) {
                return null;
            }
        }

        public function get_batches_from_purchase_order($po_id)
        {
            try {
                $this->db->select('
                    mb.*,
                    m.medicine_name,
                    m.medicine_code,
                    v.name as vendor_name,
                    vb.purchase_po_no,
                    vb.po_date,
                    vb.invoice_no,
                    vb.date_of_purchase
                ');
                $this->db->from("medicine_batches mb");
                $this->db->join("medicines m", "mb.medicine_id = m.id");
                $this->db->join("vendors v", "mb.vendor_id = v.id");
                $this->db->join(
                    "vendor_billing vb",
                    "mb.batch_number = vb.batch_number AND mb.vendor_id = vb.vendor_code",
                    "left",
                );
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $this->db->where("vb.purchase_po_no", $po_id);
                $this->db->order_by("mb.created_at", "DESC");
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_po_batch_summary($po_id)
        {
            try {
                $batches = $this->get_batches_from_purchase_order($po_id);

                $summary = [
                    "total_batches" => count($batches),
                    "total_quantity_received" => 0,
                    "total_quantity_remaining" => 0,
                    "total_quantity_distributed" => 0,
                    "total_value" => 0,
                    "batches_in_central" => 0,
                    "batches_transferred" => 0,
                    "batches_sold" => 0,
                ];

                foreach ($batches as $batch) {
                    $summary["total_quantity_received"] +=
                        $batch->quantity_purchased;
                    $summary["total_quantity_remaining"] +=
                        $batch->quantity_remaining;
                    $summary["total_quantity_distributed"] +=
                        $batch->quantity_purchased - $batch->quantity_remaining;
                    $summary["total_value"] +=
                        $batch->quantity_purchased * $batch->purchase_price;

                    // Check where this batch is located
                    $movements = $this->get_stock_movements_by_batch($batch->id);
                    $has_transfers = false;
                    $has_sales = false;

                    foreach ($movements as $movement) {
                        if (
                            $movement->movement_type == "Out" &&
                            $movement->to_center
                        ) {
                            $has_transfers = true;
                        }
                        if ($movement->patient_name) {
                            $has_sales = true;
                        }
                    }

                    if ($has_sales) {
                        $summary["batches_sold"]++;
                    } elseif ($has_transfers) {
                        $summary["batches_transferred"]++;
                    } else {
                        $summary["batches_in_central"]++;
                    }
                }

                return $summary;
            } catch (Exception $e) {
                return [];
            }
        }

        // ===============================================
        // STOCK TRACKING METHODS
        // ===============================================

        public function get_stock_movements()
        {
            try {
                $this->db->select('
                    sm.*,
                    m.medicine_name,
                    m.medicine_code,
                    mb.batch_number,
                    fc.center_name as from_center,
                    tc.center_name as to_center
                ');
                $this->db->from("stock_movements sm");
                $this->db->join("medicine_batches mb", "sm.batch_id = mb.id");
                $this->db->join("medicines m", "mb.medicine_id = m.id");
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $this->db->join(
                    "hms_centers fc",
                    "sm.from_location_id = fc.ID",
                    "left",
                );
                $this->db->join(
                    "hms_centers tc",
                    "sm.to_location_id = tc.ID",
                    "left",
                );
                $this->db->order_by("sm.created_at", "DESC");
                $this->db->limit(100);
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_stock_movements_by_batch($batch_id)
        {
            try {
                $this->db->select('
                    sm.*,
                    m.medicine_name,
                    m.medicine_code,
                    mb.batch_number,
                    mb.expiry_date,
                    fc.center_name as from_center,
                    tc.center_name as to_center
                ');
                $this->db->from("stock_movements sm");
                $this->db->join("medicine_batches mb", "sm.batch_id = mb.id");
                $this->db->join("medicines m", "mb.medicine_id = m.id");
                $this->db->join(
                    "hms_centers fc",
                    "sm.from_location_id = fc.ID",
                    "left",
                );
                $this->db->join(
                    "hms_centers tc",
                    "sm.to_location_id = tc.ID",
                    "left",
                );
                $this->db->where("sm.batch_id", $batch_id);
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $this->db->order_by("sm.created_at", "DESC");
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }


        public function get_summary_stats()
        {
            try {
                $stats = [];

                // Total transfers
                $this->db->select("COUNT(*) as total");
                $this->db->from("stock_transfers");
                $this->db->where("status", "COMPLETED");
                $result = $this->db->get()->row();
                $stats["total_transfers"] = $result ? $result->total : 0;

                // Total sales
                $this->db->select("COUNT(*) as total");
                $this->db->from("sales");
                $this->db->where("status", "CONFIRMED");
                $result = $this->db->get()->row();
                $stats["total_sales"] = $result ? $result->total : 0;

                // Active batches
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicine_batches");
                $this->db->where("batch_status", "ACTIVE");
                $this->db->where("quantity_remaining >", 0);
                $result = $this->db->get()->row();
                $stats["active_batches"] = $result ? $result->total : 0;

                // Expiring batches (within 30 days)
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicine_batches");
                $this->db->where("batch_status", "ACTIVE");
                $this->db->where("quantity_remaining >", 0);
                $this->db->where("DATEDIFF(expiry_date, CURDATE()) <=", 30);
                $this->db->where("DATEDIFF(expiry_date, CURDATE()) >=", 0);
                $result = $this->db->get()->row();
                $stats["expiring_batches"] = $result ? $result->total : 0;

                return $stats;
            } catch (Exception $e) {
                return [
                    "total_transfers" => 0,
                    "total_sales" => 0,
                    "active_batches" => 0,
                    "expiring_batches" => 0,
                ];
            }
        }
/**
     * Fetches the stock movement data based on filters.
     * This is the missing function that export_stock_report() needs.
        */
        public function search_stock_movements($filters)
        {
            try {
                $this->db->select([
                    'DATE(sm.created_at) as movement_date',
                    'm.medicine_name',
                    'm.medicine_code',
                    'mb.batch_number',
                    'sm.movement_type',
                    
                    // Use CASE statements to get names for "From" and "To"
                    "CASE 
                        WHEN sm.from_location_type = 'CENTER' THEN fc.center_name
                        WHEN sm.from_location_type = 'VENDOR' THEN fv.name
                        WHEN sm.from_location_type = 'CENTRAL' THEN 'Central Warehouse'
                        ELSE sm.from_location_type 
                    END as from_center",
                    
                    "CASE 
                        WHEN sm.to_location_type = 'CENTER' THEN tc.center_name
                        WHEN sm.to_location_type = 'CENTRAL' THEN 'Central Warehouse'
                        ELSE sm.to_location_type 
                    END as to_center",
                    
                    'sm.quantity_change',
                    'sm.unit_price',
                    'sm.total_value',
                    'sm.reference_number',
                    'mb.batch_status as status' // Get the batch status
                ]);
                $this->db->from('stock_movements sm');
                $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
                $this->db->join('hms_vendors fv', 'sm.from_location_id = fv.ID AND sm.from_location_type = "VENDOR"', 'left');
                $this->db->join('hms_centers fc', 'sm.from_location_id = fc.ID AND sm.from_location_type = "CENTER"', 'left');
                $this->db->join('hms_centers tc', 'sm.to_location_id = tc.ID AND sm.to_location_type = "CENTER"', 'left');
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                // --- Apply Filters ---
                if (!empty($filters['medicine_id'])) {
                    $this->db->where('mb.medicine_id', $filters['medicine_id']);
                }
                if (!empty($filters['batch_id'])) {
                    $this->db->where('sm.batch_id', $filters['batch_id']);
                }
                if (!empty($filters['date_from'])) {
                    $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
                }
                
                // This filter finds movements FROM or TO the selected center
                if (!empty($filters['center_id'])) {
                    $this->db->group_start();
                    $this->db->where('(sm.from_location_type = "CENTER" AND sm.from_location_id = ' . (int)$filters['center_id'] . ')');
                    $this->db->or_where('(sm.to_location_type = "CENTER" AND sm.to_location_id = ' . (int)$filters['center_id'] . ')');
                    $this->db->group_end();
                }
                // --- End Filters ---

                $this->db->order_by('sm.created_at', 'DESC');
                return $this->db->get()->result();

            } catch (Exception $e) {
                log_message('error', 'Error in search_stock_movements: ' . $e->getMessage());
                return [];
            }
        }

    /**
     * Exports the stock report to a CSV file.
     * This is your original function, corrected with an exit; call.
     */
        public function export_stock_report($filters)
        {
            try {
                // Call the function above to get the filtered data
                $movements = $this->search_stock_movements($filters);
                
                // Set headers for CSV download
                header("Content-Type: text/csv");
                header(
                    'Content-Disposition: attachment; filename="stock_movements_report_' .
                        date("Y-m-d") .
                        '.csv"',
                );
                
                $output = fopen("php://output", "w");
                
                // CSV headers
                fputcsv($output, [
                    "Date",
                    "Medicine Name",
                    "Medicine Code",
                    "Batch Number",
                    "Movement Type",
                    "From Location", // Renamed for clarity
                    "To Location",   // Renamed for clarity
                    "Quantity Change",
                    "Unit Price",
                    "Total Value",
                    "Reference Number",
                    "Batch Status", // Renamed for clarity
                ]);

                // CSV data
                foreach ($movements as $movement) {
                    fputcsv($output, [
                        $movement->movement_date,
                        $movement->medicine_name,
                        $movement->medicine_code,
                        $movement->batch_number,
                        $movement->movement_type,
                        $movement->from_center, // Matches the 'as from_center' alias
                        $movement->to_center,   // Matches the 'as to_center' alias
                        $movement->quantity_change,
                        $movement->unit_price,
                        $movement->total_value,
                        $movement->reference_number,
                        $movement->status, // Matches the 'as status' alias
                    ]);
                }

                fclose($output);
                exit; // --- CRITICAL FIX --- Add exit; here to prevent corrupting the file

            } catch (Exception $e) {
                // Can't set headers if they are already sent
                log_message('error', "Error exporting report: " . $e->getMessage());
                echo "Error exporting report: " . $e->getMessage();
                exit;
            }
        }
        /**
     * Fetches detailed sale items based on filters for reporting.
     * This is called by export_sales_report().
     */
    public function search_sales_items($filters)
    {
        try {
            $this->db->select([
                's.sale_date',
                's.sale_number',
                'c.center_name',
                's.patient_name',
                's.patient_id',
                'm.medicine_name',
                'b.brand_name',
                'mb.batch_number',
                'mb.expiry_date',
                'si.quantity_sold',
                'si.unit_price',
                'si.subtotal',
                'si.discount_amount',
                'si.tax_amount',
                'si.total',
                'e.name as sold_by_name',
                's.status as sale_status'
            ]);
            $this->db->from('sale_items si');
            $this->db->join('sales s', 'si.sale_id = s.id', 'inner');
            $this->db->join('medicine_batches mb', 'si.batch_id = mb.id', 'left');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->join('hms_centers c', 's.center_id = c.ID', 'left');
            $this->db->join('hms_employees e', 's.created_by = e.ID', 'left');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // --- Apply Filters ---
            if (!empty($filters['date_from'])) {
                $this->db->where('s.sale_date >=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $this->db->where('s.sale_date <=', $filters['date_to']);
            }
            if (!empty($filters['center_id'])) {
                $this->db->where('s.center_id', (int)$filters['center_id']);
            }
            if (!empty($filters['medicine_id'])) {
                $this->db->where('mb.medicine_id', (int)$filters['medicine_id']);
            }
            if (!empty($filters['patient_id'])) {
                // Use LIKE for patient ID search
                $this->db->like('s.patient_id', $filters['patient_id']);
            }
             if (!empty($filters['status'])) {
                $this->db->where('s.status', $filters['status']);
            } else {
                // Default to only showing CONFIRMED sales if no status is specified
                $this->db->where('s.status', 'CONFIRMED');
            }
            // --- End Filters ---

            $this->db->order_by('s.sale_date', 'DESC');
            $this->db->order_by('s.sale_number', 'DESC');
            
            return $this->db->get()->result();

        } catch (Exception $e) {
            log_message('error', 'Error in search_sales_items: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generates a CSV file for the filtered sales report and forces download.
     */
        public function export_sales_report($filters)
        {
            try {
                // 1. Get the filtered data
                $sales_items = $this->search_sales_items($filters);
                $filename = "sales_report_item_wise_" . date("Y-m-d") . ".csv";
                // 2. Set CSV headers
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                $output = fopen("php://output", "w");
                // 3. Write CSV Header Row
                fputcsv($output, [
                    "Sale Date",
                    "Sale Number",
                    "Center",
                    "Patient ID",
                    "Patient Name",
                    "Medicine",
                    "Brand",
                    "Batch Number",
                    "Expiry Date",
                    "Quantity Sold",
                    "Unit Price",
                    "Subtotal",
                    "Discount",
                    "Tax",
                    "Total",
                    "Sold By",
                    "Sale Status"
                ]);
                // 4. Write Data Rows
                foreach ($sales_items as $item) {
                    fputcsv($output, [
                        $item->sale_date,
                        $item->sale_number,
                        $item->center_name,
                        $item->patient_id,
                        $item->patient_name,
                        $item->medicine_name,
                        $item->brand_name,
                        $item->batch_number,
                        $item->expiry_date,
                        $item->quantity_sold,
                        $item->unit_price,
                        $item->subtotal,
                        $item->discount_amount,
                        $item->tax_amount,
                        $item->total,
                        $item->sold_by_name,
                        $item->sale_status
                    ]);
                }
                // 5. Close stream and exit
                fclose($output);
                exit; // Must call exit; to prevent other output from corrupting file

            } catch (Exception $e) {
                log_message('error', "Error exporting sales report: " . $e->getMessage());
                echo "Error: Could not generate report.";
                exit;
            }
        }
        /**
     * Fetches detailed transfer items based on filters for reporting.
     * This is called by export_transfer_report().
        */
        public function search_transfer_items($filters)
        {
            try {
                $this->db->select([
                    'st.transfer_date',
                    'st.transfer_number',
                    'st.transfer_type',
                    // Use CASE for 'From' location, as it could be Central Warehouse (NULL ID)
                    "CASE 
                        WHEN st.from_center_id IS NULL THEN 'Central Warehouse' 
                        ELSE from_center.center_name 
                    END as from_location_name",
                    'to_center.center_name as to_location_name',
                    'm.medicine_name',
                    'b.brand_name',
                    'mb.batch_number',
                    'mb.expiry_date',
                    'sti.quantity_transferred',
                    'sti.unit_price', // This is the purchase price
                    'sti.total_price',
                    'e.name as transferred_by_name',
                    'st.status as transfer_status'
                ]);
                $this->db->from('stock_transfer_items sti');
                $this->db->join('stock_transfers st', 'sti.transfer_id = st.id', 'inner');
                $this->db->join('medicine_batches mb', 'sti.batch_id = mb.id', 'left');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
                $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                // Join hms_centers twice: once for 'From', once for 'To'
                $this->db->join('hms_centers from_center', 'st.from_center_id = from_center.ID', 'left'); // LEFT join for NULL
                $this->db->join('hms_centers to_center', 'st.to_center_id = to_center.ID', 'left');
                $this->db->join('hms_employees e', 'st.created_by = e.ID', 'left');
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                // --- Apply Filters ---
                if (!empty($filters['date_from'])) {
                    $this->db->where('st.transfer_date >=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $this->db->where('st.transfer_date <=', $filters['date_to']);
                }
                if (!empty($filters['from_center_id'])) {
                    if (strtolower($filters['from_center_id']) == 'central') {
                        $this->db->where('st.from_center_id IS NULL');
                    } else {
                        $this->db->where('st.from_center_id', (int)$filters['from_center_id']);
                    }
                }
                if (!empty($filters['to_center_id'])) {
                    $this->db->where('st.to_center_id', (int)$filters['to_center_id']);
                }
                if (!empty($filters['medicine_id'])) {
                    $this->db->where('mb.medicine_id', (int)$filters['medicine_id']);
                }
                if (!empty($filters['status'])) {
                    $this->db->where('st.status', $filters['status']);
                } else {
                    // Default to only showing COMPLETED transfers if no status is specified
                    $this->db->where('st.status', 'COMPLETED');
                }
                // --- End Filters ---

                $this->db->order_by('st.transfer_date', 'DESC');
                $this->db->order_by('st.transfer_number', 'DESC');
                
                return $this->db->get()->result();

            } catch (Exception $e) {
                log_message('error', 'Error in search_transfer_items: ' . $e->getMessage());
                return [];
            }
        }

        /**
         * Generates a CSV file for the filtered transfer report and forces download.
         */
        public function export_transfer_report($filters)
        {
            try {
                // 1. Get the filtered data
                $transfer_items = $this->search_transfer_items($filters);
                
                $filename = "transfer_report_item_wise_" . date("Y-m-d") . ".csv";

                // 2. Set CSV headers
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                
                $output = fopen("php://output", "w");
                
                // 3. Write CSV Header Row
                fputcsv($output, [
                    "Transfer Date",
                    "Transfer Number",
                    "Transfer Type",
                    "From Location",
                    "To Location",
                    "Medicine",
                    "Brand",
                    "Batch Number",
                    "Expiry Date",
                    "Quantity Transferred",
                    "Unit Cost",
                    "Total Value",
                    "Transferred By",
                    "Status"
                ]);

                // 4. Write Data Rows
                foreach ($transfer_items as $item) {
                    fputcsv($output, [
                        $item->transfer_date,
                        $item->transfer_number,
                        $item->transfer_type,
                        $item->from_location_name,
                        $item->to_location_name,
                        $item->medicine_name,
                        $item->brand_name,
                        $item->batch_number,
                        $item->expiry_date,
                        $item->quantity_transferred,
                        $item->unit_price,
                        $item->total_price,
                        $item->transferred_by_name,
                        $item->transfer_status
                    ]);
                }

                // 5. Close stream and exit
                fclose($output);
                exit; // Must call exit; to prevent other output from corrupting file

            } catch (Exception $e) {
                log_message('error', "Error exporting transfer report: " . $e->getMessage());
                echo "Error: Could not generate report.";
                exit;
            }
        }

        public function export_batches($filters = [])
        {
            try {
                // Get filtered batches data
                $batches = $this->get_all_batches(
                    isset($filters['medicine_id']) ? $filters['medicine_id'] : null,
                    isset($filters['vendor_id']) ? $filters['vendor_id'] : null,
                    isset($filters['batch_number']) ? $filters['batch_number'] : null,
                    isset($filters['batch_status']) ? $filters['batch_status'] : null
                );

                // Set headers for CSV download
                header("Content-Type: text/csv");
                header(
                    'Content-Disposition: attachment; filename="batches_report_' .
                        date("Y-m-d") .
                        '.csv"',
                );

                $output = fopen("php://output", "w");

                // CSV headers
                fputcsv($output, [
                    "Batch Number",
                    "Medicine Name",
                    "Medicine Code",
                    "Brand Name",
                    "Vendor Name",
                    "Purchase Price",
                    "Selling Price (MRP)",
                    "Quantity Purchased",
                    "Quantity Remaining",
                    "Expiry Date",
                    "Days Left",
                    "Batch Status",
                    "Quality Status",
                    "Invoice Number",
                    "Created Date",
                ]);

                // CSV data
                foreach ($batches as $batch) {
                    fputcsv($output, [
                        isset($batch->batch_number) ? $batch->batch_number : '',
                        isset($batch->medicine_name) ? $batch->medicine_name : '',
                        isset($batch->medicine_code) ? $batch->medicine_code : '',
                        isset($batch->brand_name) ? $batch->brand_name : '',
                        isset($batch->vendor_name) ? $batch->vendor_name : '',
                        isset($batch->purchase_price) ? number_format($batch->purchase_price, 2) : '0.00',
                        isset($batch->selling_price) ? number_format($batch->selling_price, 2) : '0.00',
                        isset($batch->quantity_purchased) ? number_format($batch->quantity_purchased) : '0',
                        isset($batch->quantity_remaining) ? number_format($batch->quantity_remaining) : '0',
                        isset($batch->expiry_date) ? date('M d, Y', strtotime($batch->expiry_date)) : '',
                        isset($batch->expiry_days) ? $batch->expiry_days : '',
                        isset($batch->batch_status) ? $batch->batch_status : '',
                        isset($batch->quality_status) ? $batch->quality_status : '',
                        isset($batch->invoice_number) ? $batch->invoice_number : '',
                        isset($batch->created_at) ? date('M d, Y H:i:s', strtotime($batch->created_at)) : '',
                    ]);
                }

                fclose($output);
                exit; // --- CRITICAL FIX --- Add exit; here to prevent corrupting the file

            } catch (Exception $e) {
                // Can't set headers if they are already sent
                log_message('error', "Error exporting batches report: " . $e->getMessage());
                echo "Error exporting batches report: " . $e->getMessage();
                exit;
            }
        }

        public function get_all_active_batches()
        {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                mb.quantity_remaining as available_for_return,
                mb.selling_price,
                m.pack_size,
                m.medicine_name,
                m.medicine_code,
                COALESCE(b.brand_name, "Unknown Brand") as brand_name
            ');
            $this->db->from("medicine_batches mb");
            $this->db->join("medicines m", "mb.medicine_id = m.id", "inner");
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            // Sirf wahi batches jo expire nahi hue aur active hain
            $this->db->where("mb.batch_status", "ACTIVE");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->where("m.status", "active");
            $this->db->where("mb.expiry_date >", date('Y-m-d'));
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // Order by Medicine Name and Expiry (FEFO)
            $this->db->order_by("m.medicine_name", "ASC");
            $this->db->order_by("mb.expiry_date", "ASC");

            $result = $this->db->get()->result();
            return $result;
        }
        public function process_medicine_return($return_data, $return_items,$is_old = false)
        {
            if (empty($return_items)) {
                return false;
            }

            $this->db->trans_start();
            // 1. Generate Return Number and Insert Header
            // Format: RET + YYYYMMDD + Unique ID
            $prefix = $is_old ? "ORET" : "RET";
            $next_id = $this->db->count_all_results('medicine_returns') + 1;
            $return_data["return_number"] = $prefix . date("Ymd") . str_pad($next_id, 4, "0", STR_PAD_LEFT);
            // $return_data["return_number"] = "RET" . date("Ymd") . str_pad($this->db->count_all_results('medicine_returns') + 1, 4, "0", STR_PAD_LEFT);
            $return_data["status"] = "PENDING"; 
            $this->db->insert("medicine_returns", $return_data);
            $return_id = $this->db->insert_id();
            if (!$return_id) {
                $this->db->trans_rollback();
                return false;
            }
            // 2. Resolve Sale ID from Receipt Number
            $sale_id = null;
            if (!$is_old && !empty($return_data['receipt_number'])) {
                $sale = $this->db->get_where('sales', ['sale_number' => $return_data['receipt_number']])->row();
                if ($sale) {
                    $sale_id = $sale->id;
                }
            }
            $items_processed = 0;
            foreach ($return_items as $item) {
                $batch_id = isset($item["batch_id"]) ? (int)$item["batch_id"] : 0;
                $quantity = isset($item["return_quantity"]) ? (float)$item["return_quantity"] : 0;
                $price = isset($item["price"]) ? (float)$item["price"] : 0;
                if ($batch_id <= 0 || $quantity <= 0) continue;
                // Calculate item totals and discounts
                $item_discount_percentage = isset($item["discount_percentage"]) ? (float)$item["discount_percentage"] : 0;
                $item_total = $quantity * $price;
                $item_discount_amount = ($item_total * $item_discount_percentage) / 100;
                $item_final_amount = $item_total - $item_discount_amount;
                // 3. Insert into medicine_return_items
                $item_entry = [
                    "return_id" => $return_id,
                    "batch_id" => $batch_id,
                    "quantity_returned" => $quantity,
                    "return_price" => $price,
                    "total_amount" => $item_total,
                    "discount_percentage" => $item_discount_percentage,
                    "discount_amount" => $item_discount_amount,
                    "final_amount" => $item_final_amount,
                    "created_at" => date("Y-m-d H:i:s")
                ];
                $this->db->insert("medicine_return_items", $item_entry);
                // 4. Update sale_items IMMEDIATELY
                // This ensures that "Available for Return" = (Sold - Returned) works in real-time
                if (!$is_old && $sale_id) {
                    $this->db->set('quantity_returned', 'COALESCE(quantity_returned, 0) + ' . $quantity, FALSE);
                    $this->db->set('updated_at', date('Y-m-d H:i:s'));
                    $this->db->where(['sale_id' => $sale_id, 'batch_id' => $batch_id]);
                    $this->db->update('sale_items');
                }
                $items_processed++;
            }
            if ($items_processed == 0) {
                $this->db->trans_rollback();
                return false;
            }
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        public function approve_medicine_return($return_id)
        {
            $this->db->trans_start();
            // 1. Get Return Header
            $return = $this->db->where('id', $return_id)->get('medicine_returns')->row();
            if (!$return || $return->status != 'PENDING') {
                $this->db->trans_rollback();
                return false;
            }
            // 2. Get Return Items
            $items = $this->db->where('return_id', $return_id)->get('medicine_return_items')->result();
            $is_old = (strpos($return->return_number, 'ORET') === 0);
            foreach ($items as $item) {
                $qty = (float)$item->quantity_returned;
                $batch_id = $item->batch_id;
                // A. Update Physical Stock (center_stocks)
                $this->db->where([
                    "batch_id" => $batch_id,
                    "center_id" => $return->center_id,
                    "department" => $return->department
                ]);
                $center_stock = $this->db->get("center_stocks")->row();
                $quantity_before = 0;
                if ($center_stock) {
                    $quantity_before = (float)$center_stock->quantity;
                    $this->db->where("id", $center_stock->id);
                    $this->db->set("quantity", "quantity + " . $qty, FALSE);
                    $this->db->set("last_movement_date", date("Y-m-d H:i:s"));
                    $this->db->set("updated_at", date("Y-m-d H:i:s"));
                    $this->db->update("center_stocks");
                } else {
                    // If the batch record was somehow deleted from center_stocks, recreate it
                    $this->db->insert("center_stocks", [
                        "batch_id" => $batch_id,
                        "center_id" => $return->center_id,
                        "department" => $return->department,
                        "quantity" => $qty,
                        "status" => "ACTIVE",
                        "last_movement_date" => date("Y-m-d H:i:s"),
                        "created_at" => date("Y-m-d H:i:s")
                    ]);
                }
                $quantity_after = $quantity_before + $qty;
                // B. Update Global Master Stock (medicine_batches)
                $this->db->where("id", $batch_id);
                $this->db->set("quantity_remaining", "quantity_remaining + " . $qty, FALSE);
                $this->db->set("updated_at", date("Y-m-d H:i:s"));
                $this->db->update("medicine_batches");
                // C. Record Audit Trail (stock_movements)
                $movement_data = [
                    "batch_id" => $batch_id,
                    "movement_type" => "SALE_RETURN",
                    "from_location_type" => "PATIENT",
                    "to_location_type" => "CENTER",
                    "to_location_id" => $return->center_id,
                    "quantity_before" => $quantity_before,
                    "quantity_change" => $qty,
                    "quantity_after" => $quantity_after,
                    "unit_price" => $item->return_price,
                    "total_value" => $item->final_amount,
                    "reference_type" => "RETURN_VOUCHER",
                    "reference_id" => $return_id,
                    "reference_number" => $return->return_number,
                    "patient_id" => $return->patient_id,
                    "patient_name" => $return->patient_name,
                    "remarks" => $is_old ? "Manual restoration of old medicine stock" : "Stock restored from validated sale return",
                    "created_at" => date("Y-m-d H:i:s"),
                    "created_by" => $return->created_by // Tracking who initiated
                ];
                $this->db->insert("stock_movements", $movement_data);
            }
            // 3. Mark the Return as APPROVED
            $this->db->where('id', $return_id)->update('medicine_returns', [
                'status' => 'APPROVED', 
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $this->db->trans_complete();
            return $this->db->trans_status();
        }

        public function disapprove_medicine_return($return_id)
        {
            $this->db->trans_start();
            $return = $this->db->where('id', $return_id)->get('medicine_returns')->row();
            if (!$return || $return->status != 'PENDING') {
                $this->db->trans_rollback();
                return false;
            }

            // 1. Get items to reverse the 'quantity_returned' count in sale_items
            $items = $this->db->where('return_id', $return_id)->get('medicine_return_items')->result();
            $is_old = (strpos($return->return_number, 'ORET') === 0);
            $sale = $this->db->get_where('sales', ['sale_number' => $return->receipt_number])->row();
            $sale_id = $sale ? $sale->id : null;

            if (!$is_old) {
                $items = $this->db->where('return_id', $return_id)->get('medicine_return_items')->result();
                $sale = $this->db->get_where('sales', ['sale_number' => $return->receipt_number])->row();
                
                if ($sale) {
                    foreach ($items as $item) {
                        $this->db->set('quantity_returned', 'quantity_returned - ' . (float)$item->quantity_returned, FALSE);
                        $this->db->where(['sale_id' => $sale->id, 'batch_id' => $item->batch_id]);
                        $this->db->update('sale_items');
                    }
                }
            }
            // 2. Set status to REJECTED
            $this->db->where('id', $return_id)->update('medicine_returns', [
                'status' => 'REJECTED', 
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->trans_complete();
            return $this->db->trans_status();
        }

        public function check_batch_exists($medicine_id, $batch_number, $exclude_batch_id = null)
        {
            $this->db->from('medicine_batches');
            $this->db->where('medicine_id', $medicine_id);
            $this->db->where('batch_number', $batch_number);

            // If we are editing (exclude_batch_id is provided),
            // we must exclude this batch's own ID from the check.
            if ($exclude_batch_id) {
                $this->db->where('id !=', $exclude_batch_id);
            }

            $query = $this->db->get();
            return $query->num_rows() > 0;
        }
        public function update_batch_details($id, $data)
        {
            // try {
                // Recalculate expiry days
                if (isset($data["expiry_date"])) {
                    $data["expiry_days"] = $this->calculate_expiry_days(
                        $data["expiry_date"],
                    );
                }

                $this->db->where('id', $id);
                $this->db->update('medicine_batches', $data);

                return $this->db->affected_rows() > 0;

            // } catch (Exception $e) {
            //     log_message('error', 'Error in update_batch_details: ' . $e->getMessage());
            //     return false;
            // }
        }

        public function get_medicine_returns()
        {
            $this->db->select('mr.*, c.center_name,
                COALESCE(COUNT(mri.id), 0) as total_items,
                COALESCE(SUM(mri.quantity_returned), 0) as total_quantity,
                COALESCE(SUM(mri.total_amount), 0) as total_return_amount,
                GROUP_CONCAT(DISTINCT CONCAT(m.medicine_name, IF(b.brand_name IS NOT NULL, CONCAT(" (", b.brand_name, ")"), "")) SEPARATOR ", ") as medicine_names
            ');
            $this->db->from("medicine_returns mr");
            $this->db->join("hms_centers c", "mr.center_id = c.ID", "left");
            $this->db->join(
                "medicine_return_items mri",
                "mr.id = mri.return_id",
                "left",
            );
            $center = null;
            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
            }
            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
            }
            if ($center !== null) {
                $this->db->where('mr.center_id', $this->get_center_id($center));
            }
            $this->db->join("medicine_batches mb", "mri.batch_id = mb.id", "left");
            $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
            $this->db->join("medicine_brands b", "m.brand_id = b.id", "left");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->group_by("mr.id");
            $this->db->order_by("mr.created_at", "DESC");
            return $this->db->get()->result();
        }
        //  public function get_medicine_returns_all_items()
        // {
        //     $this->db->select('mr.*, c.center_name,
        //         COALESCE(COUNT(mri.id), 0) as total_items,
        //         COALESCE(SUM(mri.quantity_returned), 0) as total_quantity,
        //         COALESCE(SUM(mri.total_amount), 0) as total_return_amount,
        //         GROUP_CONCAT(DISTINCT CONCAT(m.medicine_name, IF(b.brand_name IS NOT NULL, CONCAT(" (", b.brand_name, ")"), "")) SEPARATOR ", ") as medicine_names
        //     ');
        //     $this->db->from("medicine_returns mr");
        //     $this->db->join("hms_centers c", "mr.center_id = c.ID", "left");
        //     $this->db->join(
        //         "medicine_return_items mri",
        //         "mr.id = mri.return_id",
        //         "left",
        //     );
        //     $center = null;
        //     if (!empty($_SESSION['logged_billing_manager']) &&
        //         ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
        //         $center = $_SESSION['logged_billing_manager']['center'];
        //     }
        //     if (!empty($_SESSION['logged_stock_manager']) &&
        //         ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
        //         $center = $_SESSION['logged_stock_manager']['center'];
        //     }
        //     if ($center !== null) {
        //         $this->db->where('mr.center_id', $this->get_center_id($center));
        //     }
        //     $this->db->join("medicine_batches mb", "mri.batch_id = mb.id", "left");
        //     $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
        //     $this->db->join("medicine_brands b", "m.brand_id = b.id", "left");
        //     $this->db->group_by("mr.id");
        //     $this->db->order_by("mr.created_at", "DESC");
        //     return $this->db->get()->result();
        // }
        public function get_all_return_items_detailed($filters = [])
        {
            $this->db->select('
                mr.return_number,
                mr.return_date,
                mr.receipt_number as sale_reference,
                mr.patient_name,
                mr.patient_id,
                c.center_name,
                mr.department,
                m.medicine_name,
                m.gst_rate,
                m.medicine_code,
                b.brand_name,
                mb.batch_number,
                mb.expiry_date,
                mri.discount_percentage,
                mri.quantity_returned,
                mri.return_price as unit_price,
                mri.discount_amount,
                mri.final_amount as net_refund,
                mr.status as return_status,
                mr.return_reason,
                -- Show live quantity currently in the center for this item
                (SELECT quantity FROM center_stocks 
                WHERE batch_id = mri.batch_id 
                AND center_id = mr.center_id 
                AND department = mr.department LIMIT 1) as current_center_stock
            ');
            $this->db->from("medicine_return_items mri");
            $this->db->join("medicine_returns mr", "mri.return_id = mr.id", "inner");
            $this->db->join("hms_centers c", "mr.center_id = c.ID", "left");
            $this->db->join("medicine_batches mb", "mri.batch_id = mb.id", "left");
            $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // Session Filters
            $center = null;
            if (!empty($_SESSION['logged_billing_manager'])) {
                $center = $_SESSION['logged_billing_manager']['center'];
            } elseif (!empty($_SESSION['logged_stock_manager'])) {
                $center = $_SESSION['logged_stock_manager']['center'];
            }
            if ($center !== null) {
                $this->db->where('mr.center_id', $this->get_center_id($center));
            }

            // URL Filters
            if (!empty($filters['center_id'])) $this->db->where('mr.center_id', $filters['center_id']);
            if (!empty($filters['date_from'])) $this->db->where('mr.return_date >=', $filters['date_from']);
            if (!empty($filters['date_to'])) $this->db->where('mr.return_date <=', $filters['date_to']);

            $this->db->order_by("mr.return_date", "DESC");
            return $this->db->get()->result_array(); // result_array is easier for CSV/Excel logic
        }

        public function get_return_by_id($id)
        {
            $this->db->select('mr.*, c.center_name,
                COALESCE(COUNT(mri.id), 0) as total_items,
                COALESCE(SUM(mri.quantity_returned), 0) as total_quantity,
                COALESCE(SUM(mri.total_amount), 0) as total_return_amount
            ');
            $this->db->from("medicine_returns mr");
            $this->db->join("hms_centers c", "mr.center_id = c.ID", "left");
            $this->db->join(
                "medicine_return_items mri",
                "mr.id = mri.return_id",
                "left",
            );
            $this->db->where("mr.id", $id);
            $this->db->group_by("mr.id");
            return $this->db->get()->row();
        }

        public function get_return_items($return_id)
        {
            $this->db->select(
                "mri.*, mb.batch_number, mb.expiry_date,m.gst_rate, m.medicine_name, m.medicine_code, b.brand_name as brand_name",
            );
            $this->db->from("medicine_return_items mri");
            $this->db->join("medicine_batches mb", "mri.batch_id = mb.id");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            // $this->db->join(
            //     $this->config->item("db_prefix") . "brands b",
            //     "m.brand_id = b.ID",
            //     "left",
            // );
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->where("mri.return_id", $return_id);
            return $this->db->get()->result();
        }

        // ===============================================
        // CENTERS FUNCTIONS
        // ===============================================
        public function get_all_centers()
        {
            try {
                $sql = "SELECT * FROM hms_centers WHERE hms_centers.status = 1 ORDER BY hms_centers.center_name ASC";
                $query = $this->db->query($sql);
                if ($query) {
                    return $query->result(); 
                } else {
                    $db_error = $this->db->error();
                    log_message('error', 'Error fetching centers - Query failed: ' . ($db_error['message'] ?? 'Unknown error'));
                    return []; 
                }
            } catch (Exception $e) {
                log_message('error', 'Error fetching centers: ' . $e->getMessage());
                return [];
            }
        }
        public function add_center($data)
        {
            return $this->db->insert(
                $this->config->item("db_prefix") . "centers",
                $data,
            );
        }

        public function update_center($id, $data)
        {
            $this->db->where("ID", $id);
            return $this->db->update(
                $this->config->item("db_prefix") . "centers",
                $data,
            );
        }

        public function get_center_by_id($id)
        {
            $this->db->where("ID", $id);
            return $this->db
                ->get($this->config->item("db_prefix") . "centers")
                ->row();
        }

        // ===============================================
        // MISSING MODEL METHODS
        // ===============================================

        public function add_category($data)
        {
            try {
                return $this->db->insert("medicine_categories", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        // ===============================================
        // PROCESS FUNCTIONS FOR RETURNS, DISPOSAL, AUDIT
        // ===============================================


        public function process_medicine_disposal($disposal_data, $disposal_items)
        {
            if (empty($disposal_items)) {
                $this->session->set_flashdata('error', 'No items selected for disposal.'); // Add user feedback
                return false;
            }
            // Check if it's Central Warehouse Noida (before converting to NULL)
            $is_central_warehouse = ($disposal_data['center_id'] === 'CENTRAL_WAREHOUSE_NOIDA');
            
            // Validate: For regular centers, center_id must be provided. For central warehouse, it's allowed to be CENTRAL_WAREHOUSE_NOIDA
            if (!$is_central_warehouse && (empty($disposal_data['center_id']) || !is_numeric($disposal_data['center_id']))) {
                $this->session->set_flashdata('error', 'Center ID is required and must be valid.');
                return false;
            }
            if (empty($disposal_data['created_by'])) {
                $this->session->set_flashdata('error', 'User ID missing.');
                return false;
            }
            
            // Set center_id to NULL for central warehouse to satisfy foreign key constraint
            if ($is_central_warehouse) {
                $disposal_data['center_id'] = null;
            }
            
            $this->db->trans_start();
            // 3. Insert Disposal Header (`disposal_reports`)
            // Initialize totals to 0, they will be calculated and updated at the end.
            $disposal_data["disposal_number"] = "DISP" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
            $disposal_data["total_items"] = 0; // Placeholder
            $disposal_data["total_cost"] = 0.00; // Placeholder
            $allowed_header_fields = ['disposal_number', 'center_id', 'disposal_date', 'disposal_reason', 'disposal_method', 'disposal_company', 'authorized_by', 'total_items', 'total_cost', 'status', 'remarks', 'created_by', 'created_at'];
            $filtered_disposal_data = array_intersect_key($disposal_data, array_flip($allowed_header_fields));
            $this->db->insert("disposal_reports", $filtered_disposal_data);
            $db_error = $this->db->error();
            if ($db_error["code"] != 0) {
                log_message('error', 'DB Error (disposal_reports insert): ' . $db_error["message"]);
                $this->db->trans_rollback();
                return false;
            }
            $disposal_id = $this->db->insert_id();
            if (!$disposal_id) {
                log_message('error', 'DB Error: Failed to get insert_id for disposal_reports.');
                $this->db->trans_rollback();
                return false;
            }
            $calculated_total_cost = 0;
            $calculated_total_items = 0; 
            $calculated_total_quantity = 0;
            // 4. Loop through items to update stock and log movements
            foreach ($disposal_items as $item) {
                // Validate item data from form
                $quantity_to_dispose = isset($item["quantity_disposed"]) ? (int)$item["quantity_disposed"] : 0;
                $batch_id = isset($item["batch_id"]) ? (int)$item["batch_id"] : 0;
                if ($batch_id <= 0 || $quantity_to_dispose <= 0) {
                    continue; 
                }
                // Get batch cost (Purchase Price)
                $batch_info = $this->db->select('purchase_price')->get_where('medicine_batches', ['id' => $batch_id])->row();
                if (!$batch_info) {
                    log_message('error', "DB Error: Batch ID {$batch_id} not found in medicine_batches.");
                    $this->db->trans_rollback(); 
                    return false;
                }
                $unit_cost = (float)$batch_info->purchase_price;
                
                // Check stock availability - either from center_stocks or central_stocks
                if ($is_central_warehouse) {
                    $this->db->where("batch_id", $batch_id);
                    $central_stock = $this->db->select('quantity')->get("central_stocks")->row();
                    $quantity_before = ($central_stock) ? (int)$central_stock->quantity : 0;
                } else {
                    $this->db->where("batch_id", $batch_id);
                    $this->db->where("center_id", $disposal_data["center_id"]);
                    $center_stock = $this->db->select('quantity')->get("center_stocks")->row();
                    $quantity_before = ($center_stock) ? (int)$center_stock->quantity : 0;
                }
                
                $actual_disposed_qty = min($quantity_to_dispose, $quantity_before);
                if ($actual_disposed_qty <= 0) {
                    continue;
                }
                $quantity_after = $quantity_before - $actual_disposed_qty;
                $item_total_cost = $unit_cost * $actual_disposed_qty; // Cost based on actual disposed qty
                
                // Update stock - either central_stocks or center_stocks
                if ($is_central_warehouse) {
                    $this->db->set("quantity", "GREATEST(0, quantity - " . $actual_disposed_qty . ")", FALSE);
                    $this->db->set("last_movement_date", "NOW()", false);
                    $this->db->set("updated_at", "NOW()", false);
                    $this->db->where("batch_id", $batch_id);
                    $this->db->update("central_stocks");
                } else {
                    $this->db->set("quantity", "GREATEST(0, quantity - " . $actual_disposed_qty . ")", FALSE);
                    $this->db->set("last_movement_date", "NOW()", false);
                    $this->db->set("updated_at", "NOW()", false);
                    $this->db->where("batch_id", $batch_id);
                    $this->db->where("center_id", $disposal_data["center_id"]);
                    $this->db->update("center_stocks");
                }
                $db_error = $this->db->error();
                if ($db_error["code"] != 0) {
                    $table_name = $is_central_warehouse ? "central_stocks" : "center_stocks";
                    log_message('error', "DB Error ({$table_name} update): " . $db_error["message"]);
                    $this->db->trans_rollback();
                    return false;
                }
                $this->db->set("quantity_remaining", "GREATEST(0, quantity_remaining - " . $actual_disposed_qty . ")", FALSE);
                $this->db->set("updated_at", "NOW()", false);
                $this->db->where("id", $batch_id);
                $this->db->update("medicine_batches");
                $db_error = $this->db->error();
                if ($db_error["code"] != 0) {
                    log_message('error', "DB Error (medicine_batches update): " . $db_error["message"]);
                    $this->db->trans_rollback();
                    return false;
                }
                if ($this->db->table_exists("stock_movements")) {
                    $movement_data = [
                        "batch_id"           => $batch_id,
                        "movement_type"      => "DISPOSAL", // Correct ENUM value
                        "from_location_type" => $is_central_warehouse ? "CENTRAL" : "CENTER",
                        "from_location_id"   => $is_central_warehouse ? 0 : $disposal_data["center_id"],
                        "to_location_type"   => "WASTAGE", // Correct ENUM value
                        "to_location_id"     => null,
                        "quantity_before"    => $quantity_before, // Qty at center before
                        "quantity_change"    => -$actual_disposed_qty, // Negative, actual qty
                        "quantity_after"     => $quantity_after,  // Qty at center after
                        "unit_price"         => $unit_cost,       // Purchase price
                        "total_value"        => $item_total_cost, // Actual cost
                        "reference_type"     => "DISPOSAL_VOUCHER", // Correct ENUM value
                        "reference_id"       => $disposal_id,
                        "reference_number"   => $disposal_data["disposal_number"],
                        "remarks"            => "Disposal Reason: " . ($disposal_data["disposal_reason"] ?? 'N/A'), // Use correct field name
                        "created_by"         => $disposal_data["created_by"], // Employee ID
                        "created_at"         => date("Y-m-d H:i:s")
                    ];
                    $this->db->insert("stock_movements", $movement_data);
                    $db_error = $this->db->error();
                    if ($db_error["code"] != 0) {
                        log_message('error', "DB Error (stock_movements insert): " . $db_error["message"]);
                        $this->db->trans_rollback();
                        return false;
                    }
                } else {
                    log_message('warn', "Stock Movements table does not exist, skipping audit log.");
                }
                $calculated_total_items++; // Count this processed item/batch line
                $calculated_total_quantity += $actual_disposed_qty;
                $calculated_total_cost += $item_total_cost;
            } 
            // End foreach loop
            if ($calculated_total_items == 0) {
                log_message('error', "Disposal failed: No valid items found with available stock.");
                $this->db->trans_rollback();
                $location = $is_central_warehouse ? 'central warehouse' : 'this center';
                $this->session->set_flashdata('error', "No stock available for the selected items/batches at {$location}."); // User feedback
                return false;
            }
            $update_data = [
                'total_items' => $calculated_total_items,
                'total_cost' => $calculated_total_cost,
                // Add total_quantity_disposed if the column exists in your table
                // 'total_quantity_disposed' => $calculated_total_quantity,
                'updated_at' => date("Y-m-d H:i:s") // Manually update timestamp
            ];
            $this->db->where('id', $disposal_id);
            $this->db->update('disposal_reports', $update_data);
            // Error Check 6: Header Update
            $db_error = $this->db->error();
            if ($db_error["code"] != 0) {
                log_message('error', "DB Error (disposal_reports update totals): " . $db_error["message"]);
                $this->db->trans_rollback();
                return false;
            }
            // 7. Complete Transaction
            $this->db->trans_complete();
            // Check transaction status
            if ($this->db->trans_status() === FALSE) {
                log_message('error', "Database transaction failed for disposal ID: " . $disposal_id);
                return false;
            } else {
                return true; // Success
            }
        } // End function
        /**
         * Gets the main details of a single disposal report by its ID.
         * Joins with centers and employees tables for names.
         */
        public function get_disposal_report_by_id($id)
        {
            try {
                $this->db->select([
                    'dr.*', // Select all columns from disposal_reports
                    'c.center_name',
                    'e.name as created_by_name'
                ]);
                $this->db->from('disposal_reports dr');
                $this->db->join('hms_centers c', 'dr.center_id = c.ID', 'left');
                $this->db->join('hms_employees e', 'dr.created_by = e.ID', 'left');
                $this->db->where('dr.id', $id);
                return $this->db->get()->row(); // Return a single row object
            } catch (Exception $e) {
                log_message('error', "Error in get_disposal_report_by_id: " . $e->getMessage());
                return null; // Return null on error
            }
        }

        /**
         * Gets the details of items disposed in a specific report
         * by querying the stock_movements table.
         * This is used because disposal_report_items table doesn't exist.
         */
        public function get_disposed_items_from_log($disposal_id)
        {
            // Renamed function to clarify source
            // try {
                $this->db->select([
                    'sm.id as movement_id',
                    'sm.quantity_change', // This will be negative
                    'sm.unit_price',      // This is the purchase_price used
                    'sm.total_value',     // Calculated value at time of movement
                    'sm.created_at as log_created_at', // Alias to avoid name conflict
                    'mb.batch_number',
                    'mb.expiry_date',
                    'm.medicine_name',
                    'm.medicine_code',
                    'b.brand_name'
                ]);
                $this->db->from('stock_movements sm');
                $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
                $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                // Filter specifically for this disposal report's log entries
                $this->db->where('sm.reference_id', $disposal_id);
                $this->db->where('sm.reference_type', 'DISPOSAL_VOUCHER');
                $this->db->where('sm.movement_type', 'DISPOSAL'); 
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $this->db->order_by('sm.created_at', 'ASC');
                return $this->db->get()->result(); // Return an array of item objects
                
            // } catch (Exception $e) {
            //     log_message('error', "Error in get_disposed_items_from_log: " . $e->getMessage());
            //     return []; // Return empty array on error
            // }
        }
        
        public function process_stock_audit($audit_header, $audit_items, $selected_department = null)
        {
            $this->db->trans_start(); // Start transaction
            $audit_header['audit_number'] = "AUD-" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
            $audit_location_key = $audit_header['center_id'];
            $is_central_audit = (strtolower($audit_location_key) == 'central' || $audit_location_key == '0');
            $location_type = $is_central_audit ? 'CENTRAL' : 'CENTER';
            $location_id = $is_central_audit ? null : (int)$audit_location_key;
            $audit_report_center_id = $is_central_audit ? null : (int)$audit_location_key;
            $audit_header['center_id'] = $audit_report_center_id;
            $this->db->insert('audit_reports', $audit_header);
            $audit_id = $this->db->insert_id();
            if (!$audit_id) {
                $this->db->trans_rollback();
                return ['status' => 'error', 'message' => 'Failed to create audit report header.'];
            }
            $total_items_audited = 0;
            $discrepancies_found = 0;
            foreach ($audit_items as $item) {
                $batch_id = (int)($item['batch_id'] ?? 0);
                $physical_quantity = (int)($item['physical_quantity'] ?? 0);
                if ($batch_id <= 0) continue; // Skip empty/invalid rows
                $item_department = $selected_department;
                if (empty($item_department) && !$is_central_audit) {
                    $stock_record = $this->db->select('department')
                        ->from('center_stocks')
                        ->where('batch_id', $batch_id)
                        ->where('center_id', $location_id)
                        ->where('quantity >', 0)
                        ->get()
                        ->row();
                    if ($stock_record && !empty($stock_record->department)) {
                        $item_department = $stock_record->department;
                    }
                }
                $system_quantity = $this->get_stock_quantity_for_batch($batch_id, $location_type, $location_id, $item_department);
                $variance = $physical_quantity - $system_quantity;
                $total_items_audited++;
                if ($variance != 0) {
                    $discrepancies_found++;
                    $unit_cost = $this->get_batch_purchase_price($batch_id);
                    $adjustment_value = $variance * $unit_cost; // Can be positive or negative
                    $new_system_quantity = $system_quantity + $variance;
                    $this->db->set('quantity', $new_system_quantity);
                    $this->db->set('last_movement_date', 'NOW()', FALSE);
                    $this->db->set('updated_at', 'NOW()', FALSE);
                    $this->db->where('batch_id', $batch_id);
                    if ($is_central_audit) {
                        $this->db->update('central_stocks');
                    } else {
                        $this->db->where('center_id', $location_id);
                        if (!empty($item_department)) {
                            $this->db->where('department', $item_department);
                        }
                        $this->db->update('center_stocks');
                    }
                    $this->db->set('quantity_remaining', 'quantity_remaining + ' . $variance, FALSE);
                    $this->db->set('updated_at', 'NOW()', FALSE);
                    $this->db->where('id', $batch_id);
                    $this->db->update('medicine_batches');
                    $movement_data = [
                        'batch_id' => $batch_id,
                        'movement_type' => $variance > 0 ? 'ADJUSTMENT_IN' : 'ADJUSTMENT_OUT',
                        'from_location_type' => $variance > 0 ? 'ADJUSTMENT' : $location_type,
                        'from_location_id' => $variance > 0 ? null : $location_id, // Use null for central
                        'to_location_type' => $variance > 0 ? $location_type : 'ADJUSTMENT',
                        'to_location_id' => $variance > 0 ? $location_id : null, // Use null for central
                        'quantity_before' => $system_quantity,
                        'quantity_change' => $variance, // e.g., +5 or -3
                        'quantity_after' => $new_system_quantity,
                        'unit_price' => $unit_cost,
                        'total_value' => abs($adjustment_value),
                        'reference_type' => 'AUDIT_REPORT',
                        'reference_id' => $audit_id,
                        'reference_number' => $audit_header['audit_number'],
                        'remarks' => "Stock Audit. Physical: $physical_quantity, System: $system_quantity",
                        'created_by' => $audit_header['created_by'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('stock_movements', $movement_data);
                }
            } // End foreach
            $this->db->where('id', $audit_id);
            $this->db->update('audit_reports', [
                'total_items_audited' => $total_items_audited,
                'department'=>$selected_department,
                'discrepancies_found' => $discrepancies_found,
                'status' => 'COMPLETED'
            ]);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                log_message('error', 'Stock Audit Transaction Failed. Rolling back.');
                return ['status' => 'error', 'message' => 'Database transaction failed.'];
            }
            return ['status' => 'success', 'discrepancies' => $discrepancies_found];
        }
        public function get_audit_report_by_id($id)
        {
        try {
            $this->db->select([
                'ar.*', // Select all columns from audit_reports
                'c.center_name',
                'e.name as created_by_name'
            ]);
            $this->db->from('audit_reports ar');
            $this->db->join('hms_centers c', 'ar.center_id = c.ID', 'left');
            $this->db->join('hms_employees e', 'ar.created_by = e.ID', 'left');
            $this->db->where('ar.id', $id);
            $result = $this->db->get()->row();
            if ($result && ($result->center_id == 0 || $result->center_id === null)) {
                $result->center_name = 'Central Warehouse';
            }
            return $result; // Return a single row object
        } catch (Exception $e) {
            log_message('error', "Error in get_audit_report_by_id: " . $e->getMessage());
            return null; // Return null on error
        }
    }

    /**
     * Gets the details of items that were ADJUSTED during an audit
     * by querying the stock_movements table.
     */
    public function get_audit_items_from_log($audit_id)
    {
        // try {
            $this->db->select([
                'sm.id as movement_id',
                'sm.quantity_change',
                'sm.quantity_before',
                'sm.movement_type',
                'sm.quantity_after',
                'sm.unit_price',      
                'sm.total_value',    
                'sm.remarks as movement_remarks', 
                'sm.created_at as log_created_at',
                'mb.batch_number',
                'mb.expiry_date',
                'm.medicine_name',
                'm.medicine_code',
                'b.brand_name'
            ]);
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where('sm.reference_id', $audit_id);
            $this->db->where('sm.reference_type', 'AUDIT_REPORT');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->order_by('sm.created_at', 'ASC'); 
            return $this->db->get()->result(); 
        // } catch (Exception $e) {
        //     log_message('error', "Error in get_audit_items_from_log: " . $e->getMessage());
        //     return []; // Return empty array on error
        // }
    }

        public function process_vendor_return($return_data, $return_items)
        {
            if (empty($return_items)) {
                $this->session->set_flashdata('error', 'No items selected for return.');
                return false;
            }
            // Check if it's Central Warehouse Noida (before converting to NULL)
            $is_central_warehouse = ($return_data['center_id'] === 'CENTRAL_WAREHOUSE_NOIDA');
            
            // Validate: For regular centers, center_id must be provided. For central warehouse, it's allowed to be CENTRAL_WAREHOUSE_NOIDA
            if (!$is_central_warehouse && (empty($return_data['center_id']) || !is_numeric($return_data['center_id']))) {
                $this->session->set_flashdata('error', 'Center ID is required and must be valid.');
                return false;
            }
            if (empty($return_data['vendor_id']) || empty($return_data['created_by'])) {
                $this->session->set_flashdata('error', 'Vendor ID or User ID missing.');
                return false;
            }
            
            // Set center_id to NULL for central warehouse to satisfy foreign key constraint
            if ($is_central_warehouse) {
                $return_data['center_id'] = null;
            }
            $this->db->trans_start();
            $return_data["return_number"] = "VRET" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
            $return_data["total_items"] = 0; // Placeholder
            $return_data["total_quantity"] = 0; // Placeholder
            $return_data["total_value"] = 0.00; // Placeholder
            $return_data["status"] = $return_data["status"] ?? "PENDING";
            $allowed_header_fields = ['return_number', 'vendor_id', 'center_id', 'return_date', 'return_reason', 'total_items', 'total_quantity', 'total_value', 'status', 'remarks', 'created_by', 'created_at'];
            $filtered_return_data = array_intersect_key($return_data, array_flip($allowed_header_fields));
            $this->db->insert("vendor_returns", $filtered_return_data);
            $db_error = $this->db->error();
            if ($db_error["code"] != 0) {
                log_message('error', 'DB Error (vendor_returns insert): ' . $db_error["message"]);
                $this->db->trans_rollback();
                return false;
            }
            $return_id = $this->db->insert_id();
            if (!$return_id) {
                log_message('error', 'DB Error: Failed to get insert_id for vendor_returns.');
                $this->db->trans_rollback();
                return false;
            }
            $calculated_total_value = 0;
            $calculated_total_items = 0; // Counts distinct batches processed
            $calculated_total_quantity = 0; // Counts total units returned
            foreach ($return_items as $item) {
                $quantity_to_return = isset($item["quantity_returned"]) ? (int)$item["quantity_returned"] : 0;
                $batch_id = isset($item["batch_id"]) ? (int)$item["batch_id"] : 0;
                $unit_price = isset($item["unit_price"]) ? (float)$item["unit_price"] : 0; // Get price from hidden input
                if ($batch_id <= 0 || $quantity_to_return <= 0 || $unit_price <= 0) {
                    continue; 
                }
                // Check stock availability - either from center_stocks or central_stocks
                if ($is_central_warehouse) {
                    $this->db->where("batch_id", $batch_id);
                    $central_stock = $this->db->select('quantity')->get("central_stocks")->row();
                    $quantity_before = ($central_stock) ? (int)$central_stock->quantity : 0;
                } else {
                    $this->db->where("batch_id", $batch_id);
                    $this->db->where("center_id", $return_data["center_id"]);
                    $center_stock = $this->db->select('quantity')->get("center_stocks")->row();
                    $quantity_before = ($center_stock) ? (int)$center_stock->quantity : 0;
                }
                $actual_returned_qty = min($quantity_to_return, $quantity_before);
                if ($actual_returned_qty <= 0) {
                    continue;
                }
                $quantity_after = $quantity_before - $actual_returned_qty;
                $actual_item_total_value = $unit_price * $actual_returned_qty; // Value based on actual returned qty
                
                // Update stock - either central_stocks or center_stocks
                if ($is_central_warehouse) {
                    $this->db->set("quantity", "GREATEST(0, quantity - " . $actual_returned_qty . ")", FALSE);
                    $this->db->set("last_movement_date", "NOW()", false);
                    $this->db->set("updated_at", "NOW()", false);
                    $this->db->where("batch_id", $batch_id);
                    $this->db->update("central_stocks");
                } else {
                    $this->db->set("quantity", "GREATEST(0, quantity - " . $actual_returned_qty . ")", FALSE);
                    $this->db->set("last_movement_date", "NOW()", false);
                    $this->db->set("updated_at", "NOW()", false);
                    $this->db->where("batch_id", $batch_id);
                    $this->db->where("center_id", $return_data["center_id"]);
                    $this->db->update("center_stocks");
                }
                $db_error = $this->db->error();
                if ($db_error["code"] != 0) {
                    $table_name = $is_central_warehouse ? "central_stocks" : "center_stocks";
                    log_message('error', "DB Error ({$table_name} update): " . $db_error["message"]);
                    $this->db->trans_rollback();
                    return false;
                }
                $this->db->set("quantity_remaining", "GREATEST(0, quantity_remaining - " . $actual_returned_qty . ")", FALSE);
                $this->db->set("updated_at", "NOW()", false);
                $this->db->where("id", $batch_id);
                $this->db->update("medicine_batches");
                $db_error = $this->db->error();
                if ($db_error["code"] != 0) {
                    log_message('error', "DB Error (medicine_batches update): " . $db_error["message"]);
                    $this->db->trans_rollback();
                    return false;
                }
                if ($this->db->table_exists("stock_movements")) {
                    $movement_data = [
                        "batch_id"           => $batch_id,
                        "movement_type"      => "PURCHASE_RETURN", // Correct ENUM value
                        "from_location_type" => $is_central_warehouse ? "CENTRAL" : "CENTER",
                        "from_location_id"   => $is_central_warehouse ? 0 : $return_data["center_id"],
                        "to_location_type"   => "VENDOR",          // Correct ENUM value
                        "to_location_id"     => $return_data["vendor_id"],
                        "quantity_before"    => $quantity_before, // Qty at center before
                        "quantity_change"    => -$actual_returned_qty, // Negative, actual qty
                        "quantity_after"     => $quantity_after,  // Qty at center after
                        "unit_price"         => $unit_price,       // Purchase price
                        "total_value"        => $actual_item_total_value, // Actual value
                        "reference_type"     => "RETURN_VOUCHER", // As per schema ENUM
                        "reference_id"       => $return_id,
                        "reference_number"   => $return_data["return_number"],
                        "remarks"            => "Return to vendor: " . ($return_data["return_reason"] ?? 'N/A'),
                        "created_by"         => $return_data["created_by"], // Employee ID
                        "created_at"         => date("Y-m-d H:i:s")
                    ];
                    $this->db->insert("stock_movements", $movement_data);
                    $db_error = $this->db->error();
                    if ($db_error["code"] != 0) {
                        log_message('error', "DB Error (stock_movements insert): " . $db_error["message"]);
                        $this->db->trans_rollback();
                        return false;
                    }
                } else {
                    log_message('warn', "Stock Movements table does not exist, skipping audit log.");
                }
                $calculated_total_items++;
                $calculated_total_quantity += $actual_returned_qty;
                $calculated_total_value += $actual_item_total_value;
            }
            if ($calculated_total_items == 0) {
                log_message('error', "Vendor Return failed: No valid items found with available stock.");
                $this->db->trans_rollback();
                $location = $is_central_warehouse ? 'central warehouse' : 'this center';
                $this->session->set_flashdata('error', "No stock available for the selected items/batches at {$location}."); // User feedback
                return false;
            }
            $update_data = [
                'total_items'    => $calculated_total_items,
                'total_quantity' => $calculated_total_quantity,
                'total_value'    => $calculated_total_value,
                'updated_at'     => date("Y-m-d H:i:s") // Manually update timestamp
            ];
            $this->db->where('id', $return_id);
            $this->db->update('vendor_returns', $update_data);
            $db_error = $this->db->error();
            if ($db_error["code"] != 0) {
                log_message('error', "DB Error (vendor_returns update totals): " . $db_error["message"]);
                $this->db->trans_rollback();
                return false;
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                log_message('error', "Database transaction failed for vendor return ID: " . $return_id);
                return false;
            } else {
                return true; // Success
            }
        }

        public function get_employee_id_from_number($employee_number)
        {
            if (empty($employee_number)) {
                return null;
            }

            // 🔑 Reset previous query state
            $this->db->reset_query();

            $this->db->select("hms_employees.ID AS employee_id");
            $this->db->from("hms_employees");
            $this->db->where("hms_employees.employee_number", $employee_number);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row()->employee_id;
            }

            return null;
        }
        public function update_category($id, $data)
        {
            try {
                $this->db->where("id", $id);
                return $this->db->update("medicine_categories", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        public function update_category_status($id, $status)
        {
            try {
                $this->db->where("id", $id);
                return $this->db->update("medicine_categories", [
                    "status" => $status,
                ]);
            } catch (Exception $e) {
                return false;
            }
        }

        public function add_generic_name($data)
        {
            try {
                return $this->db->insert("generic_names", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        public function update_generic_name($id, $data)
        {
            try {
                $this->db->where("id", $id);
                return $this->db->update("generic_names", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        public function update_generic_name_status($id, $status)
        {
            try {
                $this->db->where("id", $id);
                return $this->db->update("generic_names", ["status" => $status]);
            } catch (Exception $e) {
                return false;
            }
        }

        public function add_invoice($data)
        {
            try {
                return $this->db->insert("invoices", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        public function add_vendor_return($data)
        {
            try {
                return $this->db->insert("vendor_returns", $data);
            } catch (Exception $e) {
                return false;
            }
        }

        // ===============================================
        // ADDITIONAL BRAND MANAGEMENT METHODS
        // ===============================================

        public function get_all_brands()
        {
            try {
                $this->db->order_by("brand_name", "ASC");
                return $this->db->get("medicine_brands")->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_active_brands()
        {
            try {
                $this->db->where("status", "active");
                $this->db->order_by("brand_name", "ASC");
                return $this->db->get("medicine_brands")->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function search_brands($search_term)
        {
            try {
                $this->db->like("brand_name", $search_term);
                $this->db->or_like("manufacturer", $search_term);
                $this->db->or_like("contact_person", $search_term);
                $this->db->where("status", "active");
                $this->db->order_by("brand_name", "ASC");
                return $this->db->get("medicine_brands")->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_brand_statistics()
        {
            // try {
                $stats = [];

                // Total brands
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicine_brands");
                $result = $this->db->get()->row();
                $stats["total_brands"] = $result ? $result->total : 0;

                // Active brands
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicine_brands");
                $this->db->where("status", "active");
                $result = $this->db->get()->row();
                $stats["active_brands"] = $result ? $result->total : 0;

                // Inactive brands
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicine_brands");
                $this->db->where("status", "inactive");
                $result = $this->db->get()->row();
                $stats["inactive_brands"] = $result ? $result->total : 0;

                return $stats;
            // } catch (Exception $e) {
            //     return [
            //         "total_brands" => 0,
            //         "active_brands" => 0,
            //         "inactive_brands" => 0,
            //     ];
            // }
        }

        // ===============================================
        // ADDITIONAL VENDOR MANAGEMENT METHODS
        // ===============================================

        // public function get_all_vendors()
        // {
        //     try {
        //         $this->db->order_by("vendor_name", "ASC");
        //         return $this->db->get("vendors")->result();
        //     } catch (Exception $e) {
        //         return [];
        //     }
        // }

        public function get_active_vendors()
        {
            try {
                $this->db->where("status", "active");
                $this->db->order_by("vendor_name", "ASC");
                return $this->db->get("vendors")->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function search_vendors($search_term)
        {
            try {
                $this->db->like("vendor_name", $search_term);
                $this->db->or_like("contact_person", $search_term);
                $this->db->or_like("email", $search_term);
                $this->db->where("status", "active");
                $this->db->order_by("vendor_name", "ASC");
                return $this->db->get("vendors")->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_vendor_statistics()
        {
            try {
                $stats = [];

                // Total vendors
                $this->db->select("COUNT(*) as total");
                $this->db->from("vendors");
                $result = $this->db->get()->row();
                $stats["total_vendors"] = $result ? $result->total : 0;

                // Active vendors
                $this->db->select("COUNT(*) as total");
                $this->db->from("vendors");
                $this->db->where("status", "active");
                $result = $this->db->get()->row();
                $stats["active_vendors"] = $result ? $result->total : 0;

                // Inactive vendors
                $this->db->select("COUNT(*) as total");
                $this->db->from("vendors");
                $this->db->where("status", "inactive");
                $result = $this->db->get()->row();
                $stats["inactive_vendors"] = $result ? $result->total : 0;

                // Total credit limit
                $this->db->select("SUM(credit_limit) as total");
                $this->db->from("vendors");
                $this->db->where("status", "active");
                $result = $this->db->get()->row();
                $stats["total_credit_limit"] = $result ? $result->total : 0;

                return $stats;
            } catch (Exception $e) {
                return [
                    "total_vendors" => 0,
                    "active_vendors" => 0,
                    "inactive_vendors" => 0,
                    "total_credit_limit" => 0,
                ];
            }
        }

        public function get_vendor_purchase_summary(
            $vendor_id,
            $start_date = null,
            $end_date = null,
        ) {
            try {
                $this->db->select('
                    v.name as vendor_name,
                    COUNT(mb.id) as total_batches,
                    SUM(mb.quantity_purchased) as total_quantity,
                    SUM(mb.quantity_purchased * mb.purchase_price) as total_value,
                    AVG(mb.purchase_price) as avg_purchase_price
                ');
                $this->db->from("vendors v");
                $this->db->join("medicine_batches mb", "v.ID = mb.vendor_id");
                $this->db->where("v.id", $vendor_id);
                $this->db->where("mb.batch_status", "ACTIVE");

                if ($start_date) {
                    $this->db->where("mb.purchase_date >=", $start_date);
                }
                if ($end_date) {
                    $this->db->where("mb.purchase_date <=", $end_date);
                }

                return $this->db->get()->row();
            } catch (Exception $e) {
                return null;
            }
        }

        public function get_brand_medicine_count($brand_id)
        {
            try {
                $this->db->select("COUNT(*) as total");
                $this->db->from("medicines");
                $this->db->where("brand_id", $brand_id);
                $this->db->where("status", "active");
                $result = $this->db->get()->row();
                return $result ? $result->total : 0;
            } catch (Exception $e) {
                return 0;
            }
        }

        // ==================== PURCHASE ORDER INTEGRATION METHODS ====================

        /**
         * Get purchase orders ready for stock addition (approved/completed status)
         */
        public function get_purchase_orders_for_stock_addition()
        {
            try {
                $this->db->select("po.*, v.name as vendor_name");
                $this->db->from("purchase_orders po");
                $this->db->join(
                    $this->config->item("db_prefix") . "vendors v",
                    "po.vendor_number = v.vendor_number",
                    "left",
                );
                $this->db->where("po.status", "completed");
                $this->db->where("po.stock_added", 0); // Not yet added to stock
                $this->db->order_by("po.created_at", "DESC");
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Get purchase order items for stock addition
         */
        public function get_purchase_order_items($po_id)
        {
            try {
                $this->db->select(
                    "poi.*, m.medicine_name, m.medicine_code, m.generic_name, b.brand_name as brand_name",
                );
                $this->db->from("purchase_order_items poi");
                $this->db->join(
                    "medicines m",
                    "poi.item_number = m.medicine_code",
                    "left",
                );
                // $this->db->join(
                //     $this->config->item("db_prefix") . "brands b",
                //     "m.brand_id = b.ID",
                //     "left",
                // );
                $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                $this->db->where("poi.po_id", $po_id);
                $this->db->where("poi.quantity >", 0);
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Add stock from purchase order (main method) - Following original logic
         */
        public function add_stock_from_purchase_order($po_id, $stock_items)
        {
            try {
                $this->db->trans_start();

                $success_count = 0;
                $total_items = count($stock_items);

                foreach ($stock_items as $item) {
                    // Prepare stock data following original structure
                    $stock_data = [
                        "item_name" => $item["item_name"],
                        "company" => $item["company"],
                        "brand_name" => $item["brand_name"],
                        "generic_name" => $item["generic_name"] ?: "",
                        "vendor_number" => $item["vendor_number"],
                        "batch_number" => $item["batch_number"],
                        "quantity" =>
                            $item["quantity_received"] +
                            ($item["free_quantity"] ?: 0),
                        "price" => $item["purchase_price"],
                        "vendor_price" => $item["purchase_price"],
                        "mrp" => $item["mrp"],
                        "hsn" => $item["hsn"],
                        "pack_size" => $item["pack_size"],
                        "gstrate" => intval($item["tax_percentage"]),
                        "gstdivision" => 0,
                        "expiry" => $item["expiry_date"],
                        "expiry_day" => $item["manufacturing_date"],
                        "date_of_purchase" => $item["receipt_date"],
                        "invoice_no" => $item["invoice_number"] ?: "N/A",
                        "no_of_item" => "1",
                        "product_id" => 0,
                        "lots" => 1.0,
                        "units" =>
                            $item["quantity_received"] +
                            ($item["free_quantity"] ?: 0),
                        "safety_stock" => 0,
                        "order_qty" => 0,
                        "category" => 0,
                        "pack" => 1,
                        "type" => "medicine",
                        "medicine_type" => null,
                        "status" => 1,
                    ];

                    // Check for existing stock item (following original logic)
                    $existing_stock = $this->check_existing_stock_item(
                        $item["item_name"],
                        $item["batch_number"],
                        $item["vendor_number"],
                    );

                    if ($existing_stock) {
                        // Update existing stock quantity
                        $update_result = $this->update_stock_quantity(
                            $existing_stock["ID"],
                            $stock_data["quantity"],
                            $stock_data,
                        );
                        if ($update_result) {
                            $success_count++;
                        }
                    } else {
                        // Insert new stock item
                        $insert_result = $this->insert_stock_item($stock_data);
                        if ($insert_result) {
                            $success_count++;
                        }
                    }

                    // Create vendor billing record
                    $this->create_vendor_billing_record($po_id, $item, 0);
                }

                // Mark purchase order as stock added
                $this->db->where("id", $po_id);
                $this->db->update("purchase_orders", [
                    "stock_added" => 1,
                    "stock_added_at" => date("Y-m-d H:i:s"),
                ]);

                $this->db->trans_complete();

                return [
                    "success" => $this->db->trans_status(),
                    "success_count" => $success_count,
                    "total_items" => $total_items,
                ];
            } catch (Exception $e) {
                $this->db->trans_rollback();
                return [
                    "success" => false,
                    "error" => $e->getMessage(),
                    "success_count" => 0,
                    "total_items" => 0,
                ];
            }
        }

        /**
         * Check if stock item exists (by batch number and vendor) - Following original logic
         */
        public function check_existing_stock_item(
            $item_name,
            $batch_number,
            $vendor_number,
        ) {
            try {
                $this->db->select("*");
                $this->db->from($this->config->item("db_prefix") . "stocks");
                $this->db->where("item_name", $item_name);
                $this->db->where("batch_number", $batch_number);
                $this->db->where("vendor_number", $vendor_number);
                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    return $query->row_array();
                }
                return false;
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Update existing stock quantity - Following original logic
         */
        public function update_stock_quantity(
            $stock_id,
            $quantity,
            $stock_data = [],
        ) {
            try {
                $sql =
                    "UPDATE `" .
                    $this->config->item("db_prefix") .
                    "stocks` SET `quantity` = `quantity` + {$quantity}";
                foreach ($stock_data as $key => $value) {
                    if (
                        $key != "quantity" &&
                        $key != "add_date" &&
                        $key != "status"
                    ) {
                        $sql .= ", `{$key}` = '" . addslashes($value) . "'";
                    }
                }
                $sql .= " WHERE `ID` = '{$stock_id}'";
                $this->db->query($sql);
                return $this->db->affected_rows() > 0;
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Insert new stock item - Following original logic
         */
        public function insert_stock_item($stock_data)
        {
            try {
                $sql =
                    "INSERT INTO `" .
                    $this->config->item("db_prefix") .
                    "stocks` SET ";
                $sqlArr = [];

                foreach ($stock_data as $key => $value) {
                    $sqlArr[] = " $key = '" . addslashes($value) . "'";
                }

                $date = date("Y-m-d H:i:s");
                $sqlArr[] = " add_date = '" . addslashes($date) . "'";
                $sqlArr[] = " item_number = '" . addslashes(getGUID()) . "'";

                $sql .= implode(",", $sqlArr);
                $res = $this->db->query($sql);

                if ($res) {
                    return $this->db->insert_id();
                } else {
                    return 0;
                }
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Create vendor billing record - Following original logic
         */
        private function create_vendor_billing_record($po_id, $item, $batch_id)
        {
            try {
                $billing_data = [
                    "purchase_po_no" => $po_id,
                    "po_date" => $item["receipt_date"],
                    "vendor_name" => $this->get_vendor_name($item["vendor_number"]),
                    "vendor_code" => $item["vendor_number"],
                    "order_number" => $po_id,
                    "upload_date" => date("Y-m-d H:i:s"),
                    "invoice_no" => $item["invoice_number"] ?: "N/A",
                    "brand_name" => $item["brand_name"],
                    "mrp" => floatval($item["mrp"]),
                    "hsn" => $item["hsn"],
                    "category" => $item["company"],
                    "date_of_purchase" => $item["receipt_date"],
                    "batch_number" => $item["batch_number"],
                    "centre_location" => "Central",
                    "received_by" => $item["received_by"],
                    "date_of_receiving" => $item["receipt_date"],
                    "item_number" => $item["item_number"],
                    "item_name" => $item["item_name"],
                    "company" => $item["company"],
                    "quantity" => $item["quantity_received"],
                    "expiry" => $item["expiry_date"],
                    "vendor_price" => $item["purchase_price"],
                    "gstrate" => floatval($item["tax_percentage"]),
                    "discount_amt" => $item["discount_amount"] ?: 0,
                    "free_quantity" => $item["free_quantity"] ?: 0,
                    "total_purchase_value_excl_gst" =>
                        $item["quantity_received"] * $item["purchase_price"],
                    "freight_forwarding_charges" => 0,
                    "comment" => $item["comments"] ?: "",
                    "vendor_billing" => "",
                    "rate_per_unit" => $item["purchase_price"],
                    "total_purchase_after_discount_exculding_gst" =>
                        $item["quantity_received"] * $item["purchase_price"],
                    "total_purchase_value_incl_gst" =>
                        $item["quantity_received"] *
                        $item["purchase_price"] *
                        (1 + floatval($item["tax_percentage"]) / 100),
                    "monetary_value" => "INR",
                    "discount_rate" => "0",
                    "entry_date_in_tally" => null,
                    "msme_applicability" => "No",
                    "medicine_type" => null,
                ];

                return $this->db->insert("vendor_billing", $billing_data);
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Helper method to get vendor name - Following original logic
         */
        private function get_vendor_name($vendor_number)
        {
            try {
                if ($vendor_number == "Cash Purchase") {
                    return "Cash Purchase";
                }

                $this->db->select("name");
                $this->db->from($this->config->item("db_prefix") . "vendors");
                $this->db->where("vendor_number", $vendor_number);
                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    return $query->row()->name;
                }
                return $vendor_number;
            } catch (Exception $e) {
                return $vendor_number;
            }
        }

        /**
         * Get purchase order details for stock addition
         */
        public function get_purchase_order_for_stock_addition($po_id)
        {
            try {
                $this->db->select("po.*, v.name as vendor_name");
                $this->db->from("purchase_orders po");
                $this->db->join(
                    $this->config->item("db_prefix") . "vendors v",
                    "po.vendor_number = v.vendor_number",
                    "left",
                );
                $this->db->where("po.id", $po_id);
                $this->db->where("po.status", "completed");
                return $this->db->get()->row();
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Check if purchase order items exist
         */
        public function check_purchase_order_items_exist($po_id)
        {
            try {
                $this->db->where("po_id", $po_id);
                $this->db->where("quantity >", 0);
                $count = $this->db->count_all_results("purchase_order_items");
                return $count > 0;
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Get processed purchase orders (for history)
         */
        public function get_processed_purchase_orders()
        {
            try {
                $this->db->select("po.*, v.name as vendor_name");
                $this->db->from("purchase_orders po");
                $this->db->join(
                    $this->config->item("db_prefix") . "vendors v",
                    "po.vendor_number = v.vendor_number",
                    "left",
                );
                $this->db->where("po.stock_added", 1);
                $this->db->order_by("po.stock_added_at", "DESC");
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

        public function get_available_stocks_for_transfer(
            $transfer_type,
            $from_center_id = null,
            $from_department = null,
            $from_employee_number = null,
        ) {
            try {
                if ($transfer_type == "CENTRAL_TO_CENTER") {
                    $this->db->select('
                        mb.id as batch_id,
                        mb.batch_number,
                        mb.expiry_date,
                        cs.quantity as quantity_remaining,
                        mb.batch_status,
                        mb.purchase_price,
                        mb.selling_price,
                        DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                        m.id as medicine_id,
                        m.medicine_name,
                        m.medicine_code,
                        b.brand_name as brand_name,
                        v.name as vendor_name,
                        "CENTRAL" as center_name,
                        CASE
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                            ELSE "FRESH"
                        END as expiry_status
                    ');
                    $this->db->from("medicine_batches mb");
                    $this->db->join("central_stocks cs", "mb.id = cs.batch_id");
                    $this->db->join("medicines m", "mb.medicine_id = m.id");
                    $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                    $this->db->join(
                        $this->config->item("db_prefix") . "vendors v",
                        "mb.vendor_id = v.ID",
                        "left",
                    );
                    // Only show available central stocks
                    $this->db->where("mb.batch_status", "ACTIVE");
                    $this->db->where("m.status", "active");
                    $this->db->where("cs.quantity >", 0);
                    $this->db->where("cs.status", "ACTIVE");
                    // $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                    // $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                    // --- FIX: Do not show expired stock ---
                    $this->db->where("mb.expiry_date >", date("Y-m-d"));
                } elseif ($transfer_type == "CENTER_TO_CENTER") {
                    // Center stocks - join with center_stocks table
                    $this->db->select('
                        mb.id as batch_id,
                        mb.batch_number,
                        mb.expiry_date,
                        ccs.quantity as quantity_remaining,
                        mb.batch_status,
                        mb.purchase_price,
                        mb.selling_price,
                        DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                        m.id as medicine_id,
                        m.medicine_name,
                        m.medicine_code,
                        b.brand_name as brand_name,
                        v.name as vendor_name,
                        c.center_name,
                        CASE
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                            ELSE "FRESH"
                        END as expiry_status
                    ');
                    $this->db->from("medicine_batches mb");
                    $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id");
                    $this->db->join("medicines m", "mb.medicine_id = m.id");
                    $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                    $this->db->join(
                        $this->config->item("db_prefix") . "vendors v",
                        "mb.vendor_id = v.ID",
                        "left",
                    );
                    $this->db->join(
                        "hms_centers c",
                        "ccs.center_id = c.ID",
                        "left",
                    );
                    // Filter by source center
                    if ($from_center_id) {
                        $this->db->where("ccs.center_id", $from_center_id);
                    }
                    // Filter by source department
                    if ($from_department) {
                        $this->db->where("ccs.department", $from_department);
                    }
                    // Only show available center stocks
                    $this->db->where("mb.batch_status", "ACTIVE");
                    $this->db->where("m.status", "active");
                    // $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                    // $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                    $this->db->where("ccs.quantity >", 0);
                    $this->db->where("ccs.status", "ACTIVE");
                    // --- FIX: Do not show expired stock ---
                    $this->db->where("mb.expiry_date >", date("Y-m-d"));
                } elseif ($transfer_type == "CENTER_TO_CENTRAL") {
                    // Center stocks to return to central - join with center_stocks table
                    $this->db->select('
                        mb.id as batch_id,
                        mb.batch_number,
                        mb.expiry_date,
                        ccs.quantity as quantity_remaining,
                        mb.batch_status,
                        mb.purchase_price,
                        mb.selling_price,
                        DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                        m.id as medicine_id,
                        m.medicine_name,
                        m.medicine_code,
                        b.brand_name as brand_name,
                        v.name as vendor_name,
                        c.center_name,
                        CASE
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                            WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                            ELSE "FRESH"
                        END as expiry_status
                    ');
                    $this->db->from("medicine_batches mb");
                    $this->db->join("center_stocks ccs", "mb.id = ccs.batch_id");
                    $this->db->join("medicines m", "mb.medicine_id = m.id");
                    $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
                    $this->db->join(
                        $this->config->item("db_prefix") . "vendors v",
                        "mb.vendor_id = v.ID",
                        "left",
                    );
                    $this->db->join(
                        "hms_centers c",
                        "ccs.center_id = c.ID",
                        "left",
                    );
                    // Filter by source center
                    if ($from_center_id) {
                        $this->db->where("ccs.center_id", $from_center_id);
                    }
                    // Only show available center stocks
                    $this->db->where("mb.batch_status", "ACTIVE");
                    $this->db->where("m.status", "active");
                    $this->db->where("ccs.quantity >", 0);
                    $this->db->where("ccs.status", "ACTIVE");
                    // $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                    // $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                    // --- FIX: Do not show expired stock ---
                    $this->db->where("mb.expiry_date >", date("Y-m-d"));
                }
                // Order by FEFO (First Expiry, First Out)
                $this->db->order_by("mb.expiry_date", "ASC");
                $this->db->order_by("m.medicine_name", "ASC");
                return $this->db->get()->result();
            } catch (Exception $e) {
                log_message('error', 'get_available_stocks_for_transfer: ' . $e->getMessage());
                return [];
            }
        }
    
        public function get_all_stock_batches()
        {
        try {
                $this->db->select([
                    'cs.batch_id',
                    'cs.center_id',
                    'c.center_name',
                    'cs.quantity as available_quantity', // Stock at the specific center
                    'm.medicine_name',
                    'mb.batch_number',
                    'mb.purchase_price' // Needed for disposal/vendor return value
                ]);
                $this->db->from('center_stocks cs');
                $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id');
                $this->db->join('medicines m', 'mb.medicine_id = m.id');
                $this->db->join('hms_centers c', 'cs.center_id = c.ID');
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'"); 
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $this->db->where('cs.quantity >', 0); // Only batches with stock
                $this->db->where('mb.batch_status', 'ACTIVE'); // Only active batches
                $this->db->order_by('c.center_name', 'ASC');
                $this->db->order_by('m.medicine_name', 'ASC');
                return $this->db->get()->result();
            } catch (Exception $e) {
                return [];
            }
        }

    public function get_vendor_return_by_id($id)
    {
        try {
            $this->db->select([
                'vr.*', // Select all columns from vendor_returns
                'v.name as vendor_name',
                'c.center_name',
                'e.name as created_by_name'
            ]);
            $this->db->from('vendor_returns vr');
            $this->db->join('hms_vendors v', 'vr.vendor_id = v.ID', 'left');
            $this->db->join('hms_centers c', 'vr.center_id = c.ID', 'left');
            $this->db->join('hms_employees e', 'vr.created_by = e.ID', 'left');
            $this->db->where('vr.id', $id);
            return $this->db->get()->row(); // Return a single row object
        } catch (Exception $e) {
            log_message('error', "Error in get_vendor_return_by_id: " . $e->getMessage());
            return null; // Return null on error
        }
    }

    /**
     * Gets the items for a vendor return by querying the stock_movements log.
     * Use this because you do not have a 'vendor_return_items' table.
     */
    public function get_vendor_return_items_from_log($return_id)
    {
        try {
            $this->db->select([
                'sm.id as movement_id',
                'sm.quantity_change', // This will be negative (e.g., -10)
                'sm.unit_price',      // This is the purchase_price used
                'sm.total_value',     // Calculated value at time of movement
                'sm.created_at as log_created_at', // Alias to avoid name conflict
                'mb.batch_number',
                'mb.expiry_date',
                'm.medicine_name',
                'm.medicine_code',
                'b.brand_name'
            ]);
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left'); // Corrected table name
            $this->db->where('sm.reference_id', $return_id);
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->where('sm.reference_type', 'RETURN_VOUCHER'); // As per your schema
            $this->db->where('sm.movement_type', 'PURCHASE_RETURN');
            $this->db->order_by('sm.created_at', 'ASC');
            return $this->db->get()->result(); // Return an array of item objects
            } catch (Exception $e) {
            log_message('error', "Error in get_vendor_return_items_from_log: " . $e->getMessage());
            return []; // Return empty array on error
        }
    } 
    /**
     * Updates the header details of a PENDING vendor return.
     * Does NOT update items, as they are already processed.
     */
    public function update_vendor_return_header($id, $data)
    {
        try {
            $this->db->where('id', $id);
            // Safety check: Only update if still PENDING
            // (Note: Controller already checks this, but it's good practice)
            $this->db->where('status', 'PENDING'); 
            
            $this->db->update('vendor_returns', $data);
            
            // Check if any rows were actually changed
            return $this->db->affected_rows() > 0;

        } catch (Exception $e) {
            log_message('error', "Error in update_vendor_return_header: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Fetches the details for a single medicine, formatted for Select2.
     * Used to pre-populate the 'add_batch' form.
     */
    public function get_medicine_details_by_id($medicine_id)
    {
        if (empty($medicine_id)) {
            return null;
        }

        $this->db->select([
            'm.id',
            'm.medicine_name as text', // Select2 uses 'text' property by default
            'm.medicine_name',
            'm.generic_name',
            'm.medicine_code',
            'b.brand_name',
            'm.gst_rate',
            'm.pack_size'
        ]);
        $this->db->from('medicines m');
        $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
        $this->db->where('m.id', $medicine_id);
        $this->db->where('m.status', 'active');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        
        return $this->db->get()->row(); // Return one row
    }
    public function is_batch_editable($batch_id)
    {
        try {
            $this->db->from('stock_movements');
            $this->db->where('batch_id', $batch_id);
            $count = $this->db->count_all_results();
            // If count is 0 (shouldn't happen) or 1 (just the PURCHASE)
            // then it is editable. If it's 2 or more, it has been used.
            return ($count <= 1);

        } catch (Exception $e) {
            log_message('error', 'Error in is_batch_editable: ' . $e->getMessage());
            return false; // Fail safe: if error, lock the batch
        }
    }
    /**
     * Searches medicines for the Select2 AJAX dropdown.
     */
    public function search_medicines_for_select2($search_term = "")
    {
        $this->db->select([
            'm.id',
            'm.gst_rate',
            'm.pack_size',
            'm.medicine_name as text', // Select2 uses 'text' property
            'm.medicine_name',
            'm.generic_name',
            'm.medicine_code',
            'b.brand_name'
        ]);
        $this->db->from('medicines m');
        $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
        $this->db->where('m.status', 'active');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        if ($search_term) {
            $this->db->group_start();
            $this->db->like('m.medicine_name', $search_term);
            $this->db->or_like('m.generic_name', $search_term);
            $this->db->or_like('m.medicine_code', $search_term);
            $this->db->group_end();
        }

        $this->db->limit(30); // Limit results for performance
        $query = $this->db->get();
        
        return $query->result(); // Return array of results
    }

    /**
     * Gets all vendors for the dropdown.
     */
    public function get_all_vendors()
    {
        // Use your simple query function
        try {
            $sql = "SELECT ID, name FROM hms_vendors WHERE status = 1 ORDER BY name ASC";
            $query = $this->db->query($sql);
            return $query->result();
        } catch (Exception $e) {
            log_message('error', 'Error fetching vendors: ' . $e->getMessage());
            return [];
        }
    }


    //    add stocks from purchase order-------------------------------------------------------------------------- 
    /**
     * Finds the Primary Key (ID) of a vendor based on their vendor_number.
     */
    public function get_vendor_id_by_number($vendor_number)
    {
        $query = $this->db->select('ID')
                          ->where('vendor_number', $vendor_number)
                          ->limit(1)
                          ->get('hms_vendors');
        if ($query->num_rows() > 0) {
            return $query->row()->ID;
        }
        return false; // Vendor not found
    }

    /**
     * Checks if a center ID exists and is active.
     */
     public function center_exists($center_id) {
         $count = $this->db->where('center_number', $center_id)
                           ->where('status', 1)
                           ->count_all_results('hms_centers');
         return $count > 0;
     }
    public function receive_stock_item($item_data)
    {
        $po_item_id = $item_data['po_item_id'];
        $medicine_id = $item_data['medicine_id'];
        $batch_number = $item_data['batch_number'];
        $vendor_id = $item_data['vendor_id'];
        $center_id = $item_data['center_id'];
        $department = $item_data['department'];
        $freight_charges = $item_data['freight_charges'];
        $is_central_warehouse = isset($item_data['is_central_warehouse']) && $item_data['is_central_warehouse'] === true;
        // Also check if center_id is null/empty as fallback
        if (!$is_central_warehouse && (empty($center_id) || $center_id === 'CENTRAL_WAREHOUSE_NOIDA')) {
            $is_central_warehouse = true;
        }
        $quantity_received = $item_data['quantity'] + $item_data['free_qty']; // Total quantity
        $created_by = $item_data['created_by'];
        if (empty($medicine_id) || empty($batch_number) || empty($vendor_id)) {
            return ['status' => 'error', 'message' => 'Missing required batch data (medicine, batch no, vendor).'];
        }

        $this->db->trans_start();
        $this->db->from('medicine_batches');
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('batch_number', $batch_number);
        $existing_batch = $this->db->get()->row();
        $batch_id = null;
        $is_new_batch = false;
        if ($existing_batch) {
            // 4A. BATCH EXISTS: Get its ID.
            $batch_id = $existing_batch->id;
        } else {
            // 4B. NEW BATCH: Create it.
            $is_new_batch = true;
            $batch_data = [
                "medicine_id"        => $medicine_id,
                "vendor_id"          => $vendor_id,
                "batch_number"       => $batch_number,
                "expiry_date"        => $item_data['expiry_date'],
                "expiry_days"        => $this->calculate_expiry_days($item_data['expiry_date']),
                "purchase_price"     => $item_data['purchase_price'] * (1 + ($item_data['tax_percent'] / 100)),
                "selling_price"      => $item_data['mrp'], 
                "mrp"                => $item_data['mrp'],
                "quantity_purchased" => $quantity_received, // This is the first purchase
                "quantity_remaining" => $quantity_received, // Stock is added to batch total
                "purchase_date"      => $item_data['receive_date'],
                "invoice_number"     => $item_data['invoice_number'],
                "invoice_date"       => $item_data['invoice_date'],
                "quality_status"     => "APPROVED", // Or 'PENDING' if you have a QC step
                "batch_status"       => "ACTIVE",
                "created_by"         => $created_by,
            ];
            $this->db->insert('medicine_batches', $batch_data);
            $batch_id = $this->db->insert_id();
        }
        if (!$batch_id) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Could not create or find batch.'];
        }
        
        // 5. Determine stock location - central_stocks or center_stocks
        $quantity_before = 0;
        if ($is_central_warehouse) {
            // Save to central_stocks (no center_id, no department)
            $this->db->from('central_stocks');
            $this->db->where('batch_id', $batch_id);
            $stock_record = $this->db->get()->row();
            
            if ($stock_record) {
                // 6A. STOCK RECORD EXISTS: Update it.
                $quantity_before = $stock_record->quantity;
                $this->db->where('id', $stock_record->id);
                $this->db->set('quantity', 'quantity + ' . (float)$quantity_received, FALSE);
                $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
                $this->db->set('status', 'ACTIVE');
                $this->db->update('central_stocks');
                // Also update the main batch 'quantity_remaining'
                if (!$is_new_batch) {
                    $this->db->where('id', $batch_id);
                    $this->db->set('quantity_remaining', 'quantity_remaining + ' . (float)$quantity_received, FALSE);
                    $this->db->update('medicine_batches');
                }
            } else {
                // 6B. NEW STOCK RECORD: Insert it.
                $central_stock_data = [
                    "batch_id"  => $batch_id,
                    "quantity"  => $quantity_received,
                    "last_movement_date" => date("Y-m-d H:i:s"),
                    "status"    => "ACTIVE"
                ];
                $this->db->insert("central_stocks", $central_stock_data);
            }
            $to_location_type = "CENTRAL";
            $to_location_id = null;
        } else {
            // Save to center_stocks (with center_id and department)
            $this->db->from('center_stocks');
            $this->db->where('batch_id', $batch_id);
            $this->db->where('center_id', $center_id);
            $this->db->where('department', $department);
            $stock_record = $this->db->get()->row();
            
            /*if ($stock_record) {
                // 6A. STOCK RECORD EXISTS: Update it.
                $quantity_before = $stock_record->quantity;
                $this->db->where('id', $stock_record->id);
                $this->db->set('quantity', 'quantity + ' . (float)$quantity_received, FALSE);
                $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
                $this->db->set('department', $item_data['department'] ?? null);
                $this->db->update('center_stocks');
                // Also update the main batch 'quantity_remaining'
                if (!$is_new_batch) {
                    $this->db->where('id', $batch_id);
                    $this->db->set('quantity_remaining', 'quantity_remaining + ' . (float)$quantity_received, FALSE);
                    $this->db->update('medicine_batches');
                }
            }*/




            if ($stock_record) {

                    $quantity_before = $stock_record->quantity;
                    $this->db->where('id', $stock_record->id);                    
                    $this->db->set('quantity', 'quantity + ' . (float)$quantity_received, FALSE);
                    $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
                    $this->db->set('department', $item_data['department'] ?? null);
                    $this->db->update('center_stocks');

                    // echo $this->db->last_query();
                    // exit;
                   
                    if (!$is_new_batch) {
                        $this->db->where('id', $batch_id);
                        $this->db->set('quantity_remaining', 'quantity_remaining + ' . (float)$quantity_received, FALSE);
                        $this->db->update('medicine_batches');
                    }
                } else {
                // 6B. NEW STOCK RECORD: Insert it.
                $center_stock_data = [
                    "batch_id"  => $batch_id,
                    "center_id" => $center_id,
                    "quantity"  => $quantity_received,
                    "last_movement_date" => date("Y-m-d H:i:s"),
                    "department" => $item_data['department'] ?? null,
                    "status"    => "ACTIVE"
                ];
                $this->db->insert("center_stocks", $center_stock_data);
                

            }
            $to_location_type = "CENTER";
            $to_location_id = $center_id;
        }
        
        $movement_data = [
            "batch_id"           => $batch_id,
            "movement_type"      => "PURCHASE", 
            "from_location_type" => "VENDOR",
            "from_location_id"   => $vendor_id,
            "to_location_type"   => $to_location_type,
            "to_location_id"     => $to_location_id,
            "quantity_change"    => $quantity_received,
            "quantity_before"    => $quantity_before,
            "quantity_after"     => $quantity_before + $quantity_received,
            "unit_price"         => $item_data['purchase_price'],
            "total_value"        => $item_data['total_amount'],
            "reference_type"     => "PURCHASE_ORDER",
            "reference_id"       => $item_data['po_id'],
            "reference_number"   => $item_data['po_number'],
            "remarks"            => $item_data['remarks'],
            "created_by"         => $created_by,
            "uploaded_files"     => $item_data['uploaded_files'],
            "receive_by"         => $item_data['receive_by'],
            "receive_date"       => $item_data['receive_date'],
            "receipt_number"     => $item_data['receipt_number']
        ];
        $this->db->insert("stock_movements", $movement_data);

        //echo $this->db->last_query();
        //exit;

        $this->db->select('quantity_received, pack_size');
        $this->db->from('hms_new_purchase_order_items');
        $this->db->where('id', $item_data['po_item_id']);
        $row = $this->db->get()->row_array();
        $old_qty = $row['quantity_received'];
        $pack_size = $row['pack_size'];
        $total_qty = $old_qty + ($quantity_received/$pack_size);
        // $new_quantity = ($pack_size > 0) ? ($total_qty / $pack_size) : 0;
        $update = [
            'quantity_received' => $total_qty,
            'freight_charges' => $freight_charges
        ];
        $this->db->where('id', $item_data['po_item_id']);
        $this->db->update('hms_new_purchase_order_items', $update);

        // ***********************************************
        // 8. Complete the transaction
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'DB Transaction failed while receiving stock for batch ID: ' . $batch_id);
            return ['status' => 'error', 'message' => 'Database transaction failed.'];
        } else {
            return ['status' => 'success', 'batch_id' => $batch_id, 'new_batch' => $is_new_batch];
        }
    }

    // public function add_purchase_batch($batch_data)
    // {
    //         if (empty($batch_data['medicine_id']) || empty($batch_data['batch_number']) || empty($batch_data['vendor_id'])) {
    //             return ['status' => 'error', 'message' => 'Missing required batch data (medicine, batch no, vendor).'];
    //         }
    //         $this->db->insert('medicine_batches', $batch_data);

    //         $new_batch_id = $this->db->insert_id();

    //         if ($new_batch_id) {
    //             return ['status' => 'success', 'batch_id' => $new_batch_id];
    //         } else {
    //             $db_error = $this->db->error();
    //             if ($db_error['code'] == 1062) {
    //                 log_message('error', "Duplicate batch entry attempt: Medicine ID {$batch_data['medicine_id']}, Batch No {$batch_data['batch_number']}");
    //                 return ['status' => 'error', 'message' => 'Duplicate batch number for this medicine.'];
    //             } else {
    //                 log_message('error', "DB Error (medicine_batches insert): ".$db_error['message']);
    //                 return ['status' => 'error', 'message' => 'Database error inserting batch.'];
    //             }
    //         }
    // }

    /**
     * Adds received quantity to the stock at a specific location (Center or Central).
     * Uses INSERT...ON DUPLICATE KEY UPDATE.
     * $stock_data should contain 'batch_id', 'quantity', and 'center_id' (or indicate central)
     */
    // public function add_stock_to_location($stock_data)
    // {
    //         $is_central = empty($stock_data['center_id']); 
    //         $table_name = $is_central ? 'central_stocks' : 'center_stocks';
    //         $location_id = $stock_data['center_id'] ?? null; 
    //         if (empty($stock_data['batch_id']) || !isset($stock_data['quantity'])) {
    //              return ['status' => 'error', 'message' => 'Missing batch_id or quantity for stock update.'];
    //         }
    //         $this->db->select('quantity');
    //         $this->db->where('batch_id', $stock_data['batch_id']);
    //         if (!$is_central) {
    //              $this->db->where('center_id', $location_id);
    //         }
    //         $q_before = $this->db->get($table_name)->row();
    //         $quantity_before = ($q_before) ? (int)$q_before->quantity : 0;
    //         $quantity_after = $quantity_before + (int)$stock_data['quantity'];
    //         // Build the SQL for INSERT...ON DUPLICATE KEY UPDATE
    //         $sql = "INSERT INTO {$table_name} (batch_id, ";
    //         $sql .= $is_central ? "" : "center_id, ";
    //         $sql .= "quantity,department, status, last_movement_date, created_at, updated_at) VALUES (?, ";
    //         $params = [$stock_data['batch_id']];
    //         if (!$is_central) {
    //             $sql .= "?, "; // Placeholder for center_id
    //             $params[] = $location_id;
    //         }
    //         $sql .= "?, ?, NOW(), NOW(), NOW()) "; // Placeholders for quantity, status
    //         $params[] = $stock_data['quantity'];
    //         $params[] = $stock_data['department'] ?? 'GENERAL';
    //         $params[] = $stock_data['status'] ?? 'ACTIVE';

    //         $sql .= "ON DUPLICATE KEY UPDATE ";
    //         $sql .= "quantity = quantity + ?, ";
    //         $sql .= "status = VALUES(status), "; // Update status if provided in INSERT part
    //         $sql .= "last_movement_date = NOW(), ";
    //         $sql .= "updated_at = NOW()";
    //         $params[] = $stock_data['quantity']; // Quantity for the UPDATE part
    //         // Execute the query
    //         $this->db->query($sql, $params);
    //         // Check for errors after query execution
    //         $db_error = $this->db->error();
    //         if ($db_error['code'] != 0) {
    //              log_message('error', "DB Error ({$table_name} insert/update): ".$db_error['message']);
    //              return ['status' => 'error', 'message' => "Database error updating stock in {$table_name}."];
    //         } else {
    //              return ['status' => 'success', 'quantity_after' => $quantity_after];
    //         }
    // }

public function add_stock_to_location($stock_data)
    {
        // try {
            // Check for required data
            if (empty($stock_data['batch_id']) || !isset($stock_data['quantity'])) {
                return ['status' => 'error', 'message' => 'Missing batch_id or quantity for stock update.'];
            }
            // Determine location type and ID
            $is_central = empty($stock_data['center_id']); 
            $table_name = $is_central ? 'central_stocks' : 'center_stocks';
            $location_id = $stock_data['center_id'] ?? null; 
            // Get quantity before the update for logging
            $this->db->select('quantity');
            $this->db->where('batch_id', $stock_data['batch_id']);
            if (!$is_central) {
                 $this->db->where('center_id', $location_id);
            }
            $q_before = $this->db->get($table_name)->row();
            $quantity_before = ($q_before) ? (int)$q_before->quantity : 0;
            $quantity_after = $quantity_before + (int)$stock_data['quantity'];
            // --- Prepare SQL and Parameters based on location ---
            $sql = "";
            $params = [];
            $quantity_to_add = (int)$stock_data['quantity'];
            $status = $stock_data['status'] ?? 'ACTIVE';
            if ($is_central) {
                // --- Logic for Central Stocks (No Department) ---
                $sql = "INSERT INTO central_stocks 
                            (batch_id, quantity, status, last_movement_date, created_at, updated_at) 
                        VALUES (?, ?, ?, NOW(), NOW(), NOW())
                        ON DUPLICATE KEY UPDATE
                            quantity = quantity + ?,
                            status = VALUES(status),
                            last_movement_date = NOW(),
                            updated_at = NOW()";
                
                $params = [
                    $stock_data['batch_id'], $quantity_to_add, $status, // For INSERT
                    $quantity_to_add // For UPDATE
                ];

            } else {
                // --- Logic for Center Stocks (With Department) ---
                $department = $stock_data['department'] ?? 'GENERAL'; // Default to 'GENERAL' if not provided
                $sql = "INSERT INTO center_stocks 
                            (batch_id, center_id, department, quantity, status, last_movement_date, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW(), NOW())
                        ON DUPLICATE KEY UPDATE
                            quantity = quantity + ?,
                            department = VALUES(department), -- Update department on duplicate
                            status = VALUES(status),
                            last_movement_date = NOW(),
                            updated_at = NOW()";
                
                $params = [
                    $stock_data['batch_id'], $location_id, $department, $quantity_to_add, $status, // For INSERT
                    $quantity_to_add // For UPDATE
                ];
            }
            // --- End Correction ---
            // Execute the query
            $this->db->query($sql, $params);
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                 log_message('error', "DB Error ({$table_name} insert/update): ".$db_error['message']);
                 return ['status' => 'error', 'message' => "Database error updating stock in {$table_name}."];
            } else {
                 return ['status' => 'success', 'quantity_after' => $quantity_after];
            }

        // } catch (Exception $e) {
        //      log_message('error', "Exception in add_stock_to_location: " . $e->getMessage());
        //      return ['status' => 'error', 'message' => 'Exception occurred updating stock location.'];
        // }
    }
    /**
     * Logs a stock movement in the stock_movements table.
     */
    public function log_stock_movement($movement_data)
    {
        try {
            // Basic validation
            if (empty($movement_data['batch_id']) || empty($movement_data['movement_type']) || !isset($movement_data['quantity_change'])) {
                log_message('error', 'Attempted to log stock movement with missing required data.');
                return false;
            }
            // Ensure created_at is set if not provided
            $movement_data['created_at'] = $movement_data['created_at'] ?? date("Y-m-d H:i:s");
            $this->db->insert('stock_movements', $movement_data);
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                 log_message('error', "DB Error (stock_movements insert): ".$db_error['message']);
                 return false;
            }
            return $this->db->insert_id() > 0; // Return true on success

        } catch (Exception $e) {
             log_message('error', "Exception in log_stock_movement: " . $e->getMessage());
             return false;
        }
    }
    
    // batch dispose  functions------------------------------------------------------------ 
    /**
     * Gets a single batch's details AND all locations (centers + central) where it has stock.
     */
    public function get_batch_details_by_id($id)
    {
        try {
            $this->db->select('
                mb.*, 
                m.medicine_name, 
                m.medicine_code,
                m.gst_rate,
                b.brand_name as brand_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where('mb.id', $id);
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $query = $this->db->get();
            return $query->row();

        } catch (Exception $e) {
            log_message('error', 'Error in get_batch_details_by_id: ' . $e->getMessage());
            return null;
        }
    }
    public function get_batch_stock_locations($batch_id)
    {
        $batch_details = $this->db->select('mb.id as batch_id, mb.batch_number, mb.expiry_date, mb.purchase_price, m.medicine_name')
                                  ->from('medicine_batches mb')
                                  ->join('medicines m', 'mb.medicine_id = m.id', 'left')
                                  ->where("m.medicine_code NOT LIKE 'HK_%'")
                                  ->where("m.medicine_code NOT LIKE 'ST_%'")
                                  ->where('mb.id', $batch_id)
                                  ->get()->row_array();
        if (!$batch_details) return null;

        $locations = [];
        // 1. Get Center Stocks
        $center_stocks = $this->db->select('cs.quantity, c.ID as center_id, c.center_name')
                                ->from('center_stocks cs')
                                ->join('hms_centers c', 'cs.center_id = c.ID', 'inner')
                                ->where('cs.batch_id', $batch_id)
                                ->where('cs.quantity >', 0)
                                ->get()->result_array();
        
        foreach ($center_stocks as $stock) {
            $locations[] = [
                'type' => 'CENTER',
                'id' => $stock['center_id'],
                'name' => $stock['center_name'],
                'quantity' => $stock['quantity']
            ];
        }

        // 2. Get Central Stock
        $central_stock = $this->db->select('quantity')
                                ->from('central_stocks')
                                ->where('batch_id', $batch_id)
                                ->where('quantity >', 0)
                                ->get()->row_array();
        if ($central_stock) {
            $locations[] = [
                'type' => 'CENTRAL',
                'id' => 0, // Use 0 or null as the ID for Central
                'name' => 'Central Warehouse',
                'quantity' => $central_stock['quantity']
            ];
        }
        
        $batch_details['locations'] = $locations;
        return $batch_details;
    }

    /**
     * Processes the disposal of a single batch from a single location.
     * Creates a disposal_report, updates stock, and logs the movement.
     */
    public function process_single_batch_disposal($data)
    {
        // Data contains: batch_id, location_type, center_id, quantity_disposed, disposal_type, etc.
        $this->db->trans_start();
        // 1. Get Batch Cost
        $batch_info = $this->db->select('purchase_price')->get_where('medicine_batches', ['id' => $data['batch_id']])->row();
        if (!$batch_info) {
             $this->db->trans_rollback();
             return ['status' => 'error', 'message' => 'Batch details not found.'];
        }
        $unit_cost = (float)$batch_info->purchase_price;
        $total_cost = $unit_cost * (int)$data['quantity_disposed'];

        // 2. Create Disposal Header
        $disposal_header = [
            'disposal_number' => "DISP" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT),
            'center_id' => $data['location_type'] == 'CENTER' ? $data['center_id'] : 0, // Use 0 or a main WH ID
            'disposal_date' => $data['disposal_date'],
            'disposal_type' => $data['disposal_type'], // 'EXPIRED', 'DAMAGED', etc.
            'disposal_method' => $data['disposal_method'] ?? 'OTHER',
            'disposal_company' => $data['disposal_company'],
            'authorized_by' => $data['authorized_by'],
            'total_items' => 1, // Only one batch
            'total_cost' => $total_cost,
            'status' => 'COMPLETED',
            'remarks' => $data['remarks'],
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        // Filter out keys that don't exist in the table (like 'disposal_reason' if table has 'disposal_type')
        $allowed_header_fields = ['disposal_number', 'center_id', 'disposal_date', 'disposal_type', 'disposal_method', 'disposal_certificate', 'disposal_company', 'authorized_by', 'total_items', 'total_cost', 'status', 'remarks', 'created_by', 'created_at'];
        $filtered_header_data = array_intersect_key($disposal_header, array_flip($allowed_header_fields));

        $this->db->insert('disposal_reports', $filtered_header_data);
        $disposal_id = $this->db->insert_id();
        if (!$disposal_id) { $this->db->trans_rollback(); return ['status' => 'error', 'message' => 'Failed to create disposal report header.']; }
        // 3. Decrease Stock (Central or Center)
        $quantity_before = 0;
        $actual_disposed_qty = 0;
        
        if ($data['location_type'] == 'CENTRAL') {
            $stock_loc = $this->db->select('quantity')->from('central_stocks')->where('batch_id', $data['batch_id'])->get()->row();
            $quantity_before = ($stock_loc) ? (int)$stock_loc->quantity : 0;
            $actual_disposed_qty = min((int)$data['quantity_disposed'], $quantity_before);
            if ($actual_disposed_qty <= 0) { $this->db->trans_rollback(); return ['status' => 'error', 'message' => 'No stock found in Central Warehouse.']; }

            $this->db->set('quantity', 'GREATEST(0, quantity - ' . $actual_disposed_qty . ')', FALSE);
            $this->db->where('batch_id', $data['batch_id']);
            $this->db->update('central_stocks');
        } else { // 'CENTER'
            $stock_loc = $this->db->select('quantity')->from('center_stocks')->where('batch_id', $data['batch_id'])->where('center_id', $data['center_id'])->get()->row();
            $quantity_before = ($stock_loc) ? (int)$stock_loc->quantity : 0;
            $actual_disposed_qty = min((int)$data['quantity_disposed'], $quantity_before);
            if ($actual_disposed_qty <= 0) { $this->db->trans_rollback(); return ['status' => 'error', 'message' => 'No stock found at the selected center.']; }

            $this->db->set('quantity', 'GREATEST(0, quantity - ' . $actual_disposed_qty . ')', FALSE);
            $this->db->where('batch_id', $data['batch_id']);
            $this->db->where('center_id', $data['center_id']);
            $this->db->update('center_stocks');
        }
        $quantity_after = $quantity_before - $actual_disposed_qty;
        // 4. Decrease Master Batch Stock
        $this->db->set('quantity_remaining', 'GREATEST(0, quantity_remaining - ' . $actual_disposed_qty . ')', FALSE);
        $this->db->where('id', $data['batch_id']);
        $this->db->update('medicine_batches');
        // 5. Log Movement
        $movement_data = [
            'batch_id' => $data['batch_id'],
            'movement_type' => 'DISPOSAL',
            'from_location_type' => $data['location_type'], // 'CENTRAL' or 'CENTER'
            'from_location_id' => $data['center_id'], // Will be null for 'CENTRAL'
            'to_location_type' => 'WASTAGE',
            'quantity_before' => $quantity_before,
            'quantity_change' => -$actual_disposed_qty,
            'quantity_after' => $quantity_after,
            'unit_price' => $unit_cost,
            'total_value' => $unit_cost * $actual_disposed_qty,
            'reference_type' => 'DISPOSAL_VOUCHER',
            'reference_id' => $disposal_id,
            'reference_number' => $disposal_header['disposal_number'],
            'remarks' => $data['remarks'],
            'created_by' => $data['created_by']
        ];
        $this->db->insert('stock_movements', $movement_data);

        // 6. Commit
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Database transaction failed.'];
        }
        return ['status' => 'success'];
    }
        /**
     * FEFO ANALYTICS: Gets the total value of wasted stock (Disposed, Expired, Damaged)
     * grouped by month for the last X months.
     */
    public function get_wastage_by_month($limit_months = 12)
    {
        try {
            $this->db->select([
                "DATE_FORMAT(created_at, '%Y-%m') as month",
                "SUM(total_value) as wasted_value"
            ]);
            $this->db->from('stock_movements');
            $this->db->where_in('movement_type', ['DISPOSAL', 'EXPIRED', 'DAMAGED']);
            $this->db->where('created_at >=', date('Y-m-01', strtotime("-$limit_months months")));
            $this->db->group_by('month');
            $this->db->order_by('month', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_wastage_by_month: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * FEFO ANALYTICS: Gets "At-Risk Stock"
     * (stock that hasn't been sold in X days and is still in inventory).
     */
    public function get_at_risk_stock($days_not_sold = 90)
    {
         try {
            // Find batches that HAVE been sold in the last X days
            $sub_query = $this->db->select('DISTINCT(batch_id)')
                                  ->from('sale_items')
                                  ->join('sales', 'sale_items.sale_id = sales.id')
                                  ->where('sales.sale_date >=', date('Y-m-d', strtotime("-$days_not_sold days")))
                                  ->get_compiled_select();

            // Find stock from batches that are NOT IN the list above
            $this->db->select([
                'm.medicine_name', 'mb.batch_number', 'c.center_name', 'cs.quantity as stock_on_hand',
                'mb.expiry_date', 'DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry'
            ]);
            $this->db->from('center_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->join('hms_centers c', 'cs.center_id = c.ID', 'inner');
            $this->db->where('cs.quantity >', 0);
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->where("mb.id NOT IN ($sub_query)", NULL, FALSE); // Main filter
            $this->db->order_by('mb.expiry_date', 'ASC'); // Show oldest stock first
            $this->db->limit(50); // Limit results
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_at_risk_stock: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * INVENTORY ANALYTICS: Gets the total stock value (at purchase cost)
     * grouped by center.
     */
    public function get_center_stock_distribution()
    {
        try {
            $this->db->select([
                'c.center_name',
                'SUM(cs.quantity * mb.purchase_price) as total_stock_value',
                'SUM(cs.quantity) as total_units'
            ]);
            $this->db->from('center_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
            $this->db->join('hms_centers c', 'cs.center_id = c.ID', 'inner');
            $this->db->where('cs.quantity >', 0);
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->group_by('c.ID, c.center_name');
            $this->db->order_by('total_stock_value', 'DESC');
            
            // You can UNION this with `central_stocks` if needed
            
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_center_stock_distribution: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * INVENTORY ANALYTICS: Gets the top X best-selling medicines by revenue.
     */
    public function get_top_performing_medicines($limit = 10)
    {
        try {
            $this->db->select([
                'm.medicine_name', 'b.brand_name',
                'SUM(si.quantity_sold) as total_units_sold',
                'SUM(si.total) as total_revenue'
            ]);
            $this->db->from('sale_items si');
            $this->db->join('sales s', 'si.sale_id = s.id', 'inner');
            $this->db->join('medicine_batches mb', 'si.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
            $this->db->where('s.status', 'CONFIRMED');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->group_by('m.id, m.medicine_name, b.brand_name');
            $this->db->order_by('total_revenue', 'DESC');
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_top_performing_medicines: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * INVENTORY ANALYTICS: Gets a summary of performance by vendor.
     */
    public function get_vendor_performance()
    {
        try {
            // This is a complex query to summarize purchases, returns, and wastage by vendor
            $sql = "
                SELECT
                    v.name as vendor_name,
                    v.vendor_number,
                    COUNT(DISTINCT mb.id) as total_batches_supplied,
                    SUM(COALESCE(purchases.purchase_value, 0)) as total_purchase_value,
                    SUM(COALESCE(returns.return_count, 0)) as total_returns,
                    SUM(COALESCE(wastage.wasted_value, 0)) as total_wasted_value
                FROM hms_vendors v
                LEFT JOIN medicine_batches mb ON v.ID = mb.vendor_id
                LEFT JOIN (
                    -- Subquery for total purchase value
                    SELECT batch_id, SUM(total_value) as purchase_value
                    FROM stock_movements
                    WHERE movement_type = 'PURCHASE'
                    GROUP BY batch_id
                ) as purchases ON mb.id = purchases.batch_id
                LEFT JOIN (
                    -- Subquery for return counts
                    SELECT batch_id, COUNT(id) as return_count
                    FROM stock_movements
                    WHERE movement_type = 'PURCHASE_RETURN'
                    GROUP BY batch_id
                ) as returns ON mb.id = returns.batch_id
                LEFT JOIN (
                    -- Subquery for wasted value
                    SELECT batch_id, SUM(total_value) as wasted_value
                    FROM stock_movements
                    WHERE movement_type IN ('DISPOSAL', 'EXPIRED', 'DAMAGED')
                    GROUP BY batch_id
                ) as wastage ON mb.id = wastage.batch_id
                WHERE v.status = 1
                GROUP BY v.ID, v.name, v.vendor_number
                ORDER BY total_purchase_value DESC
            ";
            return $this->db->query($sql)->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_vendor_performance: ' . $e->getMessage());
            return [];
        }
    }

/**
     * Gets all items for a specific sale, joined with medicine details.
     * Used for the "Edit Sale" page and the "Print Sale Bill" page.
     */
    public function get_sale_items_details($sale_id)
    {
        try {
            $this->db->select([
                'si.*', // Selects all columns from sale_items (id, sale_id, batch_id, quantity_sold, unit_price, subtotal, discount_amount, tax_amount, total)
                'm.medicine_name',
                'm.medicine_code',
                'm.gst_rate',
                'm.hsn_code',      // Added HSN code for the print view
                'b.brand_name',    // From medicine_brands
                'mb.batch_number',
                'mb.expiry_date'
            ]);
            $this->db->from('sale_items si');
            $this->db->join('medicine_batches mb', 'si.batch_id = mb.id', 'left');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left'); // Correct join
            $this->db->where('si.sale_id', $sale_id);
            
            return $this->db->get()->result();

        } catch (Exception $e) {
            log_message('error', 'Error in get_sale_items_details: ' . $e->getMessage());
            return []; // Return an empty array on error
        }
    } 
 /**
     * Checks if a batch_number is unique for a specific medicine_id.
     * Returns TRUE if it is unique (does not exist).
     * Returns FALSE if it is a duplicate (already exists).
     */
    public function is_batch_unique($medicine_id, $batch_number)
    {
        try {
            $this->db->from('medicine_batches');
            $this->db->where('medicine_id', $medicine_id);
            $this->db->where('batch_number', $batch_number);
            
            $count = $this->db->count_all_results(); // Counts matching rows

            return $count == 0; // If count is 0, it's unique (TRUE). If 1 or more, it's a duplicate (FALSE).

        } catch (Exception $e) {
            log_message('error', 'Error in is_batch_unique: ' . $e->getMessage());
            return false; // Fail safe, assumes duplicate on error
        }
    }

    public function change_payment_status($sale_id, $new_status, $remark = null, $utr_transaction_id = null, $payment_image_path = null, $approved_by = null, $approved_by_name = null)
    {
        // Data to update in an associative array
        $data = [
            'payment_status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Add remark if provided
        if (!empty($remark)) {
            $data['remarks'] = $remark;
        }
        
        // Add UTR/Transaction ID if provided
        if (!empty($utr_transaction_id)) {
            $data['utr_transaction_id'] = $utr_transaction_id;
        }
        
        // Add payment image path if provided
        if (!empty($payment_image_path)) {
            $data['payment_image'] = $payment_image_path;
        }
        
        // If payment is APPROVED (PAID), record who approved it and when
        if ($new_status == 'PAID' && !empty($approved_by)) {
            $data['payment_approved_by'] = $approved_by;
            $data['payment_approved_by_name'] = $approved_by_name;
            $data['payment_approved_at'] = date('Y-m-d H:i:s');
        }
        
        // If payment is REJECTED, record who rejected it
        if ($new_status == 'REJECTED' && !empty($approved_by)) {
            $data['payment_rejected_by'] = $approved_by;
            $data['payment_rejected_by_name'] = $approved_by_name;
            $data['payment_rejected_at'] = date('Y-m-d H:i:s');
        }
        
        // Specify which sale to update (I assume your table is 'sales')
        $this->db->where('id', $sale_id);
        // Run the UPDATE query on the 'sales' table
        $this->db->update('sales', $data);
        // Check if any rows were actually changed
        // This returns true if the update was successful, and false if not.
        return $this->db->affected_rows() > 0;
    }

    /**
     * Update Payment Method for a sale
     * @param int $sale_id The sale ID to update
     * @param string $payment_method The new payment method
     * @param int $updated_by Employee ID who updated it
     * @param string $updated_by_name Name of the employee who updated it
     * @return bool True if update was successful, false otherwise
     */
    public function update_payment_method($sale_id, $payment_method, $updated_by = null, $updated_by_name = null)
    {
        // Data to update
        $data = [
            'payment_method' => $payment_method,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        // Add tracking info if provided
        // if (!empty($updated_by)) {
        //     $data['payment_method_updated_by'] = $updated_by ?? null;
        //     $data['payment_method_updated_by_name'] = $updated_by_name;
        //     $data['payment_method_updated_at'] = date('Y-m-d H:i:s');
        // }

        // Update the sale record
        $this->db->where('id', $sale_id);
        $this->db->update('sales', $data);

        // Return true if any rows were affected
        return $this->db->affected_rows() > 0;
    }

    /**
     * Restore stock when a sale payment is CANCELLED or REJECTED
     * This returns the sold items back to inventory
     * @param int $sale_id The sale ID to restore stock for
     * @param int $restored_by Employee ID who performed the restoration
     * @return array Result with status and message
     */
    public function restore_sale_stock($sale_id, $restored_by = null)
    {
        $this->db->trans_start();
        
        // Get the sale details
        $sale = $this->get_sale_by_id($sale_id);
        if (!$sale) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Sale not found.'];
        }
        
        // Only restore stock if sale was CONFIRMED (stock was already reduced)
        if ($sale->status != 'CONFIRMED') {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Stock can only be restored for confirmed sales.'];
        }
        
        // Get sale items
        $items = $this->get_sale_items($sale_id);
        if (empty($items)) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'No items found for this sale.'];
        }
        
        // Validate restored_by - check if employee exists, otherwise use NULL
        $valid_restored_by = null;
        if ($restored_by) {
            $emp_check = $this->db->select('ID')->from('hms_employees')->where('ID', $restored_by)->get()->row();
            if ($emp_check) {
                $valid_restored_by = $restored_by;
            } else {
                // Try employee_number field
                $emp_check2 = $this->db->select('ID')->from('hms_employees')->where('employee_number', $restored_by)->get()->row();
                if ($emp_check2) {
                    $valid_restored_by = $emp_check2->ID;
                }
            }
        }
        
        // Restore stock for each item
        foreach ($items as $item) {
            // Get current stock quantity for the log
            $stock_before = $this->db->select('quantity')
                                     ->from('center_stocks')
                                     ->where('batch_id', $item->batch_id)
                                     ->where('center_id', $sale->center_id)
                                     ->get()->row();
            
            $quantity_before = $stock_before ? (int)$stock_before->quantity : 0;
            $quantity_after = $quantity_before + (int)$item->quantity_sold;
            
            // Restore center stock
            $this->db->where("batch_id", $item->batch_id);
            $this->db->where("center_id", $sale->center_id);
            $this->db->set("quantity", "quantity + " . (int)$item->quantity_sold, false);
            $this->db->set("last_movement_date", "NOW()", false);
            $this->db->update("center_stocks");
            
            // Restore master batch stock
            $this->db->where("id", $item->batch_id);
            $this->db->set("quantity_remaining", "quantity_remaining + " . (int)$item->quantity_sold, false);
            $this->db->update("medicine_batches");
            
            // Log stock movement (SALE_CANCELLED)
            $movement_data = [
                "batch_id"          => $item->batch_id,
                "movement_type"     => "SALE_CANCELLED",
                "from_location_type"=> "SALE",
                "from_location_id"  => $sale_id,
                "to_location_type"  => "CENTER",
                "to_location_id"    => $sale->center_id,
                "quantity_before"   => $quantity_before,
                "quantity_change"   => (int)$item->quantity_sold,
                "quantity_after"    => $quantity_after,
                "unit_price"        => $item->unit_price,
                "total_value"       => $item->total,
                "reference_type"    => "SALE_CANCELLED",
                "reference_id"      => $sale_id,
                "reference_number"  => $sale->sale_number,
                "patient_id"        => $sale->patient_id,
                "patient_name"      => $sale->patient_name,
                "created_by"        => $valid_restored_by,
                "created_at"        => date('Y-m-d H:i:s'),
                "remarks"           => "Stock restored due to payment cancellation/rejection"
            ];
            $this->db->insert("stock_movements", $movement_data);
        }
        
        // Update the sale status to CANCELLED
        $this->db->where("id", $sale_id);
        $this->db->update("sales", [
            "status" => "CANCELLED",
            "stock_restored" => 1,
            "stock_restored_at" => date('Y-m-d H:i:s'),
            "stock_restored_by" => $valid_restored_by,
            "updated_at" => date('Y-m-d H:i:s')
        ]);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            return ['status' => 'success', 'message' => 'Stock restored successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to restore stock.'];
        }
    }
    
    /**
     * Update accountant approval status for a sale
     * @param int $sale_id The sale ID
     * @param string $approval_status APPROVED, DISAPPROVED, or CANCELLED
     * @param int $accountant_id The accountant's employee ID
     * @param string $accountant_name The accountant's name
     * @param string $remarks Remarks for the decision
     * @return bool True if successful
     */
    public function update_accountant_approval($sale_id, $approval_status, $accountant_id, $accountant_name, $remarks = null)
    {
        // Validate accountant_id - check if employee exists
        $valid_accountant_id = null;
        if ($accountant_id) {
            $emp_check = $this->db->select('ID')->from('hms_employees')->where('ID', $accountant_id)->get()->row();
            if ($emp_check) {
                $valid_accountant_id = $accountant_id;
            } else {
                // Try employee_number field
                $emp_check2 = $this->db->select('ID')->from('hms_employees')->where('employee_number', $accountant_id)->get()->row();
                if ($emp_check2) {
                    $valid_accountant_id = $emp_check2->ID;
                }
            }
        }
        
        $data = [
            'accountant_approval_status' => $approval_status,
            'accountant_approved_by' => $valid_accountant_id,
            'accountant_approved_by_name' => $accountant_name,
            'accountant_approved_at' => date('Y-m-d H:i:s'),
            'accountant_remarks' => $remarks,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // If disapproved or cancelled, also update the main status
        if ($approval_status == 'DISAPPROVED' || $approval_status == 'CANCELLED') {
            $data['status'] = 'CANCELLED';
        }
        
        $this->db->where('id', $sale_id);
        $this->db->update('sales', $data);
        
        return $this->db->affected_rows() > 0;
    }
    /**
     * Your existing add_purchase_batch function (or add_batch)
     * Make sure it returns an array
     */
    // public function add_purchase_batch($batch_data)
    // {
    //     // try {
    //         // This function is now called AFTER validation, 
    //         // but we still keep the try/catch as a final safety net
    //         $this->db->insert('medicine_batches', $batch_data);
    //         $new_batch_id = $this->db->insert_id();

    //         if ($new_batch_id) {
    //             return ['status' => 'success', 'batch_id' => $new_batch_id];
    //         } else {
    //              return ['status' => 'error', 'message' => 'Database error inserting batch.'];
    //         }
        // } catch (Exception $e) {
        //     log_message('error', "Exception in add_purchase_batch: " . $e->getMessage());
            
        //     // Check for the unique constraint error code (1062 for MySQL)
        //     if (strpos($e->getMessage(), '1062') !== false || strpos($e->getMessage(), 'Duplicate entry') !== false) {
        //          return ['status' => 'error', 'message' => 'Duplicate batch number for this medicine.'];
        //     }
        //     return ['status' => 'error', 'message' => 'Exception occurred adding batch.'];
        // }
    // }
    // --- You also need these functions (which you already have) ---
    // public function get_employee_id_from_number($number) { ... }
    // public function add_batch($batch_data) { ... }
     // ===============================================
    // MEDICINE PACKAGE MANAGEMENT
    // ===============================================

    public function create_medicine_package($package_data, $package_items)
    {
        $this->db->trans_start();
        $total_selling_price = 0;
        $total_mrp = 0;
        foreach ($package_items as $item) {
            // 1. Modified Query: Fetch pack_size along with prices
            $latest_batch = $this->db->select('b.selling_price, b.mrp, m.pack_size')
                                    ->from('medicine_batches b')
                                    ->join('medicines m', 'm.id = b.medicine_id')
                                    ->where('b.medicine_id', $item['medicine_id'])
                                    ->order_by('b.created_at', 'DESC')
                                    ->limit(1)
                                    ->get()->row();
            if ($latest_batch && $latest_batch->selling_price > 0) {
                // Ensure pack_size is at least 1 to avoid division by zero
                $pack_size = ($latest_batch->pack_size > 0) ? $latest_batch->pack_size : 1;
                // DIVIDE BY PACK SIZE HERE
                $unit_selling_price = $latest_batch->selling_price / $pack_size;
                $unit_mrp = $latest_batch->mrp / $pack_size;
                $total_selling_price += ($unit_selling_price * $item['quantity']);
                $total_mrp += ($unit_mrp * $item['quantity']);
            } else {
                // Fallback: get from medicines table
                $medicine = $this->db->select('selling_price, mrp, pack_size')
                                    ->from('medicines')
                                    ->where('id', $item['medicine_id'])
                                    ->get()->row();
                if ($medicine && isset($medicine->selling_price)) {
                    $pack_size = ($medicine->pack_size > 0) ? $medicine->pack_size : 1;
                    // DIVIDE BY PACK SIZE HERE
                    $unit_selling_price = $medicine->selling_price / $pack_size;
                    $unit_mrp = $medicine->mrp / $pack_size;
                    $total_selling_price += ($unit_selling_price * $item['quantity']);
                    $total_mrp += ($unit_mrp * $item['quantity']);
                }
            }
        }
        // Override package prices with calculated values
        $package_data['selling_price'] = round($total_selling_price, 2);
        $package_data['mrp'] = round($total_mrp, 2);

        $this->db->insert('medicine_packages', $package_data);
        $package_id = $this->db->insert_id();

        foreach ($package_items as $item) {
            $item['package_id'] = $package_id;
            $this->db->insert('package_items', $item);
        }

        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE) ? false : $package_id;
    }

    public function get_all_packages($status = 'active')
    {
        $this->db->select('mp.*, COUNT(pi.id) as total_items');
        $this->db->from('medicine_packages mp');
        $this->db->join('package_items pi', 'mp.id = pi.package_id', 'left');
        $this->db->where('mp.status', $status);
        $this->db->group_by('mp.id');
        $this->db->order_by('mp.package_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_package_by_id($package_id)
    {
        $this->db->select('mp.*');
        $this->db->from('medicine_packages mp');
        $this->db->where('mp.id', $package_id);
        return $this->db->get()->row();
    }


    public function get_package_items($package_id)
    {
        $this->db->select('pi.*, m.medicine_name, m.medicine_code, mb.brand_name');
        $this->db->from('package_items pi');
        $this->db->join('medicines m', 'pi.medicine_id = m.id', 'inner');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        $this->db->join('medicine_brands mb', 'm.brand_id = mb.id', 'left');
        $this->db->where('pi.package_id', $package_id);
        return $this->db->get()->result();
    }

    public function update_medicine_package($package_id, $package_data, $package_items = null)
    {
        $this->db->trans_start();

        // If package items are being updated, recalculate prices
        if ($package_items !== null) {
            $total_selling_price = 0;
            $total_mrp = 0;

            foreach ($package_items as $item) {
                // Get latest batch pricing for this medicine
                $latest_batch = $this->db->select('selling_price, mrp')
                                        ->from('medicine_batches')
                                        ->where('medicine_id', $item['medicine_id'])
                                        ->order_by('created_at', 'DESC')
                                        ->limit(1)
                                        ->get()->row();

                if ($latest_batch && isset($latest_batch->selling_price) && $latest_batch->selling_price > 0) {
                    $total_selling_price += ($latest_batch->selling_price * $item['quantity']);
                    $total_mrp += ($latest_batch->mrp * $item['quantity']);
                } else {
                    // Fallback: try to get from medicines table if no batches exist
                    $medicine = $this->db->select('selling_price, mrp')
                                        ->from('medicines')
                                        ->where('id', $item['medicine_id'])
                                        ->get()->row();

                    if ($medicine && isset($medicine->selling_price)) {
                        $total_selling_price += ($medicine->selling_price * $item['quantity']);
                        $total_mrp += ($medicine->mrp * $item['quantity']);
                    }
                    // If no pricing found, prices remain 0
                }
            }

            // Override package prices with recalculated values
            $package_data['selling_price'] = $total_selling_price;
            $package_data['mrp'] = $total_mrp;
        }

        // Update package
        $this->db->where('id', $package_id);
        $this->db->update('medicine_packages', $package_data);

        // Update package items if provided
        if ($package_items !== null) {
            // Delete existing items
            $this->db->where('package_id', $package_id);
            $this->db->delete('package_items');

            // Insert new items
            foreach ($package_items as $item) {
                $item['package_id'] = $package_id;
                $this->db->insert('package_items', $item);
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function delete_medicine_package($package_id)
    {
        $this->db->trans_start();

        // Delete package items first (cascade will handle this, but let's be explicit)
        $this->db->where('package_id', $package_id);
        $this->db->delete('package_items');

        // Delete package
        $this->db->where('id', $package_id);
        $this->db->delete('medicine_packages');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // ===============================================
    // PACKAGE STOCK MANAGEMENT
    // ===============================================

    public function add_package_stock($package_id, $center_id, $quantity, $department = null, $created_by = null)
    {
        $this->db->trans_start();
        $package = $this->get_package_by_id($package_id);
        if (!$package) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Package not found.'];
        }
        $package_items = $this->get_package_items($package_id);
        if (empty($package_items)) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Package has no items defined.'];
        }
        foreach ($package_items as $item) {
            $medicine_quantity_needed = $item->quantity * $quantity;
            $this->db->select('SUM(cs.quantity) as total_available');
            $this->db->from('central_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
            $this->db->where('mb.medicine_id', $item->medicine_id);
            $this->db->where('cs.status', 'ACTIVE');
            $this->db->where('cs.quantity >', 0);
            $available_stock = $this->db->get()->row();
            if (!$available_stock || $available_stock->total_available < $medicine_quantity_needed) {
                $this->db->trans_rollback();
                return ['status' => 'error', 'message' => "Not enough stock for {$item->medicine_name}. Required: {$medicine_quantity_needed}, Available: " . ($available_stock->total_available ?? 0)];
            }
        }
        foreach ($package_items as $item) {
            $medicine_quantity_needed = $item->quantity * $quantity;
            $remaining_quantity = $medicine_quantity_needed;
            $this->db->select('cs.id as central_stock_id, cs.batch_id, cs.quantity, mb.batch_number, mb.expiry_date');
            $this->db->from('central_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
            $this->db->where('mb.medicine_id', $item->medicine_id);
            $this->db->where('cs.status', 'ACTIVE');
            $this->db->where('cs.quantity >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            $this->db->order_by('cs.last_movement_date', 'ASC');
            $batches = $this->db->get()->result();
            foreach ($batches as $batch) {
                if ($remaining_quantity <= 0) break;
                $deduct_quantity = min($remaining_quantity, $batch->quantity);
                $this->db->where('id', $batch->central_stock_id);
                $this->db->set('quantity', 'quantity - ' . (float)$deduct_quantity, FALSE);
                $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
                $this->db->update('central_stocks');
                $this->db->where('id', $batch->batch_id);
                $this->db->set('quantity_remaining', 'quantity_remaining - ' . (float)$deduct_quantity, FALSE);
                $this->db->update('medicine_batches');
                $batch_details = $this->db->select('mb.selling_price, mb.purchase_price, m.gst_rate')
                                        ->from('medicine_batches mb')
                                        ->join('medicines m', 'mb.medicine_id = m.id', 'inner')
                                        ->where('mb.id', $batch->batch_id)
                                        ->get()->row();
                $this->db->insert("stock_movements", [
                    "batch_id" => $batch->batch_id,
                    "movement_type" => "PACKAGE_ASSEMBLY",
                    "from_location_type" => "CENTRAL",
                    "from_location_id" => 0, 
                    "to_location_type" => "PACKAGE_ASSEMBLY",
                    "to_location_id" => $package_id,
                    "quantity_before" => $batch->quantity,
                    "quantity_change" => - (float)$deduct_quantity,
                    "quantity_after" => $batch->quantity - $deduct_quantity,
                    "unit_price" => $batch_details->selling_price,
                    "total_value" => $batch_details->selling_price * $deduct_quantity,
                    "reference_type" => "PACKAGE_ASSEMBLY",
                    "reference_id" => $package_id,
                    "created_by" => NULL, 
                ]);
                $remaining_quantity -= $deduct_quantity;
            }
        }
        $this->db->select('id, quantity');
        $this->db->from('package_stocks');
        $this->db->where('package_id', $package_id);
        $existing_stock = $this->db->get()->row();
        if ($existing_stock) {
            $new_quantity = $existing_stock->quantity + $quantity;
            $this->db->where('id', $existing_stock->id);
            $this->db->update('package_stocks', [
                'quantity' => $new_quantity,
                'center_id'=> $center_id,
                'department'=> $department,
                'last_movement_date' => date('Y-m-d H:i:s')
            ]);
            $quantity_before = $existing_stock->quantity;
        } else {
            $this->db->insert('package_stocks', [
                'package_id' => $package_id,
                'quantity' => $quantity,
                'center_id'=> $center_id,
                'department'=> $department,
                'last_movement_date' => date('Y-m-d H:i:s')
            ]);
            $quantity_before = 0;
        }
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'PURCHASE',
            'to_location_type' => 'central',
            'to_location_id' => 'central',
            'quantity_before' => $quantity_before,
            'quantity_change' => $quantity,
            'quantity_after' => $quantity_before + $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $package->selling_price * $quantity,
            'reference_type' => 'PACKAGE_ASSEMBLY',
            'reference_id' => $package_id,
            'created_by' => $created_by
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Database transaction failed'];
        }
        return ['status' => 'success', 'message' => 'Package stock added successfully. Medicines have been deducted from central stocks.'];
    }

    // ===============================================
    // PACKAGE DISASSEMBLY (Break down packages back to medicines)
    // ===============================================

    public function disassemble_package_stock($package_id, $center_id, $quantity, $department = null, $created_by = null)
    {
        $this->db->trans_start();
        $package_stock = $this->get_package_stock($package_id, $center_id, $department);
        if (!$package_stock || $package_stock->quantity < $quantity) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Not enough package stock available for disassembly.'];
        }
        $package_items = $this->get_package_items($package_id);
        $package = $this->get_package_by_id($package_id);
        foreach ($package_items as $item) {
            $medicine_quantity_to_add = $item->quantity * $quantity;
        }

        // Deduct package stock
        $this->db->where('package_id', $package_id);
        $this->db->where('center_id', $center_id);
        if ($department) {
            $this->db->where('department', $department);
        } else {
            $this->db->where('department IS NULL');
        }
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->set('last_movement_date', date('Y-m-d H:i:s'));
        $this->db->update('package_stocks');

        // Log package disassembly
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'ADJUSTMENT', // Or create PACKAGE_DISASSEMBLY type
            'from_location_type' => 'CENTER',
            'from_location_id' => $center_id,
            'to_location_type' => 'CENTER',
            'to_location_id' => $center_id,
            'quantity_before' => $package_stock->quantity,
            'quantity_change' => - (int)$quantity,
            'quantity_after' => $package_stock->quantity - $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $package->selling_price * $quantity,
            'reference_type' => 'ADJUSTMENT',
            'reference_id' => $package_id,
            'created_by' => $created_by
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Database transaction failed'];
        }

        return ['status' => 'success', 'message' => 'Package disassembled successfully. Medicines returned to central stocks.'];
    }

    public function get_package_stock($package_id, $center_id = null, $department = null)
    {
        $this->db->select('ps.*, mp.package_name, mp.package_code');
        $this->db->from('package_stocks ps');
        $this->db->join('medicine_packages mp', 'ps.package_id = mp.id', 'inner');
        $this->db->where('ps.package_id', $package_id);
        $this->db->where('ps.status', 'ACTIVE');

        if ($center_id !== null) {
            $this->db->where('ps.center_id', $center_id);
        }

        $this->db->like('ps.department', $department);

        $this->db->limit(1);
        return $this->db->get()->row();
    }


    public function get_package_stock_report()
    {
        $this->db->select('
            mp.package_name,
            mp.package_code,
            c.center_name,
            ps.quantity,
            ps.department,
            ps.last_movement_date
        ');
        $this->db->from('package_stocks ps');
        $this->db->join('medicine_packages mp', 'ps.package_id = mp.id', 'inner');
        $this->db->join('hms_centers c', 'ps.center_id = c.id', 'inner');
        $this->db->where('ps.status', 'ACTIVE');
        $this->db->where('ps.quantity >', 0);
        $this->db->where('mp.status', 'active');
        $this->db->order_by('mp.package_name, c.center_name, ps.department');
        return $this->db->get()->result();
    }

    public function get_package_transfer_history($limit = 50)
    {
        $this->db->select('
            psm.*,
            mp.package_name,
            mp.package_code,
            fc.center_name as from_center,
            tc.center_name as to_center
        ');
        $this->db->from('package_stock_movements psm');
        $this->db->join('medicine_packages mp', 'psm.package_id = mp.id', 'inner');
        $this->db->join('centers fc', 'psm.from_location_id = fc.id', 'left');
        $this->db->join('centers tc', 'psm.to_location_id = tc.id', 'left');
        $this->db->where_in('psm.movement_type', ['TRANSFER_OUT', 'TRANSFER_IN']);
        $this->db->order_by('psm.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_available_packages_for_sale($center_id, $department = null)
    {
        $this->db->select('mp.*, ps.quantity as available_quantity, c.center_name');
        $this->db->from('medicine_packages mp');
        $this->db->join('package_stocks ps', 'mp.id = ps.package_id', 'inner');
        $this->db->join('hms_centers c', 'ps.center_id = c.id', 'inner');
        $this->db->where('ps.center_id', $center_id);
        $this->db->where('ps.quantity >', 0);
        $this->db->where('ps.status', 'ACTIVE');
        $this->db->where('mp.status', 'active');
        $this->db->order_by('mp.package_name', 'ASC');
        return $this->db->get()->result();
    }

    // ===============================================
    // PACKAGE TRANSFER
    // ===============================================

    public function transfer_package_stock($transfer_data, $created_by = null)
    {
        $this->db->trans_start();
        $package_id = $transfer_data['package_id'];
        $to_center_id = $transfer_data['to_center_id'];
        $quantity = $transfer_data['quantity'];
        $to_department = $transfer_data['to_department'] ?? null;
        $source_stock = $this->get_package_stock($package_id);
        if (!$source_stock || $source_stock->quantity < $quantity) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Not enough package stock available for transfer.'];
        }
        // Deduct from source
        $this->db->where('package_id', $package_id);
        // $this->db->where('center_id', $from_center_id);
        // if ($from_department) {
        //     $this->db->where('department', $from_department);
        // } else {
        //     $this->db->where('department IS NULL');
        // }
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->set('last_movement_date', date('Y-m-d H:i:s'));
        $this->db->update('package_stocks');
        $dest_stock = $this->get_package_stock($package_id, $to_center_id, $to_department);
        $dest_quantity_before = $dest_stock ? $dest_stock->quantity : 0;
        if ($dest_stock) {
            $this->db->where('id', $dest_stock->id);
            $this->db->set('quantity', 'quantity + ' . (int)$quantity, FALSE);
            $this->db->set('last_movement_date', date('Y-m-d H:i:s'));
            $this->db->update('package_stocks');
        } else {
            $this->db->insert('package_stocks', [
                'package_id' => $package_id,
                'center_id' => $to_center_id,
                'department' => $to_department,
                'quantity' => $quantity,
                'last_movement_date' => date('Y-m-d H:i:s')
            ]);
        }
        $package = $this->get_package_by_id($package_id);
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'TRANSFER_OUT',
            'from_location_type' => 'CENTER',
            'from_location_id' => $from_center_id,
            'to_location_type' => 'CENTER',
            'to_location_id' => $to_center_id,
            'quantity_before' => $source_stock->quantity,
            'quantity_change' => - (int)$quantity,
            'quantity_after' => $source_stock->quantity - $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $package->selling_price * $quantity,
            'reference_type' => 'TRANSFER',
            'created_by' => $created_by
        ]);

        // Incoming movement
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'TRANSFER_IN',
            'from_location_type' => 'CENTER',
            'from_location_id' => $from_center_id,
            'to_location_type' => 'CENTER',
            'to_location_id' => $to_center_id,
            'quantity_before' => $dest_quantity_before,
            'quantity_change' => (int)$quantity,
            'quantity_after' => $dest_quantity_before + $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $package->selling_price * $quantity,
            'reference_type' => 'TRANSFER',
            'created_by' => $created_by
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Database transaction failed'];
        }

        return ['status' => 'success', 'message' => 'Package transferred successfully.'];
    }

    // ===============================================
    // PACKAGE SALES
    // ===============================================

    public function get_available_packages_for_center($center_id, $department = null)
    {
        $this->db->select('mp.id, mp.package_name, mp.selling_price, mp.mrp, COALESCE(ps.quantity, 0) as stock');
        $this->db->from('medicine_packages mp');
        $this->db->join('package_stocks ps', 'mp.id = ps.package_id', 'left');
        $this->db->where('mp.status', 'active');
        $this->db->where('ps.center_id', $center_id); // Filter by center_id
        if ($department) {
            $this->db->like('ps.department', $department);
        }
        $this->db->having('stock >', 0); // Only show packages with stock > 0
        $this->db->order_by('mp.package_name', 'ASC');

        return $this->db->get()->result();
    }

    public function process_package_consumption($sale_id, $package_data, $created_by_id)
    {
        $this->db->trans_start();
        $package_id = $package_data['package_id'];
        $center_id = $package_data['center_id'];
        $department = $package_data['department'];
        $quantity = $package_data['quantity'];
        $package = $this->get_package_by_id($package_id);
        if (!$package) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Package not found.'];
        }
        $package_stock = $this->get_package_stock($package_id, $center_id, $department);

        if (!$package_stock || $package_stock->quantity < $quantity) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Not enough package stock available.'];
        }
        $total_price = $package->selling_price * $quantity;

        // Create sale item entry for the package
        $sale_item_data = [
            'sale_id' => $sale_id,
            'batch_id' => NULL, // Package sales don't have a medicine batch
            'quantity_sold' => $quantity,
            'unit_price' => $package->selling_price,
            'subtotal' => $total_price,
            'discount_amount' => 0,
            'tax_amount' => 0, // No tax for consumption
            'total' => $total_price,
            'remarks' => "Package Consumption: {$package->package_name} - Contains " . count($this->get_package_items($package_id)) . " medicines"
        ];
       
        $this->db->insert('sale_items', $sale_item_data);
        // Deduct package stock
        $this->db->where('package_id', $package_id);
        $this->db->where('center_id', $center_id);
        $this->db->where('status', 'ACTIVE');
        if ($department !== null && $department !== '') {
            $this->db->where('department', $department);
        } else {
            $this->db->group_start();
            $this->db->where('department IS NULL');
            $this->db->or_where('department', '');
            $this->db->group_end();
        }
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
        $this->db->update('package_stocks');
        $this->db->where('id', $sale_id);
        $this->db->set('payment_status', 'PAID');
        $this->db->set('status', 'PACKAGE');                
        $this->db->update('sales');
        // Log package stock movement
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'CONSUMPTION',
            'from_location_type' => 'CENTER',
            'from_location_id' => $center_id,
            'to_location_type' => 'PATIENT_CONSUMPTION',
            'to_location_id' => $sale_id,
            'quantity_before' => $package_stock->quantity,
            'quantity_change' => - (int)$quantity,
            'quantity_after' => $package_stock->quantity - $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $total_price,
            'reference_type' => 'PATIENT_CONSUMPTION_BILL',
            'reference_id' => $sale_id,
            'patient_id' => $package_data['patient_id'],
            'patient_name' => $package_data['patient_name'],
            'created_by' => $created_by_id
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Package consumption failed due to database error.'];
        }
        return [
            'status' => 'success',
            'message' => 'Package consumed successfully!',
            'total_price' => $total_price
        ];
    }

    public function process_package_sale($sale_id, $package_data, $created_by_id)
    {
        $this->db->trans_start();
        $package_id = $package_data['package_id'];
        $center_id = $package_data['center_id'];
        $department = $package_data['department'];
        $quantity = $package_data['quantity'];
        $package = $this->get_package_by_id($package_id);
        if (!$package) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Package not found.'];
        }
        $package_stock = $this->get_package_stock($package_id, $center_id, $department);
        if (!$package_stock || $package_stock->quantity < $quantity) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Not enough package stock available.'];
        }
        $package_items = $this->get_package_items($package_id);
        $taxable_unit_price = $package->selling_price / (1 + ($package->gst_rate / 100));
        $subtotal = $taxable_unit_price * $quantity;
        $tax_amount = $subtotal * ($package->gst_rate / 100);
        $total_price = $subtotal + $tax_amount;
        $sale_item_data = [
            'sale_id' => $sale_id,
            'batch_id' => NULL, // Package sales don't have a medicine batch
            'quantity_sold' => $quantity,
            'unit_price' => $package->selling_price,
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'tax_amount' => $tax_amount,
            'total' => $total_price,
            'remarks' => "Package: {$package->package_name} - Contains " . count($package_items) . " medicines"
        ];
        $this->db->insert('sale_items', $sale_item_data);
   
        // $this->db->insert("stock_movements", [
        //     "batch_id" => NULL, // Package level movement
        //     "movement_type" => "PACKAGE_SALE",
        //     "from_location_type" => "CENTER",
        //     "from_location_id" => $center_id,
        //     "to_location_type" => "SALE_CONSUMPTION_BILLING",
        //     "to_location_id" => $sale_id,
        //     "quantity_before" => $package_stock->quantity,
        //     "quantity_change" => - (int)$quantity,
        //     "quantity_after" => $package_stock->quantity - $quantity,
        //     "unit_price" => $package->selling_price,
        //     "total_value" => $total_price,
        //     "reference_type" => "PACKAGE_SALES_BILL",
        //     "reference_id" => $sale_id,
        //     "patient_id" => $package_data['patient_id'],
        //     "patient_name" => $package_data['patient_name'],
        //     "created_by" => NULL, // Package operations use NULL to avoid FK constraint issues
        // ]);
        $this->db->where('package_id', $package_id);
        $this->db->where('center_id', $center_id);
        // if ($department) {
        //     $this->db->where('department', $department);
        // }
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
        $this->db->update('package_stocks');
        $this->db->insert('package_stock_movements', [
            'package_id' => $package_id,
            'movement_type' => 'SALE',
            'from_location_type' => 'CENTER',
            'from_location_id' => $center_id,
            'to_location_type' => 'SALE_CONSUMPTION',
            'to_location_id' => $sale_id,
            'quantity_before' => $package_stock->quantity,
            'quantity_change' => - (int)$quantity,
            'quantity_after' => $package_stock->quantity - $quantity,
            'unit_price' => $package->selling_price,
            'total_value' => $package->selling_price * $quantity,
            'reference_type' => 'PACKAGE_SALES_BILL',
            'reference_id' => $sale_id,
            'patient_id' => $package_data['patient_id'],
            'patient_name' => $package_data['patient_name'],
            'created_by' => $created_by_id
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return ['status' => 'error', 'message' => 'Database transaction failed'];
        }
        return ['status' => 'success', 'total_price' => $total_price];
    }

    private function get_available_batches_for_medicine_sale($medicine_id, $center_id, $department, $required_quantity)
    {
        $this->db->select('
            cs.batch_id,
            cs.quantity as available_quantity,
            mb.batch_number,
            mb.expiry_date
        ');
        $this->db->from('center_stocks cs');
        $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id', 'inner');
        $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
        $this->db->where('m.id', $medicine_id);
        $this->db->where('cs.center_id', $center_id);
        $this->db->where('cs.status', 'ACTIVE');
        $this->db->where('cs.quantity >', 0);
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");

        if ($department) {
            $this->db->like('cs.department', $department);
        }

        $this->db->order_by('mb.expiry_date', 'ASC'); // FIFO by expiry
        $this->db->order_by('cs.last_movement_date', 'ASC'); // Then by last movement

        $batches = $this->db->get()->result();

        // Filter batches to meet required quantity
        $selected_batches = [];
        $total_available = 0;

        foreach ($batches as $batch) {
            if ($total_available >= $required_quantity) break;
            $selected_batches[] = $batch;
            $total_available += $batch->available_quantity;
        }

        return $selected_batches;
    }

    public function get_stock_additions_report($filters = []) {
        try {
            $this->db->select("
                sm.*, 
                m.medicine_name, 
                m.medicine_code, 
                mb.batch_number, 
                e.name as user_name,
                CASE 
                    WHEN sm.to_location_type = 'CENTRAL' THEN 'Central Warehouse'
                    WHEN sm.to_location_type = 'CENTER' THEN hc.center_name
                    ELSE sm.to_location_type
                END as location_name
            ");
            $this->db->from("stock_movements sm");
            $this->db->join("medicine_batches mb", "sm.batch_id = mb.id", "left");
            $this->db->join("medicines m", "mb.medicine_id = m.id", "left");
            $this->db->join("hms_employees e", "sm.created_by = e.ID", "left");
            $this->db->join("hms_centers hc", "sm.to_location_id = hc.ID AND sm.to_location_type = 'CENTER'", "left");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // --- FILTERS ---
            // This is the main filter: only show positive changes (stock additions)
            $this->db->where("sm.quantity_change >", 0);
            // Filter by Date From
            if (!empty($filters['date_from'])) {
                $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
            }
            // Filter by Date To
            if (!empty($filters['date_to'])) {
                $this->db->where('DATE(sm.created_at) <=', $filters['date_to']); // Corrected typo
            }
            // Filter by Location
            if (!empty($filters['location_id'])) {
                if ($filters['location_id'] == 'central') {
                    $this->db->where('sm.to_location_type', 'CENTRAL'); // Corrected typo
                } else {
                    $this->db->where('sm.to_location_type', 'CENTER');
                    $this->db->where('sm.to_location_id', (int)$filters['location_id']);
                }
            }
            // *** NEW: Filter by Transaction Type ***
            if (!empty($filters['movement_type'])) {
                $this->db->where('sm.movement_type', $filters['movement_type']);
            }
            // *** NEW: Filter by Batch Number (partial match) ***
            if (!empty($filters['batch_number'])) {
                $this->db->like('mb.batch_number', $filters['batch_number'], 'both');
            }
            $this->db->order_by("sm.created_at", "DESC"); // Corrected typo
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_stock_additions_report: ' . $e->getMessage());
            return [];
        }
    }

    // ===============================================
    // PATIENT BILLING FUNCTIONS
    // ===============================================

    /**
     * Get available batches for patient billing by category
     * @param string $category_type - 'embryology', 'injections', or 'consumables'
     * @param int $center_id - Center ID
     * @param string $department - Department name
     * @return array
     */
    // public function get_available_batches_for_billing($category_type, $center_id = null, $department = null)
    // {
    //     // try {
    //         $category_map = [
    //             'embryology' => '1565461628',
    //             'injections' => '1565461619',
    //             'consumables' => '1565461624'
    //         ];
    //         if (!isset($category_map[$category_type])) {
    //             return [];
    //         }

    //         $category_id = $category_map[$category_type];
    //         if (!$center_id) {
    //             if (isset($_SESSION['logged_stock_manager']['center'])) {
    //                 $center_id = $_SESSION['logged_stock_manager']['center'];
    //                 $department = $_SESSION['logged_stock_manager']['department'];
    //             } elseif (isset($_SESSION['logged_billing_manager']['center'])) {
    //                 $center_id = $_SESSION['logged_billing_manager']['center'];
    //                 $department = $_SESSION['logged_billing_manager']['department'];
    //             }
    //         }
    //         // Query center_stocks table (old structure compatibility)
    //         $this->db->select('*');
    //         $this->db->from('center_stocks');
    //         $this->db->where('category', $category_id);
    //         $this->db->where('center_number', $center_id);
    //         $this->db->where('department', $department);
    //         $this->db->where('quantity >', 0);
    //         if ($category_type == 'injections') {
    //             $this->db->where('status', '1');
    //         }
    //         $this->db->order_by('expiry', 'ASC');
    //         $result = $this->db->get()->result_array();
    //         return $result;
    // }
    // public function billing_item_insert($data)
    // {
    //     try {
    //         $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_items` SET ";
    //         $sqlArr = array();
    //         foreach ($data as $key => $value) {
    //             $sqlArr[] = " $key = '" . addslashes($value) . "'";
    //         }
    //         $sql .= implode(',', $sqlArr);
    //         $res = $this->db->query($sql);
    //         if ($res) {
    //             return $this->db->insert_id();
    //         } else {
    //             return 0;
    //         }
    //     } catch (Exception $e) {
    //         return 0;
    //     }
    // }
    // public function deduct_stock($ID, $serial, $qty)
    // {
    //     try {
    //         if (isset($_SESSION['logged_stock_manager']['employee_number'])) {
    //             $sql = "UPDATE " . $this->config->item('db_prefix') . "center_stocks
    //                     SET `quantity` = `quantity` - " . $qty . "
    //                     WHERE item_number='" . $serial . "'
    //                     AND ID='" . $ID . "'
    //                     AND department='" . $_SESSION['logged_stock_manager']['department'] . "'";
    //         } else {
    //             $sql = "UPDATE " . $this->config->item('db_prefix') . "center_stocks
    //                     SET `quantity` = `quantity` - " . $qty . "
    //                     WHERE item_number='" . $serial . "'
    //                     AND ID='" . $ID . "'
    //                     AND department='" . $_SESSION['logged_billing_manager']['department'] . "'";
    //         }
    //         $this->db->query($sql);
    //         return 1;
    //     } catch (Exception $e) {
    //         return 0;
    //     }
    // }
    public function get_batches_for_billing_form($category_name, $center_id, $department)
    {
        $this->db->select('
            m.medicine_name as item_name,
            m.medicine_code as item_number,
            mb.id as ID,
            mb.medicine_id as medicine_id,
            mb.batch_number,
            ccs.quantity,
            mb.selling_price as price,
            m.gst_rate as gstrate,
            mb.expiry_date as expiry
        ');
        $this->db->from('center_stocks ccs');
        $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id', 'inner');
        $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
        $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
        $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
        // Filters
        // $this->db->where('m.category', $category_name);
        $this->db->where('ccs.center_id', $center_id);

        // Check session overrides (but don't override the passed center_id)
        $center = null; // Initialize to avoid undefined variable error
        if (!empty($_SESSION['logged_billing_manager']) &&
            ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
            $center = $_SESSION['logged_billing_manager']['center'];
            $department = $_SESSION['logged_billing_manager']['department'] ?? null;
        }
        if (!empty($_SESSION['logged_stock_manager']) &&
            ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
            $center = $_SESSION['logged_stock_manager']['center'];
            $department = $_SESSION['logged_stock_manager']['department'] ?? null;
        }

        // If session center is different from passed center_id, use session center
        if ($center !== null) {
            $session_center_id = $this->get_center_id($center);
            if ($session_center_id && $session_center_id != $center_id) {
                $this->db->where('ccs.center_id', $session_center_id);
            }
        }
        if ($department !== null && $department !== '') {
            if ($department == 'billing') {
                $this->db->like('ccs.department', 'CASH MEDICINE');
            }elseif($department == 'Embryologist Basant Lok')
            {
                $this->db->like('ccs.department', 'Embryology Basant Lok');
            }
            else {
                $this->db->like('ccs.department', $department);
            }
        }
        // $this->db->where('ccs.department', $department);
        // Stock checks
        $this->db->where('ccs.quantity >', 0);
        $this->db->where('ccs.status', 'ACTIVE');
        $this->db->where('mb.batch_status', 'ACTIVE');
        $this->db->where('mb.expiry_date >', date('Y-m-d'));
        $this->db->order_by('mb.expiry_date', 'ASC');
        return $this->db->get()->result_array();
    }
    public function check_medicine_in_stocks($medicine_id, $center_id, $department = null)
    {
        $this->db->select('COUNT(*) as stock_count, SUM(ccs.quantity) as total_quantity');
        $this->db->from('center_stocks ccs');
        $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id', 'inner');
        $this->db->where('mb.medicine_id', $medicine_id);
        $this->db->where('ccs.center_id', $center_id);
        $this->db->where('ccs.status', 'ACTIVE');
        $this->db->where('ccs.quantity >', 0);
        $this->db->where('mb.batch_status', 'ACTIVE');
        $this->db->where('mb.expiry_date >', date('Y-m-d'));
        if ($department !== null && $department !== '') {
            $this->db->like('ccs.department', $department);
        }
        $result = $this->db->get()->row();

        return [
            'available' => ($result && $result->stock_count > 0),
            'total_quantity' => $result ? $result->total_quantity : 0,
            'batch_count' => $result ? $result->stock_count : 0
        ];
    }

    public function process_sale_item($sale_id, $item_data, $created_by_id,$department)
    {
        $this->db->trans_start();
        // try {
            $batch_id = $item_data['batch_id'];
            $center_id = $item_data['center_id'];
            $department = $item_data['department'];
            $quantity = $item_data['quantity'];
            $expected_medicine_id = isset($item_data['medicine_id']) ? $item_data['medicine_id'] : null;
                // 0. Validate batch belongs to expected medicine
            if ($expected_medicine_id) {
                $this->db->select('mb.id, mb.medicine_id, m.medicine_name');
                $this->db->from('medicine_batches mb');
                $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
                $this->db->where('mb.id', $batch_id);
                $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
                $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
                $batch_medicine_check = $this->db->get()->row();

                if (!$batch_medicine_check) {
                    $this->db->trans_rollback();
                    return ['status' => 'error', 'message' => "Batch ID {$batch_id} does not exist."];
                }

                if ($batch_medicine_check->medicine_id != $expected_medicine_id) {
                    $this->db->trans_rollback();
                    return ['status' => 'error', 'message' => "Batch ID {$batch_id} belongs to '{$batch_medicine_check->medicine_name}' but expected medicine ID {$expected_medicine_id}."];
                }
            }

            // 1. Check if medicine exists in center stocks
            if ($expected_medicine_id) {
                $medicine_stock_check = $this->check_medicine_in_stocks($expected_medicine_id, $center_id, $department);
                if (!$medicine_stock_check['available']) {
                    $this->db->trans_rollback();
                    return ['status' => 'error', 'message' => "Medicine ID {$expected_medicine_id} is not available in {$department} department stocks."];
                }
            }

            // 2. Get Batch & Stock Details (and lock the rows)
            // 2. Get Batch & Stock Details (and lock the rows)
            // IMPORTANT: Explicitly select ccs.id as center_stock_id to avoid column name conflicts
            $this->db->select('
                ccs.id as center_stock_id,
                ccs.quantity,
                ccs.batch_id,
                ccs.center_id,
                mb.id as batch_id,
                mb.batch_number,
                mb.selling_price,
                m.medicine_name,
                m.gst_rate
            ');
            $this->db->from('center_stocks ccs');
            $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->where('ccs.batch_id', $batch_id);
            $this->db->where('ccs.center_id', $center_id);
            if ($department !== null && $department !== '') {
                $this->db->like('ccs.department', $department);
            }
            $this->db->where('ccs.status','ACTIVE');
            $this->db->where('ccs.quantity >', 0);
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->order_by('ccs.quantity', 'DESC');
            $this->db->limit(1);
            $stock_record = $this->db->get()->row();
            if (!$stock_record) {
                $this->db->trans_rollback();
                return ['status' => 'error', 'message' => "Batch ID {$batch_id} not found in {$department}."];
            }

            $quantity_before = $stock_record->quantity;
            $unit_price = $stock_record->selling_price; // This is the MRP
            $gst_rate = $stock_record->gst_rate;
            // 2. Check stock
            if ($quantity_before < $quantity) {
                $this->db->trans_rollback();
                return ['status' => 'error', 'message' => "Not enough stock for {$stock_record->medicine_name} (Batch: {$stock_record->batch_number}). Available: {$quantity_before}, Requested: {$quantity}."];
            }
            // 3. Calculate Pricing (Tax-Inclusive)
            // Price *before* tax
            $taxable_unit_price = $unit_price / (1 + ($gst_rate / 100));
            $subtotal = $taxable_unit_price * $quantity;
            $tax_amount = $subtotal * ($gst_rate / 100);
            $total_price = $subtotal + $tax_amount;
            // 4. Insert into 'sale_items'
            $sale_item_data = [
                'sale_id'         => $sale_id,
                'batch_id'        => $batch_id,
                'quantity_sold'   => $quantity,
                'unit_price'      => $unit_price, // The MRP
                'subtotal'        => $subtotal,
                'discount_amount' => 0,
                'tax_amount'      => $tax_amount,
                'total'           => $total_price
            ];
            $this->db->insert('sale_items', $sale_item_data);
            // 5. Deduct from 'center_stocks' - use center_stock_id (not id) to avoid column conflicts
            $this->db->where('id', $stock_record->center_stock_id);
            $this->db->set('quantity', 'quantity - ' . (float)$quantity, FALSE);
            $this->db->set('last_movement_date', date("Y-m-d H:i:s"));
            $this->db->update('center_stocks');
            // 6. Deduct from 'medicine_batches'
            $this->db->where('id', $batch_id);
            $this->db->set('quantity_remaining', 'quantity_remaining - ' . (float)$quantity, FALSE);
            $this->db->update('medicine_batches');
            // 7. Log in 'stock_movements'
            $movement_data = [
                "batch_id"           => $batch_id,
                "movement_type"      => "SALE",
                "from_location_type" => "CENTER",
                "from_location_id"   => $center_id,
                "to_location_type"   => "SALE_CONSUMPTION_BILLING",
                "to_location_id"     => $sale_id,
                "quantity_before"    => $quantity_before,
                "quantity_change"    => - (float)$quantity,
                "quantity_after"     => $quantity_before - (float)$quantity,
                "unit_price"         => $unit_price,
                "total_value"        => $total_price,
                "reference_type"     => "SALES_BILL",
                "reference_id"       => $sale_id,
                "patient_id"         => $item_data['patient_id'],
                "patient_name"       => $item_data['patient_name'],
                "created_by"         => $created_by_id,
            ];
            $this->db->insert("stock_movements", $movement_data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                log_message('error', 'DB Transaction failed while processing sale item for batch ID: ' . $batch_id);
                return ['status' => 'error', 'message' => 'Database transaction failed for batch ' . $batch_id];
            } else {
                return ['status' => 'success', 'total_price' => $total_price];
            }
        // } catch (Exception $e) {
        //     $this->db->trans_rollback();
        //     log_message('error', 'Exception in process_sale_item: ' . $e->getMessage());
        //     return ['status' => 'error', 'message' => $e->getMessage()];
        // }
    }
    public function update_sale_totals($sale_id)
    {
        // This query calculates all totals from the 'sale_items' table
        $this->db->select('
            COUNT(id) as total_items,
            SUM(quantity_sold) as total_quantity,
            SUM(subtotal) as subtotal,
            SUM(discount_amount) as discount_amount,
            SUM(tax_amount) as tax_amount,
            SUM(total) as total_amount
        ');
        $this->db->from('sale_items');
        $this->db->where('sale_id', $sale_id);
        $totals = $this->db->get()->row_array();

        if ($totals) {
            // Update the main 'sales' record
            $this->db->where('id', $sale_id);
            $this->db->update('sales', $totals);
        }
    }
     public function get_patient_consumption($patient_id)
    {
        // Get individual medicine consumption
        $medicine_query = $this->db->select('
                sm.created_at as received_date,
                sm.quantity_change,
                sm.unit_price,
                sm.total_value,
                sm.reference_number as sale_number,
                c.center_name,
                m.medicine_name as item_name,
                m.medicine_code as item_code,
                mb.batch_number,
                e.name as user_name,
                "medicine" as item_type
            ')
            ->from('stock_movements sm')
            ->where('sm.to_location_type', 'SALE_CONSUMPTION_BILLING')
            ->where('sm.patient_id', $patient_id)
            ->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left')
            ->join('medicines m', 'mb.medicine_id = m.id', 'left')
            ->join('hms_centers c', 'sm.from_location_id = c.ID AND sm.from_location_type = "CENTER"', 'left')
            ->join('hms_employees e', 'sm.created_by = e.ID', 'left')
            ->where("m.medicine_code NOT LIKE 'HK_%'")
            ->where("m.medicine_code NOT LIKE 'ST_%'")
            ->order_by('sm.created_at', 'DESC')
            ->get_compiled_select();

        // Get package consumption
        $package_query = $this->db->select('
                psm.created_at as received_date,
                psm.quantity_change,
                psm.unit_price,
                psm.total_value,
                psm.reference_id as sale_number,
                c.center_name,
                mp.package_name as item_name,
                mp.package_code as item_code,
                "" as batch_number,
                e.name as user_name,
                "package" as item_type
            ')
            ->from('package_stock_movements psm')
            ->where('psm.movement_type', 'CONSUMPTION')
            ->where('psm.patient_id', $patient_id)
            ->join('medicine_packages mp', 'psm.package_id = mp.id', 'left')
            ->join('hms_centers c', 'psm.from_location_id = c.ID AND psm.from_location_type = "CENTER"', 'left')
            ->join('hms_employees e', 'psm.created_by = e.ID', 'left')
            ->order_by('psm.created_at', 'DESC')
            ->get_compiled_select();

        // Combine both queries with UNION ALL
        $combined_query = "($medicine_query) UNION ALL ($package_query) ORDER BY received_date DESC";

        return $this->db->query($combined_query)->result();
    }

    /**
     * *** NEW FUNCTION ***
     * Searches for patients from the 'sales' table for the Select2 box.
     */
    public function search_patients($search)
    {
        // try {
            $this->db->select('patient_id, patient_name');
            $this->db->from('sales'); // Assumes 'sales' table has patient records
            
            $this->db->group_start();
            $this->db->like('patient_id', $search, 'both');
            $this->db->or_like('patient_name', $search, 'both');
            $this->db->group_end();
            
            $this->db->where('patient_id IS NOT NULL');
            $this->db->where('patient_id !=', '');
            
            $this->db->group_by('patient_id, patient_name');
            $this->db->order_by('patient_name', 'ASC');
            $this->db->limit(50);
            
            return $this->db->get()->result();

        // } catch (Exception $e) {
        //     log_message('error', 'Error in search_patients: ' . $e->getMessage());
        //     return [];
        // }
    }


    public function get_patient_details($patient_id)
    {
        // try {
            $this->db->select('patient_id, patient_name');
            $this->db->from('sales');
            $this->db->where('patient_id', $patient_id);
            $this->db->limit(1);
            return $this->db->get()->row();
        // } catch (Exception $e) {
        //     log_message('error', 'Error in get_patient_details: ' . $e->getMessage());
        //     return null;
        // }
    }
    public function get_patient_consumption_summary($patient_id, $filters = [])
    {
        try {
            $this->db->select('
                DATE(sm.created_at) as consumption_date,
                m.category,
                SUM(sm.quantity_change) as total_consumed 
            ');
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->where('sm.movement_type', 'SALE');
            $this->db->where('sm.patient_id', $patient_id);
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            // ** NEW: Apply Date Filters **
            if (!empty($filters['start_date'])) {
                $this->db->where('DATE(sm.created_at) >=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $this->db->where('DATE(sm.created_at) <=', $filters['end_date']);
            }
            // ** NEW: Group by Date as well **
            $this->db->group_by('DATE(sm.created_at), m.category');
            $this->db->order_by('consumption_date', 'DESC');
            $this->db->order_by('m.category', 'ASC');
            
            return $this->db->get()->result();

        } catch (Exception $e) {
            log_message('error', 'Error in get_patient_consumption_summary: ' . $e->getMessage());
            return [];
        }
    }
    public function consumption_medicine_package($patient_id, $filters = [])
    {
        try {
            $this->db->select('
                DATE(psm.created_at) as consumption_date,
                psm.created_at as consumption_datetime,
                psm.quantity_change as quantity_consumed,
                psm.unit_price,
                psm.total_value,
                psm.reference_id as sale_number,
                mp.package_name,
                mp.package_code,
                psm.movement_type,
                psm.from_location_type,
                psm.to_location_type
            ');
            $this->db->from('package_stock_movements psm');
            $this->db->join('medicine_packages mp', 'psm.package_id = mp.id', 'inner');
            // $this->db->join('hms_centers c', 'psm.from_location_id = c.ID AND psm.from_location_type = "CENTER"', 'left');
            // $this->db->join('hms_employees e', 'psm.created_by = e.ID', 'left');
            $this->db->where('psm.patient_id', $patient_id);
            if (!empty($filters['start_date'])) {
                $this->db->where('DATE(psm.created_at) >=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $this->db->where('DATE(psm.created_at) <=', $filters['end_date']);
            }
            if (!empty($filters['center_id'])) {
                $this->db->where('psm.from_location_id', $filters['center_id']);
            }
            if (!empty($filters['package_id'])) {
                $this->db->where('psm.package_id', $filters['package_id']);
            }

            $this->db->order_by('psm.created_at', 'DESC');

            return $this->db->get()->result();

        } catch (Exception $e) {
            log_message('error', 'Error in consumption_medicine_package: ' . $e->getMessage());
            return [];
        }
    }
    public function get_consumption_report_pivoted($filters = [])
    {
        // try {
            $this->db->select('
                sm.patient_id,
                sm.patient_name,
                DATE(sm.created_at) as consumption_date,
                
                -- This is the PIVOT logic
                SUM(CASE WHEN m.category = "OT DCI" THEN sm.quantity_change ELSE 0 END) as ot_total,
                SUM(CASE WHEN m.category = "Package injections" THEN sm.quantity_change ELSE 0 END) as injections_total,
                SUM(CASE WHEN m.category = "EMBRYOLOGIST DCI" THEN sm.quantity_change ELSE 0 END) as embryologist_total,
                SUM(sm.quantity_change) as grand_total
            ');
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'inner');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'inner');
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            
            // We only want 'SALE' transactions
            $this->db->where('sm.to_location_type', 'SALE_CONSUMPTION_BILLING');
            
            // Apply filters
            if (!empty($filters['patient_id'])) {
                $this->db->where('sm.patient_id', $filters['patient_id']);
            }
            if (!empty($filters['start_date'])) {
                $this->db->where('DATE(sm.created_at) >=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $this->db->where('DATE(sm.created_at) <=', $filters['end_date']);
            }

            // Group by patient and date to get a daily summary
            $this->db->group_by('sm.patient_id, sm.patient_name, DATE(sm.created_at)');
            $this->db->order_by('consumption_date', 'DESC');
            
            return $this->db->get()->result();

        // } catch (Exception $e) {
        //     log_message('error', 'Error in get_consumption_report_pivoted: ' . $e->getMessage());
        //     return [];
        // }
    }

    // stationary and housekeeping 
       public function get_central_stocks_stationary_housekeeping(
        $medicine_id = null,
        $batch_number = null,
        $status = null,
    ) {
        // try {
            $this->db->select(
                "cs.*, mb.batch_number,m.pack_size, mb.expiry_date, mb.purchase_price, mb.selling_price, m.medicine_name, m.medicine_code, b.brand_name as brand_name, v.name as vendor_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days",
            );
            $this->db->from("central_stocks cs");
            $this->db->join("medicine_batches mb", "cs.batch_id = mb.id");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );
            $this->db->where("m.medicine_code LIKE 'HK_%'");
            $this->db->or_where("m.medicine_code LIKE 'ST_%'");
            if ($medicine_id && $medicine_id != "") {
                $this->db->where("mb.medicine_id", $medicine_id);
            }

            if ($batch_number && $batch_number != "") {
                $this->db->like("mb.batch_number", $batch_number);
            }

            if ($status && $status != "") {
                $this->db->where("cs.status", $status);
            }

            $this->db->order_by("mb.expiry_date", "ASC");
            return $this->db->get()->result();
        // } catch (Exception $e) {
        //     return [];
        // }
    }

      public function get_all_medicines_stationary_housekeeping($medicine_name = null, $generic_name = null, $brand_id = null, $category = null, $selected_medicine_id = null)
    {
        $this->db->reset_query();
        $this->db->select("m.*, mb.brand_name as brand_name");
        $this->db->from("medicines m");
        $this->db->join("medicine_brands mb", "m.brand_id = mb.id", "left");

        // $this->db->join(
        //     $this->config->item("db_prefix") . "brands mb",
        //     "m.brand_id = mb.ID"
        // );
        $this->db->where("m.status", "active");
        $this->db->where("m.medicine_code LIKE 'HK_%'");
        $this->db->or_where("m.medicine_code LIKE 'ST_%'");

        if (!empty($selected_medicine_id)) {
            $this->db->where("m.ID", $selected_medicine_id);
        }

        // --- APPLY FILTERS ---
        if (!empty($medicine_name)) {
            $this->db->like("m.medicine_name", $medicine_name);
        }
        if (!empty($generic_name)) {
            $this->db->like("m.generic_name", $generic_name);
        }
        if (!empty($brand_id)) {
            $this->db->where("m.brand_id", $brand_id);
        }
        if (!empty($category)) {
            $this->db->like("m.category", $category);
        }
        // --- END FILTERS ---

        $this->db->order_by("m.medicine_name", "ASC");

        return $this->db->get()->result();
    }

      public function stationary_housekeeping_center($center_id = null,$medicine_id = null,$batch_number = null,$status = null,$department = null) 
    {
        // try {
            // Select columns - ensure pack_size is explicitly included from medicines table
            // Using IFNULL (MySQL) to handle NULL pack_size values (defaults to 1)
            // This ensures pack_size is always returned, even if medicine doesn't have it set
            $this->db->select(
                "ccs.*, mb.batch_number, mb.medicine_id, IFNULL(m.pack_size, 1) as pack_size, mb.expiry_date, mb.purchase_price, mb.selling_price, mb.mrp, m.medicine_name, m.medicine_code, b.brand_name as brand_name, v.name as vendor_name, c.center_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days",
            );
            $this->db->from("center_stocks ccs");
            $this->db->join("medicine_batches mb", "ccs.batch_id = mb.id");
            $this->db->join("medicines m", "mb.medicine_id = m.id");
            $this->db->where("m.medicine_code NOT LIKE 'HK_%'");
            $this->db->where("m.medicine_code NOT LIKE 'ST_%'");
            $this->db->join("medicine_brands b",
                "m.brand_id = b.id",
            );
            $this->db->join(
                $this->config->item("db_prefix") . "vendors v",
                "mb.vendor_id = v.ID",
            );
            $this->db->join("hms_centers c", "ccs.center_id = c.ID");
            $this->db->where("m.medicine_code LIKE 'HK_%'");
            $this->db->or_where("m.medicine_code LIKE 'ST_%'");
            if ($center_id && $center_id != "") {
                $this->db->where("ccs.center_id", $center_id);
            }

            if ($medicine_id && $medicine_id != "") {
                $this->db->where("mb.medicine_id", $medicine_id);
            }

            if ($batch_number && $batch_number != "") {
                $this->db->like("mb.batch_number", $batch_number);
            }

            if ($status && $status != "") {
                $this->db->where("ccs.status", $status);
            }

            // if ($department && $department != "") {
            //     $this->db->where("ccs.department", $department);
            // }
            // if ((isset($_SESSION['logged_billing_manager']) && $_SESSION['logged_billing_manager']['role'] == 'billing_manager') || (isset($_SESSION['logged_stock_manager']) && $_SESSION['logged_stock_manager']['role'] == 'stock_manager')){
            // // if (isset($_SESSION['logged_billing_manager']) && $_SESSION['logged_billing_manager']['role'] == 'billing_manager') {
            //     $this->db->where('ccs.center_id', $this->get_center_id($_SESSION['logged_billing_manager']['center']));
            // }
            $center = null;
            if (!empty($_SESSION['logged_billing_manager']) &&
                ($_SESSION['logged_billing_manager']['role'] ?? '') === 'billing_manager') {
                $center = $_SESSION['logged_billing_manager']['center'];
                $department = $_SESSION['logged_billing_manager']['department'] ?? null;
            }
            if (!empty($_SESSION['logged_stock_manager']) &&
                ($_SESSION['logged_stock_manager']['role'] ?? '') === 'stock_manager') {
                $center = $_SESSION['logged_stock_manager']['center'];
                $department = $_SESSION['logged_stock_manager']['department'] ?? null;
            }
            if ($center !== null) {
                $this->db->where('ccs.center_id', $this->get_center_id($center));
            }
            // Filter by department if available
            if ($department !== null && $department !== '') {
                if ($department == 'billing') {
                    $this->db->like('ccs.department', 'CASH MEDICINE');
                }elseif($department == 'Embryologist Basant Lok')
                {
                    $this->db->like('ccs.department', 'Embryology Basant Lok');
                }
                 else {
                    $this->db->like('ccs.department', $department);
                }
            }
            $this->db->order_by("mb.expiry_date", "ASC");
            $results = $this->db->get()->result();
            
            // Post-process results to ensure pack_size is always set (fallback safety)
            foreach ($results as $result) {
                // Ensure pack_size is always set and valid
                if (!isset($result->pack_size) || $result->pack_size === null || $result->pack_size === '' || $result->pack_size == 0) {
                    $result->pack_size = 1;
                } else {
                    // Ensure pack_size is numeric
                    $result->pack_size = floatval($result->pack_size);
                    if ($result->pack_size <= 0) {
                        $result->pack_size = 1;
                    }
                }
            }
            
            return $results;
        // } catch (Exception $e) {
        //     return [];
        // }
    }



  /**
     * Insert or update medicine central stock configuration
     * @param array $data
     * @return bool
     */

    public function get_medicine_central_stock_config($id) {
        return $this->db->where('id', $id)
                        ->get('central_stocks')
                        ->row();
    }

public function save_medicine_central_stock_config($data) {
    try {
        // Pehle check karein ki $data mein 'id' hai ya nahi
        if (!isset($data['id']) || empty($data['id'])) {
             return false; 
        }

        $id = $data['id'];
        
        // Check if record exists
        $existing = $this->get_medicine_central_stock_config($id);

        if ($existing) {
            // Update existing record
            unset($data['id']); // Update query se ID hata dein
            $this->db->where('id', $id);
            return $this->db->update('central_stocks', $data);
        } else {
            // Insert new record
            return $this->db->insert('central_stocks', $data);
        }
    } catch (Exception $e) {
        log_message('error', 'Error: ' . $e->getMessage());
        return false;
    }
}




public function count_medicine_returns($f = []) {
    $this->apply_return_filters($f);
    return $this->db->count_all_results('medicine_returns');
}

public function get_medicine_returns_paged($f = [], $limit, $offset) {
    $this->apply_return_filters($f);
    $this->db->limit($limit, $offset);
    $this->db->order_by('id', 'DESC');
    return $this->db->get('medicine_returns')->result();
}

private function apply_return_filters($f) {
    if(!empty($f['search'])) {
        $this->db->group_start();
        $this->db->like('return_number', $f['search']);
        $this->db->or_like('patient_name', $f['search']);
        $this->db->or_like('receipt_number', $f['search']);
        $this->db->group_end();
    }
    if(!empty($f['center_id'])) $this->db->where('center_id', $f['center_id']);
    if(!empty($f['status']))    $this->db->where('status', $f['status']);
    if(!empty($f['start_date'])) $this->db->where('return_date >=', $f['start_date']);
    if(!empty($f['end_date']))   $this->db->where('return_date <=', $f['end_date']);
}

}
