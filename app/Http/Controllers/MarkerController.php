<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
{
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $marker = Marker::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return response()->json($marker, 201);
}

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
