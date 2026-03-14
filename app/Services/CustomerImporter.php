<?php

namespace App\Services;

use App\Models\Customer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Exception;

class CustomerImporter
{
    private $spreadsheet;
    private $worksheet;
    private $imported = 0;
    private $skipped = 0;
    private $errors = [];
    private $maxErrors = 100;

    /**
     * Import customers from XLSX file
     * 
     * @param string $filePath Path to the XLSX file
     * @param bool $skipHeader Whether to skip the first row
     * @return array Import results with counts and errors
     */
    public function import(string $filePath, bool $skipHeader = true): array
    {
        try {
            // Load the spreadsheet
            $this->spreadsheet = IOFactory::load($filePath);
            
            // Try to get the first sheet (or find "Customers" sheet)
            $sheetToUse = null;
            foreach ($this->spreadsheet->getSheetNames() as $sheetName) {
                if (strtolower($sheetName) === 'customers') {
                    $sheetToUse = $this->spreadsheet->getSheetByName($sheetName);
                    break;
                }
            }
            
            // If no "Customers" sheet, use the first one
            if ($sheetToUse === null) {
                // Skip "Instructions" sheet if it's the first one
                $sheetNames = $this->spreadsheet->getSheetNames();
                foreach ($sheetNames as $sheetName) {
                    if (strtolower($sheetName) !== 'instructions') {
                        $sheetToUse = $this->spreadsheet->getSheetByName($sheetName);
                        break;
                    }
                }
                
                // If still null, use the first sheet
                if ($sheetToUse === null) {
                    $sheetToUse = $this->spreadsheet->getSheet(0);
                }
            }
            
            $this->worksheet = $sheetToUse;

            // Get the highest row number
            $highestRow = $this->worksheet->getHighestRow();
            $startRow = $skipHeader ? 2 : 1;

            // Get header row for column mapping
            $headers = $this->getHeaderMapping();

            if (empty($headers)) {
                throw new Exception("No headers found in the first row. Please check your file format. Make sure the first row contains column headers: Customer Name, Mobile, GST Type, etc.");
            }

            // Process each row
            for ($row = $startRow; $row <= $highestRow; $row++) {
                if (count($this->errors) >= $this->maxErrors) {
                    break;
                }

                $this->processRow($row, $headers);
            }

            return $this->getResults();

        } catch (Exception $e) {
            $this->errors[] = "File Error: " . $e->getMessage();
            return $this->getResults();
        }
    }

    /**
     * Get header column mapping from first row
     * 
     * @return array Mapping of column index to field name
     */
    private function getHeaderMapping(): array
    {
        $headers = [];
        
        $expectedHeaders = [
            'customer_name' => ['Customer Name', 'customer_name', 'Name'],
            'address' => ['Address', 'address'],
            'city' => ['City', 'city'],
            'state' => ['State', 'state', 'Province'],
            'pin_code' => ['Pin Code', 'Postal Code', 'pin_code', 'Zip'],
            'gst_no' => ['GST No', 'gst_no', 'GST Number', 'GST'],
            'gst_type' => ['GST Type', 'gst_type', 'Type'],
            'mobile' => ['Mobile', 'mobile', 'Phone', 'Mobile Number'],
            'email' => ['Email', 'email', 'Email Address'],
        ];

        // Get all rows
        $rows = $this->worksheet->getRowIterator(1, 1);
        
        foreach ($rows as $row) {
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(false); // Important: include empty cells
            
            $columnIndex = 0;
            foreach ($cells as $cell) {
                $columnIndex++;
                
                // Get the cell value
                $cellValue = $cell->getValue();
                
                // Skip if cell is null or object
                if (is_null($cellValue)) {
                    continue;
                }
                
                // Convert to string and trim
                $cellValue = trim((string)$cellValue);
                
                if (empty($cellValue)) {
                    continue;
                }

                // Match cell value against expected headers
                foreach ($expectedHeaders as $fieldName => $possibleValues) {
                    foreach ($possibleValues as $header) {
                        if (strtolower($cellValue) === strtolower($header)) {
                            $headers[$columnIndex] = $fieldName;
                            break 2;
                        }
                    }
                }
            }
        }

        return $headers;
    }

    /**
     * Process a single row from the spreadsheet
     * 
     * @param int $row Row number to process
     * @param array $headers Header mapping
     * @return void
     */
    private function processRow(int $row, array $headers): void
    {
        try {
            $data = [];
            $isEmpty = true;

            // Extract data from cells
            foreach ($headers as $columnIndex => $fieldName) {
                // Convert column index (1-based) to column letter
                $columnLetter = Coordinate::stringFromColumnIndex($columnIndex);
                $cell = $this->worksheet->getCell($columnLetter . $row);
                $value = trim((string)$cell->getValue());

                if (!empty($value)) {
                    $isEmpty = false;
                }

                $data[$fieldName] = $value;
            }

            // Skip completely empty rows
            if ($isEmpty) {
                $this->skipped++;
                return;
            }

            // Validate and clean data
            $cleanedData = $this->validateAndCleanData($data, $row);
            
            if ($cleanedData === false) {
                return; // Error already recorded
            }

            // Create or update customer
            Customer::create($cleanedData);
            $this->imported++;

        } catch (Exception $e) {
            $this->errors[] = "Row $row: " . $e->getMessage();
        }
    }

