<form action="{{ isset($menu) ? route('menu.update', $menu->id) : route('menu.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($menu))
        @method('PUT')
    @endif

    <div class="form-group">
        <label>Nama Menu</label>
        <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu ?? '') }}" required>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga ?? '') }}" required>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="makanan" {{ (old('kategori', $menu->kategori ?? '') == 'makanan') ? 'selected' : '' }}>Makanan</option>
            <option value="minuman" {{ (old('kategori', $menu->kategori ?? '') == 'minuman') ? 'selected' : '' }}>Minuman</option>
        </select>
    </div>

    <div class="form-group">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control-file">
        @if(isset($menu) && $menu->gambar)
            <br><img src="{{ asset('storage/' . $menu->gambar) }}" width="100">
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
