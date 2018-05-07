<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ProjectLaunch;
use App\Transformers\ProjectLaunchTransformer;

class ProjectLaunchController extends Controller
{
    public function index(Request $request, ProjectLaunch $projectLaunch)
    {

        $user = $this->user();
        $ar_user_id = getArUserID($user, $request);
        $query = $projectLaunch->query();

        if ($ar_user_id) {
            $query->whereHas('point', function ($query) use ($ar_user_id) {
                $query->whereHas('arUsers', function ($query) use ($ar_user_id) {
                    $query->where('admin_staff.uid', '=', $ar_user_id);
                });
            });
        }

        $projects = $query->paginate(10);
        return $this->response->paginator($projects, new ProjectLaunchTransformer());

    }
}
