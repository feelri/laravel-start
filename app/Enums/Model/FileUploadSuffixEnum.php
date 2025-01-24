<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * 文件上传后缀
 */
enum FileUploadSuffixEnum: string
{
	use CollectTrait;

	case Jpg  = 'jpg';
	case Jpeg = 'jpeg';
	case Png  = 'png';
	case Gif  = 'gif';
	case Svg  = 'svg';
	case Webp = 'webp';
	case Ico  = 'ico';
	case Mp4  = 'mp4';
	case Md   = 'md';
	case Xlsx = 'xlsx';
	case Csv  = 'csv';
	case Docx = 'docx';
	case Doc  = 'doc';
	case Pdf  = 'pdf';
	case zip  = 'zip';
	case Apk  = 'apk';
}
