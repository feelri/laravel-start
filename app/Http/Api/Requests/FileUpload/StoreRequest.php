<?php

namespace App\Http\Api\Requests\FileUpload;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\FileUploadSuffixEnum;
use App\Http\Api\Requests\BaseRequest;
use App\Services\Model\ConfigService;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
		$suffixImplode = FileUploadSuffixEnum::implode();
		$maxSize = ConfigService::static()->key(ConfigKeyEnum::FileUpload)->get('max_size', 0);
		$maxSize = ceil(bcmul($maxSize, 1024, 2));
        return [
            'file'       => "required|file|mimes:{$suffixImplode}|max:{$maxSize}",
        ];
    }

    /**
     * 字段别名
     * @return array
     */
    public function attributes(): array
    {
        return [
            'file'  => __('validation.attributes.file'),
        ];
    }
}
