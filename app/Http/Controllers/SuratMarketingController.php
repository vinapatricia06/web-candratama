<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMarketing; // Model untuk surat_marketing
use Illuminate\Support\Facades\Storage;

class SuratMarketingController extends Controller
{
    

    public function index()
    {
        $nomorSurat = null; // Awalnya kosong
        $suratMarketings = SuratMarketing::all(); // Ambil semua surat jika diperlukan
        return view('surat.digital_marketing.index', compact('nomorSurat', 'suratMarketings'));
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

            $suratMarketing = SuratMarketing::create([
                'jenis_surat' => $jenis_surat,
                'divisi_pembuat' => $divisi_pembuat,
                'divisi_tujuan' => $divisi_tujuan,
                'file_path' => $filePath,
                'status_pengajuan' => 'Pending',
            ]);

            $id_surat = str_pad($suratMarketing->id, 3, '0', STR_PAD_LEFT);
            $nomorSurat = "{$jenis_surat}/{$id_surat}/{$divisi_pembuat}-{$divisi_tujuan}/{$bulan_romawi}/{$tahun}";

            $suratMarketing->update(['nomor_surat' => $nomorSurat]);

            session(['nomorSurat' => $nomorSurat]);

            return redirect()->route('surat.digital_marketing.list')->with([
                'success' => 'Nomor surat berhasil di-generate!',
                'id_suratMarketing' => $suratMarketing->id,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function list()
    {
        $suratMarketings = SuratMarketing::orderBy('created_at', 'desc')->get();
        return view('surat.digital_marketing.index', compact('suratMarketings'));
    }

    

    public function downloadfile($id)
    {
        $surat = SuratMarketing::findOrFail($id);

        $filePath = public_path('storage/' . $surat->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back()->withErrors('File tidak ditemukan.');
        }
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:Pending,ACC,Tolak',
        ]);

        $surat = SuratMarketing::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        return redirect()->route('surat.digital_marketing.list')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function viewPDF($id)
    {
        $suratMarketing = SuratMarketing::find($id);

        if (!$suratMarketing || !Storage::disk('public')->exists($suratMarketing->file_path)) {
            return redirect()->route('surat.digital_marketing.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.digital_marketing.pdf', compact('suratMarketing'));
    }

    public function edit($id)
    {
        $suratMarketing = SuratMarketing::findOrFail($id);
        return view('surat.digital_marketing.edit', compact('suratMarketing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'divisi_pembuat' => 'required',
            'divisi_tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $suratMarketing = SuratMarketing::findOrFail($id);

        if ($request->hasFile('file_surat')) {
            if ($suratMarketing->file_path && file_exists(storage_path('app/' . $suratMarketing->file_path))) {
                unlink(storage_path('app/' . $suratMarketing->file_path));
            }

            $filePath = $request->file('file_surat')->store('surat_files');
            $suratMarketing->file_path = $filePath;
        }

        $suratMarketing->jenis_surat = $request->jenis_surat;
        $suratMarketing->divisi_pembuat = $request->divisi_pembuat;
        $suratMarketing->divisi_tujuan = $request->divisi_tujuan;
        $suratMarketing->save();

        return redirect()->route('surat.digital_marketing.list')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratMarketing = SuratMarketing::findOrFail($id);
        $suratMarketing->delete();

        return redirect()->route('surat.digital_marketing.list')->with('success', 'Surat berhasil dihapus.');
    }

    public function create()
    {
        return view('surat.digital_marketing.create');
    }
}
