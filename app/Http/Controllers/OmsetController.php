<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OmsetExport; // Pastikan untuk mengimport OmsetExport

class OmsetController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter pencarian nama klien dan bulan
        $search = $request->get('search');
        $bulan = $request->get('bulan');

        // Mulai query untuk mengambil data omset
        $query = Omset::query();

        // Jika ada pencarian berdasarkan nama klien
        if ($search) {
            $query->where('nama_klien', 'like', '%' . $search . '%');
        }

        // Jika ada filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal', $bulan); // Filter berdasarkan bulan
        }

        // Ambil data omset yang sudah difilter dan urutkan berdasarkan id_omset secara ascending
        $omsets = $query->orderBy('id_omset', 'asc')->get(); // Urutkan berdasarkan id_omset dari terkecil ke terbesar

        return view('omsets.index', compact('omsets'));
    }

    public function create()
    {
        return view('omsets.create');
    }

    public function store(Request $request)
    {
        // Validasi input untuk memastikan nominal adalah angka yang valid
        $request->validate([
            'tanggal' => 'required|date',
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'sumber_lead' => 'required|string|max:255', // Validasi sumber_lead
            'nominal' => 'required|numeric', // Validasi untuk nominal sebagai angka
        ]);

        // Menyimpan data omset termasuk nominal yang sudah divalidasi
        Omset::create($request->all());

        return redirect()->route('omsets.index')->with('success', 'Data omset berhasil ditambahkan!');
    }

    public function edit(Omset $omset)
    {
        return view('omsets.edit', compact('omset'));
    }

    public function update(Request $request, Omset $omset)
    {
        // Validasi input untuk memastikan nominal adalah angka yang valid
        $request->validate([
            'tanggal' => 'required|date',
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'sumber_lead' => 'required|string|max:255', // Validasi sumber_lead
            'nominal' => 'required|numeric', // Validasi untuk nominal sebagai angka
        ]);

        // Memperbarui data omset termasuk nominal yang sudah divalidasi
        $omset->update($request->all());

        return redirect()->route('omsets.index')->with('success', 'Data omset berhasil diperbarui!');
    }

    public function destroy(Omset $omset)
    {
        $omset->delete();
        return redirect()->route('omsets.index')->with('success', 'Data omset berhasil dihapus!');
    }

    public function rekapBulanan()
    {
        $rekap = Omset::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(nominal) as total_omset')
            ->groupBy('tahun', 'bulan')
            ->orderByDesc('tahun')
            ->orderBy('bulan')
            ->get();

        // Data untuk tabel dan grafik
        $data = [];
        $totals = []; // Untuk menyimpan total tahunan
        $labels = []; // Label tahun untuk grafik
        $totalPerTahun = []; // Data total omset untuk grafik

        foreach ($rekap as $item) {
            $tahun = $item->tahun;
            $bulan = $item->bulan;

            if (!isset($data[$tahun])) {
                $data[$tahun] = array_fill(1, 12, 0); // Default 0 tiap bulan
                $totals[$tahun] = 0; // Inisialisasi total tahunan
            }

            $data[$tahun][$bulan] = $item->total_omset;
            $totals[$tahun] += $item->total_omset;
        }

        // Siapkan data untuk grafik
        foreach ($totals as $tahun => $total) {
            $labels[] = $tahun;
            $totalPerTahun[] = $total;
        }

        return view('omsets.rekap', compact('data', 'totals', 'labels', 'totalPerTahun'));
    }

    // Fungsi untuk download Excel
    public function exportToExcel(Request $request)
    {
        $search = $request->get('search');
        $bulan = $request->get('bulan');

        // Mulai query untuk mengambil data omset
        $query = Omset::query();

        // Jika ada pencarian berdasarkan nama klien
        if ($search) {
            $query->where('nama_klien', 'like', '%' . $search . '%');
        }

        // Jika ada filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal', $bulan); // Filter berdasarkan bulan
        }

        // Ambil data omset yang sudah difilter
        $omsets = $query->get();

        // Export data omsets ke Excel
        return Excel::download(new OmsetExport($omsets), 'omsets.xlsx');
    }
}
