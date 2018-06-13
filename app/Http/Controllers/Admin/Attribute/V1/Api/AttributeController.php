<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Api;

use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute;
use App\Http\Controllers\Admin\Attribute\V1\Request\AttributeRequest;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AttributeController extends Controller
{
    public function index(Request $request, Attribute $attribute)
    {
        $query = $attribute->query();
        $attribute = $query->get()->toHierarchy();
        return \Response::json($attribute);
    }

    public function store(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->parent_id = $request->parent_id;
        $attribute->name = $request->name;
        $attribute->desc = $request->desc;
        $attribute->save();
        return $this->response->noContent();
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $data = $request->all();
        $query = $attribute->query();
        $query->where('id', '=', $data['id'])->update($data);
        return $this->response->noContent();
    }

    public function test()
    {
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(base_path('database/seeds') . '/' . '点位拆分.xlsx');

        $cells = $spreadsheet->getSheet(0)->toArray();
        array_shift($cells);

        foreach ($cells as $cell) {
            $point = Point::whereHas('area', function ($q) use ($cell) {
                $q->where('name', '=', $cell[0]);
            })->whereHas('market', function ($q) use ($cell) {
                $q->where('name', '=', $cell[1]);
            })->where('name', '=', $cell[2])->first();

            unset($cell[0]);
            unset($cell[1]);
            unset($cell[2]);
            $data = [];
            foreach ($cell as $key => $value) {
                $values = explode('、', $value);
                $data = array_merge($data, $values);
            }
            $attributes = Attribute::query()->whereIn('name', $data)->get(['id']);

            foreach ($attributes as $attribute) {
                PointAttribute::create(['attribute_id' => $attribute->id, 'point_id' => $point->oid]);
            }
        }
    }
}
