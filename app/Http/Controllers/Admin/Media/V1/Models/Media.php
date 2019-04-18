<?php

namespace App\Http\Controllers\Admin\Media\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Media
 *
 * @package App\Http\Controllers\Admin\Media\V1\Models
 * @property String $url
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property int $size
 * @property int|null $company_id
 * @property int|null $contract_id
 * @property int $height
 * @property int $width
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\Media withoutTrashed()
 * @mixin \Eloquent
 */
class Media extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'type',
        'size',
        'url',
        'company_id',
        'contract_id',
        'height',
        'width',
    ];
}
