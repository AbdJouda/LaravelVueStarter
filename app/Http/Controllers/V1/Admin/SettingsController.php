<?php

namespace App\Http\Controllers\V1\Admin;

use App\Concerns\HasFileUpload;
use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SettingRequest;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class SettingsController extends Controller
{
    use HasFileUpload;

    /**
     * Retrieve a paginated list of jobs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSettings(Request $request): JsonResponse
    {
        $settings = AppSetting::query()
            ->get()
            ->pluck('value', 'key');

        return BossResponse::withData($settings)
            ->asSuccess();
    }

    /**
     * Update Settings
     *
     * @param SettingRequest $request
     * @return JsonResponse
     */
    public function updateSettings(SettingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $uploadableFields = ['logo', 'favicon'];

        foreach ($uploadableFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $this->handleFileUpload($request->file($field), $field);
            }
        }

        $filteredData = collect($validated)->filter(function ($value) {
            return !is_null($value) && $value !== '';
        })->toArray();

        foreach ($filteredData as $key => $value) {
            AppSetting::where('key', $key)->update(['value' => $value]);
        }


        return BossResponse::withMessage(__('actions.success.update', ['name' => __('Settings')]))
            ->asSuccess();
    }

    /**
     * Handle file upload and save to storage
     *
     * @param UploadedFile $file
     * @param string $field
     * @return string
     */
    protected function handleFileUpload(UploadedFile $file, string $field): string
    {
        $this->deleteFile(appSetting($field));

        return $file->store('/', 'public');
    }



}
