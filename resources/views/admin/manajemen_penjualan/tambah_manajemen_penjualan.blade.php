@extends('admin.layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="cx-main-content">
    <div class="cx-page-title mb-4">
        <h4>Tambah Penjualan</h4>
    </div>

    <div class="col-md-12">
        <div class="cx-card">
            <div class="cx-card-content card-default">
                <form id="penjualan-form"
                      action="{{ route('admin.manajemen.manajemen_penjualan_store_manual') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ===================== --}}
                    {{-- 🔹 Data Pelanggan --}}
                    {{-- ===================== --}}
                    <h5 class="mb-3">Data Pelanggan</h5>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama pelanggan" required>
                        @error('nama')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pelanggan</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email pelanggan" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 🔸 Nomor HP --}}
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 🔸 Alamat --}}
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Alamat pelanggan"></textarea>
                        @error('alamat')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 🔸 Foto Profil --}}
                    <div class="mb-3">
                        <label for="foto_profil" class="form-label">Foto Profil Pelanggan</label>
                        <input type="file" name="foto_profil" id="foto_profil" class="form-control" accept="image/*">
                        <div class="mt-2" id="preview-foto-container" style="display:none;">
                            <img id="preview-foto" src="" alt="Preview Foto Profil"
                                 width="100" class="rounded shadow-sm border" style="object-fit: cover;">
                        </div>
                        @error('foto_profil')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="my-4">

                    {{-- ===================== --}}
                    {{-- 🔹 Daftar Produk --}}
                    {{-- ===================== --}}
                    <h5 class="mb-3">Data Penjualan</h5>

                    <div class="mb-3">
                        <label class="form-label">Pilih Produk</label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pilih</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produks as $produk)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="produk_id[]" value="{{ $produk->id }}" class="form-check-input pilih-produk">
                                            </td>
                                            <td>
                                                <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/no-image.png') }}"
                                                     width="70" height="70" class="rounded shadow-sm" style="object-fit: cover;">
                                            </td>
                                            <td>{{ $produk->nama }}</td>
                                            <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                            <td>
                                                <input type="number" name="jumlah[{{ $produk->id }}]"
                                                       class="form-control form-control-sm jumlah-input"
                                                       value="1" min="1" disabled>
                                            </td>
                                            <td class="subtotal" data-harga="{{ $produk->harga }}">
                                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ===================== --}}
                    {{-- 🔹 Total --}}
                    {{-- ===================== --}}
                    <div class="mb-3 text-end">
                        <label class="fw-bold fs-5 me-2">Total:</label>
                        <span id="grandTotal" class="fw-bold text-primary fs-5">Rp 0</span>
                    </div>

                    {{-- ===================== --}}
                    {{-- 🔹 Status & Pembayaran --}}
                    {{-- ===================== --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="lunas">Lunas</option>
                            <option value="batal">Batal</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                            <option value="cod">COD</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                        @error('metode_pembayaran')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Upload Bukti Pembayaran --}}
                    <div class="mb-3">
                        <label for="bukti_tf" class="form-label">Bukti Pembayaran</label>
                        <input type="file" name="bukti_tf" id="bukti_tf" class="form-control" accept="image/*">
                        <div class="mt-2" id="preview-container" style="display:none;">
                            <img id="preview-img" src="" alt="Preview" width="150"
                                 class="rounded shadow-sm border" style="object-fit: cover;">
                        </div>
                        @error('bukti_tf')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ===================== --}}
                    {{-- 🔹 Tombol Aksi --}}
                    {{-- ===================== --}}
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                        <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elemen
    const buktiInput = document.getElementById('bukti_tf');
    const previewContainer = document.getElementById('preview-container');
    const previewImg = document.getElementById('preview-img');
    const checkboxes = document.querySelectorAll('.pilih-produk');
    const totalText = document.getElementById('grandTotal');

    // ========================
    // 🔸 Preview Bukti Transfer
    // ========================
    buktiInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            previewImg.src = '';
        }
    });

    // ========================
    // 🔸 Update Total Dinamis
    // ========================
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.pilih-produk:checked').forEach(chk => {
            const tr = chk.closest('tr');
            const harga = parseInt(tr.querySelector('.subtotal').dataset.harga);
            const jumlah = parseInt(tr.querySelector('.jumlah-input').value) || 1;
            total += harga * jumlah;
        });
        totalText.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function () {
            const tr = this.closest('tr');
            const input = tr.querySelector('.jumlah-input');
            input.disabled = !this.checked;
            updateTotal();
        });
    });

    document.querySelectorAll('.jumlah-input').forEach(input => {
        input.addEventListener('input', updateTotal);
    });
});


// ========================
// 🔸 Preview Foto Profil
// ========================
const fotoInput = document.getElementById('foto_profil');
const previewFotoContainer = document.getElementById('preview-foto-container');
const previewFoto = document.getElementById('preview-foto');

if (fotoInput) {
    fotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewFoto.src = e.target.result;
                previewFotoContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewFotoContainer.style.display = 'none';
            previewFoto.src = '';
        }
    });
}
</script>
@endpush
