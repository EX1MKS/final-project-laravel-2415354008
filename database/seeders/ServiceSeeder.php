<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'        => 'Shared Hosting Basic',
                'price'       => 50000,
                'description' => 'Hosting berbagi dengan kapasitas 1GB, cocok untuk website personal atau blog sederhana.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Shared Hosting Pro',
                'price'       => 120000,
                'description' => 'Hosting berbagi kapasitas 5GB dengan bandwidth unlimited, cocok untuk bisnis kecil.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'VPS Starter',
                'price'       => 150000,
                'description' => 'Virtual Private Server dengan 1 vCPU, 1GB RAM, dan 20GB SSD.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'VPS Business',
                'price'       => 350000,
                'description' => 'Virtual Private Server dengan 2 vCPU, 4GB RAM, dan 80GB SSD.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Domain .COM',
                'price'       => 175000,
                'description' => 'Registrasi domain .COM untuk 1 tahun.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Domain .ID',
                'price'       => 200000,
                'description' => 'Registrasi domain .ID untuk 1 tahun.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Email Hosting Business',
                'price'       => 85000,
                'description' => 'Layanan email hosting bisnis dengan kapasitas 10GB per akun, mendukung hingga 10 akun email.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'SSL Certificate Standard',
                'price'       => 300000,
                'description' => 'Sertifikat SSL DV (Domain Validated) untuk mengamankan 1 domain selama 1 tahun.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'SSL Premium Wildcard',
                'price'       => 750000,
                'description' => 'Sertifikat SSL Wildcard untuk mengamankan domain utama dan semua subdomain selama 1 tahun.',
                'status'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Shared Hosting Legacy',
                'price'       => 40000,
                'description' => 'Paket hosting lama yang sudah tidak dipromosikan.',
                'status'      => false, // nonaktif
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('services')->insert($services);
    }
}