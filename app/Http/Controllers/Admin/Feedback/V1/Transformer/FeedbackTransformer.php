<?php

namespace App\Http\Controllers\Admin\Feedback\V1\Transformer;

use App\Http\Controllers\Admin\Feedback\V1\Models\Feedback;
use App\Http\Controllers\Admin\Media\V1\Transformer\MediaTransformer;
use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['childrenFeedback', 'photos', 'video'];

    public function transform(Feedback $feedback)
    {

        return [
            'id' => $feedback->id,
            'title' => $feedback->title,
            'content' => $feedback->content,
            'createable_name' => $feedback->createable ? $feedback->createable->name : '',
            'createable_type' => $feedback->createable_type,
            'createable_id' => $feedback->createable_id,
            'company_name' => $feedback->createable_type === Customer::class && $feedback->createable && $feedback->createable->company
                ? $feedback->createable->company->name : '',
            'parent_id' => $feedback->parent_id,
            'top_parent_id' => $feedback->top_parent_id,
            'status' => $feedback->status,
            'status_text' => Feedback::$statusAttributeMapping[$feedback->status] ?? $feedback->status,
            'created_at' => (string)$feedback->created_at,
            'updated_at' => (string)$feedback->updated_at,

        ];
    }

    public function includeChildrenFeedback(Feedback $feedback)
    {
        $children = $feedback->childrenFeedback()->orderBy('created_at')->get();
        return $this->collection($children, new FeedbackTransformer());
    }

    public function includePhotos(Feedback $feedback)
    {
        $photos = $feedback->photos;
        return $this->collection($photos, new MediaTransformer());
    }

    public function includeVideo(Feedback $feedback)
    {
        $video = $feedback->video;
        return $this->item($video, new MediaTransformer());
    }
}