<?php
namespace App\Http\Controllers;

use App\Models\SuratCleaning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratCleaningController extends Controller
{
    // Menampilkan data surat cleaning
    public function index()
    {
        // Retrieve all surat cleaning records
        $surats = SuratCleaning::all();
        return view('surat.cleaning.index', compact('surats'));
    }

    // Form untuk membuat surat baru
    public function create()
    {
        // Passing the authenticated user's role and name to the view
        $divisi = Auth::user()->role;
        $nama = Auth::user()->nama;
        return view('surat.cleaning.create', compact('nama', 'divisi'));
    }

    // Menyimpan data surat
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('surat_cleaning_files', 'public');
        }

        // Create a new SuratCleaning record
        SuratCleaning::create([
            'nama' => Auth::user()->nama,
            'divisi' => Auth::user()->role,
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
            'status_pengajuan' => 'Pending',
        ]);

        // Redirect to index page with success message
        return redirect()->route('surat.cleaning.index')->with('success', 'Surat cleaning berhasil dibuat');
    }

    // Form untuk mengedit surat
    public function edit($id)
    {
        // Find the SuratCleaning by its ID
        $surat = SuratCleaning::findOrFail($id);
        return view('surat.cleaning.edit', compact('surat'));
    }

    // Update data surat
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'keperluan' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Find the SuratCleaning record by its ID
        $surat = SuratCleaning::findOrFail($id);
        $filePath = $surat->file_path;

        // If a new file is uploaded, delete the old one and update the file path
        if ($request->hasFile('file_surat')) {
            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }
            $filePath = $request->file('file_surat')->store('surat_cleaning_files', 'public');
        }

        // Update the SuratCleaning record
        $surat->update([
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
        ]);

        // Redirect to index page with success message
        return redirect()->route('surat.cleaning.index')->with('success', 'Surat cleaning berhasil diperbarui');
    }

    // Menghapus surat
    public function destroy($id)
    {
        // Find the SuratCleaning by its ID
        $surat = SuratCleaning::findOrFail($id);
        
        // If the surat has a file, delete it from the storage
        if ($surat->file_path && Storage::exists('public/' . $surat->file_path)) {
            Storage::delete('public/' . $surat->file_path);
        }

        // Delete the SuratCleaning record
        $surat->delete();

        // Redirect to index page with success message
        return redirect()->route('surat.cleaning.index')->with('success', 'Surat cleaning berhasil dihapus');
    }

    // Update status pengajuan
    public function updateStatus(Request $request, $id)
    {
        // Validate the status
        $request->validate([
            'status_pengajuan' => 'required|in:Pending,ACC,Tolak',
        ]);

        // Find the SuratCleaning by its ID and update the status
        $surat = SuratCleaning::findOrFail($id);
        $surat->status_pengajuan = $request->status_pengajuan;
        $surat->save();

        if (auth()->user()->role === 'cleaning_services') {
            return abort(403, 'Anda tidak diizinkan untuk mengubah status pengajuan ini.');
        }
        // Redirect back with a success message
        return redirect()->route('surat.cleaning.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    // Download file surat
    public function download($id)
    {
        // Find the SuratCleaning by its ID
        $surat = SuratCleaning::findOrFail($id);
        $filePath = public_path('storage/' . $surat->file_path);

        // Check if the file exists and return the file for download
        if (file_exists($filePath)) {
            return response()->download($filePath, "SuratCleaning_{$surat->id}.pdf");
        } else {
            return redirect()->back()->withErrors('File tidak ditemukan.');
        }
    }

    // View file surat (PDF)
    public function viewPDF($id)
    {
        // Find the SuratCleaning by its ID
        $suratCleaning = SuratCleaning::find($id);

        // Check if the file exists in storage
        if (!$suratCleaning || !Storage::disk('public')->exists($suratCleaning->file_path)) {
            return redirect()->route('surat.cleaning.index')->withErrors('File tidak ditemukan.');
        }

        // Return the view for displaying the PDF
        return view('surat.cleaning.pdf', compact('suratCleaning'));
    }
}
