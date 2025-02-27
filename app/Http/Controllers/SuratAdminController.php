<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratAdmin;
use Illuminate\Support\Facades\Storage;

class SuratAdminController extends Controller
{
    public function index()
    {
        $nomorSurat = null;
        $suratAdmins = SuratAdmin::orderBy('created_at', 'desc')->get();
        
        return view('surat.admin.index', compact('nomorSurat', 'suratAdmins'));
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

            $suratAdmin = SuratAdmin::create([
                'jenis_surat' => $jenis_surat,
                'divisi_pembuat' => $divisi_pembuat,
                'divisi_tujuan' => $divisi_tujuan,
                'file_path' => $filePath,
                'status_pengajuan' => 'Pending',
            ]);

            $id_surat = str_pad($suratAdmin->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "{$jenis_surat}/{$id_surat}/{$divisi_pembuat}-{$divisi_tujuan}/{$bulan_romawi}/{$tahun}";

            $suratAdmin->update(['nomor_surat' => $nomorSurat]);

            session(['nomorSurat' => $nomorSurat]);

            return redirect()->route('surat.admin.index')->with('success', 'Nomor surat berhasil di-generate!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadfile($id)
    {
        $surat = SuratAdmin::findOrFail($id);
        $filePath = public_path('storage/' . $surat->file_path);
        if (file_exists($filePath)) {
            $fileName = "SuratAdmin_{$surat->id}_{$surat->jenis_surat}.pdf";
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

        $surat = SuratAdmin::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        return redirect()->route('surat.admin.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function viewPDF($id)
    {
        $suratAdmin = SuratAdmin::find($id);

        if (!$suratAdmin || !Storage::disk('public')->exists($suratAdmin->file_path)) {
            return redirect()->route('surat.admin.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.admin.pdf', compact('suratAdmin'));
    }

    public function edit($id)
    {
        $suratAdmin = SuratAdmin::findOrFail($id);
        return view('surat.admin.edit', compact('suratAdmin'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $suratAdmin = SuratAdmin::findOrFail($id);

        if ($request->hasFile('file_surat')) {
            if ($suratAdmin->file_path && file_exists(storage_path('app/' . $suratAdmin->file_path))) {
                unlink(storage_path('app/' . $suratAdmin->file_path));
            }

            $filePath = $request->file('file_surat')->store('surat_files');
            $suratAdmin->file_path = $filePath;
        }

        $suratAdmin->jenis_surat = $request->jenis_surat;
        $suratAdmin->divisi_pembuat = $request->divisi_pembuat;
        $suratAdmin->divisi_tujuan = $request->divisi_tujuan;
        $suratAdmin->save();

        return redirect()->route('surat.admin.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratAdmin = SuratAdmin::findOrFail($id);
        $suratAdmin->delete();

        return redirect()->route('surat.admin.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function create()
    {
        return view('surat.admin.create');
    }

    public function dashboard()
    {
        $pending = SuratAdmin::where('status_pengajuan', 'Pending')->count();
        $acc = SuratAdmin::where('status_pengajuan', 'ACC')->count();
        $tolak = SuratAdmin::where('status_pengajuan', 'Tolak')->count();

        $divisi_pembuat = SuratAdmin::distinct()->pluck('divisi_pembuat');

        // Menghitung surat yang divisi tujuannya ke Finance
        $suratKeAdmin = SuratAdmin::where('divisi_tujuan', 'ADM')->where('status_pengajuan', 'Pending')->count();

        // Menyimpan informasi surat ke Finance di sesi jika ada
        if ($suratKeAdmin > 0) {
            session(['suratKeFinance' => $suratKeAdmin]);
        }

        $monthlyCounts = SuratAdmin::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('count', 'month');

        return view('surat.finance.dashboard', [
            'pending' => $pending,
            'acc' => $acc,
            'tolak' => $tolak,
            'months' => $monthlyCounts->keys(),
            'monthlyCounts' => $monthlyCounts->values(),
            'suratKeFinance' => $suratKeAdmin,
            'divisi_pembuat' => $divisi_pembuat // Pastikan variabel ini dikirimkan ke view
        ]);
    }

}
