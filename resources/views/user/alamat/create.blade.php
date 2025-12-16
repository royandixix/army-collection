@extends('user.layouts.app')

@section('title', 'Tambah Alamat')

@section('content')
<div class="container">
    <h3>Tambah Alamat Baru</h3>

    <form action="{{ route('user.alamat.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Label</label>
            <input type="text" name="label" class="form-control" placeholder="Rumah / Kantor" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
        </div>

        <!-- BUTTON LOKASI -->
        <div class="mb-2">
            <button type="button" class="btn btn-outline-primary btn-sm" id="btnLokasi">
                📍 Lokasi Saat Ini
            </button>
        </div>

        <!-- MAP -->
        <div class="mb-3">
            <div id="map" style="height: 300px; border-radius: 10px;"></div>
        </div>

        <!-- HIDDEN -->
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button class="btn btn-primary">Simpan</button>
    </form>
</div>

{{-- LEAFLET --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // MAP DEFAULT
    const map = L.map('map').setView([-6.200000, 106.816666], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let marker;

    function setMarker(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        map.setView([lat, lng], 16);

        // auto isi alamat
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('alamat').value = data.display_name;
                }
            });
    }

    // KLIK MAP
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    // BUTTON LOKASI SAAT INI
    document.getElementById('btnLokasi').onclick = function () {
        if (!navigator.geolocation) {
            alert('GPS tidak didukung browser');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                setMarker(
                    position.coords.latitude,
                    position.coords.longitude
                );
            },
            function() {
                alert('Gagal ambil lokasi. Izinkan GPS.');
            }
        );
    };
</script>
@endsection
