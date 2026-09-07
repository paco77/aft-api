<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutdoorActivity;
use App\Models\User;
use Illuminate\Http\Request;

class OutdoorActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = OutdoorActivity::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15);
        $clients = User::where('role', 'client')->orWhere('role', 'athlete')->orderBy('name')->get();

        return view('admin.outdoor-activities.index', compact('activities', 'clients'));
    }

    public function destroy($id)
    {
        $activity = OutdoorActivity::findOrFail($id);
        $activity->delete();

        return redirect()->back()->with('success', 'Registro de recorrido eliminado exitosamente.');
    }
}
