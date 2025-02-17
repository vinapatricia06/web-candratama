<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgressProject;
use App\Models\User1;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgressProjectController extends Controller
{
    public function index() {
        $projects = ProgressProject::with('teknisi')->get();
        return view('progress_projects.index', compact('projects'));
    }

    public function create() {
        $teknisiList = User1::where('role', 'teknisi')->get();
        return view('progress_projects.create', compact('teknisiList'));
    }

    public function store(Request $request) {
        $request->validate([
            'teknisi_id' => 'required|exists:users1,id_user',
            'klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'tanggal_setting' => 'required|date',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Waiting List,Selesai',
        ]);

        $data = $request->except(['dokumentasi']);

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['dokumentasi'] = 'image/' . $filename;
        }

        ProgressProject::create($data);

        return redirect()->route('progress_projects.index')
                         ->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit($id) {
        $progress_project = ProgressProject::findOrFail($id);
        $teknisiList = User1::where('role', 'teknisi')->get();
        return view('progress_projects.edit', compact('progress_project', 'teknisiList'));
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'teknisi_id' => 'required|exists:users1,id_user',
            'klien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'project' => 'required|string|max:255',
            'tanggal_setting' => 'required|date',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Waiting List,Selesai',
        ]);
    
        $progress_project = ProgressProject::findOrFail($id);
        $data = $request->except(['dokumentasi']);

        if ($request->hasFile('dokumentasi')) {
            if ($progress_project->dokumentasi && File::exists(public_path($progress_project->dokumentasi))) {
                File::delete(public_path($progress_project->dokumentasi));
            }

            $file = $request->file('dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image'), $filename);
            $data['dokumentasi'] = 'image/' . $filename;
        }

        $progress_project->update($data);
    
        return redirect()->route('progress_projects.index')
                         ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($id) {
        $progress_project = ProgressProject::findOrFail($id);
        if ($progress_project->dokumentasi && File::exists(public_path($progress_project->dokumentasi))) {
            File::delete(public_path($progress_project->dokumentasi));
        }

        $progress_project->delete();

        return redirect()->route('progress_projects.index')
                         ->with('success', 'Project berhasil dihapus.');
    }

    public function downloadPdf()
{
    // Mengambil data project
    $projects = ProgressProject::with('teknisi')->get();

    // Load view untuk PDF
    $pdf = PDF::loadView('progress_projects.pdf', compact('projects'));

    $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

    // Download file PDF
    return $pdf->download('progress_projects.pdf');
}
}
