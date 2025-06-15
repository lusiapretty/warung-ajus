<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('gambar', function ($row) {
                    $path = $row->gambar ?? '';
                    if ($path !== '' && Storage::disk('public')->exists($path)) {
                        return '<img src="' . asset('storage/' . $row->gambar) . '" width="50">';
                    }
                    return '-';
})
                ->addColumn('aksi', function ($row) {
                    $btn = '
                        <button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-primary btn-edit">
                            <i class="fas fa-pen-alt"></i>Edit
                        </button>
                        <button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-delete">
                            <i class="fas fa-trash-alt"></i>Hapus
                        </button>';
                    return $btn;
                })
                ->rawColumns(['gambar', 'aksi'])
                ->make(true);
        }

        return view('admin.menu.index');
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        Log::info('Masuk ke AdminController@store', $request->all());

        $validateData = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            Log::info('File gambar valid?', ['isValid' => $file->isValid()]);
            if ($file->isValid()) {
                $validateData['gambar'] = $file->store('menu', 'public');
            } else {
                Log::warning('File gambar tidak valid');
            }
        } else {
            Log::warning('Tidak ada file gambar di request');
        }
        
        $menu = Menu::create($validateData);

        return response()->json([
            'success' => true, 
            'message' => 'Menu berhasil ditambahkan!',
            'menu' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'deskripsi' => $menu->deskripsi,
                'harga' => $menu->harga,
                'kategori' => $menu->kategori,
                'gambar' => asset('storage/' . $menu->gambar),
            ]
        ]);
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        
        if (!$menu) {            
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        Log::info('Isi request update: ', $request->all());

        try {
            
            $menu = Menu::findOrFail($id);

            $menu->nama_menu = $request->nama_menu;
            $menu->deskripsi = $request->deskripsi;
            $menu->harga = $request->harga;
            $menu->kategori = $request->kategori;

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $namaFile = time().'.'.$gambar->getClientOriginalExtension();
                $gambar->move(public_path('uploads'), $namaFile);
                // $gambarPath = $request->file('gambar')->store('menu', 'public');
                $menu->gambar = 'uploads/' . $namaFile; // Simpan path gambar
            }

            $menu->save();

            return response()->json(['success' => true, 'message' => 'Menu berhasil diperbarui!', 'menu' => $menu]);
        } catch (\Exception $e) {
            Log::error('Error saat update menu: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui menu'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);

            // Coba hapus gambar jika ada
            if ($menu->gambar && file_exists(public_path($menu->gambar))) {
                unlink(public_path($menu->gambar));
            }

            // Hapus data dari database
            $menu->delete();

            return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Gagal hapus menu: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus menu']);
        }
    }

}
