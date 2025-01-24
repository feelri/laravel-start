<?php

namespace App\Models;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\FileUploadFromEnum;
use App\Services\Model\ConfigService;
use App\Services\ToolService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $appends = [
        'size_label', 'from_label', 'url'
    ];

	/**
	 * size_label 获取/访问器
	 * @return Attribute
	 */
	public function sizeLabel(): Attribute
	{
		$self = $this;
		return new Attribute(
			get: static function () use ($self)  {
				return $self->size ? ToolService::static()->bytesToSize($self->size) : '';
			}
		);
	}

    /**
     * type_label 获取/访问器
     * @return Attribute
     */
    public function fromLabel(): Attribute
    {
		return new Attribute(
            get: fn () => FileUploadFromEnum::tryFrom($this->from)?->label() ?? ''
        );
    }

    /**
     * url 获取/访问器
     *
     * @return Attribute
     */
    public function url(): Attribute
    {
        return new Attribute(
            get: function ()  {
				$service = ConfigService::static();
                $driver = $service->key(ConfigKeyEnum::FileUpload)->get('driver');
                $value = $this->path ?? $service->key(ConfigKeyEnum::System)->get('not_found');
                $baseUrl = match ($driver) {
                    FileUploadFromEnum::QiNiu->value => $service->key(ConfigKeyEnum::QiniuCloud)->get('koDo.staticUrl'),
                    FileUploadFromEnum::AliYun->value => $service->key(ConfigKeyEnum::AlibabaCloud)->get('OSS.staticUrl'),
                    default => $service->key(ConfigKeyEnum::System)->get('asset_url'),
                };
                return  trim($baseUrl, '/') . '/' . trim($value, '/');
            }
        );
    }
}
