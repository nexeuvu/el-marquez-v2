<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        return view('notifications.index', [
            'unreadNotifications' => $user->unreadNotifications,
            'readNotifications' => $user->readNotifications,
        ]);
    }

    /**
     * Marcar una notificación específica como leída.
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if ($notification->read_at === null) {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notificación marcada como leída ✅');
        }

        return redirect()->back()->with('info', 'La notificación ya estaba leída.');
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Todas las notificaciones fueron marcadas como leídas 🎉');
    }

    /**
     * Eliminar una notificación específica.
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notificación eliminada correctamente 🗑️');
    }
}
