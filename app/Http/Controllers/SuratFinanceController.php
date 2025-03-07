<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMarketing;
use App\Models\SuratFinance;
use App\Models\SuratWarehouse;
use App\Models\SuratPurchasing;
use App\Models\SuratAdmin;
use Illuminate\Support\Facades\Storage;

class SuratFinanceController extends Controller
{
    public function index()
    {
        $nomorSurat = null; // Awalnya kosong
        $suratFinances = SuratFinance::orderBy('created_at', 'desc')->get();
        
        return view('surat.finance.index', compact('nomorSurat', 'suratFinances'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        try {
            $jenis_surat = strtoupper($request->jenis_surat);
            $divisi_pembuat = strtoupper($request->divisi_pembuat);
            $divisi_tujuan = strtoupper($request->divisi_tujuan);

            $bulan = date('n');
            $tahun = date('Y');
            $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulan_romawi = $romawi[$bulan - 1];

            $filePath = $request->hasFile('file_surat')
                ? $request->file('file_surat')->store('uploads', 'public')
                : 'uploads/default.pdf';

            $suratFinance = SuratFinance::create([
                'jenis_surat' => $jenis_surat,
                'divisi_pembuat' => $divisi_pembuat,
                'divisi_tujuan' => $divisi_tujuan,
                'file_path' => $filePath,
                'status_pengajuan' => 'Pending',
            ]);

            $id_surat = str_pad($suratFinance->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "{$jenis_surat}/{$id_surat}/{$divisi_pembuat}-{$divisi_tujuan}/{$bulan_romawi}/{$tahun}";

            $suratFinance->update(['nomor_surat' => $nomorSurat]);

            session(['nomorSurat' => $nomorSurat]);

            return redirect()->route('surat.finance.index')->with([
                'success' => 'Nomor surat berhasil di-generate!',
                'id_suratFinance' => $suratFinance->id,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function list()
    {
        $suratFinances = SuratFinance::orderBy('created_at', 'desc')->get();
        return view('surat.finance.index', compact('suratFinances'));
    }

    public function downloadfile($id)
    {
        $surat = SuratFinance::findOrFail($id);

        $filePath = public_path('storage/' . $surat->file_path);

        // Mengecek apakah file ada
        if (file_exists($filePath)) {
            // Membuat nama file kustom berdasarkan ID surat
            $fileName = "SuratFinance_{$surat->id}_{$surat->jenis_surat}.pdf"; // Sesuaikan ekstensi file jika diperlukan

            // Mengunduh file dengan nama yang sudah diatur
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

        $surat = SuratFinance::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        return redirect()->route('surat.finance.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function viewPDF($id)
    {
        $suratFinance = SuratFinance::find($id);

        if (!$suratFinance || !Storage::disk('public')->exists($suratFinance->file_path)) {
            return redirect()->route('surat.finance.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.finance.pdf', compact('suratFinance'));
    }

    public function edit($id)
    {
        $suratFinance = SuratFinance::findOrFail($id);
        return view('surat.finance.edit', compact('suratFinance'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $suratFinance = SuratFinance::findOrFail($id);

        if ($request->hasFile('file_surat')) {
            if ($suratFinance->file_path && file_exists(storage_path('app/' . $suratFinance->file_path))) {
                unlink(storage_path('app/' . $suratFinance->file_path));
            }

            $filePath = $request->file('file_surat')->store('surat_files');
            $suratFinance->file_path = $filePath;
        }

        $suratFinance->jenis_surat = $request->jenis_surat;
        $suratFinance->divisi_pembuat = $request->divisi_pembuat;
        $suratFinance->divisi_tujuan = $request->divisi_tujuan;
        $suratFinance->save();

        return redirect()->route('surat.finance.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratFinance = SuratFinance::findOrFail($id);
        $suratFinance->delete();

        return redirect()->route('surat.finance.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function create()
    {
        return view('surat.finance.create');
    }

    public function dashboard()
    {
        $pending = SuratFinance::where('status_pengajuan', 'Pending')->count();
        $acc = SuratFinance::where('status_pengajuan', 'ACC')->count();
        $tolak = SuratFinance::where('status_pengajuan', 'Tolak')->count();

        $divisi_pembuat = SuratFinance::distinct()->pluck('divisi_pembuat');

        // Menghitung surat yang divisi tujuannya ke Finance
        $suratKeFinance = SuratFinance::where('divisi_tujuan', 'FNC')->where('status_pengajuan', 'Pending')->count();

        // Menyimpan informasi surat ke Finance di sesi jika ada
        if ($suratKeFinance > 0) {
            session(['suratKeFinance' => $suratKeFinance]);
        }

        // Menghitung surat dari divisi Marketing, Admin, Warehouse, Purchasing yang ditujukan ke Finance
        $suratMarketing = SuratMarketing::where('divisi_tujuan', 'FNC')->where('status_pengajuan', 'Pending')->count();
        $suratAdmin = SuratAdmin::where('divisi_tujuan', 'FNC')->where('status_pengajuan', 'Pending')->count();
        $suratWarehouse = SuratWarehouse::where('divisi_tujuan', 'FNC')->where('status_pengajuan', 'Pending')->count();
        $suratPurchasing = SuratPurchasing::where('divisi_tujuan', 'FNC')->where('status_pengajuan', 'Pending')->count();

        // Menyimpan informasi surat dari divisi lain ke Finance jika ada
        if ($suratMarketing > 0) {
            session(['suratMarketing' => $suratMarketing]);
        }
        if ($suratAdmin > 0) {
            session(['suratAdmin' => $suratAdmin]);
        }
        if ($suratWarehouse > 0) {
            session(['suratWarehouse' => $suratWarehouse]);
        }
        if ($suratPurchasing > 0) {
            session(['suratPurchasing' => $suratPurchasing]);
        }
        

        $monthlyCounts = SuratFinance::selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count")
            ->groupBy('year', 'month')
            ->orderByRaw('year ASC, month ASC')
            ->get();

        return view('surat.finance.dashboard', [
            'pending' => $pending,
            'acc' => $acc,
            'tolak' => $tolak,
            'months' => $monthlyCounts->pluck('month')->toArray(),
            'monthlyCounts' => $monthlyCounts->pluck('count')->toArray(),
            'suratKeFinance' => $suratKeFinance,
            'suratMarketing' => $suratMarketing,
            'suratAdmin' => $suratAdmin,
            'suratWarehouse' => $suratWarehouse,
            'suratPurchasing' => $suratPurchasing,
            'divisi_pembuat' => $divisi_pembuat
        ]);
    }

}
