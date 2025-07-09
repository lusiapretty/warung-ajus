<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Addon;
use Yajra\DataTables\DataTables;

class AddonController extends Controller
{
     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Addon::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    return '
                        <div class="d-flex gap-3">
                            <button class="btn btn-sm btn-primary btn-edit" data-id="' . $row->id . '">
                                <i class="fas fa-pen-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('admin.addons.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric'
        ]);

        $addon = Addon::create($request->all());

        return response()->json(['success' => true, 'addon' => $addon]);
    }

    public function edit($id)
    {
        $addon = Addon::findOrFail($id);
        return response()->json($addon);
    }

    public function update(Request $request, $id)
    {
        $addon = Addon::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric'
        ]);

        $addon->update([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);
        
        $addon->save();

        return response()->json(['success' => true, 'addon' => $addon]);
    }

    public function destroy($id)
    {
        $addon = Addon::findOrFail($id);
        $addon->delete();

        return response()->json(['success' => true]);
    }
}
