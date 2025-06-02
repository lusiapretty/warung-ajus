<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        Log::info('Store function called', $request->all()); 
        $data = $request->validate([
            'nama_menu' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar_menu', 'public');
        }

        Menu::create($data);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'nama_menu' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('gambar_menu', 'public');
        }

        $menu->update($data);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();
        return response()->json(['success' => true]);
    }
}

