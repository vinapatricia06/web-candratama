<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratPurchasing;
use Illuminate\Support\Facades\Storage;

class SuratPurchasingController extends Controller
{
    public function index()
    {
        $nomorSurat = null;
        $suratPurchasing = SuratPurchasing::orderBy('created_at', 'desc')->get();
        
        return view('surat.purchasing.index', compact('nomorSurat', 'suratPurchasing'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            $jenis_surat = strtoupper($request->jenis_surat);
            $divisi_pembuat = strtoupper($request->divisi_pembuat);
            $divisi_tujuan = strtoupper($request->divisi_tujuan);

            $bulan = date('n');
            $tahun = date('Y');
            $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulan_romawi = $romawi[$bulan];

            $filePath = $request->hasFile('file_surat')
                ? $request->file('file_surat')->store('uploads', 'public')
                : 'uploads/default.pdf';

            $suratPurchasing = SuratPurchasing::create([
                'jenis_surat' => $jenis_surat,
                'divisi_pembuat' => $divisi_pembuat,
                'divisi_tujuan' => $divisi_tujuan,
                'file_path' => $filePath,
                'status_pengajuan' => 'Pending',
            ]);

            $id_surat = str_pad($suratPurchasing->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "{$jenis_surat}/{$id_surat}/{$divisi_pembuat}-{$divisi_tujuan}/{$bulan_romawi}/{$tahun}";

            $suratPurchasing->update(['nomor_surat' => $nomorSurat]);

            session(['nomorSurat' => $nomorSurat]);

            return redirect()->route('surat.purchasing.index')->with('success', 'Nomor surat berhasil di-generate!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadfile($id)
    {
        $surat = SuratPurchasing::findOrFail($id);
        $filePath = public_path('storage/' . $surat->file_path);
        if (file_exists($filePath)) {
            $fileName = "SuratPurchasing_{$surat->id}_{$surat->jenis_surat}.pdf";
            return response()->download($filePath, $fileName);
        } else {
            return redirect()->back()->withErrors('File tidak ditemukan.');
        }
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:Pending,ACC,Tolak',
        ]);

        $surat = SuratPurchasing::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        return redirect()->route('surat.purchasing.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function viewPDF($id)
    {
        $suratPurchasing = SuratPurchasing::find($id);

        if (!$suratPurchasing || !Storage::disk('public')->exists($suratPurchasing->file_path)) {
            return redirect()->route('surat.purchasing.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.purchasing.pdf', compact('suratPurchasing'));
    }

    public function edit($id)
    {
        $suratPurchasing = SuratPurchasing::findOrFail($id);
        return view('surat.purchasing.edit', compact('suratPurchasing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $suratPurchasing = SuratPurchasing::findOrFail($id);

        if ($request->hasFile('file_surat')) {
            if ($suratPurchasing->file_path && file_exists(storage_path('app/' . $suratPurchasing->file_path))) {
                unlink(storage_path('app/' . $suratPurchasing->file_path));
            }

            $filePath = $request->file('file_surat')->store('surat_files');
            $suratPurchasing->file_path = $filePath;
        }

        $suratPurchasing->jenis_surat = $request->jenis_surat;
        $suratPurchasing->divisi_pembuat = $request->divisi_pembuat;
        $suratPurchasing->divisi_tujuan = $request->divisi_tujuan;
        $suratPurchasing->save();

        return redirect()->route('surat.purchasing.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratPurchasing = SuratPurchasing::findOrFail($id);
        $suratPurchasing->delete();

        return redirect()->route('surat.purchasing.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function create()
    {
        return view('surat.purchasing.create');
    }

    public function dashboard()
    {
        $pending = SuratPurchasing::where('status_pengajuan', 'Pending')->count();
        $acc = SuratPurchasing::where('status_pengajuan', 'ACC')->count();
        $tolak = SuratPurchasing::where('status_pengajuan', 'Tolak')->count();

        $divisi_pembuat = SuratPurchasing::distinct()->pluck('divisi_pembuat');

        // Menghitung surat yang divisi tujuannya ke Finance
        $suratKePCH = SuratPurchasing::where('divisi_tujuan', 'WRH')->where('status_pengajuan', 'Pending')->count();

        // Menyimpan informasi surat ke Finance di sesi jika ada
        if ($suratKePCH > 0) {
            session(['suratKePCH' => $suratKePCH]);
        }

        $monthlyCounts = SuratPurchasing::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        return view('surat.purchasing.dashboard', [
            'pending' => $pending,
            'acc' => $acc,
            'tolak' => $tolak,
            'months' => $monthlyCounts->keys(),
            'monthlyCounts' => $monthlyCounts->values(),
            'suratKePCH' => $suratKePCH,
            'divisi_pembuat' => $divisi_pembuat // Pastikan variabel ini dikirimkan ke view
        ]);
    }

}
