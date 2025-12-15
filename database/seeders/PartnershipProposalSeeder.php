<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PartnershipProposal;
use Illuminate\Support\Facades\Hash;

class PartnershipProposalSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil admin kalau ada. Kalau tidak ada, buat satu.
        $adminId = User::where('role', 'admin')->value('id');

        if (!$adminId) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin Seeder',
                    'password' => Hash::make('password'),
                    'role' => 'admin', // Selalu set role sebagai admin
                ]
            );
            $adminId = $admin->id;
        }

        PartnershipProposal::insert([
            [
                'user_id' => $adminId,
                'organization_name' => 'PT. Teknologi Maju Indonesia',
                'contact_email' => 'partnership@teknologimaju.com',
                'contact_phone' => '+62 21 5555 1234',
                'contact_person' => 'Budi Hartono',
                'position' => 'Direktur Kemitraan',
                'cooperation_type' => 'Beasiswa',
                'proposal_summary' => 'Program beasiswa TI untuk 10 mahasiswa per tahun.',
                'document_path' => 'partnerships/Proposal_Beasiswa_TMI_2024.pdf',
                'status' => 'pending',
                'notes' => null,
                'created_at' => '2024-01-15 09:00:00',
                'updated_at' => '2024-01-15 09:00:00',
            ],
            [
                'user_id' => $adminId,
                'organization_name' => 'Bank Mandiri Regional Jawa Tengah',
                'contact_email' => 'csr@bankmandiri.co.id',
                'contact_phone' => '+62 24 8888 5678',
                'contact_person' => 'Siti Nurhaliza',
                'position' => 'Manager CSR',
                'cooperation_type' => 'Sponsorship',
                'proposal_summary' => 'Dukungan sponsorship untuk program kewirausahaan mahasiswa.',
                'document_path' => 'partnerships/Sponsorship_Proposal_Mandiri_2024.pdf',
                'status' => 'approved',
                'notes' => 'MoU dikirim 3 hari kerja.',
                'created_at' => '2024-01-10 09:00:00',
                'updated_at' => '2024-01-10 09:00:00',
            ],
            [
                'user_id' => $adminId,
                'organization_name' => 'Universitas Gadjah Mada',
                'contact_email' => 'kemitraan@ugm.ac.id',
                'contact_phone' => '+62 274 588 688',
                'contact_person' => 'Prof. Dr. Ahmad Wijaya',
                'position' => 'Wakil Rektor Bidang Kemitraan',
                'cooperation_type' => 'Seminar',
                'proposal_summary' => 'Seminar nasional inovasi teknologi berkelanjutan.',
                'document_path' => 'partnerships/Proposal_Seminar_UGM_2024.pdf',
                'status' => 'rejected',
                'notes' => 'Jadwal bentrok, pertimbangkan semester depan.',
                'created_at' => '2024-01-12 09:00:00',
                'updated_at' => '2024-01-12 09:00:00',
            ],
            [
                'user_id' => $adminId,
                'organization_name' => 'Yayasan Pendidikan Harapan Bangsa',
                'contact_email' => 'info@harapanbangsa.org',
                'contact_phone' => '+62 274 123 4567',
                'contact_person' => 'Dr. Dewi Sartika',
                'position' => 'Direktur Eksekutif',
                'cooperation_type' => 'Event',
                'proposal_summary' => 'Festival Inovasi Mahasiswa 2024 (startup, workshop, expo).',
                'document_path' => 'partnerships/Event_Proposal_YHB_2024.pdf',
                'status' => 'onhold',
                'notes' => 'Menunggu konfirmasi anggaran & venue.',
                'created_at' => '2024-01-18 09:00:00',
                'updated_at' => '2024-01-18 09:00:00',
            ],
        ]);
    }
}
