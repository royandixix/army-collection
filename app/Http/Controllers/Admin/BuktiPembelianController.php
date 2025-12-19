<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuktiPembelian;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuktiPembelianController extends Controller
{
    // Tampilkan semua bukti pembelian
    public function index()
{
    $buktiPembelians = BuktiPembelian::with('pembelian.supplier')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($bukti) {
            // jika relasi pembelian null, buat dummy object
            if (!$bukti->pembelian) {
                $bukti->pembelian = (object)[
                    'id' => '-',
                    'supplier' => (object)['nama' => '-']
                ];
            }
            return $bukti;
        });

    $pembelians = Pembelian::with('supplier')
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('admin.bukti_pembelian.index', compact('buktiPembelians', 'pembelians'));
}


    // Upload bukti pengiriman/faktur (manual admin)
    public function upload(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelians,id',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pembelianId = $request->pembelian_id;

        // Simpan file ke folder storage/app/public/bukti_pengiriman
        $filePath = $request->file('file')->store('bukti_pengiriman', 'public');

        // Jika sudah ada bukti sebelumnya, hapus file lama
        $existingBukti = BuktiPembelian::where('pembelian_id', $pembelianId)->first();
        if ($existingBukti && Storage::disk('public')->exists($existingBukti->file)) {
            Storage::disk('public')->delete($existingBukti->file);
        }

        // Update atau buat baru
        BuktiPembelian::updateOrCreate(
            ['pembelian_id' => $pembelianId],
            [
                'file' => $filePath,
                'status_pengiriman' => 'pending'
            ]
        );

        return redirect()->back()->with('success', 'Bukti pengiriman berhasil diupload.');
    }

    // Update status pengiriman
    public function updateStatus(Request $request, $id)
    {
        $bukti = BuktiPembelian::findOrFail($id);

        $request->validate([
            'status_pengiriman' => 'required|in:pending,dikirim,diterima',
        ]);

        $bukti->status_pengiriman = $request->status_pengiriman;
        $bukti->save();

        return redirect()->route('admin.bukti_pembelian.index')
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    // Download file bukti pengiriman
    public function download($id)
    {
        $bukti = BuktiPembelian::findOrFail($id);
        $filePath = storage_path('app/public/' . $bukti->file);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
