<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    public function index() {
        // Menampilkan semua data maintenance
        $maintenances = Maintenance::all();
        return view('maintenances.index', compact('maintenances'));
    }

    public function create() {
        // Menampilkan form untuk menambah data maintenance
        return view('maintenances.create');
    }

    public function store(Request $request) {
        // Validasi inputan
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'tanggal_setting' => 'required|date',
            'tanggal_serah_terima' => 'nullable|date', // Menghapus 'required' dan hanya menggunakan 'nullable|date'
            'maintenance' => 'required|string',
            'status' => 'required|in:Waiting List,Selesai',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Menyimpan data maintenance
        $data = $request->except(['dokumentasi']);

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/dokumentasi'), $filename);
            $data['dokumentasi'] = 'storage/dokumentasi/' . $filename; // Pastikan path benar
        }

        Maintenance::create($data);

        return redirect()->route('maintenances.index')
                         ->with('success', 'Data Maintenance berhasil ditambahkan.');
    }

    public function edit($id) {
        // Menampilkan form untuk edit data maintenance
        $maintenance = Maintenance::findOrFail($id);
        return view('maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, $id) {
        // Validasi inputan
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'tanggal_setting' => 'required|date',
            'tanggal_serah_terima' => 'nullable|date', // Menghapus 'required' dan hanya menggunakan 'nullable|date'
            'maintenance' => 'required|string',
            'status' => 'required|in:Waiting List,Selesai',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $data = $request->except(['dokumentasi']);

        if ($request->hasFile('dokumentasi')) {
            // Menghapus file lama jika ada
            if ($maintenance->dokumentasi && File::exists(public_path($maintenance->dokumentasi))) {
                File::delete(public_path($maintenance->dokumentasi));
            }

            // Menyimpan file baru
            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/dokumentasi'), $filename);
            $data['dokumentasi'] = 'storage/dokumentasi/' . $filename;
        }

        $maintenance->update($data);

        return redirect()->route('maintenances.index')
                         ->with('success', 'Data Maintenance berhasil diperbarui.');
    }

    public function destroy($id) {
        // Menghapus data maintenance
        $maintenance = Maintenance::findOrFail($id);
        if ($maintenance->dokumentasi && File::exists(public_path($maintenance->dokumentasi))) {
            File::delete(public_path($maintenance->dokumentasi));
        }

        $maintenance->delete();

        return redirect()->route('maintenances.index')
                         ->with('success', 'Data Maintenance berhasil dihapus.');
    }

    public function downloadPdf()
    {
        // Mengambil data maintenance
        $maintenances = Maintenance::all();

        // Load view untuk PDF
        $pdf = PDF::loadView('maintenances.pdf', compact('maintenances'))
                  ->setPaper('A4', 'landscape')  // Menambahkan orientasi landscape jika dibutuhkan
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'margin-top' => 10,
                      'margin-left' => 10,
                      'margin-right' => 10,
                      'margin-bottom' => 10
                  ]);

        // Download file PDF
        return $pdf->download('maintenances.pdf');
    }
}
