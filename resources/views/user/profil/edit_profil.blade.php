@extends('user.layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Profil
        </h3>
        <p class="text-muted">Perbarui informasi profil Anda</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient text-white text-center py-4 rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-gear me-2"></i>Pengaturan Profil
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- FOTO --}}
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="previewImg"
                                     src="{{ $user->img ? asset('storage/'.$user->img) : asset('img/default-user.png') }}"
                                     class="rounded-circle border border-4 border-light shadow-lg"
                                     width="150" height="150" style="object-fit:cover">
                                <label for="photo" class="upload-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                            </div>
                            <p class="text-muted small mt-2">Klik ikon kamera untuk ganti foto</p>
                        </div>

                        {{-- NAMA --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name"
                                   class="form-control"
                                   value="{{ old('name',$user->username) }}" required>
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control"
                                   value="{{ old('email',$user->email) }}" required>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" id="alamat"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Klik map atau gunakan lokasi saat ini"
                                      required>{{ old('alamat',$user->alamat) }}</textarea>

                            <input type="hidden" name="latitude" id="latitude" value="{{ $user->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $user->longitude }}">

                            <button type="button"
                                    id="btnLokasi"
                                    class="btn btn-outline-primary btn-sm mt-2">
                                📍 Gunakan lokasi saat ini
                            </button>

                            <div id="map" class="mt-3 rounded shadow-sm" style="height:300px;"></div>
                        </div>

                        {{-- FOTO UPLOAD --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Profil</label>
                            <input type="file" name="photo"
                                   id="photo"
                                   class="form-control"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.profil') }}" class="btn btn-outline-secondary">Batal</a>
                            <button class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
.bg-gradient{background:linear-gradient(135deg,#2c5364,#0f2027);}
.upload-overlay{
    position:absolute;bottom:5px;right:5px;width:45px;height:45px;
    background:linear-gradient(135deg,#2c5364,#0f2027);
    border-radius:50%;display:flex;align-items:center;justify-content:center;
    cursor:pointer;color:white;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ===================
// PREVIEW FOTO
// ===================
function previewImage(e){
    const reader = new FileReader();
    reader.onload = () => previewImg.src = reader.result;
    reader.readAsDataURL(e.target.files[0]);
}

// ===================
// INIT MAP
// ===================
const latInput = document.getElementById('latitude');
const lonInput = document.getElementById('longitude');
const alamatInput = document.getElementById('alamat');

let lat = latInput.value || -6.200000;
let lon = lonInput.value || 106.816666;

const map = L.map('map').setView([lat, lon], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = L.marker([lat, lon]).addTo(map);

// ===================
// KLIK MAP
// ===================
map.on('click', async (e) => {
    lat = e.latlng.lat;
    lon = e.latlng.lng;

    marker.setLatLng([lat, lon]);
    latInput.value = lat;
    lonInput.value = lon;

    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
    const data = await res.json();
    alamatInput.value = data.display_name || '';
});

// ===================
// LOKASI SAAT INI
// ===================
document.getElementById('btnLokasi').onclick = () => {
    if(!navigator.geolocation){
        alert('GPS tidak didukung');
        return;
    }

    navigator.geolocation.getCurrentPosition(async (pos)=>{
        lat = pos.coords.latitude;
        lon = pos.coords.longitude;

        latInput.value = lat;
        lonInput.value = lon;

        marker.setLatLng([lat, lon]);
        map.setView([lat, lon], 17);

        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
        const data = await res.json();
        alamatInput.value = data.display_name || '';
    },()=>{
        alert('Gagal mengambil lokasi');
    });
};
</script>
@endpush
