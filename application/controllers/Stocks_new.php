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
            $medicine_name = $this->input->get("medicine_name");
            $generic_name = $this->input->get("generic_name");
            $brand_id = $this->input->get("brand_id");
            $category = $this->input->get("category");
            $data["medicines"] = $this->Stock_model_new->get_all_medicines(
                $medicine_name,
                $generic_name,
                $brand_id,
                $category
            );
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

    /**
     * Export medicines list to Excel or PDF
     */
    public function export_medicines_list()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $format = $this->input->get('format'); // 'excel' or 'pdf'

        // Get filter parameters
        $medicine_name = $this->input->get("medicine_name");
        $generic_name = $this->input->get("generic_name");
        $brand_id = $this->input->get("brand_id");
        $category = $this->input->get("category");

        // Get medicines data with same filters as medicines() method
        $medicines = $this->Stock_model_new->get_all_medicines(
            $medicine_name,
            $generic_name,
            $brand_id,
            $category
        );

        if (empty($medicines)) {
            $this->session->set_flashdata('error', 'No medicines found to export.');
            redirect('stocks_new/medicines');
            return;
        }

        if ($format == 'excel') {
            $this->export_medicines_excel($medicines);
        } elseif ($format == 'pdf') {
            $this->export_medicines_pdf($medicines);
        } else {
            // Default to Excel
            $this->export_medicines_excel($medicines);
        }
    }

    /**
     * Export medicines to Excel
     */
    private function export_medicines_excel($medicines)
    {
        // Set headers for Excel download
        $filename = 'Medicines_List_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'Medicine Code',
            'Medicine Name',
            'Generic Name',
            'Brand',
            'Strength',
            'Unit',
            'Category',
            'Min Stock Level',
            'Max Stock Level',
            'Status'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($medicines as $medicine) {
            $row = [
                $medicine->medicine_code ?? 'N/A',
                $medicine->medicine_name ?? 'N/A',
                $medicine->generic_name ?? 'N/A',
                $medicine->brand_name ?? 'N/A',
                $medicine->strength ?? 'N/A',
                $medicine->unit ?? 'N/A',
                $medicine->category ?? 'N/A',
                $medicine->min_stock_level ?? 0,
                $medicine->max_stock_level ?? 0,
                isset($medicine->status) ? ucfirst($medicine->status) : 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    /**
     * Export medicines to PDF (HTML print version)
     */
    private function export_medicines_pdf($medicines)
    {
        // Create a print-friendly HTML page that can be printed as PDF
        $data = [
            'medicines' => $medicines,
            'generated_date' => date('M d, Y H:i A')
        ];
        
        // Load the print view
        $this->load->view('stocks_new/print_medicines_list', $data);
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

    public function import_medicines_excel()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Check if file was uploaded
            if (!isset($_FILES["excel_file"]) || $_FILES["excel_file"]["error"] != 0) {
                $this->session->set_flashdata(
                    "error",
                    "Please select a valid Excel file to upload.",
                );
                redirect("stocks_new/add_medicine");
                return;
            }

            $file = $_FILES["excel_file"];
            $fileExtension = strtolower(
                pathinfo($file["name"], PATHINFO_EXTENSION),
            );

            // Validate file extension
            if (!in_array($fileExtension, ["xlsx", "xls"])) {
                $this->session->set_flashdata(
                    "error",
                    "Invalid file format. Please upload .xlsx or .xls file.",
                );
                redirect("stocks_new/add_medicine");
                return;
            }

            // Load PhpSpreadsheet
            // Try multiple possible paths for vendor/autoload.php
            $possiblePaths = [
                FCPATH . "vendor/autoload.php",
                APPPATH . "../vendor/autoload.php",
                __DIR__ . "/../../vendor/autoload.php",
                dirname(FCPATH) . "/vendor/autoload.php",
            ];
            
            $vendorPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $vendorPath = $path;
                    break;
                }
            }
            
            if (!$vendorPath) {
                $this->session->set_flashdata(
                    "error",
                    "PhpSpreadsheet library not found. Please run 'composer install' in the project root directory.",
                );
                redirect("stocks_new/add_medicine");
                return;
            }
            require_once $vendorPath;

            try {
                $inputFileType =
                    $fileExtension == "xlsx"
                        ? \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX
                        : \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS;

                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(
                    $inputFileType,
                );
                $spreadsheet = $reader->load($file["tmp_name"]);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();

                // Get all brands for mapping
                $brands = $this->Stock_model_new->get_medicine_brands();
                $brandMap = [];
                foreach ($brands as $brand) {
                    $brandId = isset($brand->ID) ? $brand->ID : (isset($brand->id) ? $brand->id : null);
                    $brandName = isset($brand->brand_name)
                        ? $brand->brand_name
                        : (isset($brand->name) ? $brand->name : "");
                    if (!empty($brandName) && $brandId !== null) {
                        $brandMap[strtolower(trim($brandName))] = $brandId;
                    }
                }

                // Read header row (row 1)
                $headerRow = [];
                for ($col = 1; $col <= 15; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $cellValue = $worksheet->getCell($columnLetter . '1')->getValue();
                    $headerRow[$col] = strtolower(trim($cellValue));
                }

                // Map column indices
                $colMap = [];
                foreach ($headerRow as $colIndex => $header) {
                    if (strpos($header, "medicine code") !== false) {
                        $colMap["medicine_code"] = $colIndex;
                    } elseif (strpos($header, "medicine name") !== false) {
                        $colMap["medicine_name"] = $colIndex;
                    } elseif (strpos($header, "generic name") !== false) {
                        $colMap["generic_name"] = $colIndex;
                    } elseif (strpos($header, "brand") !== false) {
                        $colMap["brand"] = $colIndex;
                    } elseif (strpos($header, "strength") !== false) {
                        $colMap["strength"] = $colIndex;
                    } elseif (strpos($header, "unit") !== false) {
                        $colMap["unit"] = $colIndex;
                    } elseif (strpos($header, "category") !== false) {
                        $colMap["category"] = $colIndex;
                    } elseif (strpos($header, "pack size") !== false) {
                        $colMap["pack_size"] = $colIndex;
                    } elseif (strpos($header, "hsn code") !== false) {
                        $colMap["hsn_code"] = $colIndex;
                    } elseif (strpos($header, "gst rate") !== false) {
                        $colMap["gst_rate"] = $colIndex;
                    } elseif (strpos($header, "min stock") !== false) {
                        $colMap["min_stock_level"] = $colIndex;
                    } elseif (strpos($header, "max stock") !== false) {
                        $colMap["max_stock_level"] = $colIndex;
                    } elseif (strpos($header, "reorder level") !== false) {
                        $colMap["reorder_level"] = $colIndex;
                    } elseif (strpos($header, "narcotic") !== false) {
                        $colMap["is_narcotic"] = $colIndex;
                    } elseif (strpos($header, "controlled") !== false) {
                        $colMap["is_controlled_substance"] = $colIndex;
                    } elseif (strpos($header, "psychotropic") !== false) {
                        $colMap["is_psychotropic"] = $colIndex;
                    }
                }

                // Validate required columns
                $requiredColumns = [
                    "medicine_code",
                    "medicine_name",
                    "generic_name",
                    "brand",
                    "strength",
                    "unit",
                    "category",
                    "min_stock_level",
                    "max_stock_level",
                ];

                $missingColumns = [];
                foreach ($requiredColumns as $reqCol) {
                    if (!isset($colMap[$reqCol])) {
                        $missingColumns[] = $reqCol;
                    }
                }

                if (!empty($missingColumns)) {
                    $this->session->set_flashdata(
                        "error",
                        "Missing required columns: " .
                            implode(", ", $missingColumns) .
                            ". Please check your Excel file format.",
                    );
                    redirect("stocks_new/add_medicine");
                    return;
                }

                // Process data rows (starting from row 2)
                $successCount = 0;
                $errorCount = 0;
                $errors = [];

                for ($row = 2; $row <= $highestRow; $row++) {
                    // Get cell values using coordinate-based access
                    $getCellValue = function($colIndex, $rowNum) use ($worksheet) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        return $worksheet->getCell($columnLetter . $rowNum)->getValue();
                    };
                    
                    $medicine_code = trim($getCellValue($colMap["medicine_code"], $row));
                    $medicine_name = trim($getCellValue($colMap["medicine_name"], $row));
                    $generic_name = trim($getCellValue($colMap["generic_name"], $row));
                    $brand_name = trim($getCellValue($colMap["brand"], $row));
                    $strength = trim($getCellValue($colMap["strength"], $row));
                    $unit = trim($getCellValue($colMap["unit"], $row));
                    $category = trim($getCellValue($colMap["category"], $row));

                    // Skip empty rows
                    if (
                        empty($medicine_code) &&
                        empty($medicine_name) &&
                        empty($generic_name)
                    ) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($medicine_code)) {
                        $errors[] = "Row $row: Medicine Code is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($medicine_name)) {
                        $errors[] = "Row $row: Medicine Name is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($generic_name)) {
                        $errors[] = "Row $row: Generic Name is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($brand_name)) {
                        $errors[] = "Row $row: Brand is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($strength)) {
                        $errors[] = "Row $row: Strength is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($unit)) {
                        $errors[] = "Row $row: Unit is required";
                        $errorCount++;
                        continue;
                    }
                    if (empty($category)) {
                        $errors[] = "Row $row: Category is required";
                        $errorCount++;
                        continue;
                    }

                    // Get brand_id from brand name
                    $brand_id = null;
                    $brand_name_lower = strtolower(trim($brand_name));
                    if (isset($brandMap[$brand_name_lower])) {
                        $brand_id = $brandMap[$brand_name_lower];
                    } else {
                        $errors[] =
                            "Row $row: Brand '$brand_name' not found in system";
                        $errorCount++;
                        continue;
                    }

                    // Check if medicine_code already exists
                    $this->db->where("medicine_code", $medicine_code);
                    $existing = $this->db->get("medicines")->row();
                    if ($existing) {
                        $errors[] =
                            "Row $row: Medicine Code '$medicine_code' already exists";
                        $errorCount++;
                        continue;
                    }

                    // Get optional fields
                    $pack_size = isset($colMap["pack_size"])
                        ? trim($getCellValue($colMap["pack_size"], $row))
                        : "";
                    $hsn_code = isset($colMap["hsn_code"])
                        ? trim($getCellValue($colMap["hsn_code"], $row))
                        : "";
                    $gst_rate = isset($colMap["gst_rate"])
                        ? trim($getCellValue($colMap["gst_rate"], $row))
                        : "12";
                    $min_stock_level = isset($colMap["min_stock_level"])
                        ? trim($getCellValue($colMap["min_stock_level"], $row))
                        : "0";
                    $max_stock_level = isset($colMap["max_stock_level"])
                        ? trim($getCellValue($colMap["max_stock_level"], $row))
                        : "0";
                    $reorder_level = isset($colMap["reorder_level"])
                        ? trim($getCellValue($colMap["reorder_level"], $row))
                        : "";
                    $is_narcotic = isset($colMap["is_narcotic"])
                        ? trim($getCellValue($colMap["is_narcotic"], $row))
                        : "0";
                    $is_controlled_substance = isset($colMap["is_controlled_substance"])
                        ? trim($getCellValue($colMap["is_controlled_substance"], $row))
                        : "0";
                    $is_psychotropic = isset($colMap["is_psychotropic"])
                        ? trim($getCellValue($colMap["is_psychotropic"], $row))
                        : "0";

                    // Prepare medicine data
                    $medicine_data = [
                        "medicine_code" => $medicine_code,
                        "brand_id" => $brand_id,
                        "medicine_name" => $medicine_name,
                        "generic_name" => $generic_name,
                        "strength" => $strength,
                        "unit" => $unit,
                        "category" => $category,
                        "pack_size" => $pack_size,
                        "hsn_code" => $hsn_code,
                        "gst_rate" => !empty($gst_rate) ? $gst_rate : "12",
                        "min_stock_level" => !empty($min_stock_level)
                            ? $min_stock_level
                            : "0",
                        "max_stock_level" => !empty($max_stock_level)
                            ? $max_stock_level
                            : "0",
                        "reorder_level" => !empty($reorder_level)
                            ? $reorder_level
                            : "",
                        "is_narcotic" => in_array(
                            strtolower($is_narcotic),
                            ["1", "yes", "y", "true"],
                        )
                            ? 1
                            : 0,
                        "is_controlled_substance" => in_array(
                            strtolower($is_controlled_substance),
                            ["1", "yes", "y", "true"],
                        )
                            ? 1
                            : 0,
                        "is_psychotropic" => in_array(
                            strtolower($is_psychotropic),
                            ["1", "yes", "y", "true"],
                        )
                            ? 1
                            : 0,
                        "status" => "active",
                    ];

                    // Insert medicine
                    if ($this->Stock_model_new->add_medicine($medicine_data)) {
                        $successCount++;
                    } else {
                        $errors[] =
                            "Row $row: Failed to insert medicine '$medicine_name'";
                        $errorCount++;
                    }
                }

                // Set flash messages
                if ($successCount > 0) {
                    $message =
                        "Successfully imported $successCount medicine(s).";
                    if ($errorCount > 0) {
                        $message .=
                            " $errorCount row(s) failed. Please check the errors below.";
                    }
                    $this->session->set_flashdata("success", $message);
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "No medicines were imported. Please check your Excel file.",
                    );
                }

                if ($errorCount > 0 && !empty($errors)) {
                    $errorMessage =
                        "Errors encountered:<br>" .
                        implode("<br>", array_slice($errors, 0, 10));
                    if (count($errors) > 10) {
                        $errorMessage .=
                            "<br>... and " .
                            (count($errors) - 10) .
                            " more errors.";
                    }
                    $this->session->set_flashdata("error_details", $errorMessage);
                }

                redirect("stocks_new/add_medicine");
            } catch (Exception $e) {
                $this->session->set_flashdata(
                    "error",
                    "Error reading Excel file: " . $e->getMessage(),
                );
                redirect("stocks_new/add_medicine");
            }
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function download_medicine_template()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Load PhpSpreadsheet
            // Try multiple possible paths for vendor/autoload.php
            $possiblePaths = [
                FCPATH . "vendor/autoload.php",
                APPPATH . "../vendor/autoload.php",
                __DIR__ . "/../../vendor/autoload.php",
                dirname(FCPATH) . "/vendor/autoload.php",
            ];
            
            $vendorPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $vendorPath = $path;
                    break;
                }
            }
            
            if (!$vendorPath) {
                $this->session->set_flashdata(
                    "error",
                    "PhpSpreadsheet library not found. Please run 'composer install' in the project root directory.",
                );
                redirect("stocks_new/add_medicine");
                return;
            }
            require_once $vendorPath;

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                "A1" => "Medicine Code",
                "B1" => "Medicine Name",
                "C1" => "Generic Name",
                "D1" => "Brand",
                "E1" => "Strength",
                "F1" => "Unit",
                "G1" => "Category",
                "H1" => "Pack Size",
                "I1" => "HSN Code",
                "J1" => "GST Rate (%)",
                "K1" => "Min Stock Level",
                "L1" => "Max Stock Level",
                "M1" => "Reorder Level",
                "N1" => "Is Narcotic (Yes/No)",
                "O1" => "Is Controlled Substance (Yes/No)",
                "P1" => "Is Psychotropic (Yes/No)",
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Style header row
            $sheet
                ->getStyle("A1:P1")
                ->getFont()
                ->setBold(true);
            $sheet
                ->getStyle("A1:P1")
                ->getFill()
                ->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                )
                ->getStartColor()
                ->setARGB("FFE0E0E0");

            // Set column widths
            $sheet->getColumnDimension("A")->setWidth(15);
            $sheet->getColumnDimension("B")->setWidth(25);
            $sheet->getColumnDimension("C")->setWidth(25);
            $sheet->getColumnDimension("D")->setWidth(20);
            $sheet->getColumnDimension("E")->setWidth(15);
            $sheet->getColumnDimension("F")->setWidth(12);
            $sheet->getColumnDimension("G")->setWidth(20);
            $sheet->getColumnDimension("H")->setWidth(12);
            $sheet->getColumnDimension("I")->setWidth(15);
            $sheet->getColumnDimension("J")->setWidth(12);
            $sheet->getColumnDimension("K")->setWidth(15);
            $sheet->getColumnDimension("L")->setWidth(15);
            $sheet->getColumnDimension("M")->setWidth(15);
            $sheet->getColumnDimension("N")->setWidth(20);
            $sheet->getColumnDimension("O")->setWidth(25);
            $sheet->getColumnDimension("P")->setWidth(25);

            // Add sample data row
            $sampleData = [
                "A2" => "MED001",
                "B2" => "Paracetamol 500mg",
                "C2" => "Paracetamol",
                "D2" => "Sample Brand",
                "E2" => "500mg",
                "F2" => "TABLET",
                "G2" => "Cash Medicines",
                "H2" => "10",
                "I2" => "30049099",
                "J2" => "12",
                "K2" => "100",
                "L2" => "1000",
                "M2" => "200",
                "N2" => "No",
                "O2" => "No",
                "P2" => "No",
            ];

            foreach ($sampleData as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Get available brands for reference
            $brands = $this->Stock_model_new->get_medicine_brands();
            $brandNames = [];
            foreach ($brands as $brand) {
                $brandName = isset($brand->brand_name)
                    ? $brand->brand_name
                    : (isset($brand->name) ? $brand->name : "");
                if (!empty($brandName)) {
                    $brandNames[] = $brandName;
                }
            }

            // Add note about available brands
            if (!empty($brandNames)) {
                $sheet->setCellValue(
                    "A4",
                    "Available Brands: " . implode(", ", array_slice($brandNames, 0, 10)),
                );
                if (count($brandNames) > 10) {
                    $sheet->setCellValue(
                        "A5",
                        "... and " . (count($brandNames) - 10) . " more brands",
                    );
                }
            }

            // Set headers for download
            header(
                "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            );
            header(
                "Content-Disposition: attachment;filename=medicine_import_template.xlsx",
            );
            header("Cache-Control: max-age=0");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save("php://output");
            exit();
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
                // $this->form_validation->set_rules("batch_number", "Batch Number", "required");
                $this->form_validation->set_rules(
                    "batch_number", 
                    "Batch Number", 
                    "required|trim" // This rule calls your function
                );
                $this->form_validation->set_rules("expiry_date", "Expiry Date", "required");
                $this->form_validation->set_rules("purchase_price", "Purchase Price", "required|numeric");
                $this->form_validation->set_rules("selling_price", "Selling Price", "required|numeric");
                $this->form_validation->set_rules("quantity_purchased", "Quantity Purchased", "required|numeric|greater_than_equal_to[0]");
                
                // Validate center_id if center stock is selected
                $stock_location = $this->input->post("stock_location");
                if ($stock_location == "center") {
                    $this->form_validation->set_rules("center_id", "Center", "required");
                }
                
                if ($this->form_validation->run() == true) {
                    $created_by_id = $this->get_employee_id_from_number(
                        $_SESSION["logged_central_stock_manager"]["employee_number"]
                    );

                    // Get pack_size from form (default to 1 if not provided)
                    $pack_size = floatval($this->input->post("pack_size")) ?: 1;
                    
                    // Get quantity purchased (in packs)
                    $quantity_purchased_packs = floatval($this->input->post("quantity_purchased"));
                    
                    // Calculate quantity in units (multiply packs by pack_size)
                    $quantity_purchased_units = $quantity_purchased_packs * $pack_size;
                    
                    // Get prices (these are pack prices)
                    $purchase_price_unit = floatval($this->input->post("purchase_price"));
                    $selling_price_pack = floatval($this->input->post("selling_price"));
                    $mrp_pack = $this->input->post("mrp") ? floatval($this->input->post("mrp")) : NULL;
                    // Calculate per unit prices (divide pack price by pack_size)
                    // $selling_price_unit = $selling_price_pack / $pack_size;
                    // $mrp_unit = $mrp_pack ? ($mrp_pack / $pack_size) : NULL;

                    $batch_data = [
                        "medicine_id" => $this->input->post("medicine_id"),
                        "vendor_id" => $this->input->post("vendor_id"),
                        "batch_number" => $this->input->post("batch_number"), // Unique check should be in model
                        "manufacturing_date" => $this->input->post("manufacturing_date") ?: NULL,
                        "expiry_date" => $this->input->post("expiry_date"),
                        "purchase_price" => $purchase_price_unit, // Store per unit price
                        "selling_price" => $selling_price_pack, // Store per unit price
                        "mrp" => $mrp_pack, // Store per unit MRP
                        "quantity_purchased" => $quantity_purchased_units, // Store quantity in units
                        "quantity_remaining" => $quantity_purchased_units, // Set remaining to purchased (in units)
                        "purchase_date" => $this->input->post("purchase_date") ?: date('Y-m-d'),
                        "invoice_number" => $this->input->post("invoice_number"),
                        "invoice_date" => $this->input->post("invoice_date") ?: NULL,
                        "quality_status" => $this->input->post("quality_status") ?: 'PENDING',
                        "batch_status" => "ACTIVE",
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $created_by_id,
                        "created_at" => date("Y-m-d H:i:s")
                    ];
                    
                    // Add center stock information if center stock is selected
                    $stock_location = $this->input->post("stock_location");
                    if ($stock_location == "center") {
                        $batch_data["center_id"] = $this->input->post("center_id");
                        $batch_data["department"] = $this->input->post("department") ?: 'GENERAL';
                    }
                    
                    $result = $this->Stock_model_new->add_batch($batch_data); 
                    if ($result) {
                        $this->session->set_flashdata("success", "Batch added successfully!");
                    } else {
                        if ($this->db->error()['code'] == 1062) {
                             $this->session->set_flashdata("error", "Error: A batch with this number already exists for this medicine.");
                        } else {
                             $this->session->set_flashdata("error", "Error adding batch!");
                        }
                    }
                    redirect("stocks_new/batches");
                }
            }
            $data = [];
            $data["selected_medicine_details"] = null; // Default to null
            if ($this->input->get("medicine_id")) {
                $selected_id = (int)$this->input->get("medicine_id");
                $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
            }
            elseif ($this->input->post("medicine_id")) {
                 $selected_id = (int)$this->input->post("medicine_id");
                 $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
            }
            $data["vendors"] = $this->Stock_model_new->get_all_vendors(); // Use your function for this
            $data["centers"] = $this->Stock_model_new->get_all_centers(); // Get centers for center stock selection
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/add_batch", $data);
            $this->load->view($template["footer"]);

        } else {
            redirect(base_url());
        }
    }

    public function import_batches_excel()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Check if file was uploaded
            if (!isset($_FILES["excel_file"]) || $_FILES["excel_file"]["error"] != 0) {
                $this->session->set_flashdata(
                    "error",
                    "Please select a valid Excel file to upload.",
                );
                redirect("stocks_new/add_batch");
                return;
            }

            $file = $_FILES["excel_file"];
            $fileExtension = strtolower(
                pathinfo($file["name"], PATHINFO_EXTENSION),
            );

            // Validate file extension
            if (!in_array($fileExtension, ["xlsx", "xls"])) {
                $this->session->set_flashdata(
                    "error",
                    "Invalid file format. Please upload .xlsx or .xls file.",
                );
                redirect("stocks_new/add_batch");
                return;
            }

            // Load PhpSpreadsheet
            $possiblePaths = [
                FCPATH . "vendor/autoload.php",
                APPPATH . "../vendor/autoload.php",
                __DIR__ . "/../../vendor/autoload.php",
                dirname(FCPATH) . "/vendor/autoload.php",
            ];
            
            $vendorPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $vendorPath = $path;
                    break;
                }
            }
            
            if (!$vendorPath) {
                $this->session->set_flashdata(
                    "error",
                    "PhpSpreadsheet library not found. Please run 'composer install' in the project root directory.",
                );
                redirect("stocks_new/add_batch");
                return;
            }
            require_once $vendorPath;

            try {
                $inputFileType =
                    $fileExtension == "xlsx"
                        ? \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX
                        : \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS;

                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(
                    $inputFileType,
                );
                $spreadsheet = $reader->load($file["tmp_name"]);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();

                // Get all medicines for mapping
                $medicines = $this->Stock_model_new->get_all_medicines();
                $medicineMap = [];
                foreach ($medicines as $medicine) {
                    $medicineId = isset($medicine->id) ? $medicine->id : (isset($medicine->ID) ? $medicine->ID : null);
                    $medicineCode = isset($medicine->medicine_code) ? strtolower(trim($medicine->medicine_code)) : "";
                    $medicineName = isset($medicine->medicine_name) ? strtolower(trim($medicine->medicine_name)) : "";
                    if ($medicineId !== null) {
                        if (!empty($medicineCode)) {
                            $medicineMap[$medicineCode] = $medicineId;
                        }
                        if (!empty($medicineName)) {
                            $medicineMap[$medicineName] = $medicineId;
                        }
                    }
                }

                // Get all vendors for mapping
                $vendors = $this->Stock_model_new->get_all_vendors();
                $vendorMap = [];
                foreach ($vendors as $vendor) {
                    $vendorId = isset($vendor->ID) ? $vendor->ID : (isset($vendor->id) ? $vendor->id : null);
                    $vendorName = isset($vendor->vendor_name) ? strtolower(trim($vendor->vendor_name)) : (isset($vendor->name) ? strtolower(trim($vendor->name)) : "");
                    if (!empty($vendorName) && $vendorId !== null) {
                        $vendorMap[$vendorName] = $vendorId;
                    }
                }
                
                // Get all centers for mapping (if center stock is selected)
                $excel_stock_location = $this->input->post("excel_stock_location") ?: "central";
                $centers = $this->Stock_model_new->get_all_centers();
                $centerMap = [];
                foreach ($centers as $center) {
                    $centerId = isset($center->ID) ? $center->ID : (isset($center->id) ? $center->id : null);
                    $centerName = isset($center->center_name) ? strtolower(trim($center->center_name)) : (isset($center->name) ? strtolower(trim($center->name)) : "");
                    $centerNumber = isset($center->center_number) ? strtolower(trim($center->center_number)) : "";
                    if ($centerId !== null) {
                        if (!empty($centerName)) {
                            $centerMap[$centerName] = $centerId;
                        }
                        if (!empty($centerNumber)) {
                            $centerMap[$centerNumber] = $centerId;
                        }
                    }
                }

                // Read header row (row 1)
                $headerRow = [];
                for ($col = 1; $col <= 20; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $cellValue = $worksheet->getCell($columnLetter . '1')->getValue();
                    $headerRow[$col] = strtolower(trim($cellValue));
                }

                // Map column indices
                $colMap = [];
                foreach ($headerRow as $colIndex => $header) {
                    if (strpos($header, "medicine code") !== false || strpos($header, "medicine_code") !== false) {
                        $colMap["medicine_code"] = $colIndex;
                    } elseif (strpos($header, "medicine name") !== false || strpos($header, "medicine_name") !== false) {
                        $colMap["medicine_name"] = $colIndex;
                    } elseif (strpos($header, "vendor") !== false) {
                        $colMap["vendor"] = $colIndex;
                    } elseif (strpos($header, "batch number") !== false || strpos($header, "batch_number") !== false) {
                        $colMap["batch_number"] = $colIndex;
                    } elseif (strpos($header, "expiry date") !== false || strpos($header, "expiry_date") !== false) {
                        $colMap["expiry_date"] = $colIndex;
                    } elseif (strpos($header, "purchase date") !== false || strpos($header, "purchase_date") !== false) {
                        $colMap["purchase_date"] = $colIndex;
                    } elseif (strpos($header, "purchase price") !== false || strpos($header, "purchase_price") !== false) {
                        $colMap["purchase_price"] = $colIndex;
                    } elseif (strpos($header, "mrp") !== false) {
                        $colMap["mrp"] = $colIndex;
                    } elseif (strpos($header, "selling price") !== false || strpos($header, "selling_price") !== false) {
                        $colMap["selling_price"] = $colIndex;
                    } elseif (strpos($header, "quantity") !== false) {
                        $colMap["quantity_purchased"] = $colIndex;
                    } elseif (strpos($header, "invoice number") !== false || strpos($header, "invoice_number") !== false) {
                        $colMap["invoice_number"] = $colIndex;
                    } elseif (strpos($header, "invoice date") !== false || strpos($header, "invoice_date") !== false) {
                        $colMap["invoice_date"] = $colIndex;
                    } elseif (strpos($header, "quality status") !== false || strpos($header, "quality_status") !== false) {
                        $colMap["quality_status"] = $colIndex;
                    } elseif (strpos($header, "remarks") !== false) {
                        $colMap["remarks"] = $colIndex;
                    } elseif (strpos($header, "center") !== false && strpos($header, "name") === false && strpos($header, "number") === false) {
                        $colMap["center"] = $colIndex;
                    } elseif (strpos($header, "center name") !== false || strpos($header, "center_name") !== false) {
                        $colMap["center_name"] = $colIndex;
                    } elseif (strpos($header, "center number") !== false || strpos($header, "center_number") !== false) {
                        $colMap["center_number"] = $colIndex;
                    } elseif (strpos($header, "department") !== false) {
                        $colMap["department"] = $colIndex;
                    }
                }

                // Validate required columns
                $requiredColumns = [
                    "batch_number",
                    "expiry_date",
                    "purchase_price",
                    "selling_price",
                    "quantity_purchased",
                ];

                $missingColumns = [];
                foreach ($requiredColumns as $reqCol) {
                    if (!isset($colMap[$reqCol])) {
                        $missingColumns[] = $reqCol;
                    }
                }

                // Check if at least one medicine identifier is present
                if (!isset($colMap["medicine_code"]) && !isset($colMap["medicine_name"])) {
                    $missingColumns[] = "medicine_code or medicine_name";
                }

                // Check if vendor is present
                if (!isset($colMap["vendor"])) {
                    $missingColumns[] = "vendor";
                }

                if (!empty($missingColumns)) {
                    $this->session->set_flashdata(
                        "error",
                        "Missing required columns: " .
                            implode(", ", $missingColumns) .
                            ". Please check your Excel file format.",
                    );
                    redirect("stocks_new/add_batch");
                    return;
                }

                // Process data rows (starting from row 2)
                $successCount = 0;
                $errorCount = 0;
                $errors = [];

                for ($row = 2; $row <= $highestRow; $row++) {
                    // Get cell values using coordinate-based access
                    $getCellValue = function($colIndex, $rowNum) use ($worksheet) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $cell = $worksheet->getCell($columnLetter . $rowNum);
                        // Try to get calculated value first (handles formulas), then fall back to value
                        try {
                            $value = $cell->getCalculatedValue();
                            // If calculated value is null or empty, try getValue
                            if ($value === null || $value === '') {
                                $value = $cell->getValue();
                            }
                        } catch (\Exception $e) {
                            $value = $cell->getValue();
                        }
                        return $value;
                    };
                    
                    // Helper function to clean numeric values (remove currency symbols, commas, etc.)
                    $cleanNumericValue = function($value) {
                        if ($value === null || $value === '') {
                            return '';
                        }
                        // Convert to string first
                        $value = (string)$value;
                        // Remove currency symbols, commas, spaces, and other formatting
                        $value = str_replace(['₹', '$', '€', '£', ',', ' ', 'Rs.', 'Rs', 'INR'], '', $value);
                        // Remove any non-numeric characters except decimal point and minus sign
                        $value = preg_replace('/[^0-9.\-]/', '', $value);
                        return trim($value);
                    };
                    
                    // Get medicine identifier
                    $medicine_code = isset($colMap["medicine_code"]) ? trim((string)$getCellValue($colMap["medicine_code"], $row)) : "";
                    $medicine_name = isset($colMap["medicine_name"]) ? trim((string)$getCellValue($colMap["medicine_name"], $row)) : "";
                    
                    $vendor_name = trim((string)$getCellValue($colMap["vendor"], $row));
                    $batch_number = trim((string)$getCellValue($colMap["batch_number"], $row));
                    $expiry_date = trim((string)$getCellValue($colMap["expiry_date"], $row));
                    $purchase_price_raw = $getCellValue($colMap["purchase_price"], $row);
                    $purchase_price = $cleanNumericValue($purchase_price_raw);
                    $selling_price_raw = $getCellValue($colMap["selling_price"], $row);
                    $selling_price = $cleanNumericValue($selling_price_raw);
                    $quantity_purchased_raw = $getCellValue($colMap["quantity_purchased"], $row);
                    $quantity_purchased = $cleanNumericValue($quantity_purchased_raw);

                    // Skip empty rows
                    if (empty($batch_number) && empty($medicine_code) && empty($medicine_name)) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($batch_number)) {
                        $errors[] = "Row $row: Batch Number is required";
                        $errorCount++;
                        continue;
                    }

                    // Find medicine_id
                    $medicine_id = null;
                    if (!empty($medicine_code)) {
                        $medicine_code_lower = strtolower(trim($medicine_code));
                        if (isset($medicineMap[$medicine_code_lower])) {
                            $medicine_id = $medicineMap[$medicine_code_lower];
                        }
                    }
                    if ($medicine_id === null && !empty($medicine_name)) {
                        $medicine_name_lower = strtolower(trim($medicine_name));
                        if (isset($medicineMap[$medicine_name_lower])) {
                            $medicine_id = $medicineMap[$medicine_name_lower];
                        }
                    }

                    if ($medicine_id === null) {
                        $errors[] = "Row $row: Medicine not found (Code: '$medicine_code', Name: '$medicine_name')";
                        $errorCount++;
                        continue;
                    }

                    // Find vendor_id
                    $vendor_id = null;
                    $vendor_name_lower = strtolower(trim($vendor_name));
                    if (isset($vendorMap[$vendor_name_lower])) {
                        $vendor_id = $vendorMap[$vendor_name_lower];
                    }

                    if ($vendor_id === null) {
                        $errors[] = "Row $row: Vendor '$vendor_name' not found in system";
                        $errorCount++;
                        continue;
                    }

                    // Validate expiry date
                    if (empty($expiry_date)) {
                        $errors[] = "Row $row: Expiry Date is required";
                        $errorCount++;
                        continue;
                    }

                    // Convert date format if needed
                    $expiry_date_formatted = $this->convertExcelDate($expiry_date);
                    if (!$expiry_date_formatted) {
                        $errors[] = "Row $row: Invalid Expiry Date format (Value: '$expiry_date'). Expected format: YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY";
                        $errorCount++;
                        continue;
                    }

                    // Validate purchase price
                    if (empty($purchase_price) || !is_numeric($purchase_price)) {
                        $raw_display = is_null($purchase_price_raw) ? 'empty' : "'" . (string)$purchase_price_raw . "'";
                        $errors[] = "Row $row: Purchase Price must be a valid number (Found: $raw_display)";
                        $errorCount++;
                        continue;
                    }

                    // Validate selling price
                    if (empty($selling_price) || !is_numeric($selling_price)) {
                        $raw_display = is_null($selling_price_raw) ? 'empty' : "'" . (string)$selling_price_raw . "'";
                        $errors[] = "Row $row: Selling Price must be a valid number (Found: $raw_display)";
                        $errorCount++;
                        continue;
                    }

                    // Validate quantity
                    if (empty($quantity_purchased) || !is_numeric($quantity_purchased) || $quantity_purchased <= 0) {
                        $raw_display = is_null($quantity_purchased_raw) ? 'empty' : "'" . (string)$quantity_purchased_raw . "'";
                        $errors[] = "Row $row: Quantity Purchased must be a valid positive number (Found: $raw_display)";
                        $errorCount++;
                        continue;
                    }

                    // Get optional fields
                    $purchase_date = isset($colMap["purchase_date"]) ? trim((string)$getCellValue($colMap["purchase_date"], $row)) : "";
                    $mrp_raw = isset($colMap["mrp"]) ? $getCellValue($colMap["mrp"], $row) : "";
                    $mrp = !empty($mrp_raw) ? $cleanNumericValue($mrp_raw) : "";
                    $invoice_number = isset($colMap["invoice_number"]) ? trim((string)$getCellValue($colMap["invoice_number"], $row)) : "";
                    $invoice_date = isset($colMap["invoice_date"]) ? trim((string)$getCellValue($colMap["invoice_date"], $row)) : "";
                    $quality_status = isset($colMap["quality_status"]) ? trim((string)$getCellValue($colMap["quality_status"], $row)) : "PENDING";
                    $remarks = isset($colMap["remarks"]) ? trim((string)$getCellValue($colMap["remarks"], $row)) : "";

                    // Convert dates
                    $purchase_date_formatted = !empty($purchase_date) ? $this->convertExcelDate($purchase_date) : date('Y-m-d');
                    if (!empty($purchase_date) && !$purchase_date_formatted) {
                        $errors[] = "Row $row: Invalid Purchase Date format (Value: '$purchase_date'). Expected format: YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY";
                        $errorCount++;
                        continue;
                    }
                    
                    $invoice_date_formatted = !empty($invoice_date) ? $this->convertExcelDate($invoice_date) : null;
                    if (!empty($invoice_date) && !$invoice_date_formatted) {
                        $errors[] = "Row $row: Invalid Invoice Date format (Value: '$invoice_date'). Expected format: YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY";
                        $errorCount++;
                        continue;
                    }

                    // Get medicine details for pack_size
                    $medicine_details = $this->Stock_model_new->get_medicine_details_by_id($medicine_id);
                    $pack_size = isset($medicine_details->pack_size) ? floatval($medicine_details->pack_size) : 1;
                    
                    // Ensure pack_size is not zero to avoid division by zero error
                    if ($pack_size <= 0) {
                        $pack_size = 1;
                    }

                    // Calculate quantity in units (multiply packs by pack_size)
                    $quantity_purchased_units = floatval($quantity_purchased);

                    // Get prices (these are pack prices from Excel)
                    // If pack_size is 1, prices are already per unit, otherwise convert
                    if ($pack_size > 1) {
                        $purchase_price_unit = floatval($purchase_price) / $pack_size; // Convert to per unit
                    } else {
                        $purchase_price_unit = floatval($purchase_price); // Already per unit
                    }
                    $selling_price_pack = floatval($selling_price); // Keep as pack price (MRP and selling price are same)
                    $mrp_pack = !empty($mrp) ? floatval($mrp) : floatval($selling_price); // If MRP not provided, use selling price

                    // Get created_by
                    $created_by_id = $this->get_employee_id_from_number(
                        $_SESSION["logged_central_stock_manager"]["employee_number"]
                    );

                    // Get center and department information
                    $center_id = null;
                    $department = 'GENERAL';
                    
                    if ($excel_stock_location == "center") {
                        // Try to get center from Excel columns
                        $center_name = isset($colMap["center_name"]) ? trim((string)$getCellValue($colMap["center_name"], $row)) : "";
                        $center_number = isset($colMap["center_number"]) ? trim((string)$getCellValue($colMap["center_number"], $row)) : "";
                        $center_col = isset($colMap["center"]) ? trim((string)$getCellValue($colMap["center"], $row)) : "";
                        
                        // Try to find center by name or number
                        if (!empty($center_name)) {
                            $center_name_lower = strtolower(trim($center_name));
                            if (isset($centerMap[$center_name_lower])) {
                                $center_id = $centerMap[$center_name_lower];
                            }
                        }
                        if ($center_id === null && !empty($center_number)) {
                            $center_number_lower = strtolower(trim($center_number));
                            if (isset($centerMap[$center_number_lower])) {
                                $center_id = $centerMap[$center_number_lower];
                            }
                        }
                        if ($center_id === null && !empty($center_col)) {
                            $center_col_lower = strtolower(trim($center_col));
                            if (isset($centerMap[$center_col_lower])) {
                                $center_id = $centerMap[$center_col_lower];
                            }
                        }
                        
                        if ($center_id === null) {
                            $errors[] = "Row $row: Center not found (Name: '$center_name', Number: '$center_number')";
                            $errorCount++;
                            continue;
                        }
                        
                        // Get department
                        $department = isset($colMap["department"]) ? trim((string)$getCellValue($colMap["department"], $row)) : 'GENERAL';
                        if (empty($department)) {
                            $department = 'GENERAL';
                        }
                    }
                    
                    // Check if batch already exists (after all variables are defined)
                    $this->db->where("medicine_id", $medicine_id);
                    $this->db->where("batch_number", $batch_number);
                    $existing = $this->db->get("medicine_batches")->row();
                    if ($existing) {
                        // Update existing batch quantity instead of creating new entry
                        $update_data = [
                            "vendor_id" => $vendor_id,
                            "selling_price" => $selling_price_pack,
                            "invoice_number" => $invoice_number,
                            "created_by" => $created_by_id,
                        ];
                        
                        if ($this->Stock_model_new->update_batch_quantity(
                            $existing->id,
                            $quantity_purchased_units,
                            $center_id,
                            $department,
                            $update_data
                        )) {
                            $successCount++;
                        } else {
                            $errors[] = "Row $row: Failed to update batch '$batch_number' quantity";
                            $errorCount++;
                        }
                        continue;
                    }
                    
                    // Prepare batch data
                    $batch_data = [
                        "medicine_id" => $medicine_id,
                        "vendor_id" => $vendor_id,
                        "batch_number" => $batch_number,
                        "manufacturing_date" => null,
                        "expiry_date" => $expiry_date_formatted,
                        "purchase_price" => $purchase_price_unit, // Store per unit price
                        "selling_price" => $selling_price_pack, // Store pack price
                        "mrp" => $mrp_pack, // Store pack MRP
                        "quantity_purchased" => $quantity_purchased_units, // Store quantity in units
                        "quantity_remaining" => $quantity_purchased_units, // Set remaining to purchased (in units)
                        "purchase_date" => $purchase_date_formatted,
                        "invoice_number" => $invoice_number,
                        "invoice_date" => $invoice_date_formatted,
                        "quality_status" => strtoupper($quality_status) ?: 'PENDING',
                        "batch_status" => "ACTIVE",
                        "remarks" => $remarks,
                        "created_by" => $created_by_id,
                        "created_at" => date("Y-m-d H:i:s")
                    ];
                    
                    // Add center stock information if center stock is selected
                    if ($excel_stock_location == "center" && $center_id !== null) {
                        $batch_data["center_id"] = $center_id;
                        $batch_data["department"] = $department;
                    }

                    // Insert batch
                    if ($this->Stock_model_new->add_batch($batch_data)) {
                        $successCount++;
                    } else {
                        $db_error = $this->db->error();
                        $error_msg = "Row $row: Failed to insert batch '$batch_number'";
                        
                        if (!empty($db_error['code'])) {
                            if ($db_error['code'] == 1062) {
                                $error_msg = "Row $row: Batch '$batch_number' already exists for this medicine (Duplicate entry)";
                            } elseif ($db_error['code'] == 1452) {
                                $error_msg = "Row $row: Invalid foreign key reference (Medicine ID: $medicine_id or Vendor ID: $vendor_id not found)";
                            } elseif ($db_error['code'] == 1048) {
                                $error_msg = "Row $row: Required field is NULL (Batch: '$batch_number')";
                            } else {
                                $error_msg = "Row $row: Database error (Code: {$db_error['code']}) - " . ($db_error['message'] ?? 'Unknown error');
                            }
                        } elseif (!empty($db_error['message'])) {
                            $error_msg .= " - " . $db_error['message'];
                        }
                        
                        $errors[] = $error_msg;
                        $errorCount++;
                    }
                }

                // Set flash messages
                if ($successCount > 0) {
                    $message = "Successfully imported $successCount batch(es).";
                    if ($errorCount > 0) {
                        $message .= " $errorCount row(s) failed. Please check the errors below.";
                    }
                    $this->session->set_flashdata("success", $message);
                } else {
                    $this->session->set_flashdata(
                        "error",
                        "No batches were imported. Please check your Excel file.",
                    );
                }

                if ($errorCount > 0 && !empty($errors)) {
                    $errorMessage = "<strong>Total Errors: $errorCount</strong><br><br>";
                    $errorMessage .= "<ol style='margin-left: 20px;'>";
                    $displayCount = min(count($errors), 50); // Show up to 50 errors
                    for ($i = 0; $i < $displayCount; $i++) {
                        $errorMessage .= "<li>" . htmlspecialchars($errors[$i]) . "</li>";
                    }
                    $errorMessage .= "</ol>";
                    if (count($errors) > 50) {
                        $errorMessage .= "<br><strong>... and " . (count($errors) - 50) . " more errors. Please fix the above errors and try again.</strong>";
                    }
                    $this->session->set_flashdata("error_details", $errorMessage);
                }

                redirect("stocks_new/add_batch");
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                $this->session->set_flashdata(
                    "error",
                    "Error reading Excel file: " . $e->getMessage() . " (File may be corrupted or in wrong format)",
                );
                redirect("stocks_new/add_batch");
            } catch (Exception $e) {
                $errorDetails = "Error: " . $e->getMessage();
                if ($e->getFile()) {
                    $errorDetails .= " in file: " . basename($e->getFile()) . " at line " . $e->getLine();
                }
                $this->session->set_flashdata(
                    "error",
                    $errorDetails,
                );
                $this->session->set_flashdata(
                    "error_details",
                    "<strong>Exception Details:</strong><br>" . htmlspecialchars($e->getMessage()) . 
                    "<br><br><strong>File:</strong> " . htmlspecialchars($e->getFile()) . 
                    "<br><strong>Line:</strong> " . $e->getLine()
                );
                redirect("stocks_new/add_batch");
            }
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    private function convertExcelDate($dateValue)
    {
        // If it's already a date string in Y-m-d format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
            return $dateValue;
        }

        // If it's a date string in d/m/Y or d-m-Y format
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        // If it's an Excel serial date number
        if (is_numeric($dateValue)) {
            $excelBaseDate = new \DateTime('1899-12-30');
            $days = intval($dateValue);
            $excelBaseDate->modify("+$days days");
            return $excelBaseDate->format('Y-m-d');
        }

        // Try to parse as date
        $timestamp = strtotime($dateValue);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return false;
    }

    public function download_batch_template()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Load PhpSpreadsheet
            $possiblePaths = [
                FCPATH . "vendor/autoload.php",
                APPPATH . "../vendor/autoload.php",
                __DIR__ . "/../../vendor/autoload.php",
                dirname(FCPATH) . "/vendor/autoload.php",
            ];
            
            $vendorPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $vendorPath = $path;
                    break;
                }
            }
            
            if (!$vendorPath) {
                $this->session->set_flashdata(
                    "error",
                    "PhpSpreadsheet library not found. Please run 'composer install' in the project root directory.",
                );
                redirect("stocks_new/add_batch");
                return;
            }
            require_once $vendorPath;

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                "A1" => "Medicine Code",
                "B1" => "Medicine Name",
                "C1" => "Vendor Name",
                "D1" => "Batch Number",
                "E1" => "Expiry Date",
                "F1" => "Purchase Date",
                "G1" => "Purchase Price (Pack)",
                "H1" => "MRP (Pack)",
                "I1" => "Selling Price (Pack)",
                "J1" => "Quantity Purchased (Packs)",
                "K1" => "Invoice Number",
                "L1" => "Invoice Date",
                "M1" => "Quality Status",
                "N1" => "Remarks",
                "O1" => "Center Name",
                "P1" => "Department",
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Style header row
            $sheet->getStyle("A1:P1")->getFont()->setBold(true);
            $sheet->getStyle("A1:P1")
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB("FFE0E0E0");

            // Set column widths
            $sheet->getColumnDimension("A")->setWidth(15);
            $sheet->getColumnDimension("B")->setWidth(25);
            $sheet->getColumnDimension("C")->setWidth(20);
            $sheet->getColumnDimension("D")->setWidth(15);
            $sheet->getColumnDimension("E")->setWidth(15);
            $sheet->getColumnDimension("F")->setWidth(15);
            $sheet->getColumnDimension("G")->setWidth(18);
            $sheet->getColumnDimension("H")->setWidth(15);
            $sheet->getColumnDimension("I")->setWidth(18);
            $sheet->getColumnDimension("J")->setWidth(20);
            $sheet->getColumnDimension("K")->setWidth(15);
            $sheet->getColumnDimension("L")->setWidth(15);
            $sheet->getColumnDimension("M")->setWidth(15);
            $sheet->getColumnDimension("N")->setWidth(30);
            $sheet->getColumnDimension("O")->setWidth(20);
            $sheet->getColumnDimension("P")->setWidth(15);

            // Add sample data row
            $sampleData = [
                "A2" => "MED001",
                "B2" => "Paracetamol 500mg",
                "C2" => "Sample Vendor",
                "D2" => "BATCH001",
                "E2" => "2025-12-31",
                "F2" => date('Y-m-d'),
                "G2" => "100.00",
                "H2" => "120.00",
                "I2" => "120.00",
                "J2" => "10",
                "K2" => "INV001",
                "L2" => date('Y-m-d'),
                "M2" => "PENDING",
                "N2" => "Sample batch import",
                "O2" => "",
                "P2" => "GENERAL",
            ];

            foreach ($sampleData as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Add instructions
            $sheet->setCellValue("A4", "Instructions:");
            $sheet->setCellValue("A5", "1. Medicine Code or Medicine Name (at least one required)");
            $sheet->setCellValue("A6", "2. Vendor Name must match existing vendor in system");
            $sheet->setCellValue("A7", "3. Batch Number must be unique for each medicine");
            $sheet->setCellValue("A8", "4. Dates should be in YYYY-MM-DD format");
            $sheet->setCellValue("A9", "5. Prices are per pack (not per unit)");
            $sheet->setCellValue("A10", "6. Quantity is in packs (will be converted to units automatically)");
            $sheet->setCellValue("A11", "7. Quality Status: PENDING, APPROVED, REJECTED, or QUARANTINE");

            // Get available medicines for reference
            $medicines = $this->Stock_model_new->get_all_medicines();
            $medicineCodes = [];
            foreach ($medicines as $medicine) {
                $code = isset($medicine->medicine_code) ? $medicine->medicine_code : "";
                if (!empty($code)) {
                    $medicineCodes[] = $code;
                }
            }

            // Get available vendors for reference
            $vendors = $this->Stock_model_new->get_all_vendors();
            $vendorNames = [];
            foreach ($vendors as $vendor) {
                $vendorName = isset($vendor->vendor_name) ? $vendor->vendor_name : (isset($vendor->name) ? $vendor->name : "");
                if (!empty($vendorName)) {
                    $vendorNames[] = $vendorName;
                }
            }

            // Add note about available medicines and vendors
            if (!empty($medicineCodes)) {
                $sheet->setCellValue("A13", "Sample Medicine Codes: " . implode(", ", array_slice($medicineCodes, 0, 5)));
            }
            if (!empty($vendorNames)) {
                $sheet->setCellValue("A14", "Sample Vendors: " . implode(", ", array_slice($vendorNames, 0, 5)));
            }

            // Set headers for download
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=batch_import_template.xlsx");
            header("Cache-Control: max-age=0");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save("php://output");
            exit();
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function download_batch_sample()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Load PhpSpreadsheet
            $possiblePaths = [
                FCPATH . "vendor/autoload.php",
                APPPATH . "../vendor/autoload.php",
                __DIR__ . "/../../vendor/autoload.php",
                dirname(FCPATH) . "/vendor/autoload.php",
            ];
            
            $vendorPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $vendorPath = $path;
                    break;
                }
            }
            
            if (!$vendorPath) {
                $this->session->set_flashdata(
                    "error",
                    "PhpSpreadsheet library not found. Please run 'composer install' in the project root directory.",
                );
                redirect("stocks_new/add_batch");
                return;
            }
            require_once $vendorPath;

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = [
                "A1" => "Medicine Code",
                "B1" => "Medicine Name",
                "C1" => "Vendor Name",
                "D1" => "Batch Number",
                "E1" => "Expiry Date",
                "F1" => "Purchase Date",
                "G1" => "Purchase Price (Pack)",
                "H1" => "MRP (Pack)",
                "I1" => "Selling Price (Pack)",
                "J1" => "Quantity Purchased (Packs)",
                "K1" => "Invoice Number",
                "L1" => "Invoice Date",
                "M1" => "Quality Status",
                "N1" => "Remarks",
                "O1" => "Center Name",
                "P1" => "Department",
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Style header row
            $sheet->getStyle("A1:P1")->getFont()->setBold(true);
            $sheet->getStyle("A1:P1")
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB("FF4472C4");
            $sheet->getStyle("A1:P1")->getFont()->getColor()->setARGB("FFFFFFFF");

            // Set column widths
            $sheet->getColumnDimension("A")->setWidth(15);
            $sheet->getColumnDimension("B")->setWidth(30);
            $sheet->getColumnDimension("C")->setWidth(20);
            $sheet->getColumnDimension("D")->setWidth(15);
            $sheet->getColumnDimension("E")->setWidth(15);
            $sheet->getColumnDimension("F")->setWidth(15);
            $sheet->getColumnDimension("G")->setWidth(20);
            $sheet->getColumnDimension("H")->setWidth(15);
            $sheet->getColumnDimension("I")->setWidth(20);
            $sheet->getColumnDimension("J")->setWidth(22);
            $sheet->getColumnDimension("K")->setWidth(15);
            $sheet->getColumnDimension("L")->setWidth(15);
            $sheet->getColumnDimension("M")->setWidth(15);
            $sheet->getColumnDimension("N")->setWidth(30);
            $sheet->getColumnDimension("O")->setWidth(20);
            $sheet->getColumnDimension("P")->setWidth(15);

            // Sample data rows
            $sampleRows = [
                [
                    "A" => "MED001",
                    "B" => "Paracetamol 500mg Tablet",
                    "C" => "ABC Pharmaceuticals",
                    "D" => "BATCH001",
                    "E" => "2025-12-31",
                    "F" => date('Y-m-d'),
                    "G" => "100.00",
                    "H" => "120.00",
                    "I" => "120.00",
                    "J" => "10",
                    "K" => "INV-2024-001",
                    "L" => date('Y-m-d'),
                    "M" => "PENDING",
                    "N" => "Initial stock purchase",
                    "O" => "",
                    "P" => "GENERAL",
                ],
                [
                    "A" => "MED002",
                    "B" => "Amoxicillin 250mg Capsule",
                    "C" => "XYZ Medical Supplies",
                    "D" => "BATCH002",
                    "E" => "2026-06-30",
                    "F" => date('Y-m-d'),
                    "G" => "150.00",
                    "H" => "180.00",
                    "I" => "180.00",
                    "J" => "5",
                    "K" => "INV-2024-002",
                    "L" => date('Y-m-d'),
                    "M" => "APPROVED",
                    "O" => "Sample Center",
                    "P" => "PHARMACY",
                    "N" => "Quality approved batch",
                ],
                [
                    "A" => "MED003",
                    "B" => "Ibuprofen 400mg Tablet",
                    "C" => "ABC Pharmaceuticals",
                    "D" => "BATCH003",
                    "E" => "2025-09-15",
                    "F" => date('Y-m-d', strtotime('-1 day')),
                    "G" => "80.00",
                    "H" => "100.00",
                    "I" => "100.00",
                    "J" => "20",
                    "K" => "INV-2024-003",
                    "L" => date('Y-m-d', strtotime('-1 day')),
                    "M" => "PENDING",
                    "N" => "New stock arrival",
                ],
                [
                    "A" => "MED004",
                    "B" => "Cetirizine 10mg Tablet",
                    "C" => "XYZ Medical Supplies",
                    "D" => "BATCH004",
                    "E" => "2026-03-20",
                    "F" => date('Y-m-d', strtotime('-2 days')),
                    "G" => "60.00",
                    "H" => "75.00",
                    "I" => "75.00",
                    "J" => "15",
                    "K" => "INV-2024-004",
                    "L" => date('Y-m-d', strtotime('-2 days')),
                    "M" => "APPROVED",
                    "N" => "Regular restocking",
                ],
                [
                    "A" => "MED005",
                    "B" => "Omeprazole 20mg Capsule",
                    "C" => "ABC Pharmaceuticals",
                    "D" => "BATCH005",
                    "E" => "2025-11-30",
                    "F" => date('Y-m-d', strtotime('-3 days')),
                    "G" => "120.00",
                    "H" => "150.00",
                    "I" => "150.00",
                    "J" => "8",
                    "K" => "INV-2024-005",
                    "L" => date('Y-m-d', strtotime('-3 days')),
                    "M" => "PENDING",
                    "N" => "High demand medicine",
                ],
            ];

            // Add sample data rows
            $rowNum = 2;
            foreach ($sampleRows as $rowData) {
                foreach ($rowData as $col => $value) {
                    $sheet->setCellValue($col . $rowNum, $value);
                }
                $rowNum++;
            }

            // Add border to data area
            $sheet->getStyle("A1:N" . ($rowNum - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            // Set headers for download
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=batch_import_sample.xlsx");
            header("Cache-Control: max-age=0");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save("php://output");
            exit();
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function edit_batch($id = 0) 
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            redirect(base_url());
        }
        $id = (int)$id;
        if ($id <= 0) {
            show_404();
        }
        $data['batch'] = $this->Stock_model_new->get_batch_details_by_id($id);
        $data['is_editable'] = $this->Stock_model_new->is_batch_editable($id);
        if (!$data['batch']) {
            show_404();
        }
        // 2. Get the medicine details for the pre-selected dropdown
        // This is needed for the Select2 dropdown to show the current medicine
        $data['selected_medicine_details'] = $this->Stock_model_new->get_medicine_details_by_id(
            $data['batch']->medicine_id
        );
        
        // Convert unit prices to pack prices for display (prices in DB are per unit)
        $pack_size = isset($data['selected_medicine_details']->pack_size) ? floatval($data['selected_medicine_details']->pack_size) : 1;
        if ($pack_size > 0) {
            // Convert unit prices to pack prices for display
            if (isset($data['batch']->purchase_price)) {
                $data['batch']->purchase_price = floatval($data['batch']->purchase_price) * $pack_size;
            }
            if (isset($data['batch']->selling_price)) {
                $data['batch']->selling_price = floatval($data['batch']->selling_price) * $pack_size;
            }
            if (isset($data['batch']->mrp) && $data['batch']->mrp) {
                $data['batch']->mrp = floatval($data['batch']->mrp) * $pack_size;
            }
        }
        
        // 3. Get the list of all vendors for the vendor dropdown
        $data['vendors'] = $this->Stock_model_new->get_all_vendors();
        // 4. Load the view
        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("stocks_new/edit_batch_view", $data); // The new view file
        $this->load->view($template["footer"]);
    }
    /**
     * NEW FUNCTION: Processes the "Edit Batch" form submission
     */
    public function update_batch() {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            redirect(base_url());
        }
        // 1. Handle Form Submission
        if ($this->input->post()) {
            $batch_id = (int)$this->input->post('batch_id');

            $this->form_validation->set_rules("medicine_id", "Medicine", "required");
            $this->form_validation->set_rules("vendor_id", "Vendor", "required");
            // Use the callback function to check for uniqueness
            $this->form_validation->set_rules(
                "batch_number", 
                "Batch Number", 
                "required|trim" // The callback works for updates too
            );
            $this->form_validation->set_rules("expiry_date", "Expiry Date", "required");
            $this->form_validation->set_rules("purchase_price", "Purchase Price", "required|numeric");
            $this->form_validation->set_rules("selling_price", "Selling Price", "required|numeric");
            if (!$this->Stock_model_new->is_batch_editable($batch_id)) {
                $this->session->set_flashdata("error", "This batch cannot be edited because it has already been used in sales or transfers.");
                redirect("stocks_new/edit_batch/" . $batch_id);
                return; // Stop execution
            }

            // Quantity is not validated here as it's handled by stock movements
            if ($this->form_validation->run() == true) {
                // Get pack_size from form (default to 1 if not provided)
                $pack_size = floatval($this->input->post("pack_size")) ?: 1;
                
                // Get prices (these are pack prices from the form)
                $purchase_price_pack = floatval($this->input->post("purchase_price"));
                $selling_price_pack = floatval($this->input->post("selling_price"));
                $mrp_pack = $this->input->post("mrp") ? floatval($this->input->post("mrp")) : NULL;
                
                // Calculate per unit prices (divide pack price by pack_size)
                $purchase_price_unit = $purchase_price_pack / $pack_size;
                $selling_price_unit = $selling_price_pack / $pack_size;
                $mrp_unit = $mrp_pack ? ($mrp_pack / $pack_size) : NULL;
                
                $batch_data = [
                    "medicine_id" => $this->input->post("medicine_id"),
                    "vendor_id" => $this->input->post("vendor_id"),
                    "batch_number" => $this->input->post("batch_number"),
                    "manufacturing_date" => $this->input->post("manufacturing_date") ?: NULL,
                    "expiry_date" => $this->input->post("expiry_date"),
                    "purchase_price" => $purchase_price_unit, // Store per unit price
                    "selling_price" => $selling_price_unit, // Store per unit price
                    "mrp" => $mrp_unit, // Store per unit MRP
                    "purchase_date" => $this->input->post("purchase_date") ?: date('Y-m-d'),
                    "invoice_number" => $this->input->post("invoice_number"),
                    "invoice_date" => $this->input->post("invoice_date") ?: NULL,
                    "quality_status" => $this->input->post("quality_status") ?: 'PENDING',
                    "batch_status" => $this->input->post("batch_status") ?: 'ACTIVE',
                    "remarks" => $this->input->post("remarks")
                ];
                // --- Call Model ---
                $result = $this->Stock_model_new->update_batch_details($batch_id, $batch_data); 
                if ($result) {
                    $this->session->set_flashdata("success", "Batch updated successfully!");
                } else {
                     $this->session->set_flashdata("error", "Error updating batch or no changes were made.");
                }
                redirect("stocks_new/batches"); // Redirect to batch list
            } else {
                // Validation failed, send user back to the edit form
                // We must reload the data just like in the edit_batch() function
                $data = [];
                $data['batch'] = $this->Stock_model_new->get_batch_details_by_id($batch_id);
                $data['is_editable'] = $this->Stock_model_new->is_batch_editable($batch_id);
                $data['selected_medicine_details'] = $this->Stock_model_new->get_medicine_details_by_id(
                    $data['batch']->medicine_id
                );
                
                // Convert unit prices to pack prices for display (prices in DB are per unit)
                $pack_size = isset($data['selected_medicine_details']->pack_size) ? floatval($data['selected_medicine_details']->pack_size) : 1;
                if ($pack_size > 0) {
                    // Convert unit prices to pack prices for display
                    if (isset($data['batch']->purchase_price)) {
                        $data['batch']->purchase_price = floatval($data['batch']->purchase_price) * $pack_size;
                    }
                    if (isset($data['batch']->selling_price)) {
                        $data['batch']->selling_price = floatval($data['batch']->selling_price) * $pack_size;
                    }
                    if (isset($data['batch']->mrp) && $data['batch']->mrp) {
                        $data['batch']->mrp = floatval($data['batch']->mrp) * $pack_size;
                    }
                }
                
                $data['vendors'] = $this->Stock_model_new->get_all_vendors();
                $template = get_header_template($logg["role"]);
                $this->load->view($template["header"]);
                $this->load->view("stocks_new/edit_batch_view", $data); // Show edit form again
                $this->load->view($template["footer"]);
            }
        }
    }
    public function check_batch_unique($batch_number)
    {
        $medicine_id = $this->input->post('medicine_id');
        $batch_id = $this->input->post('batch_id') ? (int)$this->input->post('batch_id') : null;
        if ($this->Stock_model_new->check_batch_exists($medicine_id, $batch_number, $batch_id)) {
            $this->form_validation->set_message('check_batch_unique', 'This Batch Number already exists for this medicine.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 
     * Helper to get employee ID from session.
     * Ass
    // public function add_batch()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
            
    //         $this->load->model('Stock_model_new');
    //         $this->load->library('form_validation'); 

    //         // --- Handle Form Submission ---
    //         if ($this->input->post("action") == "add_batch") {
    //             $this->form_validation->set_rules("medicine_id", "Medicine", "required");
    //             $this->form_validation->set_rules("vendor_id", "Vendor", "required");
    //             $this->form_validation->set_rules(
    //                 "batch_number", 
    //                 "Batch Number", 
    //                 "required|trim|callback_check_batch_unique" // This rule calls your function
    //             );
    //             $this->form_validation->set_rules("expiry_date", "Expiry Date", "required");
    //             $this->form_validation->set_rules("purchase_price", "Purchase Price", "required|numeric");
    //             $this->form_validation->set_rules("selling_price", "Selling Price", "required|numeric");
    //             $this->form_validation->set_rules("quantity_purchased", "Quantity Purchased", "required|numeric|greater_than[0]");

    //             // --- ADD THIS LINE ---
    //             // This pre-registers the error message for your custom rule
    //             $this->form_validation->set_message('check_batch_unique', 'Please enter another batch ID. This ID already exists for this medicine.');
    //             // --- END OF FIX ---
    //             if ($this->form_validation->run() == true) {
                    
    //                 // --- Get Employee ID ---
    //                 $created_by_id = $this->get_employee_id_from_number(
    //                     $_SESSION["logged_central_stock_manager"]["employee_number"]
    //                 );

    //                 $batch_data = [
    //                     "medicine_id" => $this->input->post("medicine_id"),
    //                     "vendor_id" => $this->input->post("vendor_id"),
    //                     "batch_number" => $this->input->post("batch_number"),
    //                     "manufacturing_date" => $this->input->post("manufacturing_date") ?: NULL,
    //                     "expiry_date" => $this->input->post("expiry_date"),
    //                     "purchase_price" => $this->input->post("purchase_price"),
    //                     "selling_price" => $this->input->post("selling_price"),
    //                     "mrp" => $this->input->post("mrp") ?: NULL,
    //                     "quantity_purchased" => $this->input->post("quantity_purchased"),
    //                     "quantity_remaining" => $this->input->post("quantity_purchased"),
    //                     "purchase_date" => $this->input->post("purchase_date") ?: date('Y-m-d'),
    //                     "invoice_number" => $this->input->post("invoice_number"),
    //                     "invoice_date" => $this->input->post("invoice_date") ?: NULL,
    //                     "quality_status" => $this->input->post("quality_status") ?: 'PENDING',
    //                     "batch_status" => "ACTIVE",
    //                     "remarks" => $this->input->post("remarks"),
    //                     "created_by" => $created_by_id,
    //                     "created_at" => date("Y-m-d H:i:s")
    //                 ];
               
    //                 $result = $this->Stock_model_new->add_batch($batch_data); 
    //                 var_dump($result); exit;

    //                 if ($result['status'] == 'success') {
    //                     $this->session->set_flashdata("success", "Batch added successfully!");
    //                     redirect("stocks_new/batches"); // Redirect to batch list
    //                 } else {
    //                      $this->session->set_flashdata("error", $result['message'] ?? 'Error adding batch!');
    //                 }
                
    //             }
    //             // --- No "else" block needed. ---
    //             // If validation fails, CodeIgniter automatically reloads the view.
    //         }

    //         // --- Prepare Data for View (This part runs on GET or if validation fails) ---
    //         $data = [];
    //         $data["selected_medicine_details"] = null;

    //         if ($this->input->get("medicine_id") && !$this->input->post()) {
    //             $selected_id = (int)$this->input->get("medicine_id");
    //             $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
    //         }
    //         elseif ($this->input->post("medicine_id")) {
    //              $selected_id = (int)$this->input->post("medicine_id");
    //              $data["selected_medicine_details"] = $this->Stock_model_new->get_medicine_details_by_id($selected_id);
    //         }
            
    //         $data["vendors"] = $this->Stock_model_new->get_all_vendors(); 

    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/add_batch", $data); // Reloads this form on error
    //         $this->load->view($template["footer"]);

    //     } else {
    //         redirect(base_url());
    //     }
    // }
    public function check_batch_unique($batch_number)
    {
        $medicine_id = $this->input->post('medicine_id');
        if (empty($medicine_id)) {
            $this->form_validation->set_message('check_batch_unique', 'Please select a medicine first.');
            return FALSE;
        }
        $this->load->model('Stock_model_new');
        if ($this->Stock_model_new->is_batch_unique($medicine_id, $batch_number)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_batch_unique', 'Please enter another batch ID. This batch number already exists for this medicine.');
            return FALSE;
        }
    }

    /**
     * Form Validation Callback function
     * This checks if the batch number is unique FOR THE GIVEN MEDICINE.
     */
 
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

        $this->load->model('Stock_model_new');
        
        // Handle POST request with id parameter (for fetching single medicine details)
        if ($this->input->post('id')) {
            $medicine_id = $this->input->post('id');
            $medicine = $this->Stock_model_new->get_medicine_details_by_id($medicine_id);
            if ($medicine) {
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'success' => true,
                    'medicine' => $medicine
                ]));
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'success' => false,
                    'message' => 'Medicine not found'
                ]));
            }
            return;
        }
        
        // Handle GET request with search term (for Select2 search)
        $search_term = $this->input->get('search') ?: $this->input->get('q');
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
            $department = $this->input->get("department");
            $data["center_stocks"] = $this->Stock_model_new->get_center_stocks(
                $center_id,
                $medicine_id,
                $batch_number,
                $status,
                $department,
            );
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["medicines"] = $this->Stock_model_new->get_all_medicines();
            // $data["departments"] = $this->Stock_model_new->get_departments_by_center();
            $data["selected_center_id"] = $center_id;
            $data["selected_medicine_id"] = $medicine_id;
            $data["selected_batch_number"] = $batch_number;
            $data["selected_status"] = $status;
            $data["selected_department"] = $department;
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

    public function delete_center_stock()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $stock_id = $this->input->post("stock_id");

            if (empty($stock_id)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Stock ID is required",
                ]);
                return;
            }

            if ($this->Stock_model_new->delete_center_stock($stock_id)) {
                echo json_encode([
                    "success" => true,
                    "message" => "Center stock deleted successfully",
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete center stock",
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Not authenticated",
            ]);
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
                    GROUP_CONCAT(DISTINCT COALESCE(c.center_name, 'Central') SEPARATOR '\n') as center_names,
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
                LEFT JOIN medicine_brands b ON m.brand_id = b.id
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

    // public function sales()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         $data["sales"] = $this->Stock_model_new->get_all_sales();
    //         $data["centers"] = $this->Stock_model_new->get_all_centers();
    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/sales", $data);
    //         $this->load->view($template["footer"]);
    //     } else {
    //         header("location:" . base_url() . "");
    //         die();
    //     }
    // }
    public function sales()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $filters = [
                'center_id'    => $this->input->get('center_id'),
                'patient_name' => $this->input->get('patient_name'),
                'status'       => $this->input->get('status'),
                'date_from'    => $this->input->get('date_from'),
                'date_to'      => $this->input->get('date_to')
            ];
            $data["sales"] = $this->Stock_model_new->get_all_sales($filters);
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
                    // --- This is the correct logic for your controller ---
                    $quantity = (float)$this->input->post("quantity_sold");
                    $unit_price = (float)$this->input->post("unit_price");
                    $discount_percent = (float)$this->input->post("discount_percent");
                    $gst_rate = (float)$this->input->post("gst_rate");
                    $subtotal = $quantity * $unit_price;
                    $discount_amount = $subtotal * ($discount_percent / 100);
                    $taxable_amount = $subtotal - $discount_amount;
                    $tax_amount = $taxable_amount * ($gst_rate / 100);
                    $total = $taxable_amount + $tax_amount;
                    $item_data = [
                        'sale_id'         => $id,
                        'batch_id'        => $this->input->post('batch_id'),
                        'quantity_sold'   => $quantity,
                        'unit_price'      => $unit_price,       // This is the price Excl. Tax
                        'subtotal'        => $subtotal,         // (Qty * Unit Price)
                        'discount_amount' => $discount_amount,  // This is the calculated discount
                        'tax_amount'      => $tax_amount,       // This is the calculated tax
                        'total'           => $total,            // This is the final total
                        'remarks'         => $this->input->post('remarks')
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
            // Get filter parameters
            $filters = [];
            $center_id = $this->input->get("center_id");
            $central_only = $this->input->get("central_only");
            $department = $this->input->get("department");
            
            if (!empty($center_id)) {
                $filters['center_id'] = $center_id;
            }
            
            if (!empty($central_only) && $central_only == '1') {
                $filters['central_only'] = true;
            }
            
            if (!empty($department)) {
                $filters['department'] = $department;
            }
            
            $data["low_stock_alerts"] = $this->Stock_model_new->get_low_stock_alerts($filters);
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $data["departments"] = $this->get_departments_by_center();
            
            // Pass selected filters back to view
            $data["selected_center_id"] = $center_id;
            $data["selected_central_only"] = $central_only;
            $data["selected_department"] = $department;

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
            $data["available_batches"] = $this->Stock_model_new->get_available_batches_for_return();
            
            // Auto-select center if user is not central stock manager
            $data["selected_center_id"] = null;
            if (!isset($_SESSION["logged_central_stock_manager"]) || empty($_SESSION["logged_central_stock_manager"])) {
                // Get center from session (try different session variables)
                $center_number = null;
                if (isset($_SESSION['logged_billing_manager']['center']) && !empty($_SESSION['logged_billing_manager']['center'])) {
                    $center_number = $_SESSION['logged_billing_manager']['center'];
                } elseif (isset($_SESSION['logged_stock_manager']['center']) && !empty($_SESSION['logged_stock_manager']['center'])) {
                    $center_number = $_SESSION['logged_stock_manager']['center'];
                } elseif (isset($_SESSION['logged_counselor']['center']) && !empty($_SESSION['logged_counselor']['center'])) {
                    $center_number = $_SESSION['logged_counselor']['center'];
                }
                
                // Convert center number to center ID using model method
                if ($center_number) {
                    $center_id_result = $this->Stock_model_new->get_center_id($center_number);
                    if ($center_id_result) {
                        $data["selected_center_id"] = $center_id_result;
                    }
                }
            }
         
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $data["departments"] = $this->get_departments_by_center();
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
                $this->form_validation->set_rules('department', 'Department', 'required');
                $this->form_validation->set_rules('return_reason', 'Return Reason', 'required');
                if ($this->form_validation->run() == true) {
                    // Get employee ID safely
                    $employee_number = null;
                    if (!empty($_SESSION['logged_central_stock_manager']['employee_number'])) {
                        $employee_number = $_SESSION['logged_central_stock_manager']['employee_number'];
                    }
                    // Stock manager
                    elseif (!empty($_SESSION['logged_stock_manager']['employee_number'])) {
                        $employee_number = $_SESSION['logged_stock_manager']['employee_number'];
                    }elseif (!empty($_SESSION['logged_billing_manager']['employee_number'])) {
                        $employee_number = $_SESSION['logged_billing_manager']['employee_number'];
                    }
                    // CodeIgniter session fallback
                    elseif ($this->session->userdata('employee_number')) {
                        $employee_number = $this->session->userdata('employee_number');
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

                    // Calculate total amounts from per-item discounts
                    $total_return_amount = $this->input->post("total_return_amount");
                    $total_discount_amount = $this->input->post("total_discount_amount");
                    $final_return_amount = $this->input->post("final_return_amount_hidden") ? (float)$this->input->post("final_return_amount_hidden") : 0;
                    
                    // Calculate amounts
                    $total_amount = 0;
                    if ($total_return_amount) {
                        $total_amount = (float)str_replace('₹', '', $total_return_amount);
                    }
                    
                    $total_discount = 0;
                    if ($total_discount_amount) {
                        $total_discount = (float)str_replace('₹', '', $total_discount_amount);
                    }
                    
                    // If final_return_amount_hidden is not set, calculate it from total and discount
                    if ($final_return_amount == 0 && $total_amount > 0) {
                        $final_return_amount = $total_amount - $total_discount;
                    }

                    // COMMENTED OUT: Old total discount calculation (now using per-item discount)
                    /*
                    $discount_percentage = $this->input->post("discount_percentage") ? (float)$this->input->post("discount_percentage") : 0;
                    
                    // If final_return_amount_hidden is not set, calculate it
                    if ($final_return_amount == 0 && $total_amount > 0) {
                        $discount_amount = ($total_amount * $discount_percentage) / 100;
                        $final_return_amount = $total_amount - $discount_amount;
                    } else {
                        // Calculate discount amount from total and final
                        $discount_amount = $total_amount - $final_return_amount;
                    }
                    */

                    $return_data = [
                        "patient_id" => $this->input->post("patient_id"),
                        "patient_name" => $this->input->post("patient_name"),
                        "receipt_number" => $this->input->post(
                            "receipt_number",
                        ),
                        "center_id" => $this->input->post("center_id"),
                        "department" => $this->input->post("department"),
                        "return_date" => $this->input->post("return_date"),
                        "return_reason" => $this->input->post("return_reason"),
                        "total_return_amount" => $total_amount,
                        "discount_amount" => $total_discount,
                        "final_return_amount" => $final_return_amount,
                        // COMMENTED OUT: Old total discount percentage (now using per-item discount)
                        // "discount_percentage" => $discount_percentage,
                        "remarks" => $this->input->post("remarks"),
                        "created_by" => $created_by_id,
                        "created_at" => date("Y-m-d H:i:s"),
                    ];
               

                    $return_items = $this->input->post("return_items");
                    // Process per-item discounts
                    if (!empty($return_items)) {
                        foreach ($return_items as $key => $item) {
                            // Get discount percentage for each item
                            $item_discount_percentage = isset($item['discount_percentage']) ? (float)$item['discount_percentage'] : 0;
                            if (isset($item['discount_percentage_hidden'])) {
                                $item_discount_percentage = (float)$item['discount_percentage_hidden'];
                            }
                            
                            // Calculate item amounts
                            $item_quantity = isset($item['return_quantity']) ? (int)$item['return_quantity'] : 0;
                            $item_price = isset($item['price']) ? (float)$item['price'] : 0;
                            $item_return_amount = $item_quantity * $item_price;
                            $item_discount_amount = ($item_return_amount * $item_discount_percentage) / 100;
                            $item_final_amount = $item_return_amount - $item_discount_amount;
                            
                            // Add discount fields to return item
                            $return_items[$key]['discount_percentage'] = $item_discount_percentage;
                            $return_items[$key]['discount_amount'] = $item_discount_amount;
                            $return_items[$key]['final_amount'] = $item_final_amount;
                        }
                    }
              
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

    /**
     * Export medicine returns list to Excel or PDF
     */
    public function export_returns_list()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $format = $this->input->get('format'); // 'excel' or 'pdf'

        // Get returns data
        $returns = $this->Stock_model_new->get_medicine_returns();

        if (empty($returns)) {
            $this->session->set_flashdata('error', 'No returns found to export.');
            redirect('stocks_new/returns');
            return;
        }

        if ($format == 'excel') {
            $this->export_returns_excel($returns);
        } elseif ($format == 'pdf') {
            $this->export_returns_pdf($returns);
        } else {
            $this->session->set_flashdata('error', 'Invalid export format.');
            redirect('stocks_new/returns');
        }
    }

    /**
     * Export medicine returns to Excel
     */
    private function export_returns_excel($returns)
    {
        // Set headers for Excel download
        $filename = 'Medicine_Returns_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'Return Number',
            'Patient Name',
            'Receipt Number',
            'Center',
            'Department',
            'Medicine Names',
            'Return Date',
            'Reason',
            'Total Items',
            'Total Amount (₹)',
            'Status',
            'Created Date'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($returns as $return) {
            $row = [
                $return->return_number ?? 'N/A',
                $return->patient_name ?? 'N/A',
                $return->receipt_number ?? 'N/A',
                $return->center_name ?? 'N/A',
                $return->department ?? 'N/A',
                $return->medicine_names ?? 'N/A',
                isset($return->return_date) ? date('d-m-Y', strtotime($return->return_date)) : 'N/A',
                $return->return_reason ?? 'N/A',
                $return->total_items ?? 0,
                number_format($return->total_return_amount ?? 0, 2),
                $return->status ?? 'COMPLETED',
                isset($return->created_at) ? date('d-m-Y H:i', strtotime($return->created_at)) : 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    /**
     * Export medicine returns to PDF (HTML print version)
     */
    private function export_returns_pdf($returns)
    {
        // Create a print-friendly HTML page that can be printed as PDF
        $data = [
            'returns' => $returns,
            'generated_date' => date('M d, Y H:i A')
        ];
        
        // Load the print view
        $this->load->view('stocks_new/print_returns_list', $data);
    }

    // ===============================================
    // STOCK AUDIT
    // ===============================================

    // public function stock_audit()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         $data["centers"] = $this->Stock_model_new->get_all_centers();
    //         $data[
    //             "available_batches"
    //         ] = $this->Stock_model_new->get_available_batches_for_audit();

    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/stock_audit", $data);
    //         $this->load->view($template["footer"]);
    //     } else {
    //         header("location:" . base_url() . "");
    //         die();
    //     }
    // }

    // public function process_audit()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         if ($this->input->post("action") == "stock_audit") {
    //             $this->form_validation->set_rules(
    //                 "audit_date",
    //                 "Audit Date",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "center_id",
    //                 "Center",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "audit_type",
    //                 "Audit Type",
    //                 "required",
    //             );
    //             $this->form_validation->set_rules(
    //                 "auditor_name",
    //                 "Auditor Name",
    //                 "required",
    //             );

    //             if ($this->form_validation->run() == true) {
    //                 $audit_data = [
    //                     "audit_date" => $this->input->post("audit_date"),
    //                     "center_id" => $this->input->post("center_id"),
    //                     "audit_type" => $this->input->post("audit_type"),
    //                     "audit_purpose" => $this->input->post("audit_purpose"),
    //                     "auditor_name" => $this->input->post("auditor_name"),
    //                     "total_items" => $this->input->post("total_items"),
    //                     "variance_items" => $this->input->post(
    //                         "variance_items",
    //                     ),
    //                     "remarks" => $this->input->post("remarks"),
    //                     "created_by" => $this->session->userdata(
    //                         "employee_number",
    //                     ),
    //                     "created_at" => date("Y-m-d H:i:s"),
    //                 ];

    //                 $audit_items = $this->input->post("audit_items");

    //                 if (
    //                     $this->Stock_model_new->process_stock_audit(
    //                         $audit_data,
    //                         $audit_items,
    //                     )
    //                 ) {
    //                     $this->session->set_flashdata(
    //                         "success",
    //                         "Stock audit completed successfully",
    //                     );
    //                     redirect("stocks_new/audit_reports");
    //                 } else {
    //                     $this->session->set_flashdata(
    //                         "error",
    //                         "Failed to process stock audit",
    //                     );
    //                 }
    //             }
    //         }

    //         redirect("stocks_new/stock_audit");
    //     }
    // }
    
    public function stock_audit()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }
        $this->load->model('Stock_model_new');
        $data = [];
        $selected_department = 0;
        $selected_center_id = $this->input->get('center_id');
        $data['selected_center_id'] = $selected_center_id;
        $data['selected_department'] = $this->input->get('department');
        $data["centers"] = $this->Stock_model_new->get_all_centers();
        $data["available_batches"] = [];
        if (!empty($selected_center_id) || !empty($selected_department)) {
            $data["available_batches"] = $this->Stock_model_new->get_available_batches_for_audit($selected_center_id,$data['selected_department']);
        }
        $data["all_batches_list"] = $this->Stock_model_new->get_all_batches_list();
        $data['departments'] = $this->get_departments_by_center();
        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("stocks_new/stock_audit", $data); // Load your view
        $this->load->view($template["footer"]);
    }
    public function get_departments_for_center_json($center_id)
    {
        $departments = $this->get_departments_by_center();
        if (!empty($departments)) {
            echo json_encode(['success' => true, 'departments' => $departments]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No departments found.']);
        }
    }

    /**
     * Processes the submitted Stock Audit form.
     */
    public function process_audit()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        if ($this->input->post("action") == "stock_audit") {
            $this->load->library('form_validation');
            $this->load->model('Stock_model_new');
            // --- Validation ---
            $this->form_validation->set_rules('audit_date', 'Audit Date', 'required');
            $this->form_validation->set_rules('center_id', 'Center', 'required|trim'); // The location being audited
            $this->form_validation->set_rules('audit_type', 'Audit Type', 'required');
            $this->form_validation->set_rules('auditor_name', 'Auditor Name', 'required|trim');
            $this->form_validation->set_rules('audit_items[]', 'Audit Items', 'required');
            if ($this->form_validation->run() == TRUE) {
                // Get Employee ID (Primary Key)
                $created_by_id = null;
                if (isset($logg['ID'])) {
                   $created_by_id = $logg['ID'];
                } elseif (!empty($_SESSION["logged_central_stock_manager"]["employee_number"])) {
                    $employee = $this->db->where("employee_number", $_SESSION["logged_central_stock_manager"]["employee_number"])->get("hms_employees")->row();
                    if ($employee) $created_by_id = $employee->ID;
                } elseif (!empty($_SESSION['logged_stock_manager']['employee_number'])) {
                    $employee = $this->db->where("employee_number", $_SESSION["logged_stock_manager"]["employee_number"])->get("hms_employees")->row();
                    if ($employee) $created_by_id = $employee->ID;
                } elseif (isset($_SESSION['billing_manager']['employee_number']) && !empty($_SESSION['billing_manager']['employee_number'])) {
                   $employee = $this->db->where("employee_number", $_SESSION["billing_manager"]["employee_number"])->get("hms_employees")->row();
                    if ($employee) $created_by_id = $employee->ID;
                }elseif (isset($_SESSION['logged_billing_manager']['employee_number']) && !empty($_SESSION['logged_billing_manager']['employee_number'])) {
                   $employee = $this->db->where("employee_number", $_SESSION["logged_billing_manager"]["employee_number"])->get("hms_employees")->row();
                    if ($employee) $created_by_id = $employee->ID;
                }
                if (!$created_by_id) {
                    $this->session->set_flashdata('error', 'Could not identify logged-in user ID.');
                    redirect('stocks_new/stock_audit');
                    return;
                }
                // --- Prepare Header Data ---
                $audit_header = [
                    // center_id is the location key (e.g., 5 or 'central')
                    'center_id' => $this->input->post('center_id'), 
                    'audit_date' => $this->input->post('audit_date'),
                    // Your table has 'audit_type' ENUM('PHYSICAL','CYCLIC','RANDOM','FULL')
                    // The form is sending 'FULL_AUDIT', 'PARTIAL_AUDIT' etc.
                    // This needs to be corrected in the form or mapped here. Let's map it.
                    'auditor_name' => $this->input->post('auditor_name'),
                    'audit_type' => 'FULL', // Defaulting to 'FULL'. Fix your form's <option> values.
                    'remarks' => $this->input->post('remarks') . " (Auditor: " . $this->input->post('auditor_name') . ")",
                    'created_by' => $created_by_id,
                    'status' => 'IN_PROGRESS', 
                ];
                // Fix for audit_type mismatch
                $form_audit_type = $this->input->post('audit_type');
                if (in_array($form_audit_type, ['PHYSICAL', 'CYCLIC', 'RANDOM', 'FULL'])) {
                    $audit_header['audit_type'] = $form_audit_type;
                } else {
                    // Map your form values
                    if ($form_audit_type == 'FULL_AUDIT') $audit_header['audit_type'] = 'FULL';
                    if ($form_audit_type == 'SPOT_CHECK') $audit_header['audit_type'] = 'RANDOM';
                    // Add other mappings as needed
                }
                $audit_items = $this->input->post('audit_items');
                $selected_department = $this->input->post('department');
                // Call the model function to process the audit
                $result = $this->Stock_model_new->process_stock_audit($audit_header, $audit_items, $selected_department);
                if ($result['status'] == 'success') {
                    $this->session->set_flashdata('success', 'Stock audit processed successfully. ' . $result['discrepancies'] . ' discrepancies found and adjusted.');
                    redirect('stocks_new/audit_reports'); // Redirect to a list of reports
                } else {
                    $this->session->set_flashdata('error', 'Audit Failed: ' . $result['message']);
                    redirect('stocks_new/stock_audit?center_id=' . $this.input->post('center_id')); // Redirect back
                }
            } else {
                // Validation failed
                $this->session->set_flashdata('error', validation_errors());
                redirect('stocks_new/stock_audit');
            }
        } else {
            // Not a POST request
            redirect('stocks_new/stock_audit');
        }
    }

    public function audit_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get filter parameters from GET request
            $filters = [
                'center_id' => $this->input->get('center_id'),
                'audit_type' => $this->input->get('audit_type'),
                'status' => $this->input->get('status'),
                'from_date' => $this->input->get('from_date'),
                'to_date' => $this->input->get('to_date')
            ];
            
            $data["audit_reports"] = $this->Stock_model_new->get_audit_reports($filters);
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
        /**
     * Loads the "View Audit Report" page for a specific audit ID.
     */
    public function view_audit($id = 0)
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Audit ID.');
            redirect('stocks_new/audit_reports');
            return;
        }

        $this->load->model('Stock_model_new');
        $data = [];

        // 1. Get the main audit report details
        $data['audit_report'] = $this->Stock_model_new->get_audit_report_by_id($id);

        if (!$data['audit_report']) {
            $this->session->set_flashdata('error', 'Audit Report not found.');
            redirect('stocks_new/audit_reports'); // Redirect to your list page
            return;
        }

        // 2. Get all adjusted items from the stock movement log
        $data['audit_items'] = $this->Stock_model_new->get_audit_items_from_log($id);
        // 3. Load the view
        $data['title'] = "View Audit Report - " . $data['audit_report']->audit_number;
        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view('stocks_new/view_audit', $data); // The new view file
        $this->load->view($template["footer"]);
    }

    /**
     * Print-friendly view of audit report
     */
    public function print_audit($id = 0)
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Audit ID.');
            redirect('stocks_new/audit_reports');
            return;
        }

        $this->load->model('Stock_model_new');
        $data = [];

        // 1. Get the main audit report details
        $data['audit_report'] = $this->Stock_model_new->get_audit_report_by_id($id);

        if (!$data['audit_report']) {
            $this->session->set_flashdata('error', 'Audit Report not found.');
            redirect('stocks_new/audit_reports');
            return;
        }

        // 2. Get all adjusted items from the stock movement log
        $data['audit_items'] = $this->Stock_model_new->get_audit_items_from_log($id);
        
        // 3. Load the print view (no header/footer)
        $this->load->view('stocks_new/print_audit', $data);
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
                // Allow center_id to be numeric or 'CENTRAL_WAREHOUSE_NOIDA'
                $center_id = $this->input->post('center_id');
                if (!empty($center_id) && $center_id !== 'CENTRAL_WAREHOUSE_NOIDA' && !is_numeric($center_id)) {
                    $this->form_validation->set_rules("center_id", "Center", "required|numeric");
                } else {
                    $this->form_validation->set_rules("center_id", "Center", "required");
                }
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

    // public function disposal_reports()
    // {
    //     $logg = checklogin();
    //     if ($logg["status"] == true) {
    //         $data[
    //             "disposal_reports"
    //         ] = $this->Stock_model_new->get_disposal_reports();
    //         $data["centers"] = $this->Stock_model_new->get_all_centers();

    //         $template = get_header_template($logg["role"]);
    //         $this->load->view($template["header"]);
    //         $this->load->view("stocks_new/disposal_reports", $data);
    //         $this->load->view($template["footer"]);
    //     } else {
    //         header("location:" . base_url() . "");
    //         die();
    //     }
    // }
    public function disposal_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // --- NEW: Read search filters from the URL ---
            $filters = [
                'center_id' => $this->input->get('center_id'),
                'status'    => $this->input->get('status'),
                'from_date' => $this->input->get('from_date'),
                'to_date'   => $this->input->get('to_date')
            ];
            // --- MODIFIED: Pass filters to the model ---
            $data["disposal_reports"] = $this->Stock_model_new->get_disposal_reports($filters);
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            // --- NEW: Pass filters to the view so the search form can show them ---
            $data["filters"] = $filters;

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
            $filters = [
                'vendor_id' => $this->input->get('vendor_id'),
                'status'    => $this->input->get('status'),
                'from_date' => $this->input->get('from_date'),
                'to_date'   => $this->input->get('to_date')
            ];
            $data["vendor_returns"] = $this->Stock_model_new->get_vendor_returns($filters);
            // $data[
            //     "vendor_returns"
            // ] = $this->Stock_model_new->get_vendor_returns();
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

    /**
     * Export vendor returns list to Excel or PDF
     */
    public function export_vendor_returns_list()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $format = $this->input->get('format'); // 'excel' or 'pdf'
        $filters = [
            'vendor_id' => $this->input->get('vendor_id'),
            'status'    => $this->input->get('status'),
            'from_date' => $this->input->get('from_date'),
            'to_date'   => $this->input->get('to_date')
        ];

        // Get vendor returns data
        $vendor_returns = $this->Stock_model_new->get_vendor_returns($filters);

        if (empty($vendor_returns)) {
            $this->session->set_flashdata('error', 'No vendor returns found to export.');
            redirect('stocks_new/vendor_returns');
            return;
        }

        if ($format == 'excel') {
            $this->export_vendor_returns_excel($vendor_returns, $filters);
        } elseif ($format == 'pdf') {
            $this->export_vendor_returns_pdf($vendor_returns, $filters);
        } else {
            $this->session->set_flashdata('error', 'Invalid export format.');
            redirect('stocks_new/vendor_returns');
        }
    }

    /**
     * Export vendor returns to Excel
     */
    private function export_vendor_returns_excel($vendor_returns, $filters)
    {
        // Set headers for Excel download
        $filename = 'Vendor_Returns_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'Return Number',
            'Vendor Name',
            'Center',
            'Return Date',
            'Total Items',
            'Total Quantity',
            'Total Value (₹)',
            'Status',
            'Created Date',
            'Created By'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($vendor_returns as $return) {
            $row = [
                $return->return_number ?? 'N/A',
                $return->vendor_name ?? 'N/A',
                $return->center_name ?? 'N/A',
                date('d-m-Y', strtotime($return->return_date)),
                $return->total_items ?? 0,
                $return->total_quantity ?? 0,
                number_format($return->total_value ?? 0, 2),
                $return->status ?? 'N/A',
                isset($return->created_at) ? date('d-m-Y H:i', strtotime($return->created_at)) : 'N/A',
                $return->created_by ?? 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    /**
     * Export vendor returns to PDF (HTML print version)
     */
    private function export_vendor_returns_pdf($vendor_returns, $filters)
    {
        // Create a print-friendly HTML page that can be printed as PDF
        $data = [
            'vendor_returns' => $vendor_returns,
            'filters' => $filters,
            'generated_date' => date('M d, Y H:i A')
        ];
        
        // Load the print view
        $this->load->view('stocks_new/print_vendor_returns', $data);
    }

    /**
     * Export disposal reports list to Excel or PDF
     */
    public function export_disposal_reports()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $format = $this->input->get('format'); // 'excel' or 'pdf'
        $filters = [
            'center_id' => $this->input->get('center_id'),
            'status'    => $this->input->get('status'),
            'from_date' => $this->input->get('from_date'),
            'to_date'   => $this->input->get('to_date')
        ];

        // Get disposal reports data
        $disposal_reports = $this->Stock_model_new->get_disposal_reports($filters);

        if (empty($disposal_reports)) {
            $this->session->set_flashdata('error', 'No disposal reports found to export.');
            redirect('stocks_new/disposal_reports');
            return;
        }

        if ($format == 'excel') {
            $this->export_disposal_reports_excel($disposal_reports, $filters);
        } elseif ($format == 'pdf') {
            $this->export_disposal_reports_pdf($disposal_reports, $filters);
        } else {
            $this->session->set_flashdata('error', 'Invalid export format.');
            redirect('stocks_new/disposal_reports');
        }
    }

    /**
     * Export disposal reports to Excel
     */
    private function export_disposal_reports_excel($disposal_reports, $filters)
    {
        // Set headers for Excel download
        $filename = 'Disposal_Reports_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'Disposal Number',
            'Center Name',
            'Disposal Date',
            'Disposal Type',
            'Disposal Method',
            'Total Items',
            'Total Cost (₹)',
            'Status',
            'Disposal Reason',
            'Created Date',
            'Created By'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($disposal_reports as $report) {
            $row = [
                $report->disposal_number ?? 'N/A',
                $report->center_name ?? 'N/A',
                date('d-m-Y', strtotime($report->disposal_date)),
                $report->disposal_type ?? 'N/A',
                $report->disposal_method ?? 'N/A',
                $report->total_items ?? 0,
                number_format($report->total_cost ?? 0, 2),
                $report->status ?? 'N/A',
                $report->disposal_reason ?? 'N/A',
                isset($report->created_at) ? date('d-m-Y H:i', strtotime($report->created_at)) : 'N/A',
                $report->created_by ?? 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    /**
     * Export disposal reports to PDF (HTML print version)
     */
    private function export_disposal_reports_pdf($disposal_reports, $filters)
    {
        // Create a print-friendly HTML page that can be printed as PDF
        $data = [
            'disposal_reports' => $disposal_reports,
            'filters' => $filters,
            'generated_date' => date('M d, Y H:i A')
        ];
        
        // Load the print view
        $this->load->view('stocks_new/print_disposal_reports', $data);
    }

    /**
     * Export sales list to Excel or PDF
     */
    public function export_sales_list()
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }

        $format = $this->input->get('format'); // 'excel' or 'pdf'
        $filters = [
            'center_id' => $this->input->get('center_id'),
            'patient_name' => $this->input->get('patient_name'),
            'status' => $this->input->get('status'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];

        // Get sales data
        $sales = $this->Stock_model_new->get_sales($filters);

        if (empty($sales)) {
            $this->session->set_flashdata('error', 'No sales found to export.');
            redirect('stocks_new/sales');
            return;
        }

        if ($format == 'excel') {
            $this->export_sales_excel($sales, $filters);
        } elseif ($format == 'pdf') {
            $this->export_sales_pdf($sales, $filters);
        } else {
            $this->session->set_flashdata('error', 'Invalid export format.');
            redirect('stocks_new/sales');
        }
    }

    /**
     * Export sales to Excel
     */
    private function export_sales_excel($sales, $filters)
    {
        // Set headers for Excel download
        $filename = 'Sales_List_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'Sale Number',
            'Patient ID',
            'Patient Name',
            'Doctor Name',
            'Center',
            'Sold By',
            'Sale Date',
            'Total Items',
            'Total Qty',
            'Subtotal (₹)',
            'Discount (₹)',
            'Taxable Amount (₹)',
            'Tax Amount (₹)',
            'Total Amount (₹)',
            'Payment Status',
            'Sale Status',
            'Approval Status',
            'Approved By',
            'Approved Date',
            'Approval Remarks',
            'Created Date'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($sales as $sale) {
            // Calculate taxable amount
            $subtotal = $sale->subtotal ?? 0;
            $discount = $sale->discount_amount ?? 0;
            $taxable_amount = $subtotal - $discount;
            
            $row = [
                $sale->sale_number ?? 'N/A',
                $sale->patient_id ?? 'N/A',
                $sale->patient_name ?? 'N/A',
                $sale->doctor_name ?? 'N/A',
                $sale->center_name ?? 'N/A',
                $sale->salesperson_name ?? 'N/A',
                isset($sale->sale_date) ? date('d-m-Y', strtotime($sale->sale_date)) : 'N/A',
                $sale->total_items ?? 0,
                $sale->total_quantity ?? 0,
                number_format($subtotal, 2),
                number_format($discount, 2),
                number_format($taxable_amount, 2),
                number_format($sale->tax_amount ?? 0, 2),
                number_format($sale->total_amount ?? 0, 2),
                $sale->payment_status ?? 'N/A',
                $sale->status ?? 'N/A',
                $sale->accountant_approval_status ?? 'PENDING',
                $sale->accountant_approved_by_name ?? '-',
                isset($sale->accountant_approved_at) && $sale->accountant_approved_at ? date('d-m-Y H:i', strtotime($sale->accountant_approved_at)) : '-',
                $sale->accountant_remarks ?? '-',
                isset($sale->created_at) ? date('d-m-Y H:i', strtotime($sale->created_at)) : 'N/A'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    /**
     * Export sales to PDF (HTML print version)
     */
    private function export_sales_pdf($sales, $filters)
    {
        // Create a print-friendly HTML page that can be printed as PDF
        $data = [
            'sales' => $sales,
            'filters' => $filters,
            'generated_date' => date('M d, Y H:i A')
        ];
        
        // Load the print view
        $this->load->view('stocks_new/print_sales_list', $data);
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
        /**
     * Handles the GET request to export a filtered vendor return report to CSV.
     */
    public function export_vendor_return_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // 1. Get filter parameters (same as your report page)
            $filters = [
                "vendor_id" => $this->input->get("vendor_id"),
                "status"    => $this->input->get("status"),
                "from_date" => $this->input->get("from_date"),
                "to_date"   => $this->input->get("to_date"),
            ];
            // 2. Load the model
            $this->load->model('Stock_model_new');
            // 3. Call the model function to generate and download the CSV
            // This function will handle the file generation and 'exit'
            $this->Stock_model_new->export_vendor_return_data($filters);

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
        
        // Validate vendor_id (must be numeric)
        if (empty($vendor_id) || !is_numeric($vendor_id)) {
            $this->output->set_status_header(400); // Bad Request
            echo json_encode(['error' => 'Invalid Vendor ID']);
            return;
        }
        
        // Validate center_id (can be numeric or 'CENTRAL_WAREHOUSE_NOIDA')
        if (empty($center_id) || (!is_numeric($center_id) && $center_id !== 'CENTRAL_WAREHOUSE_NOIDA')) {
            $this->output->set_status_header(400); // Bad Request
            echo json_encode(['error' => 'Invalid Center ID']);
            return;
        }
        
        $this->load->model('Stock_model_new');
        // Pass center_id as-is (string for CENTRAL_WAREHOUSE_NOIDA, or cast to int for regular centers)
        $center_id_param = ($center_id === 'CENTRAL_WAREHOUSE_NOIDA') ? $center_id : (int)$center_id;
        $batches = $this->Stock_model_new->get_batches_by_vendor_center((int)$vendor_id, $center_id_param);
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
            // $data["available_batches"] = []; 
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
                // Allow center_id to be numeric or 'CENTRAL_WAREHOUSE_NOIDA'
                $center_id = $this->input->post('center_id');
                if (!empty($center_id) && $center_id !== 'CENTRAL_WAREHOUSE_NOIDA' && !is_numeric($center_id)) {
                    $this->form_validation->set_rules('center_id', 'From Center', 'required|numeric');
                } else {
                    $this->form_validation->set_rules('center_id', 'From Center', 'required');
                }
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
/**
     * Handles the GET request to export a filtered sales report to CSV.
     * The model function handles the actual query and file generation.
     */
    public function export_sales_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // Get format parameter (excel or pdf)
            $format = $this->input->get('format');
            
            // 1. Get all filters from the URL query string
            $filters = [
                "date_from"   => $this->input->get("date_from") ?: $this->input->get("start_date"),
                "date_to"     => $this->input->get("date_to") ?: $this->input->get("end_date"),
                "center_id"   => $this->input->get("center_id"),
                "medicine_id" => $this->input->get("medicine_id"), // Filter by medicine
                "patient_id"  => $this->input->get("patient_id"),  // Filter by patient
                "status"      => $this->input->get("status")       // Filter by status (DRAFT, CONFIRMED)
            ];

            // 2. Load the model
            $this->load->model('Stock_model_new');

            if ($format == 'pdf') {
                // Handle PDF export
                $sales = $this->Stock_model_new->get_sales_report(
                    $filters['date_from'], 
                    $filters['date_to'], 
                    $filters['center_id']
                );

                if (empty($sales)) {
                    $this->session->set_flashdata('error', 'No sales report data found to export.');
                    redirect('stocks_new/sales_report');
                    return;
                }

                // Get centers for filter display
                $centers = $this->Stock_model_new->get_all_centers();
                $center_name = 'All Centers';
                if (!empty($filters['center_id'])) {
                    foreach ($centers as $center) {
                        if ($center->ID == $filters['center_id']) {
                            $center_name = $center->center_name;
                            break;
                        }
                    }
                }

                // Create a print-friendly HTML page that can be printed as PDF
                $data = [
                    'sales' => $sales,
                    'filters' => $filters,
                    'center_name' => $center_name,
                    'generated_date' => date('M d, Y H:i A')
                ];
                
                // Load the print view
                $this->load->view('stocks_new/print_sales_report', $data);
            } else {
                // Default to Excel/CSV export (existing functionality)
                // 3. Call the model function that does all the work
                // The model will generate the CSV and call exit;
                $this->Stock_model_new->export_sales_report($filters);
            }

        } else {
            // Not logged in, redirect to login
            header("location:" . base_url() . "");
            die();
        }
    }
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
    /**
     * Handles the GET request to export a filtered transfer report to CSV. transfer report
     */
    public function export_transfer_report()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            // 1. Get all filters from the URL query string
            $filters = [
                "date_from"      => $this->input->get("date_from"),
                "date_to"        => $this->input->get("date_to"),
                "from_center_id" => $this->input->get("from_center_id"),
                "to_center_id"   => $this->input->get("to_center_id"),
                "medicine_id"    => $this->input->get("medicine_id"),
                "status"         => $this->input->get("status") // e.g., COMPLETED, PENDING
            ];

            // 2. Load the model
            $this->load->model('Stock_model_new');

            // 3. Call the model function that does all the work
            // The model will generate the CSV and call exit;
            $this->Stock_model_new->export_transfer_report($filters);

        } else {
            // Not logged in, redirect to login
            header("location:" . base_url() . "");
            die();
        }
    }
    /**
     * Loads the main "All Reports" page, which contains forms
     * to download various individual reports (CSV, etc.).
     */
    public function export_all_reports()
    {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $this->load->model('Stock_model_new');
            // --- Get data for filters ---
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            // Use your function that gets all medicines for a dropdown
            $data["medicines"] = $this->Stock_model_new->get_all_medicines(); 
            
            $data['title'] = "Export Reports";
            $template = get_header_template($logg["role"]);
            
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/all_reports_page", $data); // Load the new view
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
                    "brand_name",
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
                        "brand_name" => $this->input->post("brand_name"),
                        "status" => $this->input->post("status"),
                        "date" => date("Y-m-d H:i:s"),
                        // "brand_number" => $this->generate_brand_number(),
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
                    "brand_name",
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
                        "brand_name" => $this->input->post("brand_name"),
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
        /**
     * Loads a print-friendly invoice page for a specific sale.
     */
    public function print_sale($id)
    {
        $logg = checklogin();
        if ($logg["status"] != true) {
            redirect(base_url());
            return;
        }
        $this->load->model('Stock_model_new');
        $data = [];
        // 1. Get the main sale details (which recalculates totals)
        $data['sale'] = $this->Stock_model_new->get_sale_by_id($id);
        if (!$data['sale']) {
            $this->session->set_flashdata('error', 'Sale not found.');
            redirect('stocks_new/sales');
            return;
        }
        // 2. Get all items sold in this sale
        $data['sale_items'] = $this->Stock_model_new->get_sale_items_details($id); // Use the detailed function
        // 3. Get the details of the center (for address, GSTN, etc.)
        $data['center_details'] = $this->Stock_model_new->get_center_by_id($data['sale']->center_id);
        $data['title'] = "Print Sale - " . $data['sale']->sale_number;
        // Note: This loads the view file without the standard site template
        // for a clean, print-only page.
        $this->load->view("stocks_new/print_sale_bill", $data);
    }

        /**
     * This is the function your AJAX script will call.
     * It handles changing the payment status.
     */
    public function update_payment_status() 
    {
        // Clean any output buffers to prevent extra characters
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // 1. Get the data from the AJAX POST request
        $sale_id = $this->input->post('sale_id');
        $new_status = $this->input->post('new_status'); 
        $remark = $this->input->post('remarks');
        $utr_transaction_id = $this->input->post('utr_transaction_id');
        $response = [];
        
        // Get the current user info for approval tracking
        $approved_by = null;
        $approved_by_name = null;
        
        if (isset($_SESSION['logged_accountant'])) {
            $approved_by = $_SESSION['logged_accountant']['employee_number'] ?? null;
            $approved_by_name = $_SESSION['logged_accountant']['name'] ?? 'Accountant';
        } elseif (isset($_SESSION['logged_administrator'])) {
            $approved_by = $_SESSION['logged_administrator']['employee_number'] ?? null;
            $approved_by_name = $_SESSION['logged_administrator']['name'] ?? 'Administrator';
        } elseif (isset($_SESSION['logged_billing_manager'])) {
            $approved_by = $_SESSION['logged_billing_manager']['employee_number'] ?? null;
            $approved_by_name = $_SESSION['logged_billing_manager']['name'] ?? 'Billing Manager';
        } else {
            $approved_by = $this->session->userdata('employee_number');
            $approved_by_name = $this->session->userdata('name') ?? 'User';
        }
        
        // 2. Basic validation
        if (!$sale_id || !$new_status) {
            $response = [
                'success' => false, 
                'message' => 'Sale ID and New Status are required.'
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            // 3. Handle image upload if provided
            $payment_image_path = null;
            if (!empty($_FILES['payment_image']['name'])) {
                // Use the configured upload path from config
                $dest_path = $this->config->item('upload_path');
                if (empty($dest_path)) {
                    $dest_path = FCPATH . 'assets/';
                }
                
                $config['upload_path']   = $dest_path . 'payment_proofs/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|pdf';
                $config['max_size']      = 5120; // 5 MB
                $config['file_ext_tolower'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $config['overwrite']     = FALSE;
                $config['encrypt_name']  = TRUE;
                
                // Create directory if it doesn't exist
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('payment_image')) {
                    $error = $this->upload->display_errors('', '');
                    $response = [
                        'success' => false, 
                        'message' => 'Image upload failed: ' . $error
                    ];
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($response, JSON_UNESCAPED_SLASHES);
                    exit;
                }
                
                $upload_data = $this->upload->data();
                $payment_image_path = 'payment_proofs/' . $upload_data['file_name'];
            }
            
            // 4. If status is CANCELLED or REJECTED, restore the stock first
            if ($new_status == 'CANCELLED' || $new_status == 'REJECTED') {
                $restore_result = $this->Stock_model_new->restore_sale_stock($sale_id, $approved_by);
                
                if ($restore_result['status'] == 'error') {
                    // If stock restoration fails, still update payment status but warn user
                    // This handles cases where sale was DRAFT (stock not yet reduced)
                    log_message('info', 'Stock restoration skipped or failed for sale ' . $sale_id . ': ' . $restore_result['message']);
                }
            }
            
            // 5. Call the model to update the database with approval tracking
            $success = $this->Stock_model_new->change_payment_status(
                $sale_id, 
                $new_status, 
                $remark, 
                $utr_transaction_id, 
                $payment_image_path,
                $approved_by,
                $approved_by_name
            );
            
            // 6. Prepare the JSON response
            if ($success) {
                $message = 'Payment status updated to ' . $new_status . ' successfully.';
                
                // Add additional info based on status
                if ($new_status == 'PAID') {
                    $message .= ' Approved by ' . $approved_by_name . '.';
                } elseif ($new_status == 'CANCELLED' || $new_status == 'REJECTED') {
                    $message .= ' Stock has been restored to inventory.';
                }
                
                $response = [
                    'success' => true, 
                    'message' => $message
                ];
            } else {
                $response = [
                    'success' => false, 
                    'message' => 'Failed to update payment status. The status might be the same or a database error occurred.'
                ];
            }
        }
        
        // 7. Send the JSON response back to the JavaScript
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Accountant Sale Approval - Approve, Disapprove, or Cancel a confirmed sale
     * Only accessible by accountant role
     */
    public function accountant_approve_sale()
    {
        // Clean any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $response = [];
        
        // Check if user is logged in as accountant
        if (!isset($_SESSION['logged_accountant']) || empty($_SESSION['logged_accountant'])) {
            $response = [
                'success' => false,
                'message' => 'Unauthorized. Only accountants can approve/disapprove sales.'
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response, JSON_UNESCAPED_SLASHES);
            exit;
        }
        
        // Get POST data
        $sale_id = $this->input->post('sale_id');
        $approval_action = $this->input->post('approval_action'); // APPROVED, DISAPPROVED, CANCELLED
        $remarks = $this->input->post('remarks');
        
        // Validation
        if (!$sale_id || !$approval_action) {
            $response = [
                'success' => false,
                'message' => 'Sale ID and action are required.'
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response, JSON_UNESCAPED_SLASHES);
            exit;
        }
        
        if (!in_array($approval_action, ['APPROVED', 'DISAPPROVED', 'CANCELLED'])) {
            $response = [
                'success' => false,
                'message' => 'Invalid action. Must be APPROVED, DISAPPROVED, or CANCELLED.'
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response, JSON_UNESCAPED_SLASHES);
            exit;
        }
        
        // Get accountant info
        $accountant_id = $_SESSION['logged_accountant']['employee_number'] ?? null;
        $accountant_name = $_SESSION['logged_accountant']['name'] ?? 'Accountant';
        
        // If DISAPPROVED or CANCELLED, restore stock first
        if ($approval_action == 'DISAPPROVED' || $approval_action == 'CANCELLED') {
            $restore_result = $this->Stock_model_new->restore_sale_stock($sale_id, $accountant_id);
            
            if ($restore_result['status'] == 'error') {
                log_message('info', 'Stock restoration note for sale ' . $sale_id . ': ' . $restore_result['message']);
            }
        }
        
        // Update the sale with accountant approval
        $result = $this->Stock_model_new->update_accountant_approval(
            $sale_id,
            $approval_action,
            $accountant_id,
            $accountant_name,
            $remarks
        );
        
        if ($result) {
            $action_text = $approval_action == 'APPROVED' ? 'approved' : ($approval_action == 'DISAPPROVED' ? 'disapproved' : 'cancelled');
            $message = 'Sale has been ' . $action_text . ' by ' . $accountant_name . '.';
            
            if ($approval_action == 'DISAPPROVED' || $approval_action == 'CANCELLED') {
                $message .= ' Stock has been restored to inventory.';
            }
            
            $response = [
                'success' => true,
                'message' => $message
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update sale approval status.'
            ];
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function get_payment_details()
    {
        // Clean any output buffers to prevent extra characters
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $logg = checklogin();
        if ($logg["status"] == false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Unauthorized access'], JSON_UNESCAPED_SLASHES);
            exit;
        }

        $sale_id = $this->input->get('sale_id');
        
        if (!$sale_id) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Sale ID is required'], JSON_UNESCAPED_SLASHES);
            exit;
        }

        // Get payment details from database including approval/rejection info
        $this->db->select('payment_status, utr_transaction_id, payment_image, remarks, updated_at, 
                          payment_approved_by, payment_approved_by_name, payment_approved_at,
                          payment_rejected_by, payment_rejected_by_name, payment_rejected_at,
                          stock_restored, stock_restored_at, stock_restored_by, status');
        $this->db->from('sales');
        $this->db->where('id', $sale_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $sale = $query->row();
            $response = [
                'success' => true,
                'data' => [
                    'payment_status' => $sale->payment_status ? $sale->payment_status : 'N/A',
                    'sale_status' => $sale->status ? $sale->status : 'N/A',
                    'utr_transaction_id' => $sale->utr_transaction_id ? $sale->utr_transaction_id : null,
                    'payment_image' => $sale->payment_image ? $sale->payment_image : null,
                    'remarks' => $sale->remarks ? $sale->remarks : null,
                    'updated_at' => $sale->updated_at ? date('M d, Y h:i A', strtotime($sale->updated_at)) : null,
                    // Approval info
                    'payment_approved_by_name' => isset($sale->payment_approved_by_name) ? $sale->payment_approved_by_name : null,
                    'payment_approved_at' => isset($sale->payment_approved_at) && $sale->payment_approved_at ? date('M d, Y h:i A', strtotime($sale->payment_approved_at)) : null,
                    // Rejection info
                    'payment_rejected_by_name' => isset($sale->payment_rejected_by_name) ? $sale->payment_rejected_by_name : null,
                    'payment_rejected_at' => isset($sale->payment_rejected_at) && $sale->payment_rejected_at ? date('M d, Y h:i A', strtotime($sale->payment_rejected_at)) : null,
                    // Stock restoration info
                    'stock_restored' => isset($sale->stock_restored) ? $sale->stock_restored : 0,
                    'stock_restored_at' => isset($sale->stock_restored_at) && $sale->stock_restored_at ? date('M d, Y h:i A', strtotime($sale->stock_restored_at)) : null
                ]
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Sale not found'
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function central_stocks_export()
    {

        $logg = checklogin();
        if ($logg["status"] == false) {
             header("location:" . base_url() . "");
             die();
        }
        $medicine_id = $this->input->get("medicine_id");
        $batch_number = $this->input->get("batch_id");
        $status = $this->input->get("status");
        $filename = 'central_stock_export_' . date('Y-m-d') . '.csv';
        // 3. Set headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        // 4. Fetch data using your *existing* model function and filters
        $stock_data = $this->Stock_model_new->get_central_stocks(
            $medicine_id,
            $batch_number,
            $status
        );
        // 5. Open output stream
        $output = fopen('php://output', 'w');
        // 6. Write CSV Header Row
        // These headers match the data from your get_central_stocks() function
        fputcsv($output, [
            'Medicine Code',
            'Medicine Name',
            'Brand',
            'Batch Number',
            'Expiry Date',
            'Days Left',
            'Quantity',
            'Vendor Price',
            'Single unit price',
            'Total Vendor Price',
            'Mrp',
            'Pack Size',
            'Status'
        ]);
        // 7. Write CSV Data Rows
        if (!empty($stock_data)) {
            foreach ($stock_data as $row) {
                fputcsv($output, [
                    $row->medicine_code,
                    $row->medicine_name,
                    $row->brand_name,
                    $row->batch_number,
                    $row->expiry_date,
                    $row->expiry_days,
                    $row->quantity, // from ccs.quantity
                    $row->purchase_price,
                    $row->purchase_price/$row->pack_size,
                    ($row->purchase_price/$row->pack_size)*$row->quantity,
                    $row->selling_price,
                    $row->pack_size,
                    $row->status 
                ]);
            }
        }
        // 8. Close output stream and stop script
        fclose($output);
        exit();
    }

    public function center_stocks_export()
    {
        $logg = checklogin();
        if ($logg["status"] == false) {
             header("location:" . base_url());
             die();
        }
        $center_id = $this->input->get("center_id");
        $medicine_id = $this->input->get("medicine_id");
        $batch_number = $this->input->get("batch_number");
        $status = $this->input->get("status");
        $department = $this->input->get("department");
     
        $filename = 'center_stock_export_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $stock_data = $this->Stock_model_new->get_center_stocks(
                $center_id,
                $medicine_id,
                $batch_number,
                $status,
                $department,
        );  
        // 5. Open output stream
        $output = fopen('php://output', 'w');
        
        // 6. Write CSV Header Row
        fputcsv($output, [
            'Center Name',
            'Department',
            'Medicine Code',
            'Medicine Name',
            'Brand',
            'Batch Number',
            'Expiry Date',
            'Days Left',
            'Quantity',
            'Vendor Price',
            'Single unit price',
            'Total Vendor Price',
            'Mrp',
            'Pack Size',
            'Status'
        ]);
        
        // 7. Write CSV Data Rows
        if (!empty($stock_data)) {
            foreach ($stock_data as $row) {
                fputcsv($output, [
                    $row->center_name,
                    $row->department,
                    $row->medicine_code,
                    $row->medicine_name,
                    $row->brand_name,
                    $row->batch_number,
                    $row->expiry_date,
                    $row->expiry_days,
                    $row->quantity, // from ccs.quantity
                    $row->purchase_price,
                    $row->purchase_price/$row->pack_size,
                    ($row->purchase_price/$row->pack_size)*$row->quantity,
                    $row->selling_price,
                    $row->pack_size,
                    $row->status    // from ccs.status
                ]);
            }
        }
        
        // 8. Close output stream and stop script
        fclose($output);
        exit();
    }

    public function stock_additions_report() {
        $logg = checklogin();
        if ($logg["status"] == true) {
            $filters = [
                'date_from'     => $this->input->get('date_from'),
                'date_to'       => $this->input->get('date_to'),
                'location_id'   => $this->input->get('location_id'), // 'central' or a center ID
                'movement_type' => $this->input->get('movement_type'), // *** NEW ***
                'batch_number'  => $this->input->get('batch_number')   // *** NEW ***
            ];
            $data["stock_additions"] = $this->Stock_model_new->get_stock_additions_report($filters);
            $data["centers"] = $this->Stock_model_new->get_all_centers();
            $template = get_header_template($logg["role"]);
            $this->load->view($template["header"]);
            $this->load->view("stocks_new/stock_additions_report_view", $data);
            $this->load->view($template["footer"]); // Corrected typo
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

    public function patient_consumption_report()
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            redirect(base_url());
            die();
        }

        $data = [
            'consumption_data' => [],
            'selected_patient' => null
        ];
        $patient_id = $this->input->get('patient_id');
        if (!empty($patient_id)) {
            $data['consumption_data'] = $this->Stock_model_new->get_patient_consumption($patient_id);
            $data['selected_patient'] = $this->Stock_model_new->get_patient_details($patient_id);
        }

        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("stocks_new/patient_consumption_report", $data); 
        $this->load->view($template["footer"]);
    }
    public function patient_consumption_export()
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            $this->session->set_flashdata('error', 'You must be logged in to export data.');
            redirect('stocks_new/patient_consumption_report');
            return;
        }
        
        $patient_id = $this->input->get('patient_id');

        if (empty($patient_id)) {
            $this->session->set_flashdata('error', 'Please search for a patient before exporting.');
            redirect('stocks_new/patient_consumption_report');
            return;
        }

        // Get the data
        $data = $this->Stock_model_new->get_patient_consumption($patient_id);
        $patient = $this->Stock_model_new->get_patient_details($patient_id);
        $filename = 'patient_consumption_' . $patient_id . '_' . date('Y-m-d') . '.csv';

        // --- CSV Generation ---
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; charset=utf-8");

        $file = fopen('php://output', 'w');
        
        // Add UTF-8 BOM
        fputs($file, "\xEF\xBB\xBF"); 

        // Add headers
        $headers = [
            "Patient ID", "Patient Name", "Received Date", "Sale # / Ref", "Medicine",
            "Medicine Code", "Batch #", "Qty", "Unit Price", "Total",
            "Consumed At", "Billed By"
        ];
        fputcsv($file, $headers);

        // Add data
        foreach ($data as $item) {
            $row = [
                $patient->patient_id ?? $patient_id, // Add patient ID
                $patient->patient_name ?? 'N/A',     // Add patient name
                date('d-m-Y H:i', strtotime($item->received_date)),
                $item->sale_number,
                $item->medicine_name,
                $item->medicine_code,
                $item->batch_number,
                abs($item->quantity_change),
                $item->unit_price,
                $item->total_value,
                $item->center_name,
                $item->user_name
            ];
            fputcsv($file, $row);
        }
        
        fclose($file);
        exit;
    }

    public function search_patients_json() {
        if (!checklogin()['status']) {
            echo json_encode([]); // Return empty on no session
            return;
        }
        
        $search = $this->input->get('search');
        $data = $this->Stock_model_new->search_patients($search);
        echo json_encode($data);
    }
    public function patient_consumption_summary()
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            redirect(base_url());
            die();
        }
        // ** NEW: Get filters from URL **
        $filters = [
            'start_date' => $this->input->get('start_date'),
            'end_date'   => $this->input->get('end_date'),
            'patient_id' => $this->input->get('patient_id'),
        ];
        $data = [
            'consumption_summary' => [],
            'selected_patient' => null,
            'filters' => $filters // Pass filters to the view
        ];
        $patient_id = $filters['patient_id'];
        if (!empty($patient_id)) {
            // ** NEW: Pass filters to the model **
            $data['consumption_summary'] = $this->Stock_model_new->get_patient_consumption_summary($patient_id, $filters);
            // Get the patient's details to display in the search box
            $data['selected_patient'] = $this->Stock_model_new->get_patient_details($patient_id);
        }
        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        // Load the view file
        $this->load->view("stocks_new/patient_consumption_summary", $data); 
        $this->load->view($template["footer"]);
    }

    public function all_consumption_report()
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            redirect(base_url());
            die();
        }

        // Get filters from URL
        $filters = [
            'patient_id' => $this->input->get('patient_id'),
            'start_date' => $this->input->get('start_date'),
            'end_date'   => $this->input->get('end_date')
        ];
        
        $data['filters'] = $filters;
        $data['selected_patient'] = null;
        $data['report_data'] = [];

        // Get the data from the model
        $data['report_data'] = $this->Stock_model_new->get_consumption_report_pivoted($filters);

        // If a patient was searched, get their details to pre-fill the box
        if (!empty($filters['patient_id'])) {
            $data['selected_patient'] = $this->Stock_model_new->get_patient_details($filters['patient_id']);
        }
        
        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view('stocks_new/all_consumption_report', $data); // Load the new view
        $this->load->view($template["footer"]);
    }
    
    // ===============================================
    // PATIENT BILLING ITEMS
    // ===============================================

    /**
     * Add billing items for patient procedures
     * Handles Embryology, Injections, and OT Consumables
     */
    // public function add_billing_item()
    // {
    //     $logg = checklogin();
    //     if ($logg['status'] == true) {
    //         if (isset($_POST['action']) && $_POST['action'] == 'add_billing_item') {
    //             unset($_POST['action']);

    //             $post_arr['receipt_number'] = $_POST['receipt_number'];
    //             unset($_POST['receipt_number']);

    //             // Initialize counters
    //             $icounte = $mcounte = $ccounte = 1;
    //             $i_counte = $m_counte = $c_counte = array();
    //             $i_counter = $m_counter = $c_counter = array();

    //             // Parse POST data to find item counters
    //             foreach ($_POST as $key => $val) {
    //                 if (strpos($key, 'injections_name_') !== false) {
    //                     $iid = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
    //                     $i_counter[] = $iid;
    //                 }
    //                 if (strpos($key, 'medicine_name_') !== false) {
    //                     $mid = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
    //                     $m_counter[] = $mid;
    //                 }
    //                 if (strpos($key, 'consumables_name_') !== false) {
    //                     $cid = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
    //                     $c_counter[] = $cid;
    //                 }
    //             }

    //             // Process Injections
    //             if (!empty($i_counter)) {
    //                 foreach ($i_counter as $key => $icounte) {
    //                     if ($_POST['injections_name_' . $icounte] == '') {
    //                         // Skip empty rows
    //                         continue;
    //                     } else {
    //                         $this->process_injection_item($icounte, $i_counte);
    //                     }
    //                 }
    //             }

    //             // Process Medicine/Embryology
    //             if (!empty($m_counter)) {
    //                 foreach ($m_counter as $key => $mcounte) {
    //                     if ($_POST['medicine_name_' . $mcounte] == '') {
    //                         continue;
    //                     } else {
    //                         $this->process_medicine_item($mcounte, $m_counte);
    //                     }
    //                 }
    //             }

    //             // Process Consumables
    //             if (!empty($c_counter)) {
    //                 foreach ($c_counter as $key => $ccounte) {
    //                     if ($_POST['consumables_name_' . $ccounte] == '') {
    //                         continue;
    //                     } else {
    //                         $this->process_consumable_item($ccounte, $c_counte);
    //                     }
    //                 }
    //             }

    //             // Prepare final data
    //             $details = array();
    //             $details['data']['consumables'] = $c_counte;
    //             $details['data']['injections'] = $i_counte;
    //             $details['data']['medicine'] = $m_counte;
    //             $post_arr['data'] = serialize($details);
    //             $post_arr['employee_number'] = $_POST['employee_number'];
    //             unset($_POST['employee_number']);
    //             $post_arr['procedure_name'] = $_POST['procedure_name'];
    //             unset($_POST['procedure_name']);
    //             $post_arr['patient_id'] = $_POST['patient_id'];
    //             unset($_POST['patient_id']);
    //             $post_arr['add_on'] = date("Y-m-d H:i:s");

    //             // Insert billing record
    //             $result = $this->Stock_model_new->billing_item_insert($post_arr);

    //             if ($result > 0) {
    //                 // Deduct stock for all items
    //                 if (!empty($i_counter)) {
    //                     foreach ($i_counter as $key => $icounte) {
    //                         if (isset($_POST['injections_ID_' . $icounte])) {
    //                             $ID = $_POST['injections_ID_' . $icounte];
    //                             $serial = $_POST['injections_serial_' . $icounte];
    //                             $qty = $_POST['injections_quantity_' . $icounte];
    //                             $this->Stock_model_new->deduct_stock($ID, $serial, $qty);
    //                         }
    //                     }
    //                 }
    //                 if (!empty($m_counter)) {
    //                     foreach ($m_counter as $key => $mcounte) {
    //                         if (isset($_POST['medicine_ID_' . $mcounte])) {
    //                             $ID = $_POST['medicine_ID_' . $mcounte];
    //                             $serial = $_POST['medicine_serial_' . $mcounte];
    //                             $qty = $_POST['medicine_quantity_' . $mcounte];
    //                             $this->Stock_model_new->deduct_stock($ID, $serial, $qty);
    //                         }
    //                     }
    //                 }
    //                 if (!empty($c_counter)) {
    //                     foreach ($c_counter as $key => $ccounte) {
    //                         if (isset($_POST['consumables_ID_' . $ccounte])) {
    //                             $ID = $_POST['consumables_ID_' . $ccounte];
    //                             $serial = $_POST['consumables_serial_' . $ccounte];
    //                             $qty = $_POST['consumables_quantity_' . $ccounte];
    //                             $this->Stock_model_new->deduct_stock($ID, $serial, $qty);
    //                         }
    //                     }
    //                 }

    //                 $this->session->set_flashdata('success', 'Patient Items added successfully!');
    //                 redirect("stocks_new/add_billing_item");
    //             } else {
    //                 $this->session->set_flashdata('error', 'Something went wrong!');
    //                 redirect("stocks_new/add_billing_item");
    //             }
    //         }

    //         // Load view
    //         $template = get_header_template($logg['role']);
    //         $data['consumables'] = $this->Stock_model_new->get_available_batches_for_billing('consumables');
    //         $data['injections'] = $this->Stock_model_new->get_available_batches_for_billing('injections');
    //         $data['medicine'] = $this->Stock_model_new->get_available_batches_for_billing('embryology');

    //         $this->load->view($template['header']);
    //         $this->load->view('stocks_new/add_billing_item', $data);
    //         $this->load->view($template['footer']);
    //     } else {
    //         redirect(base_url());
    //     }
    // }
    // private function process_injection_item($icounte, &$i_counte)
    // {
    //     $i_counte[$icounte]['name'] = $_POST['injections_name_' . $icounte];
    //     $i_counte[$icounte]['batch'] = $_POST['injections_batch_' . $icounte];
    //     $i_counte[$icounte]['quantity'] = $_POST['injections_quantity_' . $icounte];
    //     $i_counte[$icounte]['price'] = $_POST['injections_price_' . $icounte];
    //     $i_counte[$icounte]['gst'] = $_POST['injections_gst_' . $icounte];
    //     $i_counte[$icounte]['total'] = $_POST['injections_total_' . $icounte];

    //     // Insert into central stock report
    //     $report_data = array(
    //         'item_name' => $_POST['injections_name_' . $icounte],
    //         'batch_number' => $_POST['injections_batch_' . $icounte],
    //         'quantity' => $_POST['injections_quantity_' . $icounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Injections'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'central_stock_report', $report_data);

    //     // Insert into consumptions
    //     $consumption_data = array(
    //         'item_name' => $_POST['injections_name_' . $icounte],
    //         'batch_number' => $_POST['injections_batch_' . $icounte],
    //         'quantity' => $_POST['injections_quantity_' . $icounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Injections'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'consumptions', $consumption_data);
    // }
    // private function process_medicine_item($mcounte, &$m_counte)
    // {
    //     $m_counte[$mcounte]['name'] = $_POST['medicine_name_' . $mcounte];
    //     $m_counte[$mcounte]['batch'] = $_POST['medicine_batch_' . $mcounte];
    //     $m_counte[$mcounte]['quantity'] = $_POST['medicine_quantity_' . $mcounte];
    //     $m_counte[$mcounte]['price'] = $_POST['medicine_price_' . $mcounte];
    //     $m_counte[$mcounte]['gst'] = $_POST['medicine_gst_' . $mcounte];
    //     $m_counte[$mcounte]['total'] = $_POST['medicine_total_' . $mcounte];

    //     // Insert into central stock report
    //     $report_data = array(
    //         'item_name' => $_POST['medicine_name_' . $mcounte],
    //         'batch_number' => $_POST['medicine_batch_' . $mcounte],
    //         'quantity' => $_POST['medicine_quantity_' . $mcounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Medicine'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'central_stock_report', $report_data);

    //     // Insert into consumptions
    //     $consumption_data = array(
    //         'item_name' => $_POST['medicine_name_' . $mcounte],
    //         'batch_number' => $_POST['medicine_batch_' . $mcounte],
    //         'quantity' => $_POST['medicine_quantity_' . $mcounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Medicine'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'consumptions', $consumption_data);
    // }

    // /**
    //  * Process consumable item and insert into consumption records
    //  */
    // private function process_consumable_item($ccounte, &$c_counte)
    // {
    //     $c_counte[$ccounte]['name'] = $_POST['consumables_name_' . $ccounte];
    //     $c_counte[$ccounte]['batch'] = $_POST['consumables_batch_' . $ccounte];
    //     $c_counte[$ccounte]['quantity'] = $_POST['consumables_quantity_' . $ccounte];
    //     $c_counte[$ccounte]['price'] = $_POST['consumables_price_' . $ccounte];
    //     $c_counte[$ccounte]['gst'] = $_POST['consumables_gst_' . $ccounte];
    //     $c_counte[$ccounte]['total'] = $_POST['consumables_total_' . $ccounte];

    //     // Insert into central stock report
    //     $report_data = array(
    //         'item_name' => $_POST['consumables_name_' . $ccounte],
    //         'batch_number' => $_POST['consumables_batch_' . $ccounte],
    //         'quantity' => $_POST['consumables_quantity_' . $ccounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Consumables'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'central_stock_report', $report_data);

    //     // Insert into consumptions
    //     $consumption_data = array(
    //         'item_name' => $_POST['consumables_name_' . $ccounte],
    //         'batch_number' => $_POST['consumables_batch_' . $ccounte],
    //         'quantity' => $_POST['consumables_quantity_' . $ccounte],
    //         'center_number' => isset($_SESSION['logged_stock_manager']['center']) ?
    //                           $_SESSION['logged_stock_manager']['center'] :
    //                           $_SESSION['logged_billing_manager']['center'],
    //         'department' => isset($_SESSION['logged_stock_manager']['department']) ?
    //                        $_SESSION['logged_stock_manager']['department'] :
    //                        $_SESSION['logged_billing_manager']['department'],
    //         'add_on' => date("Y-m-d H:i:s"),
    //         'type' => 'Consumables'
    //     );
    //     $this->db->insert($this->config->item('db_prefix') . 'consumptions', $consumption_data);
    // }
    public function get_center_id_by_number($center_number)
    {
        $center = $this->db->get_where('hms_centers', ['center_number' => $center_number])->row();
        if (!$center) {
            return false;
        }
        return $center->ID;
    }

    public function add_billing_item()
    {
        $logg = checklogin();
        if ($logg['status'] == true) {
            $employee_number = null;
            $center_name = null;     
            $department = null;
            if (isset($_SESSION['logged_billing_manager']['employee_number']) && !empty($_SESSION['logged_billing_manager']['employee_number'])) {
                $employee_number = $_SESSION['logged_billing_manager']['employee_number'];
                 $center_number = $_SESSION['logged_billing_manager']['center'];
                 $department = $_SESSION['logged_billing_manager']['department'];
            } elseif (isset($_SESSION['logged_stock_manager']['employee_number']) && !empty($_SESSION['logged_stock_manager']['employee_number'])) {
                $employee_number = $_SESSION['logged_stock_manager']['employee_number'];
                $center_number = $_SESSION['logged_stock_manager']['center'];
                $department = $_SESSION['logged_stock_manager']['department'];
            } elseif (isset($_SESSION['logged_counselor']['employee_number']) && !empty($_SESSION['logged_counselor']['employee_number'])) {
                $employee_number = $_SESSION['logged_counselor']['employee_number'];
                $center_number = $_SESSION['logged_counselor']['center'];
                $department = $_SESSION['logged_counselor']['department'];
            } elseif (isset($_SESSION['billing_manager']['employee_number']) && !empty($_SESSION['billing_manager']['employee_number'])) {
                $employee_number = $_SESSION['billing_manager']['employee_number'];
                $center_number = $_SESSION['billing_manager']['center'];
                $department = $_SESSION['billing_manager']['department'];
            }
            // $employee_number = $_SESSION['logged_stock_manager']['employee_number'];
            // $center_number = $_SESSION['logged_stock_manager']['center'];
            // $department = $_SESSION['logged_stock_manager']['department'];
            $center_id = $this->get_center_id_by_number($center_number);
            if (!$center_id) {
                $this->session->set_flashdata('error', 'Your assigned center could not be found in the database.');
                redirect('stocks_new/dashboard');
                return;
            }
            $created_by_id = $this->get_employee_id_from_number($employee_number);
            if (isset($_POST['action']) && $_POST['action'] == 'add_billing_item') {
                $this->form_validation->set_rules('patient_id', 'Patient ID', 'required');
                $this->form_validation->set_rules('procedure_name', 'Procedure Name', 'required');
                if ($this->form_validation->run() == FALSE) {
                    $this->session->set_flashdata('error', validation_errors());
                    redirect("stocks_new/add_billing_item");
                    return;
                }
                $sale_data = [
                    'center_id'      => $center_id,
                    'sale_number'    => 'SALE-' . date('YmdHis') . rand(100, 999), 
                    'patient_id'     => $this->input->post('patient_id'),
                    'patient_name'   => $this->input->post('patient_name'),
                    'doctor_name'    => $this->input->post('procedure_name'), 
                    'sale_date'      => date('Y-m-d'),
                    'sale_time'      => date('H:i:s'),
                    'payment_status' => 'PENDING', 
                    'status'         => 'CONFIRMED', 
                    'created_by'     => $created_by_id,
                ];
                $this->db->insert('sales', $sale_data);
                
                $sale_id = $this->db->insert_id();
                if (!$sale_id) {
                    $this->session->set_flashdata('error', 'Failed to create a new sale record.');
                    redirect("stocks_new/add_billing_item");
                    return;
                }
                $items_processed = 0;
                $items_failed = 0;
                $error_messages = [];
                $total_amount = 0;
                $m_counter = $this->input->post('medicine_name_1') ? [1] : []; // Simple check for first row
                foreach ($m_counter as $mcounte) {
                    if (!empty($_POST['medicine_name_' . $mcounte]) && (int)$_POST['medicine_quantity_' . $mcounte] > 0) {
                        $batch_id = (int)$_POST['medicine_ID_' . $mcounte]; // Your form sends Batch ID in the 'ID' field
                        $quantity = (int)$_POST['medicine_quantity_' . $mcounte];
                        $item_data = [
                            'batch_id'    => $batch_id,
                            'center_id'   => $center_id,
                            'department'  => $department,
                            'quantity'    => $quantity,
                            'patient_id'  => $this->input->post('patient_id'),
                            'patient_name'=> $this->input->post('patient_name'),
                        ];
                        $result = $this->Stock_model_new->process_sale_item($sale_id, $item_data, $created_by_id);
                        if ($result['status'] == 'success') {
                            $items_processed++;
                            $total_amount += $result['total_price'];
                        } else {
                            $items_failed++;
                            $error_messages[] = "Medicine: " . $result['message'];
                        }
                    }
                }
                $i_counter = $this->input->post('injections_name_1') ? [1] : [];
                foreach ($i_counter as $icounte) {
                    if (!empty($_POST['injections_name_' . $icounte]) && (int)$_POST['injections_quantity_' . $icounte] > 0) {
                        $batch_id = (int)$_POST['injections_ID_' . $icounte];
                        $quantity = (int)$_POST['injections_quantity_' . $icounte];
                        $item_data = [
                            'batch_id'    => $batch_id,
                            'center_id'   => $center_id,
                            'department'  => $department,
                            'quantity'    => $quantity,
                            'patient_id'  => $this->input->post('patient_id'),
                            'patient_name'=> $this->input->post('patient_name'),
                        ];
                        $result = $this->Stock_model_new->process_sale_item($sale_id, $item_data, $created_by_id);
                        if ($result['status'] == 'success') {
                            $items_processed++;
                            $total_amount += $result['total_price'];
                        } else {
                            $items_failed++;
                            $error_messages[] = "Injection: " . $result['message'];
                        }
                    }
                }
                $c_counter = $this->input->post('consumables_name_1') ? [1] : [];
                foreach ($c_counter as $ccounte) {
                    if (!empty($_POST['consumables_name_' . $ccounte]) && (int)$_POST['consumables_quantity_' . $ccounte] > 0) {
                        $batch_id = (int)$_POST['consumables_ID_' . $ccounte];
                        $quantity = (int)$_POST['consumables_quantity_' . $ccounte];
                        $item_data = [
                            'batch_id'    => $batch_id,
                            'center_id'   => $center_id,
                            'department'  => $department,
                            'quantity'    => $quantity,
                            'patient_id'  => $this->input->post('patient_id'),
                            'patient_name'=> $this->input->post('patient_name'),
                        ];
                        $result = $this->Stock_model_new->process_sale_item($sale_id, $item_data, $created_by_id);
                        if ($result['status'] == 'success') {
                            $items_processed++;
                            $total_amount += $result['total_price'];
                        } else {
                            $items_failed++;
                            $error_messages[] = "Consumable: " . $result['message'];
                        }
                    }
                }
                // --- 5. Finalize Sale ---
                if ($items_processed > 0) {
                    $this->Stock_model_new->update_sale_totals($sale_id);
                    $this->session->set_flashdata('success', "Billing created successfully with {$items_processed} items. Sale Number: " . $sale_data['sale_number']);
                    if ($items_failed > 0) {
                        $this->session->set_flashdata('error', "{$items_failed} items failed: <br>" . implode("<br>", $error_messages));
                    }
                } else {
                    $this->db->delete('sales', ['id' => $sale_id]);
                    $this->session->set_flashdata('error', 'No valid items were processed. Billing was not created. Errors: <br>' . implode("<br>", $error_messages));
                }
                redirect("stocks_new/add_billing_item");
            } else {
                $template = get_header_template($logg['role']);
                $data['consumables'] = $this->Stock_model_new->get_batches_for_billing_form('OT DCI', $center_id, $department);
                $data['injections'] = $this->Stock_model_new->get_batches_for_billing_form('Package injections', $center_id, $department);
                $data['medicine'] = $this->Stock_model_new->get_batches_for_billing_form('EMBRYOLOGIST DCI', $center_id, $department);
                $this->load->view($template['header']);
                $this->load->view('stocks_new/add_billing_item', $data);
                $this->load->view($template['footer']);
            }
        } else {
            header("location:" . base_url() . "");
            die();
        }
    }

}
