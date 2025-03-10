<?php
namespace App\Http\Controllers;

use App\Models\SuratInteriorConsultan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratInteriorConsultanController extends Controller
{
    public function index()
    {
        $surats = SuratInteriorConsultan::all();
        return view('surat.interior_consultan.index', compact('surats'));
    }

    public function create()
    {
        $divisi = Auth::user()->role;
        $nama = Auth::user()->nama;
        return view('surat.interior_consultan.create', compact('nama', 'divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_konsultasi_interior_files', 'public');
        }

        SuratInteriorConsultan::create([
            'nama' => Auth::user()->nama,
            'divisi' => Auth::user()->role,
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
            'status_pengajuan' => 'Pending',
        ]);

        return redirect()->route('surat.interior_consultan.index')->with('success', 'Surat konsultasi interior berhasil dibuat');
    }

    public function edit($id)
    {
        $surat = SuratInteriorConsultan::findOrFail($id);
        return view('surat.interior_consultan.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $surat = SuratInteriorConsultan::findOrFail($id);
        $filePath = $surat->file_path;

        if ($request->hasFile('file_surat')) {
            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }
            $filePath = $request->file('file_surat')->store('surat_konsultasi_interior_files', 'public');
        }

        $surat->update([
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
        ]);

        return redirect()->route('surat.interior_consultan.index')->with('success', 'Surat konsultasi interior berhasil diperbarui');
    }

    public function destroy($id)
    {
        $surat = SuratInteriorConsultan::findOrFail($id);
        if ($surat->file_path && Storage::exists('public/' . $surat->file_path)) {
            Storage::delete('public/' . $surat->file_path);
        }
        $surat->delete();

        return redirect()->route('surat.interior_consultan.index')->with('success', 'Surat konsultasi interior berhasil dihapus');
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:Pending,ACC,Tolak',
        ]);

        $surat = SuratInteriorConsultan::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        return redirect()->route('surat.interior_consultan.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function downloadfile($id)
    {
        $surat = SuratInteriorConsultan::findOrFail($id);
        $filePath = public_path('storage/' . $surat->file_path);
        if (file_exists($filePath)) {
            $fileName = "SuratKonsultasiInterior_{$surat->id}.pdf";
            return response()->download($filePath, $fileName);
        } else {
            return redirect()->back()->withErrors('File tidak ditemukan.');
        }
    }

    public function viewPDF($id)
    {
        $suratKonsultasiInterior = SuratInteriorConsultan::find($id);

        if (!$suratKonsultasiInterior || !Storage::disk('public')->exists($suratKonsultasiInterior->file_path)) {
            return redirect()->route('surat.interior_consultan.index')->withErrors('File tidak ditemukan.');
        }

        return view('surat.interior_consultan.pdf', compact('suratKonsultasiInterior'));
    }
}