    /**
     * Validate and clean customer data
     * 
     * @param array $data Raw data from row
     * @param int $row Row number for error messages
     * @return array|false Cleaned data or false if validation fails
     */
    private function validateAndCleanData(array $data, int $row)
    {
        $cleaned = [];

        // Customer Name (Required)
        $customerName = trim($data['customer_name'] ?? '');
        if (empty($customerName)) {
            $this->errors[] = "Row $row: Customer name is required.";
            return false;
        }
        if (strlen($customerName) < 2) {
            $this->errors[] = "Row $row: Customer name must be at least 2 characters.";
            return false;
        }
        if (strlen($customerName) > 255) {
            $this->errors[] = "Row $row: Customer name exceeds 255 characters.";
            return false;
        }
        $cleaned['customer_name'] = $customerName;

        // Address (Optional)
        $address = trim($data['address'] ?? '');
        if (!empty($address) && strlen($address) > 500) {
            $this->errors[] = "Row $row: Address exceeds 500 characters.";
            return false;
        }
        $cleaned['address'] = !empty($address) ? $address : null;

        // City (Optional)
        $city = trim($data['city'] ?? '');
        if (!empty($city) && strlen($city) > 100) {
            $this->errors[] = "Row $row: City exceeds 100 characters.";
            return false;
        }
        $cleaned['city'] = !empty($city) ? $city : null;

        // State (Optional)
        $state = trim($data['state'] ?? '');
        if (!empty($state) && strlen($state) > 100) {
            $this->errors[] = "Row $row: State exceeds 100 characters.";
            return false;
        }
        $cleaned['state'] = !empty($state) ? $state : null;

        // Pin Code (Optional)
        $pinCode = trim($data['pin_code'] ?? '');
        if (!empty($pinCode)) {
            if (!preg_match('/^\d{1,6}$/', $pinCode)) {
                $this->errors[] = "Row $row: Pin code must be numeric and up to 6 digits.";
                return false;
            }
        }
        $cleaned['pin_code'] = !empty($pinCode) ? $pinCode : null;

        // Mobile (Required)
        $mobile = preg_replace('/[^0-9]/', '', $data['mobile'] ?? '');
        if (empty($mobile)) {
            $this->errors[] = "Row $row: Mobile number is required.";
            return false;
        }
        if (!preg_match('/^\d{10}$/', $mobile)) {
            $this->errors[] = "Row $row: Mobile number must be exactly 10 digits.";
            return false;
        }
        $cleaned['mobile'] = $mobile;

        // GST Type (Required)
        $gstType = strtolower(trim($data['gst_type'] ?? ''));
        if (empty($gstType)) {
            $this->errors[] = "Row $row: GST Type is required.";
            return false;
        }
        if (!in_array($gstType, ['instate', 'outofstate'])) {
            $this->errors[] = "Row $row: GST Type must be 'instate' or 'outofstate'.";
            return false;
        }
        $cleaned['gst_type'] = $gstType;

        // GST No (Optional)
        $gstNo = trim($data['gst_no'] ?? '');
        if (!empty($gstNo)) {
            if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i', $gstNo)) {
                $this->errors[] = "Row $row: Invalid GST number format. Expected: 27AABCU9603R1Z5";
                return false;
            }
        }
        $cleaned['gst_no'] = !empty($gstNo) ? strtoupper($gstNo) : null;

        // Email (Optional)
        $email = trim($data['email'] ?? '');
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Row $row: Invalid email format.";
                return false;
            }
            if (strlen($email) > 255) {
                $this->errors[] = "Row $row: Email exceeds 255 characters.";
                return false;
            }
        }
        $cleaned['email'] = !empty($email) ? $email : null;

        return $cleaned;
    }

    /**
     * Get import results
     * 
     * @return array Results array with counts and errors
     */
    private function getResults(): array
    {
        return [
            'success' => count($this->errors) === 0,
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'total_errors' => count($this->errors),
            'message' => $this->buildMessage()
        ];
    }

    /**
     * Build result message
     * 
     * @return string Result message
     */
    private function buildMessage(): string
    {
        $message = "✅ Successfully imported {$this->imported} customer(s)";
        
        if ($this->skipped > 0) {
            $message .= " (Skipped {$this->skipped} empty row(s))";
        }

        if (count($this->errors) > 0) {
            $message .= " - ⚠️ " . count($this->errors) . " error(s) found";
        }

        return $message;
    }

    /**
     * Generate a sample XLSX template
     * 
     * @param string $filePath Path where to save the template
     * @return void
     */
    public static function generateTemplate(string $filePath): void
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Customer Name', 'Address', 'City', 'State', 'Pin Code', 'Mobile', 'GST Type', 'GST No', 'Email'];
        $worksheet->fromArray($headers, NULL, 'A1');

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '366092']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];

        foreach (range('A', 'I') as $column) {
            $worksheet->getStyle($column . '1')->applyFromArray($headerStyle);
        }

        // Add sample row
        $sampleData = [
            'Rajesh Kumar',
            'Industrial Area, Plot No. 245',
            'Rajkot',
            'Gujarat',
            '360004',
            '9876543210',
            'instate',
            '27AABCU9603R1Z5',
            'rajesh@example.com'
        ];
        $worksheet->fromArray($sampleData, NULL, 'A2');

        // Set column widths
        $columnWidths = ['A' => 25, 'B' => 35, 'C' => 15, 'D' => 15, 'E' => 12, 'F' => 15, 'G' => 15, 'H' => 20, 'I' => 25];
        foreach ($columnWidths as $column => $width) {
            $worksheet->getColumnDimension($column)->setWidth($width);
        }

        // Freeze header row
        $worksheet->freezePane('A2');

        // Save the file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }
}
