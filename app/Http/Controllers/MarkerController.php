<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    /**
     * Display a listing of the markers.
     */
    public function index()
    {
        $markers = Marker::all();

        return view('markers.index', compact('markers'));
    }

    /**
     * Show the form for creating a new marker.
     */
    public function create()
    {
        return view('markers.create');
    }

    /**
     * Store a newly created marker in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

            Marker::create($validatedData);

            return redirect()->route('markers.index')
            ->with('success', 'Marker updated successfully');

        

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
