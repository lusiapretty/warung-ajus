<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Addon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;


class MenuController extends Controller
{
    public function indexMakanan()
    {
        $menus = Menu::with('addons')->where('kategori', 'makanan')->get();
        return view('pelanggan.menu-makanan', compact('menus'));    
    }

    public function indexMinuman()
    {
        $menus = Menu::with('addons')->where('kategori', 'minuman')->get();
        return view('pelanggan.menu-minuman', compact('menus'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Menu::with('addons')->latest();

            if ($request->has('kategori') && $request->kategori != '') {
                $query->where('kategori', $request->kategori);
            }

            if ($request->stok == 'tersedia') {
                    $query->where('status', 1); // Tersedia
                } elseif ($request->stok == 'habis') {
                    $query->where('status', 0); // Habis
                }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('gambar', function ($row) {
                    $path = $row->gambar ?? '';
                    if ($path !== '' && Storage::disk('public')->exists($path)) {
                        return '<img src="' . asset('storage/' . $row->gambar) . '" width="50">';
                    }
                    return '-';
                })
                ->addColumn('addons', function ($row) {
                    return $row->addons->pluck('nama')->implode(', ');
                })
                ->addColumn('stok', function ($row) {
                    $icon = $row->status
                        ? '<i class="fas fa-check-circle text-light"></i> Tersedia'
                        : '<i class="fas fa-times-circle text-light"></i> Habis';
                    return '<button class="btn btn-sm toggle-status ' . ($row->status ? 'btn-success' : 'btn-danger') . '" data-id="' . $row->id . '" style="min-width: 90px">' . $icon . '</button>';
                })
                ->addColumn('aksi', function ($row) {
                    $editButton = '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-primary btn-edit">
                                    <i class="fas fa-pen-alt"></i> Edit</button>';

                    $deleteButton = '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash-alt"></i> Hapus</button>';

                    return  $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['gambar', 'stok', 'aksi'])
                ->make(true);
        }
        $addons = Addon::all();
        return view('admin.menu.index', compact('addons'));
    }

    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->status = !$menu->status;
        $menu->save();

        return response()->json(['success' => true, 'message' => 'Status menu diperbarui!']);
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        Log::info('Masuk ke MenuController@store', $request->all());

        $validateData = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;

                try {
                    $path = $file->storeAs('menu', $filename, 'public');
                    Log::info('File berhasil disimpan ke:', ['path' => $path]);

                    $validateData['gambar'] = $path;
                } catch (\Exception $e) {
                    Log::error('Gagal simpan file: ' . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Gagal menyimpan gambar.'], 500);
                }
            } else {
                Log::warning('File upload tidak valid!');
            }
        }

        $menu = Menu::create($validateData);

        if ($request->has('addons')) {
            $menu->addons()->sync($request->addons);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'menu' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'deskripsi' => $menu->deskripsi,
                'harga' => $menu->harga,
                'kategori' => $menu->kategori,
                'addons' => $menu->addons,
                'gambar' => $menu->gambar ? asset('storage/' . $menu->gambar) : null,
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

        return response()->json([
            // 'id' => $menu->id,
            'nama_menu' => $menu->nama_menu,
            'deskripsi' => $menu->deskripsi,
            'harga' => $menu->harga,
            'kategori' => $menu->kategori,
            'addons' => $menu->addons->pluck('id')->toArray(),
            'gambar' => asset('storage/' . $menu->gambar),
        ]); 
    }

    public function update(Request $request, $id)
    {
        Log::info('Isi request update: ', $request->all());

        $menu = Menu::findOrFail($id);

        // Validasi data
        $validatedData = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'addons' => 'nullable|array',
        ]);

        // Update field teks
        $menu->nama_menu = $validatedData['nama_menu'];
        $menu->deskripsi = $validatedData['deskripsi'] ?? null;
        $menu->harga = $validatedData['harga'];
        $menu->kategori = $validatedData['kategori'];

        // Jika ada file gambar baru
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;

                try {
                    // Hapus gambar lama jika ada
                    if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                        Storage::disk('public')->delete($menu->gambar);
                    }

                    // Simpan gambar baru ke folder storage/app/public/menu/
                    $path = $file->storeAs('menu', $filename, 'public');

                    // Simpan path ke database
                    $menu->gambar = $path;
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan gambar saat update: ' . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Gagal menyimpan gambar.'], 500);
                }
            }
        }

        // Simpan perubahan
        $menu->save();

        // Sinkronisasi relasi addons
        $menu->addons()->sync($request->addons ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!',
            'menu' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'deskripsi' => $menu->deskripsi,
                'harga' => $menu->harga,
                'kategori' => $menu->kategori,
                'addons' => $menu->addons,
                'gambar' => $menu->gambar ? asset('storage/' . $menu->gambar) : null,
            ]
        ]);
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