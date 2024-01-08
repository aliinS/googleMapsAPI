<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = Marker::all();
        return response()->json($markers);
    }

    public function create()
    {
        return view('markers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        Marker::create($request->all());

        return redirect()->route('markers.index');
    }

    public function edit(Marker $marker)
    {
        return view('markers.edit', compact('marker'));
    }

    public function update(Request $request, Marker $marker)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $marker->update($request->all());

        return redirect()->route('markers.index');
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();

        return redirect()->route('markers.index');
    }
}
