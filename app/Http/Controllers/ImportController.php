<?php

namespace App\Http\Controllers;

use App\Models\ImportLog;
use App\Services\Import\CustomerImportService;
use App\Services\Import\PackageImportService;
use App\Services\Import\ServiceImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportController extends Controller
{
    protected array $importServices = [
        'customers' => CustomerImportService::class,
        'services' => ServiceImportService::class,
        'packages' => PackageImportService::class,
    ];

    protected array $entityKeys = ['customers', 'services', 'packages'];

    protected function getEntityLabel(string $key): string
    {
        return __("import.{$key}");
    }

    public function index(): View
    {
        $imports = ImportLog::with('user')
            ->latest()
            ->paginate(15);

        $entities = collect($this->entityKeys)->map(function ($key) {
            return [
                'key' => $key,
                'label' => $this->getEntityLabel($key),
            ];
        })->values();

        return view('imports.index', compact('imports', 'entities'));
    }

    public function create(string $entity): View
    {
        if (! isset($this->importServices[$entity])) {
            abort(404, __('import.invalid_entity'));
        }

        $service = new $this->importServices[$entity];

        return view('imports.create', [
            'entity' => $entity,
            'entityLabel' => $this->getEntityLabel($entity),
            'requiredColumns' => $service->getRequiredColumns(),
            'availableColumns' => $service->getAvailableColumns(),
        ]);
    }

    public function upload(Request $request, string $entity): RedirectResponse
    {
        if (! isset($this->importServices[$entity])) {
            abort(404, __('import.invalid_entity'));
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'], // 10MB max
        ], [
            'file.required' => __('import.file_required'),
            'file.mimes' => __('import.file_mimes'),
            'file.max' => __('import.file_max'),
        ]);

        $file = $request->file('file');
        $fileName = uniqid('import_').'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('imports', $fileName);

        return redirect()->route('imports.preview', [
            'entity' => $entity,
            'file' => $fileName,
        ]);
    }

    public function preview(Request $request, string $entity): View|RedirectResponse
    {
        if (! isset($this->importServices[$entity])) {
            abort(404, __('import.invalid_entity'));
        }

        $fileName = $request->query('file');
        $filePath = 'imports/'.$fileName;

        if (! $fileName || ! Storage::exists($filePath)) {
            return redirect()->route('imports.create', $entity)
                ->with('error', __('import.file_not_found'));
        }

        $service = new $this->importServices[$entity];
        $preview = $service->preview($filePath, 10);

        return view('imports.preview', [
            'entity' => $entity,
            'entityLabel' => $this->getEntityLabel($entity),
            'fileName' => $fileName,
            'preview' => $preview,
            'requiredColumns' => $service->getRequiredColumns(),
            'availableColumns' => $service->getAvailableColumns(),
        ]);
    }

    public function process(Request $request, string $entity): RedirectResponse
    {
        if (! isset($this->importServices[$entity])) {
            abort(404, __('import.invalid_entity'));
        }

        $fileName = $request->input('file');
        $filePath = 'imports/'.$fileName;

        if (! $fileName || ! Storage::exists($filePath)) {
            return redirect()->route('imports.create', $entity)
                ->with('error', __('import.file_not_found'));
        }

        // Create import log
        $importLog = ImportLog::create([
            'user_id' => auth()->id(),
            'entity_type' => $entity,
            'file_name' => $fileName,
            'original_file_name' => $request->input('original_name', $fileName),
            'status' => 'pending',
        ]);

        // Process import
        $service = new $this->importServices[$entity];
        $importLog = $service->import($importLog, $filePath);

        if ($importLog->status === 'completed') {
            $message = __('import.import_success', [
                'entity' => $this->getEntityLabel($entity),
                'success' => $importLog->success_count,
            ]);

            if ($importLog->skipped_count > 0) {
                $message .= __('import.import_success_with_update', ['skipped' => $importLog->skipped_count]);
            }

            if ($importLog->error_count > 0) {
                $message .= __('import.import_success_with_error', ['error' => $importLog->error_count]);
            }

            $message .= '.';

            return redirect()->route('imports.show', $importLog)
                ->with('success', $message);
        }

        return redirect()->route('imports.show', $importLog)
            ->with('error', __('import.import_failed'));
    }

    public function show(ImportLog $import): View
    {
        $import->load('user');

        return view('imports.show', compact('import'));
    }

    public function template(string $entity): StreamedResponse
    {
        if (! isset($this->importServices[$entity])) {
            abort(404, __('import.invalid_entity'));
        }

        $service = new $this->importServices[$entity];
        $spreadsheet = $service->generateTemplate();

        $fileName = "template_import_{$entity}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function destroy(ImportLog $import): RedirectResponse
    {
        $import->delete();

        return redirect()->route('imports.index')
            ->with('success', __('import.log_deleted'));
    }
}
