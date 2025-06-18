<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage; // Untuk upload file

class ParafController extends Controller
{
    /**
     * Menampilkan halaman Paraf.
     * Akan menampilkan pesan jika belum ada dokumen atau daftar dokumen yang sudah diupload.
     */
    public function index()
    {
        $faker = Faker::create('id_ID');

        // Ini akan mensimulasikan apakah sudah ada dokumen atau belum.
        // Untuk testing, bisa diubah ke true/false.
        // Di aplikasi nyata, ini akan dicek dari database.
        $hasDocuments = true; // Ganti ke false untuk melihat tampilan "Belum ada dokumen"

        $documents = [];
        if ($hasDocuments) {
            for ($i = 1; $i <= 5; $i++) { // Contoh 5 dokumen dummy
                $documents[] = [
                    'id' => $i,
                    'file_name' => $faker->word() . '_document_' . $faker->randomNumber(3) . '.pdf',
                    'uploaded_at' => $faker->dateTimeBetween('-1 month', 'now')->format('d M Y'),
                    'status' => $faker->randomElement(['Menunggu Paraf', 'Sudah Diparaf', 'Ditolak']),
                    'uploaded_by' => $faker->name(),
                ];
            }
        }

        // Mendapatkan display name role untuk sidebar jika diperlukan
        $userRole = null;
        $roleDisplayName = 'Pengguna';
        if (\Auth::check()) {
            $userRole = \Auth::user()->role;
            $loginController = new LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }

        return view('paraf.index', compact('hasDocuments', 'documents', 'userRole', 'roleDisplayName'));
    }

    /**
     * Memproses upload dokumen untuk diparaf.
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ], [
            'document_file.required' => 'Dokumen wajib diunggah.',
            'document_file.file' => 'Input harus berupa file.',
            'document_file.mimes' => 'Format file harus PDF, DOC, atau DOCX.',
            'document_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Simpan file ke storage
        $filePath = $request->file('document_file')->store('paraf_documents', 'public');
        
        // Simulasikan penyimpanan data dokumen ke database di sini
        // Misalnya: Document::create(['user_id' => Auth::id(), 'file_path' => $filePath, 'status' => 'Menunggu Paraf']);

        return redirect()->route('paraf.index')->with('success', 'Dokumen berhasil diunggah dan siap untuk diparaf!');
    }

    /**
     * Mengubah status paraf (dummy, nanti dari DB).
     */
    public function updateParafStatus(Request $request, $id)
    {
        // Logika update status dokumen di database
        // return redirect()->back()->with('success', 'Status dokumen berhasil diperbarui!');
    }
}