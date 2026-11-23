<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
        
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'SI202'],
            [
                'nama_mk' => 'Analisis dan Perancangan Sistem Informasi',
                'deskripsi' => 'Membahas pemodelan proses bisnis, perancangan basis data, dan arsitektur sistem informasi.',
                'sks' => 3,
                'semester' => 'Genap 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'SI304'],
            [
                'nama_mk' => 'Interaksi Manusia dan Komputer',
                'deskripsi' => 'Membahas prinsip desain antarmuka, pengalaman pengguna, dan evaluasi kegunaan sistem.',
                'sks' => 3,
                'semester' => 'Ganjil 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        $course1->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $assignment1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 1: Routing & Middleware Laravel'],
            [
                'deskripsi' => 'Buat implementasi middleware autentikasi dan proteksi route pada aplikasi LMS.',
                'deadline' => now()->addDays(7),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Tugas 1: Pemodelan BPMN & ERD'],
            [
                'deskripsi' => 'Susun diagram alur proses dan rancangan relasi tabel basis data.',
                'deadline' => now()->addDays(10),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Link repository dan dokumentasi konfigurasi middleware sudah dilampirkan.',
                'submitted_at' => now()->subDay(),
                'nilai' => 88,
                'catatan_dosen' => 'Struktur kode sudah rapi dan sesuai ketentuan.',
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Pengumpulan tugas modul routing dan autentikasi.',
                'submitted_at' => now()->subHours(5),
                'nilai' => null,
                'catatan_dosen' => null,
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment2->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Diagram BPMN dan kamus data telah selesai disusun.',
                'submitted_at' => now()->subDays(2),
                'nilai' => 92,
                'catatan_dosen' => 'Analisis alur proses sangat jelas.',
            ]
        );
    }
}
