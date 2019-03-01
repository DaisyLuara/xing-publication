<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Transformer\NotificationTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use DB;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(10);

        return $this->response()->paginator($notifications, new NotificationTransformer());
    }

    public function stats()
    {
        $unreadCount = DatabaseNotification::query()
            ->where("notifiable_id", $this->user()->id)
            ->whereRaw("read_at is null")
            ->count();
        return $this->response()->array([
            'unread_count' => $unreadCount
        ]);
    }

    public function read()
    {
        $this->user()->markAsRead();
        return $this->response()->noContent();
    }

    public function destroy(Request $request, DatabaseNotification $notification)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            DatabaseNotification::query()->where('id', $id)->delete();
        }
        return $this->response()->noContent();
    }
}
