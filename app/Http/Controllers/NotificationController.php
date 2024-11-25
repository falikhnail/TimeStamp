<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $filter = request()->input('filter','');
        $title = 'Notifications';

        $guard = Auth::guard('web')->check() ? 'web' : 'karyawan';
        $user = Auth::guard($guard)->user();

        $inboxs = $user->notifications()
                        ->when($filter == 'read', fn($query)=>$query->whereNotNull('read_at'))
                        ->when($filter == 'unread', fn($query)=>$query->whereNull('read_at'))
                        ->paginate(10)
                        ->withQueryString();

        if (auth()->user()->is_admin == 'admin') {
            return view('notifications.indexAdmin', compact(
                'inboxs',
                'title'
            ));
        } else {
            return view('notifications.index', compact(
                'inboxs',
                'title'
            ));
        }
    }

    public function read()
    {
        $filter = request()->input('filter','');
        $title = 'Notifications';

        $inboxs = auth()->user()->notifications()
                        ->whereNotNull('read_at')
                        ->paginate(10)
                        ->withQueryString();

        return view('notifications.indexAdmin', compact(
            'inboxs',
            'title'
        ));
    }

    public function unread()
    {
        $filter = request()->input('filter','');
        $title = 'Notifications';

        $inboxs = auth()->user()->notifications()
                        ->whereNull('read_at')
                        ->paginate(10)
                        ->withQueryString();

        return view('notifications.indexAdmin', compact(
            'inboxs',
            'title'
        ));
    }

    public function readMessage($id)
    {
        $notifikasi = auth()->user()->notifications()->where('id', $id)->whereNull('read_at')->first();
        $notifikasi->markAsRead();
        return redirect($notifikasi->data["action"]);
    }

}
