<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Attribute\V1\Models\{
    Attribute, PointAttribute, ProjectAttribute
};
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use Illuminate\Filesystem\Filesystem;


class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $fileSystem = new Filesystem();
        $attributes = json_decode($fileSystem->get(base_path('database/seeds') . '/' . 'attributes.json'), true);

        foreach ($attributes as $attribute) {
            $parentAttribute = Attribute::query()->updateOrCreate(['name' => $attribute['name']], ['name' => $attribute['name'], 'desc' => $attribute['desc']]);
            foreach ($attribute['children'] as $child) {
                Attribute::query()->updateOrCreate(['name' => $child['name']], ['name' => $child['name'], 'desc' => $child['desc'], 'pid' => $parentAttribute->id]);
            }
        }

        DB::table('project_attributes')->truncate();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load("database/seeds/节目拆分.xlsx");

        $cells = $spreadsheet->getSheet(0)->toArray();
        array_shift($cells);

        foreach ($cells as $cell) {
            $project = Project::query()->where(['name' => $cell[1]])->first();
            $cellBak = $cell;
            unset($cell[0]);
            unset($cell[1]);
            $attributeNames = [];
            foreach ($cell as $key => $value) {
                $values = explode('、', $value);
                $attributeNames = array_merge($attributeNames, $values);
            }
            $attributes = Attribute::query()->whereIn('name', $attributeNames)->get(['id', 'name']);
            if ($project) {


                foreach ($attributes as $attribute) {
                    ProjectAttribute::query()->create(['project_id' => $project->id, 'belong' => $project->versionname, 'attribute_id' => $attribute->id]);
                }
            } else {
                Log::info('project_not_found', ['project' => $cellBak]);
            }

        }


    }
}
