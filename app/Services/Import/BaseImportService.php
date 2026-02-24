<?php

namespace App\Services\Import;

use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

abstract class BaseImportService
{
    protected ImportLog $importLog;

    protected array $errors = [];

    protected int $successCount = 0;

    protected int $errorCount = 0;

    protected int $skippedCount = 0;

    /**
     * Get the required columns for this import type.
     *
     * @return array<string>
     */
    abstract public function getRequiredColumns(): array;

    /**
     * Get all available columns for this import type.
     *
     * @return array<string, string>
     */
    abstract public function getAvailableColumns(): array;

    /**
     * Process a single row of data.
     *
     * @param  array<string, mixed>  $row
     * @return array{success: bool, message: string, data?: mixed}
     */
    abstract protected function processRow(array $row, int $rowNumber): array;

    /**
     * Get sample data for template.
     *
     * @return array<array<string, mixed>>
     */
    abstract public function getSampleData(): array;

    public function import(ImportLog $importLog, string $filePath): ImportLog
    {
        $this->importLog = $importLog;
        $this->errors = [];
        $this->successCount = 0;
        $this->errorCount = 0;
        $this->skippedCount = 0;

        $importLog->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        try {
            $rows = $this->parseExcel($filePath);

            if (empty($rows)) {
                throw new \Exception('File Excel kosong atau tidak valid.');
            }

            $importLog->update(['total_rows' => count($rows)]);

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because index 0 = row 2 (after header)

                try {
                    $result = $this->processRow($row, $rowNumber);

                    if ($result['success']) {
                        $this->successCount++;
                    } else {
                        if (isset($result['skipped']) && $result['skipped']) {
                            $this->skippedCount++;
                        } else {
                            $this->errorCount++;
                        }
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'message' => $result['message'],
                            'data' => $row,
                        ];
                    }
                } catch (\Exception $e) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => $e->getMessage(),
                        'data' => $row,
                    ];
                }
            }

            DB::commit();

            $importLog->update([
                'status' => 'completed',
                'success_count' => $this->successCount,
                'error_count' => $this->errorCount,
                'skipped_count' => $this->skippedCount,
                'errors' => $this->errors,
                'summary' => $this->generateSummary(),
                'completed_at' => now(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Import failed: '.$e->getMessage(), [
                'import_log_id' => $importLog->id,
                'file' => $filePath,
            ]);

            $importLog->update([
                'status' => 'failed',
                'errors' => [['message' => $e->getMessage()]],
                'completed_at' => now(),
            ]);
        }

        // Clean up uploaded file
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        return $importLog->fresh();
    }

    /**
     * Parse Excel file and return array of rows.
     *
     * @return array<array<string, mixed>>
     */
    protected function parseExcel(string $filePath): array
    {
        $fullPath = Storage::path($filePath);
        $rows = [];

        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        // Read header row (row 1)
        $headerRow = $worksheet->rangeToArray('A1:'.$highestColumn.'1', null, true, true, true)[1];
        $headers = [];

        foreach ($headerRow as $cell) {
            if ($cell !== null && $cell !== '') {
                $headers[] = strtolower(trim((string) $cell));
            }
        }

        if (empty($headers)) {
            throw new \Exception('Gagal membaca header Excel.');
        }

        // Validate required columns
        $this->validateColumns($headers);

        // Read data rows (starting from row 2)
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $worksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true)[$row];

            // Check if row is empty
            $isEmpty = true;
            foreach ($rowData as $cell) {
                if ($cell !== null && $cell !== '') {
                    $isEmpty = false;
                    break;
                }
            }

            if ($isEmpty) {
                continue;
            }

            // Map to associative array
            $mappedRow = [];
            $colIndex = 0;
            foreach ($rowData as $cell) {
                if (isset($headers[$colIndex])) {
                    $mappedRow[$headers[$colIndex]] = $cell !== null ? (string) $cell : '';
                }
                $colIndex++;
            }

            $rows[] = $mappedRow;
        }

        return $rows;
    }

    /**
     * Validate that required columns are present.
     *
     * @param  array<string>  $headers
     */
    protected function validateColumns(array $headers): void
    {
        $requiredColumns = array_map('strtolower', $this->getRequiredColumns());
        $missingColumns = array_diff($requiredColumns, $headers);

        if (! empty($missingColumns)) {
            throw new \Exception('Kolom wajib tidak ditemukan: '.implode(', ', $missingColumns));
        }
    }

    /**
     * Preview data from Excel file.
     *
     * @return array{headers: array<string>, rows: array<array<string, mixed>>, total: int, valid: bool, errors: array<string>}
     */
    public function preview(string $filePath, int $limit = 5): array
    {
        $fullPath = Storage::path($filePath);
        $rows = [];
        $headers = [];
        $errors = [];
        $total = 0;

        try {
            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            // Read header row
            $headerRow = $worksheet->rangeToArray('A1:'.$highestColumn.'1', null, true, true, true)[1];

            foreach ($headerRow as $cell) {
                if ($cell !== null && $cell !== '') {
                    $headers[] = trim((string) $cell);
                }
            }

            if (empty($headers)) {
                return [
                    'headers' => [],
                    'rows' => [],
                    'total' => 0,
                    'valid' => false,
                    'errors' => ['Gagal membaca header Excel.'],
                ];
            }

            // Validate columns
            $requiredColumns = $this->getRequiredColumns();
            $normalizedHeaders = array_map('strtolower', $headers);
            $normalizedRequired = array_map('strtolower', $requiredColumns);
            $missingColumns = array_diff($normalizedRequired, $normalizedHeaders);

            if (! empty($missingColumns)) {
                $errors[] = 'Kolom wajib tidak ditemukan: '.implode(', ', $missingColumns);
            }

            // Count total rows and read preview
            $count = 0;
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $worksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true)[$row];

                // Check if row is empty
                $isEmpty = true;
                foreach ($rowData as $cell) {
                    if ($cell !== null && $cell !== '') {
                        $isEmpty = false;
                        break;
                    }
                }

                if ($isEmpty) {
                    continue;
                }

                $total++;

                if ($count < $limit) {
                    $mappedRow = [];
                    $colIndex = 0;
                    foreach ($rowData as $cell) {
                        if (isset($headers[$colIndex])) {
                            $mappedRow[$headers[$colIndex]] = $cell !== null ? (string) $cell : '';
                        }
                        $colIndex++;
                    }
                    $rows[] = $mappedRow;
                    $count++;
                }
            }
        } catch (\Exception $e) {
            return [
                'headers' => [],
                'rows' => [],
                'total' => 0,
                'valid' => false,
                'errors' => ['Gagal membaca file Excel: '.$e->getMessage()],
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
            'total' => $total,
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Generate import summary.
     *
     * @return array<string, mixed>
     */
    protected function generateSummary(): array
    {
        return [
            'total_rows' => $this->successCount + $this->errorCount + $this->skippedCount,
            'success' => $this->successCount,
            'errors' => $this->errorCount,
            'skipped' => $this->skippedCount,
            'success_rate' => $this->successCount + $this->errorCount + $this->skippedCount > 0
                ? round(($this->successCount / ($this->successCount + $this->errorCount + $this->skippedCount)) * 100, 1)
                : 0,
        ];
    }

    /**
     * Generate Excel template.
     */
    public function generateTemplate(): Spreadsheet
    {
        $columns = $this->getAvailableColumns();
        $headers = array_keys($columns);
        $descriptions = array_values($columns);
        $sampleData = $this->getSampleData();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Import');

        // Write headers in row 1
        $colIndex = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($colIndex, 1, $header);

            // Style header
            $sheet->getStyleByColumnAndRow($colIndex, 1)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E11D48'], // Rose-600
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ]);

            // Auto-size column
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);

            $colIndex++;
        }

        // Write sample data starting from row 2
        $rowIndex = 2;
        foreach ($sampleData as $row) {
            $colIndex = 1;
            foreach ($headers as $header) {
                $value = $row[$header] ?? '';
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $value);
                $colIndex++;
            }
            $rowIndex++;
        }

        // Add instructions sheet
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Petunjuk');

        $instructionSheet->setCellValue('A1', 'PETUNJUK PENGISIAN');
        $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $instructionSheet->setCellValue('A3', 'Kolom yang tersedia:');
        $instructionSheet->getStyle('A3')->getFont()->setBold(true);

        $row = 4;
        foreach ($columns as $column => $description) {
            $instructionSheet->setCellValue('A'.$row, $column);
            $instructionSheet->setCellValue('B'.$row, $description);

            if (in_array($column, $this->getRequiredColumns())) {
                $instructionSheet->getStyle('A'.$row)->getFont()->setBold(true);
                $instructionSheet->setCellValue('C'.$row, '(WAJIB)');
                $instructionSheet->getStyle('C'.$row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));
            }

            $row++;
        }

        $instructionSheet->getColumnDimension('A')->setAutoSize(true);
        $instructionSheet->getColumnDimension('B')->setAutoSize(true);
        $instructionSheet->getColumnDimension('C')->setAutoSize(true);

        // Set first sheet as active
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Clean and normalize a value.
     */
    protected function cleanValue(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return trim($value);
    }

    /**
     * Parse date in various formats.
     */
    protected function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Check if it's an Excel serial date number
        if (is_numeric($value)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value);

                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                // Not a valid Excel date, continue with string parsing
            }
        }

        // Try various date formats
        $formats = [
            'Y-m-d',
            'd/m/Y',
            'd-m-Y',
            'm/d/Y',
            'Y/m/d',
            'd M Y',
            'd F Y',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Parse boolean value.
     */
    protected function parseBoolean(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        $value = strtolower(trim($value));

        return in_array($value, ['1', 'true', 'yes', 'ya', 'aktif', 'active']);
    }

    /**
     * Parse numeric value.
     */
    protected function parseNumber(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Remove thousand separators and normalize decimal
        $value = str_replace(['.', ','], ['', '.'], trim($value));

        return is_numeric($value) ? (float) $value : null;
    }
}
