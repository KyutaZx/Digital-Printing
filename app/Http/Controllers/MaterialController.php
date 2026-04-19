<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display All Materials
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $materials = Material::orderBy('name')->paginate(15);

        return view('materials.index', compact('materials'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store New Material
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string'
        ]);

        Material::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'unit' => $request->unit
        ]);

        return redirect()->back()->with('success', 'Material created');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Material
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string',
            'unit' => 'required|string'
        ]);

        $material->update([
            'name' => $request->name,
            'unit' => $request->unit
        ]);

        return redirect()->back()->with('success', 'Material updated');
    }

    /*
    |--------------------------------------------------------------------------
    | Restock Material
    |--------------------------------------------------------------------------
    */

    public function restock(Request $request, Material $material)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $material->increment('stock', $request->quantity);

        MaterialStockLog::create([
            'material_id' => $material->id,
            'change_amount' => $request->quantity,
            'type' => 'restock',
            'notes' => 'Material restocked'
        ]);

        return redirect()->back()->with('success', 'Material restocked');
    }

    /*
    |--------------------------------------------------------------------------
    | Material Stock History
    |--------------------------------------------------------------------------
    */

    public function history(Material $material)
    {
        $logs = MaterialStockLog::where('material_id', $material->id)
            ->latest()
            ->paginate(20);

        return view('materials.history', compact('material', 'logs'));
    }
}