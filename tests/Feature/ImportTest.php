<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ImportLog;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected User $beautician;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->beautician = User::factory()->create([
            'role' => 'beautician',
            'is_active' => true,
        ]);

        // Mark setup as completed so we don't get redirected to setup page
        Setting::set('setup_completed', true, 'boolean');
        Setting::set('business_type', 'clinic', 'string');

        Storage::fake('local');
    }

    /**
     * Create an Excel file with given data.
     */
    protected function createExcelFile(array $headers, array $rows): string
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Write headers
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }

        // Write data rows
        $rowNum = 2;
        foreach ($rows as $row) {
            $col = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }

        $fileName = 'import_'.uniqid().'.xlsx';
        $filePath = Storage::path('imports/'.$fileName);

        // Ensure directory exists
        if (! is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $fileName;
    }

    public function test_import_index_page_is_accessible_by_owner(): void
    {
        $response = $this->actingAs($this->owner)->get(route('imports.index'));

        $response->assertStatus(200);
        $response->assertSee('Import Data');
    }

    public function test_import_index_page_is_not_accessible_by_beautician(): void
    {
        $response = $this->actingAs($this->beautician)->get(route('imports.index'));

        $response->assertStatus(403);
    }

    public function test_import_create_page_shows_customer_import_form(): void
    {
        $response = $this->actingAs($this->owner)->get(route('imports.create', 'customers'));

        $response->assertStatus(200);
        $response->assertSee('Import Pelanggan');
        $response->assertSee('name');
        $response->assertSee('phone');
    }

    public function test_can_download_customer_import_template(): void
    {
        $response = $this->actingAs($this->owner)->get(route('imports.template', 'customers'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_excel_upload_validates_file_type(): void
    {
        $file = UploadedFile::fake()->create('customers.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->owner)->post(route('imports.upload', 'customers'), [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_can_process_customer_import(): void
    {
        $fileName = $this->createExcelFile(
            ['name', 'phone', 'email', 'gender'],
            [
                ['John Doe', '081234567890', 'john@example.com', 'male'],
                ['Jane Doe', '081234567891', 'jane@example.com', 'female'],
            ]
        );

        $response = $this->actingAs($this->owner)->post(route('imports.process', 'customers'), [
            'file' => $fileName,
            'original_name' => 'customers.xlsx',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'phone' => '081234567890',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Jane Doe',
            'phone' => '081234567891',
        ]);

        $this->assertDatabaseHas('import_logs', [
            'entity_type' => 'customers',
            'status' => 'completed',
            'success_count' => 2,
        ]);
    }

    public function test_customer_import_updates_existing_customer_by_phone(): void
    {
        Customer::factory()->create([
            'name' => 'Old Name',
            'phone' => '081234567890',
            'email' => 'old@example.com',
        ]);

        $fileName = $this->createExcelFile(
            ['name', 'phone', 'email'],
            [
                ['New Name', '081234567890', 'new@example.com'],
            ]
        );

        $this->actingAs($this->owner)->post(route('imports.process', 'customers'), [
            'file' => $fileName,
        ]);

        $this->assertDatabaseHas('customers', [
            'phone' => '081234567890',
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $this->assertDatabaseCount('customers', 1);
    }

    public function test_customer_import_validates_phone_format(): void
    {
        $fileName = $this->createExcelFile(
            ['name', 'phone', 'email'],
            [
                ['John Doe', '12345', 'john@example.com'],
            ]
        );

        $this->actingAs($this->owner)->post(route('imports.process', 'customers'), [
            'file' => $fileName,
        ]);

        $this->assertDatabaseCount('customers', 0);

        $importLog = ImportLog::first();
        $this->assertEquals(1, $importLog->error_count);
    }

    public function test_can_process_service_import(): void
    {
        $fileName = $this->createExcelFile(
            ['name', 'category', 'price', 'duration_minutes', 'description'],
            [
                ['Facial Basic', 'Facial', '150000', '60', 'Basic facial treatment'],
                ['Deep Cleansing', 'Facial', '200000', '90', 'Deep cleansing facial'],
            ]
        );

        $response = $this->actingAs($this->owner)->post(route('imports.process', 'services'), [
            'file' => $fileName,
        ]);

        $response->assertRedirect();

        // Category should be auto-created
        $this->assertDatabaseHas('service_categories', [
            'name' => 'Facial',
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Facial Basic',
            'price' => 150000,
            'duration_minutes' => 60,
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Deep Cleansing',
            'price' => 200000,
        ]);
    }

    public function test_can_view_import_log_details(): void
    {
        $importLog = ImportLog::create([
            'user_id' => $this->owner->id,
            'entity_type' => 'customers',
            'file_name' => 'test.xlsx',
            'original_file_name' => 'customers.xlsx',
            'total_rows' => 10,
            'success_count' => 8,
            'error_count' => 2,
            'status' => 'completed',
            'errors' => [
                ['row' => 3, 'message' => 'Invalid phone number'],
                ['row' => 7, 'message' => 'Duplicate email'],
            ],
        ]);

        $response = $this->actingAs($this->owner)->get(route('imports.show', $importLog));

        $response->assertStatus(200);
        $response->assertSee('Pelanggan');
        $response->assertSee('8'); // success count
        $response->assertSee('2'); // error count
        $response->assertSee('Invalid phone number');
    }

    public function test_can_delete_import_log(): void
    {
        $importLog = ImportLog::create([
            'user_id' => $this->owner->id,
            'entity_type' => 'customers',
            'file_name' => 'test.xlsx',
            'original_file_name' => 'customers.xlsx',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->owner)->delete(route('imports.destroy', $importLog));

        $response->assertRedirect(route('imports.index'));
        $this->assertDatabaseMissing('import_logs', ['id' => $importLog->id]);
    }

    public function test_invalid_entity_type_returns_404(): void
    {
        $response = $this->actingAs($this->owner)->get(route('imports.create', 'invalid'));

        $response->assertStatus(404);
    }

    public function test_package_import_requires_existing_service(): void
    {
        $fileName = $this->createExcelFile(
            ['name', 'service', 'total_sessions', 'package_price'],
            [
                ['Package A', 'NonExistent Service', '5', '500000'],
            ]
        );

        $this->actingAs($this->owner)->post(route('imports.process', 'packages'), [
            'file' => $fileName,
        ]);

        $this->assertDatabaseCount('packages', 0);

        $importLog = ImportLog::first();
        $this->assertEquals(1, $importLog->error_count);
    }

    public function test_package_import_with_existing_service(): void
    {
        $category = ServiceCategory::create([
            'name' => 'Facial',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Facial Basic',
            'price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $fileName = $this->createExcelFile(
            ['name', 'service', 'total_sessions', 'package_price', 'validity_days'],
            [
                ['Package Facial 5x', 'Facial Basic', '5', '450000', '180'],
            ]
        );

        $this->actingAs($this->owner)->post(route('imports.process', 'packages'), [
            'file' => $fileName,
        ]);

        $this->assertDatabaseHas('packages', [
            'name' => 'Package Facial 5x',
            'service_id' => $service->id,
            'total_sessions' => 5,
            'package_price' => 450000,
            'original_price' => 500000, // 100000 * 5
            'validity_days' => 180,
        ]);
    }
}
