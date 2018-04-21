<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Topic;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\V1\TopicRequest;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        /**
         * @todo 权限的设置
         */
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }
}