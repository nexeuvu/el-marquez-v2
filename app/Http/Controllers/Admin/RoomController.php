<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index()
    {
        return view('Admin.room.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:50|unique:rooms,number',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            Room::create([
                'number' => $request->number,
                'room_type' => $request->room_type,
                'price' => $request->price,
                'status' => true // Activo por defecto
            ]);

            return redirect()->route('admin.room.index')
                ->with('success', 'La habitación fue registrada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:50|unique:rooms,number,' . $room->id,
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $validator->validate();

            $room->update([
                'number' => $request->number,
                'room_type' => $request->room_type,
                'price' => $request->price,
            ]);

            return redirect()->route('admin.room.index')
                ->with('success', 'La habitación fue actualizada correctamente.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors())->withInput();
        }
    }


    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->update(['status' => false]);

        return redirect()->route('admin.room.index')
            ->with('success', 'La habitación fue eliminada correctamente.');
    }
}
