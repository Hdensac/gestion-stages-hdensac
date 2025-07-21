<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Retourne les notifications du MS connecté (toutes ou non lues)
     */
    public function list(Request $request)
    {
        $user = auth()->user();
        // On peut filtrer par non lues si besoin
        $onlyUnread = $request->query('unread', false);
        $notifications = $onlyUnread ? $user->unreadNotifications : $user->notifications;
        return response()->json([
            'notifications' => $notifications
        ]);
    }

    /**
     * Marque toutes les notifications comme lues pour le MS connecté
     */
    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
