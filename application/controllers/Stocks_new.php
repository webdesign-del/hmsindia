<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Stocks_new extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Stock_model_new");
        $this->load->model("Center_model");
        $this->load->library("form_validation");
        $this->load->helper("form");
        $this->load->helper("url_helper");
        $this->load->helper("myhelper");
        $this->load->library("session");
        $this->load->model("order_model");
        $this->load->model("stock_model");
        $this->load->model("vendors_model");
        $this->load->model("billings_model");
        $this->load->library("pagination");
    }

    // ===============================================
    // DASHBOARD
    // ===============================================

    public function dashboard()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get dashboard summary data
            $data[
                "dashboard_summary"
            ] = $this->Stock_model_new->get_dashboard_summary();

            // Get low stock alerts
            $data[
                "low_stock_alerts"
            ] = $this->Stock_model_new->get_low_stock_alerts();

            // Get expiry alerts
            $data[
                "expiry_alerts"
            ] = $this->Stock_model_new->get_expiry_alerts();

            // Get recent sales
            $data["recent_sales"] = $this->Stock_model_new->get_recent_sales(
                10,
            );

            // Get recent transfers
            $data[
                "recent_transfers"
            ] = $this->Stock_model_new->get_recent_transfers(10);

            // Get sales analytics (last 30 days)
            $data[
                "sales_analytics"
            ] = $this->Stock_model_new->get_sales_analytics(30);

            // Get transfer analytics (last 30 days)
            $data[
                "transfer_analytics"
            ] = $this->Stock_model_new->get_transfer_analytics(30);

            // Get top selling medicines
            $data[
                "top_selling_medicines"
            ] = $this->Stock_model_new->get_top_selling_medicines(10);

            // Get center-wise stock summary
            $data[
                "center_stock_summary"
            ] = $this->Stock_model_new->get_center_stock_summary();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/dashboard", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // MEDICINE MANAGEMENT
    // ===============================================

    public function medicines()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            $data["brands"] = $this->Stock_model_new->get_medicine_brands();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/medicines", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_medicine()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_medicine") {
                $this->form_validation->set_rules(
                    "medicine_code",
                    "Medicine Code",
                    "required|is_unique[medicines.medicine_code]",
                );
                $this->form_validation->set_rules(
                    "medicine_name",
                    "Medicine Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "brand_id",
                    "Brand",
                    "required",
                );
                $this->form_validation->set_rules(
                    "generic_name",
                    "Generic Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "strength",
                    "Strength",
                    "required",
                );
                $this->form_validation->set_rules("unit", "Unit", "required");
                $this->form_validation->set_rules(
                    "category",
                    "Category",
                    "required",
                );
                $this->form_validation->set_rules(
                    "min_stock_level",
                    "Minimum Stock Level",
                    "required|numeric",
                );
                $this->form_validation->set_rules(
                    "max_stock_level",
                    "Maximum Stock Level",
                    "required|numeric",
                );

                if ($this->form_validation->run() == true) {
                    $medicine_data = [
                        "medicine_code" => $this->input->post("medicine_code"),
                        "brand_id" => $this->input->post("brand_id"),
                        "medicine_name" => $this->input->post("medicine_name"),
                        "generic_name" => $this->input->post("generic_name"),
                        "strength" => $this->input->post("strength"),
                        "unit" => $this->input->post("unit"),
                        "category" => $this->input->post("category"),
                        "pack_size" => $this->input->post("pack_size"),
                        "hsn_code" => $this->input->post("hsn_code"),
                        "gst_rate" => $this->input->post("gst_rate"),
                        "min_stock_level" => $this->input->post(
                            "min_stock_level",
                        ),
                        "max_stock_level" => $this->input->post(
                            "max_stock_level",
                        ),
                        "reorder_level" => $this->input->post("reorder_level"),
                        "is_narcotic" => $this->input->post("is_narcotic")
                            ? 1
                            : 0,
                        "is_controlled_substance" => $this->input->post(
                            "is_controlled_substance",
                        )
                            ? 1
                            : 0,
                        "is_psychotropic" => $this->input->post(
                            "is_psychotropic",
                        )
                            ? 1
                            : 0,
                        "status" => "active",
                    ];
                    $result = $this->Stock_model_new->add_medicine(
                        $medicine_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Medicine added successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error adding medicine!",
                        );
                    }

                    redirect("stocks_new/medicines");
                }
            }

            $data["brands"] = $this->Stock_model_new->get_medicine_brands();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_medicine", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_medicine($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "update_medicine") {
                $this->form_validation->set_rules(
                    "medicine_name",
                    "Medicine Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "brand_id",
                    "Brand",
                    "required",
                );
                $this->form_validation->set_rules(
                    "generic_name",
                    "Generic Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "strength",
                    "Strength",
                    "required",
                );
                $this->form_validation->set_rules("unit", "Unit", "required");
                $this->form_validation->set_rules(
                    "category",
                    "Category",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $medicine_data = [
                        "brand_id" => $this->input->post("brand_id"),
                        "medicine_name" => $this->input->post("medicine_name"),
                        "generic_name" => $this->input->post("generic_name"),
                        "strength" => $this->input->post("strength"),
                        "unit" => $this->input->post("unit"),
                        "category" => $this->input->post("category"),
                        "pack_size" => $this->input->post("pack_size"),
                        "hsn_code" => $this->input->post("hsn_code"),
                        "gst_rate" => $this->input->post("gst_rate"),
                        "min_stock_level" => $this->input->post(
                            "min_stock_level",
                        ),
                        "max_stock_level" => $this->input->post(
                            "max_stock_level",
                        ),
                        "reorder_level" => $this->input->post("reorder_level"),
                        "is_narcotic" => $this->input->post("is_narcotic")
                            ? 1
                            : 0,
                        "is_controlled_substance" => $this->input->post(
                            "is_controlled_substance",
                        )
                            ? 1
                            : 0,
                        "is_psychotropic" => $this->input->post(
                            "is_psychotropic",
                        )
                            ? 1
                            : 0,
                        "status" => $this->input->post("status"),
                    ];

                    $result = $this->Stock_model_new->update_medicine(
                        $id,
                        $medicine_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Medicine updated successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error updating medicine!",
                        );
                    }

                    redirect("stocks_new/medicines");
                }
            }

            $data["medicine"] = $this->Stock_model_new->get_medicine_by_id($id);
            $data["brands"] = $this->Stock_model_new->get_medicine_brands();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/edit_medicine", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // BATCH MANAGEMENT
    // ===============================================

    public function batches()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $medicine_id = $this->input->get("medicine_id");
            $vendor_id = $this->input->get("vendor_id");
            $batch_number = $this->input->get("batch_number");
            $batch_status = $this->input->get("batch_status");

            $data["batches"] = $this->Stock_model_new->get_all_batches(
                $medicine_id,
                $vendor_id,
                $batch_number,
                $batch_status,
            );
            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            $data["vendors"] = $this->Stock_model_new->get_vendors();
            $data["selected_medicine_id"] = $medicine_id;
            $data["selected_vendor_id"] = $vendor_id;
            $data["selected_batch_number"] = $batch_number;
            $data["selected_batch_status"] = $batch_status;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/batches", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // public function add_batch()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         if ($this->input->post("action") == "add_batch") {
    //             $this->form_validation->set_rules(
    //                 "medicine_id",
    //                 "Medicine",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "vendor_id",
    //                 "Vendor",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "batch_number",
    //                 "Batch Number",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "expiry_date",
    //                 "Expiry Date",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "purchase_price",
    //                 "Purchase Price",
    //                 "required|numeric",
    //             );
    //             $this->form_validation->set_rules(
    //                 "selling_price",
    //                 "Selling Price",
    //                 "required|numeric",
    //             );
    //             $this->form_validation->set_rules(
    //                 "quantity_purchased",
    //                 "Quantity Purchased",
    //                 "required|numeric",
    //             );

    //             if ($this->form_validation->run() == true) {
    //                 // Generate unique batch number
    //                 $batch_number = $this->generate_unique_batch_number(
    //                     $this->input->post("medicine_id"),
    //                     $this->input->post("batch_number"),
    //                 );

    //                 $batch_data = [
    //                     "medicine_id" => $this->input->post("medicine_id"),
    //                     "vendor_id" => $this->input->post("vendor_id"),
    //                     "batch_number" => $batch_number,
    //                     "manufacturing_date" => $this->input->post(
    //                         "manufacturing_date",
    //                     ),
    //                     "expiry_date" => $this->input->post("expiry_date"),
    //                     "purchase_price" => $this->input->post(
    //                         "purchase_price",
    //                     ),
    //                     "selling_price" => $this->input->post("selling_price"),
    //                     "mrp" => $this->input->post("mrp"),
    //                     "quantity_purchased" => $this->input->post(
    //                         "quantity_purchased",
    //                     ),
    //                     "quantity_remaining" => $this->input->post(
    //                         "quantity_purchased",
    //                     ),
    //                     "purchase_date" => $this->input->post("purchase_date"),
    //                     "invoice_number" => $this->input->post(
    //                         "invoice_number",
    //                     ),
    //                     "invoice_date" => $this->input->post("invoice_date"),
    //                     "quality_status" => $this->input->post(
    //                         "quality_status",
    //                     ),
    //                     "batch_status" => "ACTIVE",
    //                     "remarks" => $this->input->post("remarks"),
    //                     "created_by" => $this->get_employee_id_from_number(
    //                         $_SESSION["logged_central_stock_manager"][
    //                             "employee_number"
    //                         ],
    //                     ),
    //                 ];

    //                 $result = $this->Stock_model_new->add_batch($batch_data);

    //                 if ($result) {
    //                     $this->session->set_flashdata(
    //                         "success",
    //                         "Batch added successfully!",
    //                     );
    //                 } else {
    //                     $this->session->set_flashdata(
    //                         "error",
    //                         "Error adding batch!",
    //                     );
    //                 }

    //                 redirect("stocks_new/batches");
    //             }
    //         }
    //         if($this->input->get("medicine_id") != null){
    //             $data["selected_medicine_id"] = $this->input->get("medicine_id");
    //         }

    //         $data["medicines"] = $this->Stock_model_new->get_all_medicines($data["selected_medicine_id"] ?? null);
    //         $data["vendors"] = $this->Stock_model_new->get_vendors();

    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/add_batch", $data);
    //         $this->load->view($template["footer"]);
    //     } else {
    //         header("location:" . base_url() . "");
    //         die();
    //     }
    // }
    public function add_batch()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            
            $this->load->model('Stock_model_new'); // Load model at the top

            // --- Handle Form Submission ---
            if ($this->input->post("action") == "add_batch") {
                $this->form_validation->set_rules("medicine_id", "Medicine", "required");
                $this->form_validation->set_rules("vendor_id", "Vendor", "required");
                $this->form_validation->set_rules("batch_number", "Batch Number", "required");
                $this->form_validation->set_rules("expiry_date", "Expiry Date", "required");
                $this->form_validation->set_rules("purchase_price", "Purchase Price", "required|numeric");
                $this->form_validation->set_rules("selling_price", "Selling Price", "required|numeric");
                $this->form_validation->set_rules("quantity_purchased", "Quantity Purchased", "required|numeric|greater_than[0]");

                if ($this->form_validation->run() == true) {
                    
                    // --- Get Employee ID (Make sure function exists) ---
                    // Assuming get_employee_id_from_number() is a helper you've defined
                    $created_by_id = $this->get_employee_id_from_number(
                        $_SESSION["logged_central_stock_manager"]["employee_number"]
                    );

                    $batch_data = [
                        "medicine_id" => $this->input->post("medicine_id"),
                        "vendor_id" => $this->input->post("vendor_id"),
                        "batch_number" => $this->input->post("batch_number"), // Unique check should be in model
                        "manufacturing_date" => $this->input->post("manufacturing_date") ?: NULL,
                        "expiry_date" => $this->input->post("expiry_date"),
                        "purchase_price" => $this->input->post("purchase_price"),
                        "selling_price" => $this->input->post("selling_price"),
                        "mrp" => $this->input->post("mrp") ?: NULL,
                        "quantity_purchased" => $this->input->post("quantity_purchased"),
                        "quantity_remaining" => $this->input->post("quantity_purchased"), // Set remaining to purchased
                        "purchase_date" => $this->input->post("purchase_date") ?: date('Y-m-d'),
                        "invoice_number" => $this->input->post("invoice_number"),
                        "invoice_date" => $this->input->post("invoice_date") ?: NULL,
                        "quality_status" => $this->input->post("quality_status") ?: 'PENDING',
                        "batch_status" => "ACTIVE",
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $created_by_id,
                        "created_at" => date("Y-m-d H:i:s")
                    ];

                    // --- Call Model (Assuming add_batch also adds to central_stocks) ---
                    $result = $this->Stock_model_new->add_batch($batch_data); 

                    if ($result) {
                        $this->session->set_flashdata("success", "Batch added successfully!");
                    } else {
                        // Check for specific unique constraint error
                        if ($this->db->error()['code'] == 1062) {
                             $this->session->set_flashdata("error", "Error: A batch with this number already exists for this medicine.");
                        } else {
                             $this->session->set_flashdata("error", "Error adding batch!");
                        }
                    }
                    redirect("stocks_new/batches"); // Redirect to batch list
                }
                // --- End Form Submission ---
            }

            // --- Prepare Data for View ---
            $data = [];
            $data["selected_medicine_details"] = null; // Default to null

            // Check for pre-selection from URL (GET request)
            if ($this->input->get("medicine_id")) {
                $selected_id = (int)$this->input->get("medicine_id");
                $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
            }
            // Check for re-population after validation fail (POST request)
            elseif ($this->input->post("medicine_id")) {
                 $selected_id = (int)$this->input->post("medicine_id");
                 $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
            }
            
            // Do NOT load all medicines. AJAX will handle searching.
            // $data["medicines"] = ...; 

            // Load vendors
            $data["vendors"] = $this->Stock_model_new->get_all_vendors(); // Use your function for this

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_batch", $data);
            $this->load->view($template["footer"]);

        } else {
            redirect(base_url());
        }
    }

    /**
     * AJAX endpoint for Select2 medicine search
     */
    public function search_medicines()
    {
        $logg = checklogin();
        if (!$logg || $logg["status"] != true) {
             $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized']));
             return;
        }

        $search_term = $this->input->get('q');
        $this->load->model('Stock_model_new');
        // You need to create this model function:
        $medicines = $this->Stock_model_new->search_medicines_for_select2($search_term); 
        
        $this->output->set_content_type('application/json')->set_output(json_encode($medicines));
    }

    // AJAX endpoint for medicine search
    // public function search_medicines()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         $search_term = $this->input->get("q");
    //         $medicines = $this->Stock_model_new->search_medicines($search_term);

    //         $results = [];
    //         foreach ($medicines as $medicine) {
    //             $results[] = [
    //                 "id" => $medicine->id,
    //                 "text" =>
    //                     $medicine->medicine_name .
    //                     " (" .
    //                     $medicine->generic_name .
    //                     ")",
    //                 "medicine_name" => $medicine->medicine_name,
    //                 "generic_name" => $medicine->generic_name,
    //                 "medicine_code" => $medicine->medicine_code,
    //                 "brand_name" => $medicine->brand_name,
    //             ];
    //         }

    //         $this->output
    //             ->set_content_type("application/json")
    //             ->set_output(json_encode($results));
    //     } else {
    //         $this->output
    //             ->set_status_header(401)
    //             ->set_content_type("application/json")
    //             ->set_output(json_encode(["error" => "Unauthorized"]));
    //     }
    // }

    // ===============================================
    // CENTRAL STOCKS MANAGEMENT
    // ===============================================

    public function central_stocks()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $medicine_id = $this->input->get("medicine_id");
            $batch_number = $this->input->get("batch_number");
            $status = $this->input->get("status");

            $data[
                "central_stocks"
            ] = $this->Stock_model_new->get_central_stocks(
                $medicine_id,
                $batch_number,
                $status,
            );
            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            $data["selected_medicine_id"] = $medicine_id;
            $data["selected_batch_number"] = $batch_number;
            $data["selected_status"] = $status;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/central_stocks", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function update_central_stock_status()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $stock_id = $this->input->post("stock_id");
            $status = $this->input->post("status");

            if (
                $this->Stock_model_new->update_central_stock_status(
                    $stock_id,
                    $status,
                )
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Stock status updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update stock status",
                ]);
            }
        }
    }

    // ===============================================
    // CENTER STOCKS MANAGEMENT
    // ===============================================

    public function center_stocks()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $center_id = $this->input->get("center_id");
            $medicine_id = $this->input->get("medicine_id");
            $batch_number = $this->input->get("batch_number");
            $status = $this->input->get("status");
            $data["center_stocks"] = $this->Stock_model_new->get_center_stocks(
                $center_id,
                $medicine_id,
                $batch_number,
                $status,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            $data["selected_center_id"] = $center_id;
            $data["selected_medicine_id"] = $medicine_id;
            $data["selected_batch_number"] = $batch_number;
            $data["selected_status"] = $status;
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/center_stocks", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function update_center_stock_status()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $stock_id = $this->input->post("stock_id");
            $status = $this->input->post("status");

            if (
                $this->Stock_model_new->update_center_stock_status(
                    $stock_id,
                    $status,
                )
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Stock status updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update stock status",
                ]);
            }
        }
    }

    // ===============================================
    // BATCH STATUS MANAGEMENT
    // ===============================================

    public function update_batch_status()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $batch_id = $this->input->post("batch_id");
            $status = $this->input->post("status");

            if (
                $this->Stock_model_new->update_batch_status($batch_id, $status)
            ) {
                echo json_encode([
                    "success" => true,
                    "message" => "Batch status updated successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to update batch status",
                ]);
            }
        }
    }

    // ===============================================
    // STOCK MANAGEMENT
    // ===============================================

    public function stock_levels()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $center_id = $this->input->get("center_id");
            $medicine_name = $this->input->get("medicine_name");
            $stock_status = $this->input->get("stock_status");

            $data[
                "stock_levels"
            ] = $this->Stock_model_new->get_current_stock_levels(
                $center_id,
                $medicine_name,
                $stock_status,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["selected_center"] = $center_id;
            $data["selected_medicine_name"] = $medicine_name;
            $data["selected_stock_status"] = $stock_status;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_levels", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function stock_summary()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "stock_summary"
            ] = $this->Stock_model_new->get_medicine_stock_summary();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_summary", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // STOCK TRANSFERS
    // ===============================================

    public function transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $to_center_id = $this->input->get("to_center_id");
            $status = $this->input->get("status");
            $data["transfers"] = $this->Stock_model_new->get_all_transfers(
                $transfer_type,
                $from_center_id,
                $to_center_id,
                $status,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfers", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_transfer()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_transfer") {
                $this->form_validation->set_rules(
                    "transfer_type",
                    "Transfer Type",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_center_id",
                    "Destination Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "transfer_date",
                    "Transfer Date",
                    "required",
                );
                if ($this->form_validation->run() == true) {
                    $transfer_data = [
                        "transfer_type" => $this->input->post("transfer_type"),
                        "from_center_id" => $this->input->post(
                            "from_center_id",
                        ),
                        "to_center_id" => $this->input->post("to_center_id"),
                        "transfer_date" => $this->input->post("transfer_date"),
                        "from_department" => $this->input->post(
                            "from_department",
                        ),
                        "to_department" => $this->input->post("to_department"),
                        "expected_delivery_date" => $this->input->post(
                            "expected_delivery_date",
                        ),
                        "remarks" => $this->input->post("remarks"),
                        "created_by" =>
                            $this->session->userdata("employee_number") ?: 1, // Default to 1 if no session
                        "status" => "DRAFT",
                    ];
                    $transfer_id = $this->Stock_model_new->add_transfer(
                        $transfer_data,
                    );
                    if ($transfer_id) {
                        $transfer_items = $this->input->post("transfer_items");
                        if (!empty($transfer_items)) {
                            foreach (
                                $transfer_items
                                as $batch_id => $item_data
                            ) {
                                if (
                                    isset($item_data["quantity"]) &&
                                    $item_data["quantity"] > 0
                                ) {
                                    $item_data["transfer_id"] = $transfer_id;
                                    $item_data["batch_id"] = $batch_id;
                                    $this->Stock_model_new->add_transfer_item(
                                        $item_data,
                                    );
                                }
                            }
                        }
                        $this->session->set_flashdata(
                            "success",
                            "Transfer created successfully!",
                        );
                        redirect("stocks_new/edit_transfer/" . $transfer_id);
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error creating transfer!",
                        );
                    }
                }
            }
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["departments"] = $this->get_departments_by_center();
            $data["all_employees"] = $this->get_employee_list();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_transfer", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function get_available_stocks_for_transfer()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $from_department = $this->input->get("from_department");
            $from_employee_number = $this->input->get("from_employee_number");

            $stocks = $this->Stock_model_new->get_available_stocks_for_transfer(
                $transfer_type,
                $from_center_id,
                $from_department,
                $from_employee_number,
            );

            header("Content-Type: application/json");
            echo json_encode($stocks);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_transfer($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_transfer_item") {
                $this->form_validation->set_rules(
                    "batch_id",
                    "Batch",
                    "required",
                );
                $this->form_validation->set_rules(
                    "quantity_transferred",
                    "Quantity",
                    "required|numeric",
                );
                if ($this->form_validation->run() == true) {
                    $item_data = [
                        "transfer_id" => $id,
                        "batch_id" => $this->input->post("batch_id"),
                        "quantity_transferred" => $this->input->post(
                            "quantity_transferred",
                        ),
                        "unit_price" => $this->input->post("unit_price"),
                        "total_price" =>
                            $this->input->post("quantity_transferred") *
                            $this->input->post("unit_price"),
                        "remarks" => $this->input->post("remarks"),
                    ];
                    $result = $this->Stock_model_new->add_transfer_item(
                        $item_data,
                    );
                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Transfer item added successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error adding transfer item!",
                        );
                    }
                    redirect("stocks_new/edit_transfer/" . $id);
                }
            }
            $data["transfer"] = $this->Stock_model_new->get_transfer_by_id($id);
            $data[
                "transfer_items"
            ] = $this->Stock_model_new->get_transfer_items($id);
            $data[
                "batches"
            ] = $this->Stock_model_new->get_available_stocks_for_transfer(
                $data["transfer"]->transfer_type,
                $data["transfer"]->from_center_id,
                $data["transfer"]->from_department,
                null,
                null,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["departments"] = $this->get_departments_by_center();
            $data["all_employees"] = $this->get_employee_list();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/edit_transfer", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function approve_transfer($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->approve_transfer(
                $id,
                $this->session->userdata("employee_number"),
            );

            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Transfer approved successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error approving transfer!",
                );
            }

            redirect("stocks_new/transfers");
        }
    }

    public function transfer_details($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["transfer"] = $this->Stock_model_new->get_transfer_by_id($id);
            $data[
                "transfer_items"
            ] = $this->Stock_model_new->get_transfer_items($id);

            if (!$data["transfer"]) {
                $this->session->set_flashdata("error", "Transfer not found!");
                redirect("stocks_new/transfers");
            }

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_details", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function transfer_debug()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_debug");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function transfer_debug_detailed()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_debug_detailed");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function test_model_method()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->get_available_stocks_for_transfer(
                "CENTRAL_TO_CENTER",
            );

            header("Content-Type: application/json");
            echo json_encode([
                "success" => true,
                "count" => count($result),
                "data" => $result,
            ]);
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    public function remove_transfer_item($item_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->remove_transfer_item($item_id);

            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Transfer item removed successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error removing transfer item!",
                );
            }

            // Redirect back to the referring page or transfers list
            $referrer = $this->input->server("HTTP_REFERER");
            if ($referrer) {
                redirect($referrer);
            } else {
                redirect("stocks_new/transfers");
            }
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function transfer_data_fix()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_data_fix");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function fix_transfer_totals()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $updated = 0;
            $total_items = 0;
            $total_value = 0;

            // Get all transfers
            $transfers = $this->db->get("stock_transfers")->result();

            foreach ($transfers as $transfer) {
                // Calculate totals for this transfer
                $totals = $this->db
                    ->select(
                        '
                    COUNT(id) as total_items,
                    SUM(quantity_transferred) as total_quantity,
                    SUM(total_price) as total_value
                ',
                    )
                    ->from("stock_transfer_items")
                    ->where("transfer_id", $transfer->id)
                    ->get()
                    ->row();

                // Update transfer record
                $this->db->where("id", $transfer->id);
                $this->db->update("stock_transfers", [
                    "total_items" => $totals->total_items ?: 0,
                    "total_quantity" => $totals->total_quantity ?: 0,
                    "total_value" => $totals->total_value ?: 0,
                ]);

                $updated++;
                $total_items += $totals->total_items ?: 0;
                $total_value += $totals->total_value ?: 0;
            }

            header("Content-Type: application/json");
            echo json_encode([
                "success" => true,
                "updated" => $updated,
                "total_items" => $total_items,
                "total_value" => $total_value,
            ]);
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    public function bulk_approve_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $transfer_ids = $this->input->post("transfer_ids");
            $approved_by = $this->session->userdata("employee_number") ?: 1;

            if (empty($transfer_ids) || !is_array($transfer_ids)) {
                $this->session->set_flashdata(
                    "error",
                    "No transfers selected for approval!",
                );
                redirect("stocks_new/transfers");
            }

            $result = $this->Stock_model_new->bulk_approve_transfers(
                $transfer_ids,
                $approved_by,
            );

            if ($result["success_count"] > 0) {
                $this->session->set_flashdata(
                    "success",
                    $result["success_count"] .
                        " transfers approved successfully!",
                );
            }

            if ($result["failed_count"] > 0) {
                $this->session->set_flashdata(
                    "error",
                    $result["failed_count"] .
                        " transfers failed to approve. Check if they have items.",
                );
            }

            redirect("stocks_new/transfers");
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function approve_all_pending_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get all pending transfers that have items
            $pending_transfers = $this->db
                ->select("st.id")
                ->from("stock_transfers st")
                ->join("stock_transfer_items sti", "st.id = sti.transfer_id")
                ->where_in("st.status", ["DRAFT", "PENDING", "APPROVED"])
                ->group_by("st.id")
                ->get()
                ->result();

            $transfer_ids = array_column($pending_transfers, "id");
            $approved_by = $this->session->userdata("employee_number") ?: 1;

            if (empty($transfer_ids)) {
                $this->session->set_flashdata(
                    "error",
                    "No pending transfers with items found!",
                );
                redirect("stocks_new/transfers");
            }

            $result = $this->Stock_model_new->bulk_approve_transfers(
                $transfer_ids,
                $approved_by,
            );

            if ($result["success_count"] > 0) {
                $this->session->set_flashdata(
                    "success",
                    $result["success_count"] .
                        " transfers approved and completed successfully!",
                );
            }

            if ($result["failed_count"] > 0) {
                $this->session->set_flashdata(
                    "error",
                    $result["failed_count"] . " transfers failed to approve.",
                );
            }

            redirect("stocks_new/transfers");
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function database_fix()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/database_fix");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function run_database_fix()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $results = [];

            try {
                // Add approved_date column
                $sql1 =
                    "ALTER TABLE `stock_transfers` ADD COLUMN `approved_date` TIMESTAMP NULL AFTER `approved_by`";
                $this->db->query($sql1);
                $results[] = "Added approved_date column";
            } catch (Exception $e) {
                $results[] = "approved_date column: " . $e->getMessage();
            }

            try {
                // Add from_department column
                $sql2 =
                    "ALTER TABLE `stock_transfers` ADD COLUMN `from_department` VARCHAR(100) NULL AFTER `from_center_id`";
                $this->db->query($sql2);
                $results[] = "Added from_department column";
            } catch (Exception $e) {
                $results[] = "from_department column: " . $e->getMessage();
            }

            try {
                // Add to_department column
                $sql3 =
                    "ALTER TABLE `stock_transfers` ADD COLUMN `to_department` VARCHAR(100) NULL AFTER `to_center_id`";
                $this->db->query($sql3);
                $results[] = "Added to_department column";
            } catch (Exception $e) {
                $results[] = "to_department column: " . $e->getMessage();
            }

            try {
                // Add indexes
                $sql4 =
                    "ALTER TABLE `stock_transfers` ADD INDEX `idx_status` (`status`)";
                $this->db->query($sql4);
                $results[] = "Added status index";
            } catch (Exception $e) {
                $results[] = "status index: " . $e->getMessage();
            }

            header("Content-Type: application/json");
            echo json_encode([
                "success" => true,
                "results" => $results,
            ]);
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    public function stock_levels_debug()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_levels_debug");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function fix_stock_levels()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $results = [];

            try {
                // Drop existing view if it exists
                $this->db->query("DROP VIEW IF EXISTS v_current_stock_levels");
                $results[] = "Dropped existing view";
            } catch (Exception $e) {
                $results[] = "Drop view: " . $e->getMessage();
            }

            try {
                // Create corrected view with proper aggregation
                $create_view_sql = "
                CREATE VIEW v_current_stock_levels AS
                SELECT
                    m.id as medicine_id,
                    m.medicine_code,
                    m.medicine_name,
                    m.generic_name,
                    COALESCE(b.name, 'Unknown') as brand_name,
                    mb.id as batch_id,
                    mb.batch_number,
                    mb.expiry_date,
                    DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                    mb.purchase_price,
                    mb.selling_price,
                    mb.mrp,
                    mb.quantity_remaining,
                    SUM(COALESCE(cs.quantity, 0)) as central_quantity,
                    SUM(COALESCE(ccs.quantity, 0)) as center_quantity,
                    SUM(COALESCE(cs.quantity, 0) + COALESCE(ccs.quantity, 0)) as total_quantity,
                    GROUP_CONCAT(DISTINCT COALESCE(c.center_name, 'Central') SEPARATOR ', ') as center_names,
                    GROUP_CONCAT(DISTINCT ccs.center_id SEPARATOR ',') as center_ids,
                    mb.batch_status,
                    mb.quality_status,
                    CASE
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN 'EXPIRED'
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN 'EXPIRING_SOON'
                        ELSE 'FRESH'
                    END as expiry_status,
                    ROW_NUMBER() OVER (
                        ORDER BY mb.expiry_date ASC, mb.created_at ASC
                    ) as fifo_rank
                FROM medicines m
                LEFT JOIN hms_brands b ON m.brand_id = b.ID
                LEFT JOIN medicine_batches mb ON m.id = mb.medicine_id
                LEFT JOIN central_stocks cs ON mb.id = cs.batch_id
                LEFT JOIN center_stocks ccs ON mb.id = ccs.batch_id
                LEFT JOIN hms_centers c ON ccs.center_id = c.ID
                WHERE m.status = 'active'
                AND mb.batch_status = 'ACTIVE'
                AND (COALESCE(cs.quantity, 0) > 0 OR COALESCE(ccs.quantity, 0) > 0)
                GROUP BY m.id, mb.id
                ";

                $this->db->query($create_view_sql);
                $results[] = "Created corrected view";
            } catch (Exception $e) {
                $results[] = "Create view: " . $e->getMessage();
            }

            header("Content-Type: application/json");
            echo json_encode([
                "success" => true,
                "results" => $results,
            ]);
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    public function stock_calculation_test()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_calculation_test");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function center_to_center_debug()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/center_to_center_debug", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function test_center_to_center()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $from_center_id = $this->input->post("from_center_id");
            $to_center_id = $this->input->post("to_center_id");

            try {
                // Get available stocks for center to center transfer
                $stocks = $this->Stock_model_new->get_available_stocks_for_transfer(
                    "CENTER_TO_CENTER",
                    $from_center_id,
                );

                // Get center names
                $source_center = $this->db
                    ->select("center_name")
                    ->from("hms_centers")
                    ->where("ID", $from_center_id)
                    ->get()
                    ->row();
                $dest_center = $this->db
                    ->select("center_name")
                    ->from("hms_centers")
                    ->where("ID", $to_center_id)
                    ->get()
                    ->row();

                header("Content-Type: application/json");
                echo json_encode([
                    "success" => true,
                    "available_stocks" => count($stocks),
                    "source_center" => $source_center
                        ? $source_center->center_name
                        : "Unknown",
                    "destination_center" => $dest_center
                        ? $dest_center->center_name
                        : "Unknown",
                    "stocks" => $stocks,
                ]);
            } catch (Exception $e) {
                header("Content-Type: application/json");
                echo json_encode([
                    "success" => false,
                    "error" => $e->getMessage(),
                    "available_stocks" => 0,
                ]);
            }
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    public function fix_center_to_center()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $results = [];

            try {
                // Fix the CENTER_TO_CENTRAL section in get_available_stocks_for_transfer
                // This is a code fix that needs to be applied manually
                $results[] =
                    "CENTER_TO_CENTRAL section uses c.name instead of c.center_name - needs manual fix";

                // Check if center_stocks table has status column
                $columns = $this->db->list_fields("center_stocks");
                if (!in_array("status", $columns)) {
                    $this->db->query(
                        "ALTER TABLE center_stocks ADD COLUMN status VARCHAR(20) DEFAULT 'ACTIVE'",
                    );
                    $results[] = "Added status column to center_stocks table";
                } else {
                    $results[] =
                        "Status column already exists in center_stocks table";
                }

                // Check if central_stocks table has status column
                $columns = $this->db->list_fields("central_stocks");
                if (!in_array("status", $columns)) {
                    $this->db->query(
                        "ALTER TABLE central_stocks ADD COLUMN status VARCHAR(20) DEFAULT 'ACTIVE'",
                    );
                    $results[] = "Added status column to central_stocks table";
                } else {
                    $results[] =
                        "Status column already exists in central_stocks table";
                }

                // Update existing records to have ACTIVE status
                $this->db->query(
                    "UPDATE center_stocks SET status = 'ACTIVE' WHERE status IS NULL OR status = ''",
                );
                $this->db->query(
                    "UPDATE central_stocks SET status = 'ACTIVE' WHERE status IS NULL OR status = ''",
                );
                $results[] = "Updated existing records to have ACTIVE status";
            } catch (Exception $e) {
                $results[] = "Error: " . $e->getMessage();
            }

            header("Content-Type: application/json");
            echo json_encode([
                "success" => true,
                "results" => $results,
            ]);
        } else {
            header("Content-Type: application/json");
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
        }
    }

    // ===============================================
    // MULTI-ITEM STOCK TRANSFER
    // ===============================================

    public function multi_transfer()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "multi_transfer") {
                $this->form_validation->set_rules(
                    "from_center_id",
                    "Source Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "from_department",
                    "Source Department",
                    "required",
                );
                $this->form_validation->set_rules(
                    "from_employee_number",
                    "Source Employee",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_center_id",
                    "Destination Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_department",
                    "Destination Department",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_employee_number",
                    "Destination Employee",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $transfer_data = [
                        "from_center_id" => $this->input->post(
                            "from_center_id",
                        ),
                        "from_department" => $this->input->post(
                            "from_department",
                        ),
                        "from_employee_number" => $this->input->post(
                            "from_employee_number",
                        ),
                        "to_center_id" => $this->input->post("to_center_id"),
                        "to_department" => $this->input->post("to_department"),
                        "to_employee_number" => $this->input->post(
                            "to_employee_number",
                        ),
                        "transfer_date" => date("Y-m-d H:i:s"),
                        "remarks" => $this->input->post("remarks"),
                        "transferred_by" => $this->session->userdata(
                            "employee_number",
                        ),
                        "transfer_items" => $this->input->post(
                            "transfer_items",
                        ),
                    ];

                    $result = $this->Stock_model_new->process_multi_transfer(
                        $transfer_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Stock transfer completed successfully!",
                        );
                        redirect("stocks_new/stock_levels");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error processing transfer!",
                        );
                    }
                }
            }

            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["batches"] = $this->Stock_model_new->get_all_batches();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/multi_transfer", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function get_available_stocks()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $center_id = $this->input->get("center_id");
            $department = $this->input->get("department");
            $employee_number = $this->input->get("employee_number");

            $stocks = $this->Stock_model_new->get_stocks_by_location(
                $center_id,
                $department,
                $employee_number,
            );

            header("Content-Type: application/json");
            echo json_encode($stocks);
        }
    }

    public function get_employees_by_location()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $center_id = $this->input->get("center_id");
            $department = $this->input->get("department");
            $employees = $this->Stock_model_new->get_employees_by_location(
                $center_id,
                $department,
            );
            header("Content-Type: application/json");
            echo json_encode($employees);
        }
    }

    public function department_transfer()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "department_transfer") {
                $this->form_validation->set_rules(
                    "from_center_id",
                    "Source Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "from_department",
                    "Source Department",
                    "required",
                );
                $this->form_validation->set_rules(
                    "from_employee_number",
                    "Source Employee",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_center_id",
                    "Destination Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_department",
                    "Destination Department",
                    "required",
                );
                $this->form_validation->set_rules(
                    "to_employee_number",
                    "Destination Employee",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $transfer_data = [
                        "from_center_id" => $this->input->post(
                            "from_center_id",
                        ),
                        "from_department" => $this->input->post(
                            "from_department",
                        ),
                        "from_employee_number" => $this->input->post(
                            "from_employee_number",
                        ),
                        "to_center_id" => $this->input->post("to_center_id"),
                        "to_department" => $this->input->post("to_department"),
                        "to_employee_number" => $this->input->post(
                            "to_employee_number",
                        ),
                        "transfer_date" => date("Y-m-d H:i:s"),
                        "remarks" => $this->input->post("remarks"),
                        "transferred_by" => $this->session->userdata(
                            "employee_number",
                        ),
                        "transfer_items" => $this->input->post(
                            "transfer_items",
                        ),
                    ];

                    $result = $this->Stock_model_new->process_department_transfer(
                        $transfer_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Department transfer completed successfully!",
                        );
                        redirect("stocks_new/stock_levels");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error processing department transfer!",
                        );
                    }
                }
            }

            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["batches"] = []; // Will be loaded via AJAX

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/department_transfer", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // SALES MANAGEMENT
    // ===============================================

    public function sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["sales"] = $this->Stock_model_new->get_all_sales();
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/sales", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function get_appointment_details($appointment_id)
    {
        return $this->db
            ->select(
                "hms_patients.wife_name as patient_name,hms_patients.patient_id,hms_doctors.ID as doctor_id,hms_doctors.name as doctor_name",
            )
            ->from("hms_doctor_consultation")
            ->join(
                "hms_patients",
                "hms_doctor_consultation.patient_id = hms_patients.patient_id",
                "left",
            )
            ->join(
                "hms_doctors",
                "hms_doctor_consultation.doctor_id = hms_doctors.ID",
                "left",
            )
            ->where("appointment_id", $appointment_id)
            ->get()
            ->row();
    }

    public function add_sale()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_sale") {
                $this->form_validation->set_rules(
                    "center_id",
                    "Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "patient_name",
                    "Patient Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "sale_date",
                    "Sale Date",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    // Get employee ID from employee_number (required for foreign key constraint)
                    $employee_number = isset(
                        $_SESSION["logged_central_stock_manager"][
                            "employee_number"
                        ],
                    )
                        ? $_SESSION["logged_central_stock_manager"][
                            "employee_number"
                        ]
                        : null;

                    $created_by_id = $this->get_employee_id_from_number(
                        $employee_number,
                    );
                    if (!$created_by_id) {
                        $created_by_id = 1; // Default fallback
                    }

                    $sale_data = [
                        "center_id" => $this->input->post("center_id"),
                        "patient_id" => $this->input->post("patient_id"),
                        "patient_name" => $this->input->post("patient_name"),
                        "doctor_id" => $this->input->post("doctor_id"),
                        "doctor_name" => $this->input->post("doctor_name"),
                        "sale_date" => $this->input->post("sale_date"),
                        "sale_time" => date("H:i:s"),
                        "payment_method" => $this->input->post(
                            "payment_method",
                        ),
                        "payment_status" => $this->input->post(
                            "payment_status",
                        ),
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $created_by_id,
                        "status" => "DRAFT",
                    ];
                    $sale_id = $this->Stock_model_new->add_sale($sale_data);
                    if ($sale_id) {
                        $this->session->set_flashdata(
                            "success",
                            "Sale created successfully!",
                        );
                        redirect("stocks_new/edit_sale/" . $sale_id);
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error creating sale!",
                        );
                    }
                }
            }

            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $appointment_id = $this->input->get("appointment_id");
            if ($appointment_id) {
                $appointment = $this->get_appointment_details($appointment_id);
                // var_dump($appointment);
                if ($appointment) {
                    $data["patient_id"] = $appointment->patient_id;
                    $data["patient_name"] = $appointment->patient_name;
                    $data["doctor_id"] = $appointment->doctor_id;
                    $data["doctor_name"] = $appointment->doctor_name;
                }
            }
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_sale", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_sale($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_sale_item") {
                $this->form_validation->set_rules(
                    "batch_id",
                    "Batch",
                    "required",
                );
                $this->form_validation->set_rules(
                    "quantity_sold",
                    "Quantity",
                    "required|numeric",
                );
                if ($this->form_validation->run() == true) {
                    $item_data = [
                        "sale_id" => $id,
                        "batch_id" => $this->input->post("batch_id"),
                        "quantity_sold" => $this->input->post("quantity_sold"),
                        "unit_price" => $this->input->post("unit_price"),
                        "discount_amount" => $this->input->post("discount_amount"),
                        "tax_amount"=>$this->input->post("tax_amount"),
                        "subtotal" =>
                            $this->input->post("quantity_sold") *
                            $this->input->post("unit_price"),
                        "total" =>
                            $this->input->post("quantity_sold") *
                            $this->input->post("unit_price"),
                        "remarks" => $this->input->post("remarks"),
                    ];
                    $result = $this->Stock_model_new->add_sale_item($item_data);
                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Sale item added successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error adding sale item!",
                        );
                    }
                    var_dump($id);
                    redirect("stocks_new/edit_sale/" . $id);
                }
            }

            $data["sale"] = $this->Stock_model_new->get_sale_by_id($id);
            $data["sale_items"] = $this->Stock_model_new->get_sale_items($id);
            $data[
                "batches"
            ] = $this->Stock_model_new->get_available_batches_for_sale(
                $data["sale"]->center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/edit_sale", $data);
            $this->load->view($template["footer"]);
        }
    }

    public function remove_sale_item($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->remove_sale_item($id);
            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Sale item removed successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error removing sale item!",
                );
            }
            $referrer = $this->input->server("HTTP_REFERER");
            if ($referrer) {
                redirect($referrer);
            } else {
                redirect("stocks_new/sales");
            }
        }
    }
    public function confirm_sale($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->confirm_sale($id);

            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Sale confirmed successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error confirming sale!",
                );
            }

            redirect("stocks_new/sales");
        }
    }

    public function view_vendor_return($id)
    {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data = array();
            $this->load->model('Stock_model_new'); // Ensure model is loaded
            $data['return_details'] = $this->Stock_model_new->get_vendor_return_by_id($id);
            if(!$data['return_details']) {
                $this->session->set_flashdata('error', 'Vendor return report not found.');
                redirect('stocks_new/vendor_returns'); // Redirect to your list page
                return;
            }
            $data['return_items'] = $this->Stock_model_new->get_vendor_return_items_from_log($id);
            $template = get_header_template($logg['role']);
            $this->load->view($template["header"]);
            $this->load->view('stocks_new/view_vendor_return', $data); // Load the main content
            $this->load->view($template["footer"]); // Load the footer, NOT 'reports'
        } else {
            redirect(base_url());
        }
    }

    // ===============================================
    // REPORTS
    // ===============================================

    public function reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/reports");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function stock_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $center_id = $this->input->get("center_id");
            $data[
                "stock_levels"
            ] = $this->Stock_model_new->get_current_stock_levels($center_id);
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["selected_center"] = $center_id;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function sales_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = $this->input->get("start_date") ?: date("Y-m-01");
            $end_date = $this->input->get("end_date") ?: date("Y-m-d");
            $center_id = $this->input->get("center_id");

            $data["sales"] = $this->Stock_model_new->get_sales_report(
                $start_date,
                $end_date,
                $center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_center"] = $center_id;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/sales_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function todays_sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $today = date("Y-m-d");
            $center_id = $this->input->get("center_id");

            $data["sales"] = $this->Stock_model_new->get_sales_report(
                $today,
                $today,
                $center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $today;
            $data["end_date"] = $today;
            $data["selected_center"] = $center_id;
            $data["report_title"] = "Today's Sales";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/sales_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function weekly_sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = date("Y-m-d", strtotime("monday this week"));
            $end_date = date("Y-m-d", strtotime("sunday this week"));
            $center_id = $this->input->get("center_id");

            $data["sales"] = $this->Stock_model_new->get_sales_report(
                $start_date,
                $end_date,
                $center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_center"] = $center_id;
            $data["report_title"] = "Weekly Sales";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/sales_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function monthly_sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-t");
            $center_id = $this->input->get("center_id");

            $data["sales"] = $this->Stock_model_new->get_sales_report(
                $start_date,
                $end_date,
                $center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_center"] = $center_id;
            $data["report_title"] = "Monthly Sales";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/sales_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function transfer_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = $this->input->get("start_date") ?: date("Y-m-01");
            $end_date = $this->input->get("end_date") ?: date("Y-m-d");
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $to_center_id = $this->input->get("to_center_id");

            $data["transfers"] = $this->Stock_model_new->get_transfer_report(
                $start_date,
                $end_date,
                $transfer_type,
                $from_center_id,
                $to_center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_transfer_type"] = $transfer_type;
            $data["selected_from_center"] = $from_center_id;
            $data["selected_to_center"] = $to_center_id;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function todays_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $today = date("Y-m-d");
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $to_center_id = $this->input->get("to_center_id");

            $data["transfers"] = $this->Stock_model_new->get_transfer_report(
                $today,
                $today,
                $transfer_type,
                $from_center_id,
                $to_center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $today;
            $data["end_date"] = $today;
            $data["selected_transfer_type"] = $transfer_type;
            $data["selected_from_center"] = $from_center_id;
            $data["selected_to_center"] = $to_center_id;
            $data["report_title"] = "Today's Transfers";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function weekly_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = date("Y-m-d", strtotime("monday this week"));
            $end_date = date("Y-m-d", strtotime("sunday this week"));
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $to_center_id = $this->input->get("to_center_id");

            $data["transfers"] = $this->Stock_model_new->get_transfer_report(
                $start_date,
                $end_date,
                $transfer_type,
                $from_center_id,
                $to_center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_transfer_type"] = $transfer_type;
            $data["selected_from_center"] = $from_center_id;
            $data["selected_to_center"] = $to_center_id;
            $data["report_title"] = "Weekly Transfers";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function monthly_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-t");
            $transfer_type = $this->input->get("transfer_type");
            $from_center_id = $this->input->get("from_center_id");
            $to_center_id = $this->input->get("to_center_id");

            $data["transfers"] = $this->Stock_model_new->get_transfer_report(
                $start_date,
                $end_date,
                $transfer_type,
                $from_center_id,
                $to_center_id,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
            $data["selected_transfer_type"] = $transfer_type;
            $data["selected_from_center"] = $from_center_id;
            $data["selected_to_center"] = $to_center_id;
            $data["report_title"] = "Monthly Transfers";

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/transfer_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // AJAX FUNCTIONS
    // ===============================================

    public function get_medicine_details()
    {
        $medicine_id = $this->input->post("medicine_id");
        $medicine = $this->Stock_model_new->get_medicine_by_id($medicine_id);

        if ($medicine) {
            echo json_encode([
                "status" => "success",
                "data" => $medicine,
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Medicine not found",
            ]);
        }
    }

    public function get_batch_details()
    {
        $batch_id = $this->input->post("batch_id");
        $batch = $this->Stock_model_new->get_batch_by_id($batch_id);

        if ($batch) {
            echo json_encode([
                "status" => "success",
                "data" => $batch,
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Batch not found",
            ]);
        }
    }

    public function get_available_stock()
    {
        $batch_id = $this->input->post("batch_id");
        $center_id = $this->input->post("center_id");

        $stock = $this->Stock_model_new->get_available_stock(
            $batch_id,
            $center_id,
        );

        echo json_encode([
            "status" => "success",
            "data" => $stock,
        ]);
    }

    public function get_dashboard_data()
    {
        $data = $this->Stock_model_new->get_dashboard_summary();

        echo json_encode([
            "status" => "success",
            "data" => $data,
        ]);
    }

    // ===============================================
    // REPORTS & ANALYTICS
    // ===============================================

    public function low_stock_alerts()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "low_stock_alerts"
            ] = $this->Stock_model_new->get_low_stock_alerts();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/low_stock_alerts", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function expiry_alerts()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "expiry_alerts"
            ] = $this->Stock_model_new->get_expiry_alerts();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/expiry_alerts", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // MEDICINE RETURNS
    // ===============================================

    public function medicine_returns()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {

            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data[
                "available_batches"
            ] = $this->Stock_model_new->get_available_batches_for_return();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/medicine_returns", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" .base_url(). "");
            die();
        }
    }

    public function process_return() {
        $logg = checklogin();
        if($logg['status'] == true) {

            if($this->input->post('action') == 'return_medicine') {
                $this->form_validation->set_rules('patient_id', 'Patient ID', 'required');
                $this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
                $this->form_validation->set_rules('receipt_number', 'Receipt Number', 'required');
                $this->form_validation->set_rules('center_id', 'Center', 'required');
                $this->form_validation->set_rules('return_reason', 'Return Reason', 'required');
                if ($this->form_validation->run() == true) {
                    // Get employee ID safely
                    $employee_number = null;
                    if (
                        isset(
                            $_SESSION["logged_central_stock_manager"][
                                "employee_number"
                            ],
                        )
                    ) {
                        $employee_number =
                            $_SESSION["logged_central_stock_manager"][
                                "employee_number"
                            ];
                    } elseif ($this->session->userdata("employee_number")) {
                        $employee_number = $this->session->userdata(
                            "employee_number",
                        );
                    }
                    // Get employee ID from number
                    $created_by_id = null;
                    if ($employee_number) {
                        $employee = $this->db
                            ->where("employee_number", $employee_number)
                            ->get("hms_employees")
                            ->row();
                        if ($employee) {
                            $created_by_id = $employee->ID;
                        }
                    }

                    $return_data = [
                        "patient_id" => $this->input->post("patient_id"),
                        "patient_name" => $this->input->post("patient_name"),
                        "receipt_number" => $this->input->post(
                            "receipt_number",
                        ),
                        "center_id" => $this->input->post("center_id"),
                        "return_date" => $this->input->post("return_date"),
                        "return_reason" => $this->input->post("return_reason"),
                        "total_return_amount" => $this->input->post(
                            "total_return_amount",
                        ),
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $created_by_id,
                        "created_at" => date("Y-m-d H:i:s"),
                    ];

                    $return_items = $this->input->post("return_items");

                    if (
                        $this->Stock_model_new->process_medicine_return(
                            $return_data,
                            $return_items,
                        )
                    ) {
                        $this->session->set_flashdata(
                            "success",
                            "Medicine return processed successfully",
                        );
                        redirect("stocks_new/returns");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to process medicine return",
                        );
                    }
                }
            }

            redirect("stocks_new/medicine_returns");
        }
    }

    public function returns()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["returns"] = $this->Stock_model_new->get_medicine_returns();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/returns_list", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function view_return($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["return"] = $this->Stock_model_new->get_return_by_id($id);
            $data["return_items"] = $this->Stock_model_new->get_return_items(
                $id,
            );

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/view_return", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // STOCK AUDIT
    // ===============================================

    public function stock_audit()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data[
                "available_batches"
            ] = $this->Stock_model_new->get_available_batches_for_audit();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_audit", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function process_audit()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "stock_audit") {
                $this->form_validation->set_rules(
                    "audit_date",
                    "Audit Date",
                    "required",
                );
                $this->form_validation->set_rules(
                    "center_id",
                    "Center",
                    "required",
                );
                $this->form_validation->set_rules(
                    "audit_type",
                    "Audit Type",
                    "required",
                );
                $this->form_validation->set_rules(
                    "auditor_name",
                    "Auditor Name",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $audit_data = [
                        "audit_date" => $this->input->post("audit_date"),
                        "center_id" => $this->input->post("center_id"),
                        "audit_type" => $this->input->post("audit_type"),
                        "audit_purpose" => $this->input->post("audit_purpose"),
                        "auditor_name" => $this->input->post("auditor_name"),
                        "total_items" => $this->input->post("total_items"),
                        "variance_items" => $this->input->post(
                            "variance_items",
                        ),
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $this->session->userdata(
                            "employee_number",
                        ),
                        "created_at" => date("Y-m-d H:i:s"),
                    ];

                    $audit_items = $this->input->post("audit_items");

                    if (
                        $this->Stock_model_new->process_stock_audit(
                            $audit_data,
                            $audit_items,
                        )
                    ) {
                        $this->session->set_flashdata(
                            "success",
                            "Stock audit completed successfully",
                        );
                        redirect("stocks_new/audit_reports");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to process stock audit",
                        );
                    }
                }
            }

            redirect("stocks_new/stock_audit");
        }
    }

    public function audit_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "audit_reports"
            ] = $this->Stock_model_new->get_audit_reports();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/audit_reports", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // MEDICINE DISPOSAL
    // ===============================================

    public function medicine_disposal()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data[
                "available_batches"
            ] = $this->Stock_model_new->get_available_batches_for_disposal();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/medicine_disposal", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // public function process_disposal()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         if ($this->input->post("action") == "medicine_disposal") {
    //             $this->form_validation->set_rules(
    //                 "disposal_date",
    //                 "Disposal Date",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "center_id",
    //                 "Center",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "disposal_type",
    //                 "Disposal Type",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "disposal_method",
    //                 "Disposal Method",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "authorized_by",
    //                 "Authorized By",
    //                 "required",
    //             );

    //             if ($this->form_validation->run() == true) {
    //                 $disposal_data = [
    //                     "disposal_date" => $this->input->post("disposal_date"),
    //                     "center_id" => $this->input->post("center_id"),
    //                     "disposal_type" => $this->input->post("disposal_type"),
    //                     "disposal_method" => $this->input->post(
    //                         "disposal_method",
    //                     ),
    //                     "disposal_company" => $this->input->post(
    //                         "disposal_company",
    //                     ),
    //                     "authorized_by" => $this->input->post("authorized_by"),
    //                     "total_items" => $this->input->post("total_items"),
    //                     "total_cost" => $this->input->post("total_cost"),
    //                     "remarks" => $this->input->post("remarks"),
    //                     "created_by" => $this->session->userdata(
    //                         "employee_number",
    //                     ),
    //                     "created_at" => date("Y-m-d H:i:s"),
    //                 ];

    //                 $disposal_items = $this->input->post("disposal_items");

    //                 if (
    //                     $this->Stock_model_new->process_medicine_disposal(
    //                         $disposal_data,
    //                         $disposal_items,
    //                     )
    //                 ) {
    //                     $this->session->set_flashdata(
    //                         "success",
    //                         "Medicine disposal processed successfully",
    //                     );
    //                     redirect("stocks_new/disposal_reports");
    //                 } else {
    //                     $this->session->set_flashdata(
    //                         "error",
    //                         "Failed to process medicine disposal",
    //                     );
    //                 }
    //             }
    //         }

    //         redirect("stocks_new/medicine_disposal");
    //     }
    // }
    public function process_disposal()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "medicine_disposal") {
                // --- Validation Rules ---
                $this->form_validation->set_rules("disposal_date", "Disposal Date", "required|trim");
                $this->form_validation->set_rules("center_id", "Center", "required|numeric");
                $this->form_validation->set_rules("disposal_type", "Disposal Type", "required|trim");
                $this->form_validation->set_rules("disposal_method", "Disposal Method", "required|trim");
                $this->form_validation->set_rules("authorized_by", "Authorized By", "required|trim");
                $this->form_validation->set_rules("disposal_company", "Disposal Company", "trim");
                $this->form_validation->set_rules("remarks", "Remarks", "trim");
                $this->form_validation->set_rules('disposal_items[]', 'Disposal Items', 'required',
                    ['required' => 'You must add at least one item to dispose.']
                );
                // --- File Upload Configuration ---
                $certificate_file_name = NULL; 
                if (isset($_FILES['disposal_certificate']) && $_FILES['disposal_certificate']['error'] != UPLOAD_ERR_NO_FILE) {
                    $upload_path = './uploads/disposal_certificates/'; 
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, TRUE);
                    }
                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|';
                    $config['max_size']      = 2048; // 2MB max size
                    $config['encrypt_name']  = TRUE; // Encrypt file name for security
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('disposal_certificate')) {
                        $upload_error = $this->upload->display_errors('', ''); // Get error message
                        $this->session->set_flashdata("error", "Certificate Upload Failed: " . $upload_error);
                        $this->load->model('Stock_model_new');
                        $data["centers"] = $this->Stock_model_new->get_all_centers();
                        $data["available_batches"] = $this->Stock_model_new->get_all_stock_batches();
                        $template = get_header_template($logg["role"]);
                        $this->load->view($template["header"]);
                        $this->load->view("stocks_new/medicine_disposal", $data);
                        $this->load->view($template["footer"]);
                        return; // Stop execution
                    } else {
                        $upload_data = $this->upload->data();
                        $certificate_file_name = $upload_data['file_name']; // Get the uploaded file name
                    }
                } // End file upload check
                if ($this->form_validation->run() == TRUE) { 
                    $created_by_id = null;
                    if (isset($logg['ID'])) { $created_by_id = $logg['ID']; }
                    elseif ($_SESSION["logged_central_stock_manager"]["employee_number"]) {
                        $employee = $this->db->where("employee_number", $_SESSION["logged_central_stock_manager"]["employee_number"])->get("hms_employees")->row();
                        if ($employee) $created_by_id = $employee->ID;
                    }

                    if (!$created_by_id) {
                         $this->session->set_flashdata("error", "Could not identify logged-in user ID.");
                         redirect("stocks_new/medicine_disposal");
                         return;
                    }

                    // --- Prepare Data for Model ---
                    $disposal_data = [
                        "disposal_date"     => $this->input->post("disposal_date"),
                        "center_id"         => $this->input->post("center_id"),
                        "disposal_type"     => $this->input->post("disposal_type"),
                        "disposal_method"   => $this->input->post("disposal_method"),
                        "disposal_company"  => $this->input->post("disposal_company"),
                        "authorized_by"     => $this->input->post("authorized_by"),
                        "disposal_certificate" => $certificate_file_name, // Add file name (or NULL)
                        "remarks"           => $this->input->post("remarks"),
                        "created_by"        => $created_by_id,
                        "created_at"        => date("Y-m-d H:i:s"),
                        "status"            => "COMPLETED"
                    ];

                    $disposal_items = $this->input->post("disposal_items");
                    // --- Call Model ---
                    $this->load->model('Stock_model_new');
                    if ($this->Stock_model_new->process_medicine_disposal($disposal_data, $disposal_items)) {
                        $this->session->set_flashdata("success", "Medicine disposal processed successfully.");
                        redirect("stocks_new/disposal_reports");
                    } else {
                        // Model failed, potentially remove uploaded file if needed
                         if ($certificate_file_name && file_exists($upload_path . $certificate_file_name)) {
                             unlink($upload_path . $certificate_file_name);
                         }
                        if (!$this->session->flashdata('error')) {
                             $this->session->set_flashdata("error", "Failed to process medicine disposal. Database transaction failed.");
                        }
                        redirect("stocks_new/medicine_disposal");
                    }
                } else {
                    // Validation failed (before or during upload check)
                    $this->session->set_flashdata("error", validation_errors());
                     $this->load->model('Stock_model_new');
                     $data["centers"] = $this->Stock_model_new->get_all_centers();
                     $data["available_batches"] = $this->Stock_model_new->get_all_stock_batches();
                     $template = get_header_template($logg["role"]);
                     $this->load->view($template["header"]);
                     $this->load->view("stocks_new/medicine_disposal", $data);
                     $this->load->view($template["footer"]);
                }
            } else {
                 redirect("stocks_new/medicine_disposal");
            }
        } else {
             redirect(base_url());
        }
    }
    /**
     * Processes a stock disposal.
     * Updates disposal_reports, center_stocks, medicine_batches, and stock_movements.
     * Does NOT use disposal_report_items table.
     */
    // public function process_medicine_disposal($disposal_data, $disposal_items)
    // {
    //     if (empty($disposal_items)) {
    //         $this->session->set_flashdata('error', 'No items selected for disposal.'); // Add user feedback
    //         return false;
    //     }
    //     if (empty($disposal_data['center_id']) || empty($disposal_data['created_by'])) {
    //          $this->session->set_flashdata('error', 'Center ID or User ID missing.');
    //          return false;
    //     }
    //     $this->db->trans_start();
    //     // 3. Insert Disposal Header (`disposal_reports`)
    //     // Initialize totals to 0, they will be calculated and updated at the end.
    //     $disposal_data["disposal_number"] = "DISP" . date("Ymd") . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
    //     $disposal_data["total_items"] = 0; // Placeholder
    //     $disposal_data["total_cost"] = 0.00; // Placeholder
    //     $allowed_header_fields = ['disposal_number', 'center_id', 'disposal_date', 'disposal_reason', 'disposal_method', 'disposal_company', 'authorized_by', 'total_items', 'total_cost', 'status', 'remarks', 'created_by', 'created_at'];
    //     $filtered_disposal_data = array_intersect_key($disposal_data, array_flip($allowed_header_fields));
    //     $this->db->insert("disposal_reports", $filtered_disposal_data);
    //     $db_error = $this->db->error();
    //     if ($db_error["code"] != 0) {
    //         log_message('error', 'DB Error (disposal_reports insert): ' . $db_error["message"]);
    //         $this->db->trans_rollback();
    //         return false;
    //     }
    //     $disposal_id = $this->db->insert_id();
    //     if (!$disposal_id) {
    //          log_message('error', 'DB Error: Failed to get insert_id for disposal_reports.');
    //          $this->db->trans_rollback();
    //          return false;
    //     }
    //     // Initialize calculated totals
    //     $calculated_total_cost = 0;
    //     $calculated_total_items = 0; // Counts distinct batches processed
    //     $calculated_total_quantity = 0; // Counts total units disposed
    //     // 4. Loop through items to update stock and log movements
    //     foreach ($disposal_items as $item) {
    //         // Validate item data from form
    //         $quantity_to_dispose = isset($item["quantity_disposed"]) ? (int)$item["quantity_disposed"] : 0;
    //         $batch_id = isset($item["batch_id"]) ? (int)$item["batch_id"] : 0;
    //         if ($batch_id <= 0 || $quantity_to_dispose <= 0) {
    //             continue; // Skip invalid items silently
    //         }
    //         // Get batch cost (Purchase Price)
    //         $batch_info = $this->db->select('purchase_price')->get_where('medicine_batches', ['id' => $batch_id])->row();
    //         if (!$batch_info) {
    //              log_message('error', "DB Error: Batch ID {$batch_id} not found in medicine_batches.");
    //              $this->db->trans_rollback(); // Batch doesn't exist, critical error
    //              return false;
    //         }
    //         $unit_cost = (float)$batch_info->purchase_price;
    //         // --- NO INSERT INTO disposal_report_items ---
    //         // 4a. Decrease Center Stock (`center_stocks`)
    //         $this->db->where("batch_id", $batch_id);
    //         $this->db->where("center_id", $disposal_data["center_id"]);
    //         $center_stock = $this->db->select('quantity')->get("center_stocks")->row();
    //         $quantity_before = ($center_stock) ? (int)$center_stock->quantity : 0;
    //         // Only dispose what's actually available at the center for this batch
    //         $actual_disposed_qty = min($quantity_to_dispose, $quantity_before);
    //         if ($actual_disposed_qty <= 0) {
    //              // No stock for this batch at this center, skip stock updates for this item
    //              continue;
    //         }
    //         $quantity_after = $quantity_before - $actual_disposed_qty;
    //         $item_total_cost = $unit_cost * $actual_disposed_qty; // Cost based on actual disposed qty
    //         // Update using GREATEST to prevent negative stock
    //         $this->db->set("quantity", "GREATEST(0, quantity - " . $actual_disposed_qty . ")", FALSE);
    //         $this->db->set("last_movement_date", "NOW()", false);
    //         $this->db->set("updated_at", "NOW()", false);
    //         $this->db->where("batch_id", $batch_id);
    //         $this->db->where("center_id", $disposal_data["center_id"]);
    //         $this->db->update("center_stocks");
    //         // Error Check 4a: Center Stock Update
    //         $db_error = $this->db->error();
    //         if ($db_error["code"] != 0) {
    //             log_message('error', "DB Error (center_stocks update): " . $db_error["message"]);
    //             $this->db->trans_rollback();
    //             return false;
    //         }
    //         // 4b. Decrease Master Batch Stock (`medicine_batches`) - By the actual disposed amount
    //         $this->db->set("quantity_remaining", "GREATEST(0, quantity_remaining - " . $actual_disposed_qty . ")", FALSE);
    //         $this->db->set("updated_at", "NOW()", false);
    //         $this->db->where("id", $batch_id);
    //         $this->db->update("medicine_batches");
    //         // Error Check 4b: Master Batch Update
    //          $db_error = $this->db->error();
    //          if ($db_error["code"] != 0) {
    //              log_message('error', "DB Error (medicine_batches update): " . $db_error["message"]);
    //              $this->db->trans_rollback();
    //              return false;
    //          }
    //         // 4c. Record Audit Trail (`stock_movements`)
    //         if ($this->db->table_exists("stock_movements")) {
    //             $movement_data = [
    //                 "batch_id"           => $batch_id,
    //                 "movement_type"      => "DISPOSAL", // Correct ENUM value
    //                 "from_location_type" => "CENTER",
    //                 "from_location_id"   => $disposal_data["center_id"],
    //                 "to_location_type"   => "WASTAGE", // Correct ENUM value
    //                 "to_location_id"     => null,
    //                 "quantity_before"    => $quantity_before, // Qty at center before
    //                 "quantity_change"    => -$actual_disposed_qty, // Negative, actual qty
    //                 "quantity_after"     => $quantity_after,  // Qty at center after
    //                 "unit_price"         => $unit_cost,       // Purchase price
    //                 "total_value"        => $item_total_cost, // Actual cost
    //                 "reference_type"     => "DISPOSAL_VOUCHER", // Correct ENUM value
    //                 "reference_id"       => $disposal_id,
    //                 "reference_number"   => $disposal_data["disposal_number"],
    //                 "remarks"            => "Disposal Reason: " . ($disposal_data["disposal_reason"] ?? 'N/A'), // Use correct field name
    //                 "created_by"         => $disposal_data["created_by"], // Employee ID
    //                 "created_at"         => date("Y-m-d H:i:s")
    //             ];
    //             $this->db->insert("stock_movements", $movement_data);
    //             // Error Check 4c: Stock Movement Insert
    //              $db_error = $this->db->error();
    //              if ($db_error["code"] != 0) {
    //                  log_message('error', "DB Error (stock_movements insert): " . $db_error["message"]);
    //                  $this->db->trans_rollback();
    //                  return false;
    //              }
    //         } else {
    //             log_message('warn', "Stock Movements table does not exist, skipping audit log.");
    //         }
    //         // Update calculated totals
    //         $calculated_total_items++; // Count this processed item/batch line
    //         $calculated_total_quantity += $actual_disposed_qty;
    //         $calculated_total_cost += $item_total_cost;
    //     } // End foreach loop
    //     // 5. Final check: if no valid items were actually processed (e.g., all had 0 stock)
    //     if ($calculated_total_items == 0) {
    //          log_message('error', "Disposal failed: No valid items found with available stock.");
    //          $this->db->trans_rollback();
    //          $this->session->set_flashdata('error', 'No stock available for the selected items/batches at this center.'); // User feedback
    //          return false;
    //     }
    //     // 6. Update Header Totals (`disposal_reports`) with CALCULATED values
    //     $update_data = [
    //         'total_items' => $calculated_total_items,
    //         'total_cost' => $calculated_total_cost,
    //         // Add total_quantity_disposed if the column exists in your table
    //         // 'total_quantity_disposed' => $calculated_total_quantity,
    //         'updated_at' => date("Y-m-d H:i:s") // Manually update timestamp
    //     ];
    //     $this->db->where('id', $disposal_id);
    //     $this->db->update('disposal_reports', $update_data);
    //     // Error Check 6: Header Update
    //     $db_error = $this->db->error();
    //     if ($db_error["code"] != 0) {
    //         log_message('error', "DB Error (disposal_reports update totals): " . $db_error["message"]);
    //         $this->db->trans_rollback();
    //         return false;
    //     }
    //     // 7. Complete Transaction
    //     $this->db->trans_complete();
    //     // Check transaction status
    //     if ($this->db->trans_status() === FALSE) {
    //         log_message('error', "Database transaction failed for disposal ID: " . $disposal_id);
    //         return false;
    //     } else {
    //         return true; // Success
    //     }
    // } // End function

    public function disposal_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "disposal_reports"
            ] = $this->Stock_model_new->get_disposal_reports();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/disposal_reports", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function view_disposal($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["disposal_report"] = $this->Stock_model_new->get_disposal_report_by_id(
                $id,
            );
            $data[
                "disposal_items"
            ] = $this->Stock_model_new->get_disposed_items_from_log($id);
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/view_disposal_report", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // INVOICE MANAGEMENT
    // ===============================================

    public function invoices()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);

            $data["invoices"] = $this->Stock_model_new->get_invoices();
            $data["vendors"] = $this->Stock_model_new->get_vendors();

            $this->load->view("stocks_new/invoices", $data);
            $this->load->view($template["footer"]);
        }
    }

    public function add_invoice()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_invoice") {
                $this->form_validation->set_rules(
                    "invoice_number",
                    "Invoice Number",
                    "required|is_unique[hms_invoices.invoice_number]",
                );
                $this->form_validation->set_rules(
                    "vendor_id",
                    "Vendor",
                    "required",
                );
                $this->form_validation->set_rules(
                    "invoice_date",
                    "Invoice Date",
                    "required",
                );
                $this->form_validation->set_rules(
                    "due_date",
                    "Due Date",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $invoice_data = [
                        "invoice_number" => $this->input->post(
                            "invoice_number",
                        ),
                        "vendor_id" => $this->input->post("vendor_id"),
                        "invoice_date" => $this->input->post("invoice_date"),
                        "due_date" => $this->input->post("due_date"),
                        "total_amount" => $this->input->post("total_amount"),
                        "paid_amount" => $this->input->post("paid_amount"),
                        "balance_amount" => $this->input->post(
                            "balance_amount",
                        ),
                        "status" => "DRAFT",
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $this->session->userdata(
                            "employee_number",
                        ),
                        "created_at" => date("Y-m-d H:i:s"),
                    ];

                    if ($this->Stock_model_new->add_invoice($invoice_data)) {
                        $this->session->set_flashdata(
                            "success",
                            "Invoice created successfully",
                        );
                        redirect("stocks_new/invoices");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to create invoice",
                        );
                    }
                }
            }

            $data["vendors"] = $this->Stock_model_new->get_vendors();
            $this->load->view("stocks_new/add_invoice", $data);
        }
    }

    public function edit_invoice($invoice_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["invoice"] = $this->Stock_model_new->get_invoice($invoice_id);
            $data["invoice_items"] = $this->Stock_model_new->get_invoice_items(
                $invoice_id,
            );
            $data[
                "available_batches"
            ] = $this->Stock_model_new->get_available_batches_for_invoice();

            $this->load->view("stocks_new/edit_invoice", $data);
        }
    }

    public function approve_invoice($invoice_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->Stock_model_new->approve_invoice($invoice_id)) {
                $this->session->set_flashdata(
                    "success",
                    "Invoice approved successfully",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Failed to approve invoice",
                );
            }

            redirect("stocks_new/invoices");
        }
    }

    public function print_invoice($invoice_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["invoice"] = $this->Stock_model_new->get_invoice($invoice_id);
            $data["invoice_items"] = $this->Stock_model_new->get_invoice_items(
                $invoice_id,
            );

            $this->load->view("stocks_new/print_invoice", $data);
        }
    }

    // ===============================================
    // CATEGORIES MANAGEMENT
    // ===============================================

    public function categories()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["categories"] = $this->Stock_model_new->get_categories();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/categories", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_category()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_category") {
                $this->form_validation->set_rules(
                    "category_name",
                    "Category Name",
                    "required|is_unique[hms_categories.category_name]",
                );
                $this->form_validation->set_rules(
                    "description",
                    "Description",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $category_data = [
                        "category_name" => $this->input->post("category_name"),
                        "description" => $this->input->post("description"),
                        "parent_category" => $this->input->post(
                            "parent_category",
                        ),
                        "status" => "ACTIVE",
                        "created_at" => date("Y-m-d H:i:s"),
                    ];

                    if ($this->Stock_model_new->add_category($category_data)) {
                        $this->session->set_flashdata(
                            "success",
                            "Category added successfully",
                        );
                        redirect("stocks_new/categories");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to add category",
                        );
                    }
                }
            }

            $data["categories"] = $this->Stock_model_new->get_categories();
            $this->load->view("stocks_new/add_category", $data);
        }
    }

    public function edit_category($category_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "update_category") {
                $this->form_validation->set_rules(
                    "category_name",
                    "Category Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "description",
                    "Description",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $category_data = [
                        "category_name" => $this->input->post("category_name"),
                        "description" => $this->input->post("description"),
                        "parent_category" => $this->input->post(
                            "parent_category",
                        ),
                        "status" => $this->input->post("status"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ];

                    if (
                        $this->Stock_model_new->update_category(
                            $category_id,
                            $category_data,
                        )
                    ) {
                        $this->session->set_flashdata(
                            "success",
                            "Category updated successfully",
                        );
                        redirect("stocks_new/categories");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to update category",
                        );
                    }
                }
            }

            $data["category"] = $this->Stock_model_new->get_category(
                $category_id,
            );
            $data["categories"] = $this->Stock_model_new->get_categories();

            $this->load->view("stocks_new/edit_category", $data);
        }
    }

    public function activate_category($category_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if (
                $this->Stock_model_new->update_category_status(
                    $category_id,
                    "ACTIVE",
                )
            ) {
                $this->session->set_flashdata(
                    "success",
                    "Category activated successfully",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Failed to activate category",
                );
            }

            redirect("stocks_new/categories");
        }
    }

    public function deactivate_category($category_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if (
                $this->Stock_model_new->update_category_status(
                    $category_id,
                    "INACTIVE",
                )
            ) {
                $this->session->set_flashdata(
                    "success",
                    "Category deactivated successfully",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Failed to deactivate category",
                );
            }

            redirect("stocks_new/categories");
        }
    }

    // ===============================================
    // GENERIC NAMES MANAGEMENT
    // ===============================================

    public function generic_names()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "generic_names"
            ] = $this->Stock_model_new->get_generic_names();
            $data["categories"] = $this->Stock_model_new->get_categories();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/generic_names", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_generic_name()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_generic_name") {
                $this->form_validation->set_rules(
                    "generic_name",
                    "Generic Name",
                    "required|is_unique[generic_names.generic_name]",
                );
                $this->form_validation->set_rules(
                    "generic_code",
                    "Generic Code",
                    "required|is_unique[generic_names.generic_code]",
                );

                if ($this->form_validation->run() == true) {
                    $generic_data = [
                        "generic_name" => $this->input->post("generic_name"),
                        "generic_code" => $this->input->post("generic_code"),
                        "description" => $this->input->post("description"),
                        "therapeutic_class" => $this->input->post(
                            "therapeutic_class",
                        ),
                        "status" => $this->input->post("status") ?: "active",
                        "created_at" => date("Y-m-d H:i:s"),
                    ];

                    if (
                        $this->Stock_model_new->add_generic_name($generic_data)
                    ) {
                        $this->session->set_flashdata(
                            "success",
                            "Generic name added successfully",
                        );
                        redirect("stocks_new/generic_names");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to add generic name",
                        );
                    }
                }
            }

            // Initialize data array
            $data = [];

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_generic_name", $data);
            $this->load->view($template["footer"]);
        }
    }

    public function edit_generic_name($generic_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "update_generic_name") {
                $this->form_validation->set_rules(
                    "generic_name",
                    "Generic Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "category_id",
                    "Category",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $generic_data = [
                        "generic_name" => $this->input->post("generic_name"),
                        "category_id" => $this->input->post("category_id"),
                        "description" => $this->input->post("description"),
                        "status" => $this->input->post("status"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    ];

                    if (
                        $this->Stock_model_new->update_generic_name(
                            $generic_id,
                            $generic_data,
                        )
                    ) {
                        $this->session->set_flashdata(
                            "success",
                            "Generic name updated successfully",
                        );
                        redirect("stocks_new/generic_names");
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Failed to update generic name",
                        );
                    }
                }
            }

            $data["generic"] = $this->Stock_model_new->get_generic_name(
                $generic_id,
            );
            $data["categories"] = $this->Stock_model_new->get_categories();

            $this->load->view("stocks_new/edit_generic_name", $data);
        }
    }

    public function activate_generic_name($generic_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if (
                $this->Stock_model_new->update_generic_name_status(
                    $generic_id,
                    "ACTIVE",
                )
            ) {
                $this->session->set_flashdata(
                    "success",
                    "Generic name activated successfully",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Failed to activate generic name",
                );
            }

            redirect("stocks_new/generic_names");
        }
    }

    public function deactivate_generic_name($generic_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if (
                $this->Stock_model_new->update_generic_name_status(
                    $generic_id,
                    "INACTIVE",
                )
            ) {
                $this->session->set_flashdata(
                    "success",
                    "Generic name deactivated successfully",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Failed to deactivate generic name",
                );
            }

            redirect("stocks_new/generic_names");
        }
    }

    // ===============================================
    // VENDOR RETURNS
    // ===============================================

    public function vendor_returns()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "vendor_returns"
            ] = $this->Stock_model_new->get_vendor_returns();
            $data["vendors"] = $this->Stock_model_new->get_vendors();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/vendor_returns", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function vendor_return_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get filter parameters
            $vendor_id = $this->input->get("vendor_id");
            $status = $this->input->get("status");
            $from_date = $this->input->get("from_date");
            $to_date = $this->input->get("to_date");

            $data[
                "vendor_returns"
            ] = $this->Stock_model_new->get_vendor_return_reports(
                $vendor_id,
                $status,
                $from_date,
                $to_date,
            );
            $data["vendors"] = $this->Stock_model_new->get_vendors();
            $data[
                "summary_stats"
            ] = $this->Stock_model_new->get_vendor_return_summary_stats(
                $vendor_id,
                $status,
                $from_date,
                $to_date,
            );

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/vendor_return_reports", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }
    public function get_batches_for_vendor_center()
    {
        $logg = checklogin();
        if (!$logg || $logg["status"] != true) {
             $this->output->set_status_header(401); // Unauthorized
             echo json_encode(['error' => 'Unauthorized']);
             return;
        }
        $vendor_id = $this->input->get('vendor_id', TRUE);
        $center_id = $this->input->get('center_id', TRUE);
        if (empty($vendor_id) || empty($center_id) || !is_numeric($vendor_id) || !is_numeric($center_id)) {
            $this->output->set_status_header(400); // Bad Request
            echo json_encode(['error' => 'Invalid Vendor ID or Center ID']);
            return;
        }
        $this->load->model('Stock_model_new');
        $batches = $this->Stock_model_new->get_batches_by_vendor_center((int)$vendor_id, (int)$center_id);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($batches ?? []));
    }
    public function add_vendor_return()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->load->model('Stock_model_new'); // Load model
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["vendors"] = $this->Stock_model_new->get_vendors();
            // $data["available_batches"] = []; // Load empty initially, JS will fetch via AJAX

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_vendor_return", $data); // Your view file name
            $this->load->view($template["footer"]);
        } else {
            redirect(base_url());
        }
    }
    public function process_vendor_return() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if($this->input->post('action') == 'add_vendor_return') {
                $this->form_validation->set_rules('center_id', 'From Center', 'required|numeric');
                $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|numeric');
                $this->form_validation->set_rules('return_date', 'Return Date', 'required|trim');
                $this->form_validation->set_rules('return_reason', 'Return Reason', 'trim');
                $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
                $this->form_validation->set_rules('return_items[]', 'Return Items', 'required',
                        ['required' => 'You must add at least one item to return.']
                );
                if ($this->form_validation->run() == TRUE) {
                    $created_by_id = null;
                    if (isset($logg['ID'])) { $created_by_id = $logg['ID']; }
                    elseif ($_SESSION["logged_central_stock_manager"]["employee_number"]) {
                        $emp = $this->db->where("employee_number", $_SESSION["logged_central_stock_manager"]["employee_number"])->get("hms_employees")->row();
                        if ($emp) $created_by_id = $emp->ID;
                    }
                    if (!$created_by_id) {
                            $this->session->set_flashdata("error", "Could not identify logged-in user ID.");
                            redirect("stocks_new/add_vendor_return"); return;
                    }
                    $return_data = [
                        "center_id"     => $this->input->post("center_id"),
                        "vendor_id"     => $this->input->post("vendor_id"),
                        "return_date"   => $this->input->post("return_date"),
                        "return_reason" => $this->input->post("return_reason"),
                        "remarks"       => $this->input->post("remarks"),
                        "created_by"    => $created_by_id,
                        "created_at"    => date("Y-m-d H:i:s"),
                        "status"        => "PENDING" // Default status
                    ];
                    $return_items = $this->input->post("return_items");
                    $this->load->model('Stock_model_new');
                    if ($this->Stock_model_new->process_vendor_return($return_data, $return_items)) {
                        $this->session->set_flashdata("success", "Vendor return processed successfully. Status: PENDING");
                        redirect("stocks_new/vendor_returns"); 
                    } else {
                        if (!$this->session->flashdata('error')) {
                            $this->session->set_flashdata("error", "Failed to process vendor return. Please check stock levels or contact support.");
                        }
                        redirect("stocks_new/add_vendor_return"); 
                    }
                } else {
                    $this->session->set_flashdata("error", validation_errors());
                    $this->load->model('Stock_model_new');
                    $data["centers"] = $this->Stock_model_new->get_all_centers();
                    $data["vendors"] = $this->Stock_model_new->get_all_vendors();
                    $template = get_header_template($logg["role"]);
                    $this->load->view($template["header"]);
                    $this->load->view("stocks_new/add_vendor_return", $data);
                    $this->load->view($template["footer"]);
                }
            } else {
                redirect("stocks_new/create_vendor_return");
            }
        } else {
            redirect(base_url());
        }
    }

    // public function add_vendor_return()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         if ($this->input->post("action") == "add_vendor_return") {
    //             $this->form_validation->set_rules(
    //                 "vendor_id",
    //                 "Vendor",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "return_date",
    //                 "Return Date",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "return_reason",
    //                 "Return Reason",
    //                 "required",
    //             );

    //             if ($this->form_validation->run() == true) {
    //                 $return_data = [
    //                     "vendor_id" => $this->input->post("vendor_id"),
    //                     "return_date" => $this->input->post("return_date"),
    //                     "return_reason" => $this->input->post("return_reason"),
    //                     "total_amount" => $this->input->post("total_amount"),
    //                     "remarks" => $this->input->post("remarks"),
    //                     "created_by" => $this->session->userdata(
    //                         "employee_number",
    //                     ),
    //                     "created_at" => date("Y-m-d H:i:s"),
    //                 ];

    //                 $return_items = $this->input->post("return_items");

    //                 if (
    //                     $this->Stock_model_new->process_vendor_return(
    //                         $return_data,
    //                         $return_items,
    //                     )
    //                 ) {
    //                     $this->session->set_flashdata(
    //                         "success",
    //                         "Vendor return processed successfully",
    //                     );
    //                     redirect("stocks_new/vendor_returns");
    //                 } else {
    //                     $this->session->set_flashdata(
    //                         "error",
    //                         "Failed to process vendor return",
    //                     );
    //                 }
    //             }
    //         }

    //         $data["vendors"] = $this->Stock_model_new->get_vendors();
    //         $data[
    //             "available_batches"
    //         ] = $this->Stock_model_new->get_available_batches_for_vendor_return();
    //         $data['centers'] = $this->Stock_model_new->get_all_centers();
    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/add_vendor_return", $data);
    //         $this->load->view($template["footer"]);
    //     } else {
    //         header("location:" . base_url() . "");
    //         die();
    //     }
    // }

    // ===============================================
    // STOCK TRACKING PANEL
    // ===============================================

    public function stock_tracking_panel()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);

            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            $data["batches"] = $this->Stock_model_new->get_all_batches();
            $data["centers"] = $this->Stock_model_new->get_all_centers();

            $this->load->view("stocks_new/stock_tracking_panel", $data);
            $this->load->view($template["footer"]);
        }
    }

    public function get_stock_movements()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $movements = $this->Stock_model_new->get_stock_movements();
            echo json_encode($movements);
        }
    }

    public function get_transfers()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $transfers = $this->Stock_model_new->get_all_transfers();
            echo json_encode($transfers);
        }
    }

    public function get_sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $sales = $this->Stock_model_new->get_all_sales();
            echo json_encode($sales);
        }
    }

    public function get_summary_stats()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $stats = $this->Stock_model_new->get_summary_stats();
            echo json_encode($stats);
        }
    }

    public function search_stock_movements()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $filters = [
                "medicine_id" => $this->input->post("medicine_id"),
                "batch_id" => $this->input->post("batch_id"),
                "center_id" => $this->input->post("center_id"),
                "date_from" => $this->input->post("date_from"),
                "date_to" => $this->input->post("date_to"),
            ];

            $movements = $this->Stock_model_new->search_stock_movements(
                $filters,
            );
            echo json_encode($movements);
        }
    }

    public function export_stock_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $filters = [
                "medicine_id" => $this->input->get("medicine_id"),
                "batch_id" => $this->input->get("batch_id"),
                "center_id" => $this->input->get("center_id"),
                "date_from" => $this->input->get("date_from"),
                "date_to" => $this->input->get("date_to"),
            ];

            $this->Stock_model_new->export_stock_report($filters);
        }
    }

    // ===============================================
    // PURCHASE ORDER BATCH TRACKING
    // ===============================================

    public function track_po_batches($po_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get purchase order details
            $data[
                "purchase_order"
            ] = $this->Stock_model_new->get_purchase_order_details($po_id);

            if (!$data["purchase_order"]) {
                $this->session->set_flashdata(
                    "error",
                    "Purchase order not found!",
                );
                redirect("stocks_new/dashboard");
                return;
            }

            // Get batches created from this purchase order
            $data[
                "po_batches"
            ] = $this->Stock_model_new->get_batches_from_purchase_order($po_id);

            // Get movement history for these batches
            $data["batch_movements"] = [];
            if (!empty($data["po_batches"])) {
                foreach ($data["po_batches"] as $batch) {
                    $movements = $this->Stock_model_new->get_stock_movements_by_batch(
                        $batch->id,
                    );
                    $data["batch_movements"][$batch->id] = $movements;
                }
            }

            // Get summary statistics
            $data[
                "summary_stats"
            ] = $this->Stock_model_new->get_po_batch_summary($po_id);

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/track_po_batches", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // STOCK MOVEMENTS HISTORY
    // ===============================================

    public function stock_movements()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get batch_id from URL parameter
            $batch_id = $this->input->get("batch_id");

            // Get batch details if batch_id is provided
            if ($batch_id) {
                $data[
                    "batch_details"
                ] = $this->Stock_model_new->get_batch_by_id($batch_id);
                $data[
                    "batch_movements"
                ] = $this->Stock_model_new->get_stock_movements_by_batch(
                    $batch_id,
                );
            } else {
                $data["batch_details"] = null;
                $data["batch_movements"] = [];
            }

            // Get all batches for dropdown
            $data["batches"] = $this->Stock_model_new->get_all_batches();
            $data["selected_batch_id"] = $batch_id;

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_movements", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // BRAND MANAGEMENT
    // ===============================================

    private function generate_brand_number()
    {
        return "BR" . date("Ymd") . rand(1000, 9999);
    }

    private function generate_vendor_number()
    {
        return "VN" . date("Ymd") . rand(1000, 9999);
    }

    public function brands()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["brands"] = $this->Stock_model_new->get_medicine_brands();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/brands", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_brand()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_brand") {
                $this->form_validation->set_rules(
                    "name",
                    "Brand Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "status",
                    "Status",
                    "required",
                );
                if ($this->form_validation->run() == true) {
                    $brand_data = [
                        "name" => $this->input->post("name"),
                        "status" => $this->input->post("status"),
                        "date" => date("Y-m-d H:i:s"),
                        "brand_number" => $this->generate_brand_number(),
                    ];
                    $result = $this->Stock_model_new->add_medicine_brand(
                        $brand_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Brand added successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error adding brand!",
                        );
                    }

                    redirect("stocks_new/brands");
                }
            }
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_brand");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_brand($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "update_brand") {
                $this->form_validation->set_rules(
                    "name",
                    "Brand Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "status",
                    "Status",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    $brand_data = [
                        "name" => $this->input->post("name"),
                        "status" => $this->input->post("status"),
                        "date" => date("Y-m-d H:i:s"),
                    ];

                    $result = $this->Stock_model_new->update_medicine_brand(
                        $id,
                        $brand_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Brand updated successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error updating brand!",
                        );
                    }

                    redirect("stocks_new/brands");
                }
            }

            $data["brand"] = $this->Stock_model_new->get_medicine_brand_by_id(
                $id,
            );

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/edit_brand", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function delete_brand($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->update_medicine_brand($id, [
                "status" => "0",
                "date" => date("Y-m-d H:i:s"),
            ]);

            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Brand deactivated successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error deactivating brand!",
                );
            }

            redirect("stocks_new/brands");
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    // ===============================================
    // VENDOR MANAGEMENT
    // ===============================================

    public function vendors()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data["vendors"] = $this->Stock_model_new->get_vendors();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/vendors", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function add_vendor()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_vendor") {
                // Set validation rules for all required fields
                $this->form_validation->set_rules(
                    "name",
                    "Vendor Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "company_name",
                    "Company Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "companies_type",
                    "Company Type",
                    "required",
                );
                $this->form_validation->set_rules(
                    "company_address",
                    "Company Address",
                    "required",
                );
                $this->form_validation->set_rules(
                    "phone_number",
                    "Phone Number",
                    "required",
                );
                $this->form_validation->set_rules(
                    "contact_person_name",
                    "Contact Person",
                    "required",
                );
                $this->form_validation->set_rules(
                    "contact_person_designation",
                    "Contact Person Designation",
                    "required",
                );
                $this->form_validation->set_rules(
                    "gst_number",
                    "GST Number",
                    "required",
                );
                $this->form_validation->set_rules(
                    "status",
                    "Status",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    // Prepare vendor data with all fields
                    $vendor_data = [
                        "name" => $this->input->post("name"),
                        "company_name" => $this->input->post("company_name"),
                        "companies_type" => $this->input->post(
                            "companies_type",
                        ),
                        "company_address" => $this->input->post(
                            "company_address",
                        ),
                        "phone_number" => $this->input->post("phone_number"),
                        "email" => $this->input->post("email"),
                        "contact_person_name" => $this->input->post(
                            "contact_person_name",
                        ),
                        "contact_person_designation" => $this->input->post(
                            "contact_person_designation",
                        ),
                        "bank_name" => $this->input->post("bank_name"),
                        "branch_name" => $this->input->post("branch_name"),
                        "beneficiary_name" => $this->input->post(
                            "beneficiary_name",
                        ),
                        "account_no" => $this->input->post("account_no"),
                        "ifsc_code" => $this->input->post("ifsc_code"),
                        "account_type" => $this->input->post("account_type"),
                        "gst_number" => $this->input->post("gst_number"),
                        "drug_license_number" => $this->input->post(
                            "drug_license_number",
                        ),
                        "pan_number" => $this->input->post("pan_number"),
                        "fssai_number" => $this->input->post("fssai_number"),
                        "msme_number" => $this->input->post("msme_number"),
                        "status" => $this->input->post("status"),
                    ];

                    // Add vendor first to get the ID
                    $result = $this->Stock_model_new->add_vendor($vendor_data);

                    if ($result) {
                        $vendor_id = $this->db->insert_id();

                        // Handle file uploads
                        $file_fields = [
                            "gst_file",
                            "drug_license_file",
                            "pan_file",
                            "fssai_file",
                            "msme_file",
                            "cancel_check",
                            "mou_file",
                        ];
                        $file_data = [];

                        foreach ($file_fields as $field) {
                            $uploaded_file = $this->Stock_model_new->handle_vendor_file_upload(
                                $field,
                                $vendor_id,
                            );
                            if ($uploaded_file !== null) {
                                if ($uploaded_file === false) {
                                    $this->session->set_flashdata(
                                        "error",
                                        "Invalid file type for " .
                                            $field .
                                            ". Only PDF, JPG, JPEG, PNG files are allowed.",
                                    );
                                    redirect("stocks_new/add_vendor");
                                    return;
                                } else {
                                    $file_data[$field] = $uploaded_file;
                                }
                            }
                        }

                        // Update vendor with file data if any files were uploaded
                        if (!empty($file_data)) {
                            $this->Stock_model_new->update_vendor(
                                $vendor_id,
                                $file_data,
                            );
                        }

                        $this->session->set_flashdata(
                            "success",
                            "Vendor added successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error adding vendor!",
                        );
                    }

                    redirect("stocks_new/vendors");
                }
            }

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_vendor");
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_vendor($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "update_vendor") {
                // Set validation rules for all required fields
                $this->form_validation->set_rules(
                    "name",
                    "Vendor Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "company_name",
                    "Company Name",
                    "required",
                );
                $this->form_validation->set_rules(
                    "companies_type",
                    "Company Type",
                    "required",
                );
                $this->form_validation->set_rules(
                    "company_address",
                    "Company Address",
                    "required",
                );
                $this->form_validation->set_rules(
                    "phone_number",
                    "Phone Number",
                    "required",
                );
                $this->form_validation->set_rules(
                    "contact_person_name",
                    "Contact Person",
                    "required",
                );
                $this->form_validation->set_rules(
                    "contact_person_designation",
                    "Contact Person Designation",
                    "required",
                );
                $this->form_validation->set_rules(
                    "gst_number",
                    "GST Number",
                    "required",
                );
                $this->form_validation->set_rules(
                    "status",
                    "Status",
                    "required",
                );

                if ($this->form_validation->run() == true) {
                    // Prepare vendor data with all fields
                    $vendor_data = [
                        "name" => $this->input->post("name"),
                        "company_name" => $this->input->post("company_name"),
                        "companies_type" => $this->input->post(
                            "companies_type",
                        ),
                        "company_address" => $this->input->post(
                            "company_address",
                        ),
                        "phone_number" => $this->input->post("phone_number"),
                        "email" => $this->input->post("email"),
                        "contact_person_name" => $this->input->post(
                            "contact_person_name",
                        ),
                        "contact_person_designation" => $this->input->post(
                            "contact_person_designation",
                        ),
                        "bank_name" => $this->input->post("bank_name"),
                        "branch_name" => $this->input->post("branch_name"),
                        "beneficiary_name" => $this->input->post(
                            "beneficiary_name",
                        ),
                        "account_no" => $this->input->post("account_no"),
                        "ifsc_code" => $this->input->post("ifsc_code"),
                        "account_type" => $this->input->post("account_type"),
                        "gst_number" => $this->input->post("gst_number"),
                        "drug_license_number" => $this->input->post(
                            "drug_license_number",
                        ),
                        "pan_number" => $this->input->post("pan_number"),
                        "fssai_number" => $this->input->post("fssai_number"),
                        "msme_number" => $this->input->post("msme_number"),
                        "status" => $this->input->post("status"),
                    ];

                    // Handle file uploads
                    $file_fields = [
                        "gst_file",
                        "drug_license_file",
                        "pan_file",
                        "fssai_file",
                        "msme_file",
                        "cancel_check",
                        "mou_file",
                    ];

                    foreach ($file_fields as $field) {
                        $uploaded_file = $this->Stock_model_new->handle_vendor_file_upload(
                            $field,
                            $id,
                        );
                        if ($uploaded_file !== null) {
                            if ($uploaded_file === false) {
                                $this->session->set_flashdata(
                                    "error",
                                    "Invalid file type for " .
                                        $field .
                                        ". Only PDF, JPG, JPEG, PNG files are allowed.",
                                );
                                redirect("stocks_new/edit_vendor/" . $id);
                                return;
                            } else {
                                $vendor_data[$field] = $uploaded_file;
                            }
                        }
                    }

                    $result = $this->Stock_model_new->update_vendor(
                        $id,
                        $vendor_data,
                    );

                    if ($result) {
                        $this->session->set_flashdata(
                            "success",
                            "Vendor updated successfully!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "error",
                            "Error updating vendor!",
                        );
                    }

                    redirect("stocks_new/vendors");
                }
            }

            $data["vendor"] = $this->Stock_model_new->get_vendor_by_id($id);

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/edit_vendor", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function delete_vendor($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $result = $this->Stock_model_new->update_vendor($id, [
                "status" => "0",
            ]);

            if ($result) {
                $this->session->set_flashdata(
                    "success",
                    "Vendor deactivated successfully!",
                );
            } else {
                $this->session->set_flashdata(
                    "error",
                    "Error deactivating vendor!",
                );
            }

            redirect("stocks_new/vendors");
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function get_vendor_details($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $vendor = $this->Stock_model_new->get_vendor_by_id($id);

            if ($vendor) {
                $response = [
                    "success" => true,
                    "vendor" => $vendor,
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Vendor not found",
                ];
            }

            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($response));
        } else {
            $response = [
                "success" => false,
                "message" => "Unauthorized access",
            ];

            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($response));
        }
    }

    public function view_document($document_type, $vendor_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $vendor = $this->Stock_model_new->get_vendor_by_id($vendor_id);

            if (!$vendor) {
                show_404();
                return;
            }

            $file_field = $document_type . "_file";
            $filename = isset($vendor->$file_field)
                ? $vendor->$file_field
                : null;

            if (!$filename || empty($filename)) {
                $this->session->set_flashdata("error", "Document not found");
                redirect("stocks_new/vendors");
                return;
            }

            $file_path = "uploads/vendors/" . $filename;

            if (!file_exists($file_path)) {
                $this->session->set_flashdata(
                    "error",
                    "File not found on server",
                );
                redirect("stocks_new/vendors");
                return;
            }

            $file_extension = strtolower(
                pathinfo($filename, PATHINFO_EXTENSION),
            );
            $mime_type = $this->get_mime_type($file_extension);

            header("Content-Type: " . $mime_type);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header("Content-Length: " . filesize($file_path));
            header("Cache-Control: private, max-age=0, must-revalidate");
            header("Pragma: public");

            readfile($file_path);
            exit();
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function download_document($document_type, $vendor_id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $vendor = $this->Stock_model_new->get_vendor_by_id($vendor_id);

            if (!$vendor) {
                show_404();
                return;
            }

            $file_field = $document_type . "_file";
            $filename = isset($vendor->$file_field)
                ? $vendor->$file_field
                : null;

            if (!$filename || empty($filename)) {
                $this->session->set_flashdata("error", "Document not found");
                redirect("stocks_new/vendors");
                return;
            }

            $file_path = "uploads/vendors/" . $filename;

            if (!file_exists($file_path)) {
                $this->session->set_flashdata(
                    "error",
                    "File not found on server",
                );
                redirect("stocks_new/vendors");
                return;
            }

            $file_extension = strtolower(
                pathinfo($filename, PATHINFO_EXTENSION),
            );
            $mime_type = $this->get_mime_type($file_extension);

            // Generate a clean download filename
            $download_filename =
                ucfirst($document_type) .
                "_" .
                $vendor->name .
                "_" .
                date("Y-m-d") .
                "." .
                $file_extension;
            $download_filename = preg_replace(
                "/[^A-Za-z0-9_\-\.]/",
                "_",
                $download_filename,
            );

            header("Content-Type: " . $mime_type);
            header(
                'Content-Disposition: attachment; filename="' .
                    $download_filename .
                    '"',
            );
            header("Content-Length: " . filesize($file_path));
            header("Cache-Control: private, max-age=0, must-revalidate");
            header("Pragma: public");

            readfile($file_path);
            exit();
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    private function get_mime_type($extension)
    {
        $mime_types = [
            "pdf" => "application/pdf",
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "gif" => "image/gif",
            "bmp" => "image/bmp",
            "tiff" => "image/tiff",
            "doc" => "application/msword",
            "docx" =>
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "xls" => "application/vnd.ms-excel",
            "xlsx" =>
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];

        return isset($mime_types[$extension])
            ? $mime_types[$extension]
            : "application/octet-stream";
    }

    // ===============================================
    // MISSING SAVE METHODS
    // ===============================================

    public function save_medicine()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "medicine_name",
                "Medicine Name",
                "required",
            );
            $this->form_validation->set_rules(
                "medicine_code",
                "Medicine Code",
                "required",
            );
            $this->form_validation->set_rules("brand_id", "Brand", "required");
            $this->form_validation->set_rules(
                "minimum_stock_level",
                "Minimum Stock Level",
                "required|numeric",
            );

            if ($this->form_validation->run() == true) {
                $medicine_data = [
                    "medicine_name" => $this->input->post("medicine_name"),
                    "medicine_code" => $this->input->post("medicine_code"),
                    "brand_id" => $this->input->post("brand_id"),
                    "description" => $this->input->post("description"),
                    "minimum_stock_level" => $this->input->post(
                        "minimum_stock_level",
                    ),
                    "maximum_stock_level" => $this->input->post(
                        "maximum_stock_level",
                    ),
                    "status" => "active",
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_medicine($medicine_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Medicine added successfully",
                    );
                    redirect("stocks_new/medicines");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to add medicine",
                    );
                    redirect("stocks_new/add_medicine");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_medicine");
            }
        }
    }

    public function save_batch()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "medicine_id",
                "Medicine",
                "required",
            );
            $this->form_validation->set_rules(
                "batch_number",
                "Batch Number",
                "required",
            );
            $this->form_validation->set_rules(
                "vendor_id",
                "Vendor",
                "required",
            );
            $this->form_validation->set_rules(
                "expiry_date",
                "Expiry Date",
                "required",
            );
            $this->form_validation->set_rules(
                "quantity_purchased",
                "Quantity Purchased",
                "required|numeric",
            );
            $this->form_validation->set_rules(
                "purchase_price",
                "Purchase Price",
                "required|numeric",
            );
            $this->form_validation->set_rules(
                "selling_price",
                "Selling Price",
                "required|numeric",
            );

            if ($this->form_validation->run() == true) {
                $batch_data = [
                    "medicine_id" => $this->input->post("medicine_id"),
                    "batch_number" => $this->input->post("batch_number"),
                    "vendor_id" => $this->input->post("vendor_id"),
                    "manufacturing_date" => $this->input->post(
                        "manufacturing_date",
                    ),
                    "expiry_date" => $this->input->post("expiry_date"),
                    "quantity_purchased" => $this->input->post(
                        "quantity_purchased",
                    ),
                    "quantity_remaining" => $this->input->post(
                        "quantity_purchased",
                    ),
                    "purchase_price" => $this->input->post("purchase_price"),
                    "selling_price" => $this->input->post("selling_price"),
                    "mrp" => $this->input->post("mrp"),
                    "purchase_date" => $this->input->post("purchase_date"),
                    "invoice_number" => $this->input->post("invoice_number"),
                    "batch_status" => "ACTIVE",
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_batch($batch_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Batch added successfully",
                    );
                    redirect("stocks_new/batches");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to add batch",
                    );
                    redirect("stocks_new/add_batch");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_batch");
            }
        }
    }

    public function save_transfer()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "from_center_id",
                "From Center",
                "required",
            );
            $this->form_validation->set_rules(
                "to_center_id",
                "To Center",
                "required",
            );
            $this->form_validation->set_rules(
                "transfer_date",
                "Transfer Date",
                "required",
            );

            if ($this->form_validation->run() == true) {
                $transfer_data = [
                    "transfer_number" =>
                        "TRF" .
                        date("Ymd") .
                        str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT),
                    "transfer_type" => $this->input->post("transfer_type"),
                    "from_center_id" => $this->input->post("from_center_id"),
                    "to_center_id" => $this->input->post("to_center_id"),
                    "transfer_date" => $this->input->post("transfer_date"),
                    "expected_delivery_date" => $this->input->post(
                        "expected_delivery_date",
                    ),
                    "status" => "DRAFT",
                    "remarks" => $this->input->post("remarks"),
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_transfer($transfer_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Transfer created successfully",
                    );
                    redirect("stocks_new/transfers");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to create transfer",
                    );
                    redirect("stocks_new/add_transfer");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_transfer");
            }
        }
    }

    public function save_sale()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "center_id",
                "Center",
                "required",
            );
            $this->form_validation->set_rules(
                "sale_date",
                "Sale Date",
                "required",
            );
            $this->form_validation->set_rules(
                "patient_name",
                "Patient Name",
                "required",
            );

            if ($this->form_validation->run() == true) {
                $sale_data = [
                    "sale_number" =>
                        "SAL" .
                        date("Ymd") .
                        str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT),
                    "center_id" => $this->input->post("center_id"),
                    "patient_id" => $this->input->post("patient_id"),
                    "patient_name" => $this->input->post("patient_name"),
                    "doctor_id" => $this->input->post("doctor_id"),
                    "doctor_name" => $this->input->post("doctor_name"),
                    "sale_date" => $this->input->post("sale_date"),
                    "sale_time" => date("H:i:s"),
                    "subtotal" => $this->input->post("subtotal"),
                    "discount_amount" => $this->input->post("discount_amount"),
                    "tax_amount" => $this->input->post("tax_amount"),
                    "total_amount" => $this->input->post("total_amount"),
                    "payment_method" => $this->input->post("payment_method"),
                    "status" => "DRAFT",
                    "remarks" => $this->input->post("remarks"),
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_sale($sale_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Sale created successfully",
                    );
                    redirect("stocks_new/sales");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to create sale",
                    );
                    redirect("stocks_new/add_sale");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_sale");
            }
        }
    }

    public function save_invoice()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "vendor_id",
                "Vendor",
                "required",
            );
            $this->form_validation->set_rules(
                "invoice_date",
                "Invoice Date",
                "required",
            );
            $this->form_validation->set_rules(
                "total_amount",
                "Total Amount",
                "required|numeric",
            );

            if ($this->form_validation->run() == true) {
                $invoice_data = [
                    "invoice_number" => $this->input->post("invoice_number"),
                    "vendor_id" => $this->input->post("vendor_id"),
                    "center_id" => $this->input->post("center_id"),
                    "invoice_date" => $this->input->post("invoice_date"),
                    "due_date" => $this->input->post("due_date"),
                    "subtotal" => $this->input->post("subtotal"),
                    "tax_amount" => $this->input->post("tax_amount"),
                    "total_amount" => $this->input->post("total_amount"),
                    "status" => "DRAFT",
                    "remarks" => $this->input->post("remarks"),
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_invoice($invoice_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Invoice created successfully",
                    );
                    redirect("stocks_new/invoices");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to create invoice",
                    );
                    redirect("stocks_new/add_invoice");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_invoice");
            }
        }
    }

    public function save_category()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "category_name",
                "Category Name",
                "required|is_unique[medicine_categories.category_name]",
            );

            if ($this->form_validation->run() == true) {
                $category_data = [
                    "category_name" => $this->input->post("category_name"),
                    "description" => $this->input->post("description"),
                    "status" => "active",
                ];

                if ($this->Stock_model_new->add_category($category_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Category added successfully",
                    );
                    redirect("stocks_new/categories");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to add category",
                    );
                    redirect("stocks_new/add_category");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_category");
            }
        }
    }

    public function save_generic_name()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "generic_name",
                "Generic Name",
                "required|is_unique[generic_names.generic_name]",
            );
            $this->form_validation->set_rules(
                "generic_code",
                "Generic Code",
                "required|is_unique[generic_names.generic_code]",
            );

            if ($this->form_validation->run() == true) {
                $generic_data = [
                    "generic_name" => $this->input->post("generic_name"),
                    "generic_code" => $this->input->post("generic_code"),
                    "description" => $this->input->post("description"),
                    "therapeutic_class" => $this->input->post(
                        "therapeutic_class",
                    ),
                    "status" => $this->input->post("status") ?: "active",
                    "created_at" => date("Y-m-d H:i:s"),
                ];

                if ($this->Stock_model_new->add_generic_name($generic_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Generic name added successfully",
                    );
                    redirect("stocks_new/generic_names");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to add generic name",
                    );
                    redirect("stocks_new/add_generic_name");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_generic_name");
            }
        }
    }

    public function save_vendor_return()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->form_validation->set_rules(
                "vendor_id",
                "Vendor",
                "required",
            );
            $this->form_validation->set_rules(
                "center_id",
                "Center",
                "required",
            );
            $this->form_validation->set_rules(
                "return_date",
                "Return Date",
                "required",
            );

            if ($this->form_validation->run() == true) {
                $return_data = [
                    "return_number" =>
                        "VRET" .
                        date("Ymd") .
                        str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT),
                    "vendor_id" => $this->input->post("vendor_id"),
                    "center_id" => $this->input->post("center_id"),
                    "return_date" => $this->input->post("return_date"),
                    "return_reason" => $this->input->post("return_reason"),
                    "status" => "PENDING",
                    "remarks" => $this->input->post("remarks"),
                    "created_by" => $logg["user_id"],
                ];

                if ($this->Stock_model_new->add_vendor_return($return_data)) {
                    $this->session->set_flashdata(
                        "success",
                        "Vendor return created successfully",
                    );
                    redirect("stocks_new/vendor_returns");
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to create vendor return",
                    );
                    redirect("stocks_new/add_vendor_return");
                }
            } else {
                $this->session->set_flashdata("error", validation_errors());
                redirect("stocks_new/add_vendor_return");
            }
        }
    }

    /**
     * Generate unique batch number for a medicine
     */
    private function generate_unique_batch_number(
        $medicine_id,
        $base_batch_number = "",
    ) {
        // If no base batch number provided, generate one
        if (empty($base_batch_number)) {
            $base_batch_number = "BATCH" . date("Ymd") . $medicine_id;
        }

        // Check if this batch number already exists for this medicine
        $this->db->where("medicine_id", $medicine_id);
        $this->db->where("batch_number", $base_batch_number);
        $existing = $this->db->get("medicine_batches")->row();

        if ($existing) {
            // If exists, append a counter
            $counter = 1;
            do {
                $new_batch_number = $base_batch_number . "_" . $counter;
                $this->db->where("medicine_id", $medicine_id);
                $this->db->where("batch_number", $new_batch_number);
                $existing = $this->db->get("medicine_batches")->row();
                $counter++;
            } while ($existing);

            return $new_batch_number;
        }

        return $base_batch_number;
    }

    // ==================== PURCHASE ORDER INTEGRATION METHODS ====================

    /**
     * Display purchase orders ready for stock addition
     */
    public function purchase_orders_for_stock()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $data[
                "purchase_orders"
            ] = $this->Stock_model_new->get_purchase_orders_for_stock_addition();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/purchase_orders_for_stock", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
        }
    }

    /**
     * Display purchase order details for stock addition
     */
    public function add_stock_from_po($po_id = null)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if (!$po_id) {
                $this->session->set_flashdata(
                    "error",
                    "Purchase Order ID is required!",
                );
                redirect("stocks_new/purchase_orders_for_stock");
                return;
            }

            // Get purchase order details
            $data[
                "purchase_order"
            ] = $this->Stock_model_new->get_purchase_order_for_stock_addition(
                $po_id,
            );
            if (!$data["purchase_order"]) {
                $this->session->set_flashdata(
                    "error",
                    "Purchase order not found or not ready for stock addition!",
                );
                redirect("stocks_new/purchase_orders_for_stock");
                return;
            }

            // Get purchase order items
            $data[
                "po_items"
            ] = $this->Stock_model_new->get_purchase_order_items($po_id);
            if (empty($data["po_items"])) {
                $this->session->set_flashdata(
                    "error",
                    "No items found for this purchase order!",
                );
                redirect("stocks_new/purchase_orders_for_stock");
                return;
            }

            // Get vendors for dropdown
            $data["vendors"] = $this->Stock_model_new->get_vendors();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_stock_from_po", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
        }
    }

    /**
     * Process stock addition from purchase order
     */
    public function process_stock_from_po()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            if ($this->input->post("action") == "add_stock_from_po") {
                $po_id = $this->input->post("po_id");
                if (!$po_id) {
                    $this->session->set_flashdata(
                        "error",
                        "Purchase Order ID is required!",
                    );
                    redirect("stocks_new/purchase_orders_for_stock");
                    return;
                }

                // Validate purchase order
                $purchase_order = $this->Stock_model_new->get_purchase_order_for_stock_addition(
                    $po_id,
                );
                if (!$purchase_order) {
                    $this->session->set_flashdata(
                        "error",
                        "Purchase order not found or not ready for stock addition!",
                    );
                    redirect("stocks_new/purchase_orders_for_stock");
                    return;
                }

                // Process stock items - Following original structure
                $stock_items = [];
                $i = 1;

                while ($this->input->post("item_number_" . $i)) {
                    $quantity_received =
                        floatval(
                            $this->input->post("quantity_received_" . $i),
                        ) ?:
                        0;
                    $quantity_rejected =
                        floatval(
                            $this->input->post("quantity_rejected_" . $i),
                        ) ?:
                        0;
                    $free_quantity =
                        floatval($this->input->post("free_quantity_" . $i)) ?:
                        0;

                    if ($quantity_received > 0) {
                        $stock_items[] = [
                            "item_number" => $this->input->post(
                                "item_number_" . $i,
                            ),
                            "item_name" => $this->input->post(
                                "item_name_" . $i,
                            ),
                            "brand_name" => $this->input->post(
                                "brand_name_" . $i,
                            ),
                            "generic_name" => $this->input->post(
                                "generic_name_" . $i,
                            ),
                            "company" => $this->input->post("company_" . $i),
                            "vendor_number" => $purchase_order->vendor_number,
                            "batch_number" => $this->input->post(
                                "batch_number_" . $i,
                            ),
                            "quantity_received" => $quantity_received,
                            "quantity_rejected" => $quantity_rejected,
                            "free_quantity" => $free_quantity,
                            "purchase_price" => floatval(
                                $this->input->post("purchase_price_" . $i),
                            ),
                            "selling_price" => floatval(
                                $this->input->post("selling_price_" . $i),
                            ),
                            "mrp" => floatval($this->input->post("mrp_" . $i)),
                            "expiry_date" => $this->input->post(
                                "expiry_date_" . $i,
                            ),
                            "manufacturing_date" => $this->input->post(
                                "manufacturing_date_" . $i,
                            ),
                            "pack_size" => $this->input->post(
                                "pack_size_" . $i,
                            ),
                            "hsn" => $this->input->post("hsn_" . $i),
                            "tax_percentage" => floatval(
                                $this->input->post("tax_percentage_" . $i),
                            ),
                            "discount_amount" => floatval(
                                $this->input->post("discount_amount_" . $i),
                            ),
                            "invoice_number" => $this->input->post(
                                "invoice_number",
                            ),
                            "receipt_date" => $this->input->post(
                                "receipt_date",
                            ),
                            "received_by" => $this->input->post("received_by"),
                            "comments" => $this->input->post("comments_" . $i),
                        ];
                    }
                    $i++;
                }

                if (empty($stock_items)) {
                    $this->session->set_flashdata(
                        "error",
                        "No items to add to stock!",
                    );
                    redirect("stocks_new/add_stock_from_po/" . $po_id);
                    return;
                }

                // Add stock from purchase order
                $result = $this->Stock_model_new->add_stock_from_purchase_order(
                    $po_id,
                    $stock_items,
                );

                if ($result["success"]) {
                    if ($result["success_count"] == $result["total_items"]) {
                        $this->session->set_flashdata(
                            "success",
                            "Stock added successfully for all {$result["total_items"]} item(s)!",
                        );
                    } else {
                        $this->session->set_flashdata(
                            "warning",
                            "Stock added for {$result["success_count"]} out of {$result["total_items"]} item(s). Please check failed items.",
                        );
                    }
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "Failed to add stock: " .
                            (isset($result["error"])
                                ? $result["error"]
                                : "Unknown error"),
                    );
                }

                redirect("stocks_new/purchase_orders_for_stock");
            }
        } else {
            header("location:" . base_url() . "");
        }
    }

    /**
     * View purchase order stock addition history
     */
    public function po_stock_history()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get purchase orders that have been processed for stock
            $data[
                "processed_pos"
            ] = $this->Stock_model_new->get_processed_purchase_orders();

            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/po_stock_history", $data);
            $this->load->view($template["footer"]);
        } else {
            header("location:" . base_url() . "");
        }
    }

    /**
     * Get employee ID from employee number
     */
    private function get_employee_id_from_number($employee_number)
    {
        if (empty($employee_number)) {
            return null;
        }

        $this->db->select("ID");
        $this->db->from("hms_employees");
        $this->db->where("employee_number", $employee_number);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->ID;
        }

        return null;
    }

    function get_employee_list()
    {
        $result = [];
        $sql_condition = "";
        $sql =
            "Select * from " .
            $this->config->item("db_prefix") .
            "employees where other_role='stock_manager' and status='1'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return $result;
        }
    }

    function get_departments_by_center()
    {
        $result = [];
        $sql_condition = "";
        $sql =
            "Select DISTINCT department from " .
            $this->config->item("db_prefix") .
            "employees where status='1' and department != '' ORDER BY department ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)) {
            return $result;
        }
    }

    function get_employees_ajax()
    {
        $center_id = $this->input->post("center_id");
        $department = $this->input->post("department");
        var_dump($center_id, $department);
        exit();
        $result = [];
        $sql_condition = "";
        if (!empty($center_id)) {
            $sql_condition .= " AND center_id = '" . $center_id . "'";
        }

        if (!empty($department)) {
            $sql_condition .= " AND department = '" . $department . "'";
        }

        $sql =
            "Select DISTINCT designation from " .
            $this->config->item("db_prefix") .
            "employees WHERE status='1' " .
            $sql_condition .
            " ORDER BY designation ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)) {
            return $result;
        }
    }

    /**
     * Loads the "Edit Vendor Return" form to edit header details
     */
    public function edit_vendor_return($id)
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->load->model('Stock_model_new');
            $data['return_details'] = $this->Stock_model_new->get_vendor_return_by_id($id);
            if (!$data['return_details']) {
                $this->session->set_flashdata('error', 'Vendor return report not found.');
                redirect('stocks_new/vendor_returns');
                return;
            }
            if ($data['return_details']->status != 'PENDING') {
                $this->session->set_flashdata('error', 'This return is already ' . strtolower($data['return_details']->status) . ' and cannot be edited.');
                redirect('stocks_new/view_vendor_return/' . $id); // View details instead
                return;
            }
            $data['return_items'] = $this->Stock_model_new->get_vendor_return_items_from_log($id);
            $data["vendors"] = $this->Stock_model_new->get_all_vendors();
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $template = get_header_template($logg['role']);
            $this->load->view($template["header"]);
            $this->load->view('stocks_new/edit_vendor_return', $data); // Load the new edit view
            $this->load->view($template["footer"]);
        } else {
            redirect(base_url());
        }
    }
    public function process_edit_vendor_return($id)
    {
        $logg = checklogin();
        if ($logg['status'] == true) {
            $this->load->model('Stock_model_new');
            $return_details = $this->Stock_model_new->get_vendor_return_by_id($id);
            if (!$return_details) {
                show_404();
                return;
            }
            // 2. CRITICAL CHECK: Only allow update if status is still PENDING
            if ($return_details->status != 'PENDING') {
                $this->session->set_flashdata('error', 'This return is already ' . strtolower($return_details->status) . ' and cannot be edited.');
                redirect('stocks_new/view_vendor_return/' . $id);
                return;
            }
            // 3. Validation
            $this->form_validation->set_rules('return_date', 'Return Date', 'required|trim');
            $this->form_validation->set_rules('return_reason', 'Return Reason', 'trim');
            $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            // We do NOT validate vendor_id or center_id as they shouldn't be changed
            if ($this->form_validation->run() == TRUE) {
                // 4. Prepare data for update (Only editable fields)
                $update_data = [
                    "return_date"   => $this->input->post("return_date"),
                    "return_reason" => $this->input->post("return_reason"),
                    "remarks"       => $this->input->post("remarks"),
                    "status"        => $this->input->post("status"), // Allow changing status (e.g., to APPROVED)
                    "updated_at"    => date("Y-m-d H:i:s")
                ];
                // 5. Call Model function to update
                if ($this->Stock_model_new->update_vendor_return_header($id, $update_data)) {
                    $this->session->set_flashdata("success", "Vendor return details updated successfully.");
                } else {
                    $this->session->set_flashdata("error", "Failed to update vendor return. No changes made or database error.");
                }
                redirect('stocks_new/view_vendor_return/' . $id); // Redirect back to view page
            } else {
                // Validation failed, reload the edit form
                $this->session->set_flashdata("error", validation_errors());
                // Reload data for the form
                $data['return_details'] = $return_details;
                $data['return_items'] = $this->Stock_model_new->get_vendor_return_items_from_log($id);
                $data["vendors"] = $this->Stock_model_new->get_all_vendors();
                $data["centers"] = $this->Stock_model_new->get_all_centers();
                $template = get_header_template($logg['role']);
                $this->load->view($template["header"]);
                $this->load->view('stocks_new/edit_vendor_return', $data);
                $this->load->view($template["footer"]);
            }
        } else {
            redirect(base_url());
        }
    }
    /**
     * Loads the "Dispose Batch" page for a single batch (GET request)
     * OR processes the disposal (POST request).
     */
    public function dispose_batch($batch_id = 0)
    {
        $logg = checklogin();
        if ($logg["status"] != true) { redirect(base_url()); return; }

        $this->load->model('Stock_model_new');
        $this->load->library('form_validation');
        // --- Handle POST request (Form Submission) ---
        if ($this->input->post('action') == 'dispose_single_batch') {
            
            $posted_batch_id = $this->input->post('batch_id');
            $this->form_validation->set_rules('location_key', 'Location', 'required|trim');
            $this->form_validation->set_rules('quantity_disposed', 'Quantity', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('disposal_type', 'Disposal Reason', 'required|trim');
            $this->form_validation->set_rules('disposal_date', 'Disposal Date', 'required|trim');
            $this->form_validation->set_rules('authorized_by', 'Authorized By', 'required|trim');

            if ($this->form_validation->run() == TRUE) {
                
                // Get Employee ID (Primary Key)
                $created_by_id = null;
                if (isset($logg['ID'])) {
                   $created_by_id = $logg['ID'];
                } elseif ($_SESSION['logged_central_stock_manager']["employee_number"]) {
                    $employee = $this->db->where("employee_number", $_SESSION['logged_central_stock_manager']["employee_number"])->get("hms_employees")->row();
                    if ($employee) $created_by_id = $employee->ID;
                }

                if (!$created_by_id) {
                     $this->session->set_flashdata("error", "Could not identify logged-in user ID.");
                     redirect('stocks_new/dispose_batch/' . $posted_batch_id);
                     return;
                }

                // Parse the location_key (e.g., "CENTER|5" or "CENTRAL|0")
                $location_parts = explode('|', $this->input->post('location_key'));
                $location_type = $location_parts[0]; // 'CENTER' or 'CENTRAL'
                $center_id = ($location_type == 'CENTER') ? (int)$location_parts[1] : null;

                // Prepare data for the model
                $disposal_data = [
                    'batch_id'          => $posted_batch_id,
                    'location_type'     => $location_type,
                    'center_id'         => $center_id, // Null if CENTRAL
                    'disposal_type'     => $this->input->post('disposal_type'), // Maps to disposal_reports.disposal_type
                    'quantity_disposed' => $this->input->post('quantity_disposed'),
                    'disposal_method'   => $this->input->post('disposal_method'), // From form
                    'disposal_company'  => $this->input->post('disposal_company'), // From form
                    'authorized_by'     => $this->input->post('authorized_by'), // From form
                    'disposal_date'     => $this->input->post('disposal_date'),
                    'remarks'           => $this->input->post('remarks'),
                    'created_by'        => $created_by_id
                ];

                $result = $this->Stock_model_new->process_single_batch_disposal($disposal_data);
                
                if ($result['status'] == 'success') {
                    $this->session->set_flashdata('success', 'Batch disposed successfully.');
                    redirect('stocks_new/batches'); // Redirect to batch list
                } else {
                    $this->session->set_flashdata('error', 'Disposal failed: ' . $result['message']);
                    redirect('stocks_new/dispose_batch/' . $posted_batch_id);
                }
            } else {
                // Validation failed
                $this->session->set_flashdata('error', validation_errors());
                redirect('stocks_new/dispose_batch/' . $this->input->post('batch_id'));
            }
            return; // End POST logic
        }
        if ($batch_id == 0) {
            $this->session->set_flashdata('error', 'No batch ID provided.');
            redirect('stocks_new/batches'); // Redirect to batch list
            return;
        }
        $data['batch_info'] = $this->Stock_model_new->get_batch_stock_locations($batch_id);
        if (!$data['batch_info'] || empty($data['batch_info']['locations'])) {
            $this->session->set_flashdata('error', 'Batch not found or no stock available for this batch.');
            redirect('stocks_new/batches');
            return;
        }

        $template = get_header_template($logg['role']);
        $this->load->view($template["header"]);
        $this->load->view('stocks_new/dispose_batch', $data); // The new view file
        $this->load->view($template["footer"]);
    }

        /**
     * Loads the FEFO Analytics report page.
     * This report focuses on waste, expiry, and stock rotation.
     */
    public function fefo_analytics()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $this->load->model('Stock_model_new');
        $data['title'] = "FEFO Analytics";

        // --- FIX IS HERE ---
        // 1. Define the number of days in a variable
        $days_to_check = 90; 
        
        // 2. Pass the variable to your model function
        $data['at_risk_stock'] = $this->Stock_model_new->get_at_risk_stock($days_to_check); 
        
        // 3. Pass the variable to the view data array
        $data['days_not_sold'] = $days_to_check; 
        // --- END FIX ---

        // Get other data
        $data['wastage_by_month'] = $this->Stock_model_new->get_wastage_by_month(12);

        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("stocks_new/fefo_analytics", $data); // This view will now receive $days_not_sold
        $this->load->view($template["footer"]);
    }

    /**
     * Loads the Inventory Analytics report page.
     * This report focuses on value, performance, and distribution.
     */
    public function inventory_analytics()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $this->load->model('Stock_model_new');
        $data['title'] = "Inventory Analytics";

        // Get data from the model
        $data['stock_distribution'] = $this->Stock_model_new->get_center_stock_distribution();
        $data['top_medicines'] = $this->Stock_model_new->get_top_performing_medicines(10);
        $data['vendor_performance'] = $this->Stock_model_new->get_vendor_performance();

        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("stocks_new/inventory_analytics", $data); // New view file
        $this->load->view($template["footer"]);
    }

}
