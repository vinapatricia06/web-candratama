<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratEkspedisi;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class SuratEkspedisiController extends Controller
{
    // Menampilkan data
    public function index()
    {
        $surats = SuratEkspedisi::all();
        return view('surat.ekspedisi.index', compact('surats'));
    }

    // Form untuk membuat surat baru
    public function create()
    {
        $divisi = Auth::user()->role;  // Mengambil divisi dari role user yang login
        $nama = Auth::user()->nama;    // Mengambil nama dari user yang login
        return view('surat.ekspedisi.create', compact('nama', 'divisi'));
    }

    // Menyimpan data surat
    public function store(Request $request)
    {
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_ekspedisi_files', 'public');
        }

        SuratEkspedisi::create([
            'nama' => Auth::user()->nama,
            'divisi' => Auth::user()->role,
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
            'status_pengajuan' => 'Pending',
        ]);

        return redirect()->route('surat.ekspedisi.index')->with('success', 'Surat ekspedisi berhasil dibuat');
    }

    // Form untuk mengedit surat
    public function edit($id)
    {
        $surat = SuratEkspedisi::findOrFail($id);
        return view('surat.ekspedisi.edit', compact('surat'));
    }

    // Update data surat
    public function update(Request $request, $id)
    {
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $surat = SuratEkspedisi::findOrFail($id);
        $filePath = $surat->file_path;

        if ($request->hasFile('file_surat')) {
            // Menghapus file lama jika ada
            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }
            $filePath = $request->file('file_surat')->store('surat.ekspedisi_files', 'public');
        }

        $surat->update([
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
        ]);

        return redirect()->route('surat.ekspedisi.index')->with('success', 'Surat ekspedisi berhasil diperbarui');
    }

    // Menghapus surat
    public function destroy($id)
    {
        $surat = SuratEkspedisi::findOrFail($id);
        if ($surat->file_path && Storage::exists('public/' . $surat->file_path)) {
            Storage::delete('public/' . $surat->file_path);
        }
        $surat->delete();

        return redirect()->route('surat.ekspedisi.index')->with('success', 'Surat ekspedisi berhasil dihapus');
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:Pending,ACC,Tolak',
        ]);

        $surat = SuratEkspedisi::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();
        if (auth()->user()->role === 'ekspedisi') {
            return abort(403, 'Anda tidak diizinkan untuk mengubah status pengajuan ini.');
        }

        return redirect()->route('surat.ekspedisi.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function downloadfile($id)
    {
        $surat = SuratEkspedisi::findOrFail($id);
        $filePath = public_path('storage/' . $surat->file_path);
        if (file_exists($filePath)) {
            $fileName = "SuratEkspedisi_{$surat->id}_{$surat->jenis_surat}.pdf";
            return response()->download($filePath, $fileName);
        } else {
            return redirect()->back()->withErrors('File tidak ditemukan.');
        }
    }

    public function viewPDF($id)
    {
        $suratEkspedisi = SuratEkspedisi::find($id);

        if (!$suratEkspedisi || !Storage::disk('public')->exists($suratEkspedisi->file_path)) {
            return redirect()->route('surat.ekspedisi.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.ekspedisi.pdf', compact('suratEkspedisi'));
    }
}
