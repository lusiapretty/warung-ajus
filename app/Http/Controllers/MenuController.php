<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Addon;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('addons')->get();
        return view('menus.makanan', compact('menus'));
    }

    public function create()
    {
        $addons = Addon::all();
        return view('menus.create', compact('addons'));
    }

    public function store(Request $request)
    {
        $menu = Menu::create($request->only('nama', 'deskripsi', 'harga', 'gambar'));
        $menu->addons()->sync($request->input('addons', []));
        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $addons = Addon::all();
        return view('menus.edit', compact('menu', 'addons'));
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($request->only('nama', 'deskripsi', 'harga', 'gambar'));
        $menu->addons()->sync($request->input('addons', []));
        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }


}
