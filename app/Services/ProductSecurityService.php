<?php

namespace App\Services;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Support\PublicFile;

class ProductSecurityService
{
    /**
     * Format: PRD-[TIMESTAMP]-[6 KARAKTER RANDOM UPPERCASE]
     */
    public function generateKodeProduk(): string
    {
        $timestamp = time();
        $random    = strtoupper(Str::random(6));
        return 'PRD-' . $timestamp . '-' . $random;
    }

    /**
     * Secret key 32 karakter unik per unit
     */
    public function generateSecretKey(): string
    {
        return Str::random(32);
    }

    /**
     * M = kode_produk | nama_kategori | batch_produk | tanggal_produksi
     */
    public function generateHash(
        string $kodeProduk,
        string $namaKategori,   // ← ganti dari $namaProduk
        string $batchProduk,
        string $tanggalProduksi,
        string $secretKey
    ): string {
        $message = $kodeProduk    . '|'
                 . $namaKategori  . '|'
                 . $batchProduk   . '|'
                 . $tanggalProduksi;

        return hash_hmac('sha256', $message, $secretKey);
    }

    /**
     * Verifikasi hash dengan hash_equals() — anti timing attack
     */
    public function verifyHash(
        string $kodeProduk,
        string $namaKategori,   // ← ganti dari $namaProduk
        string $batchProduk,
        string $tanggalProduksi,
        string $secretKey,
        string $hashDatabase
    ): bool {
        $hashBaru = $this->generateHash(
            $kodeProduk,
            $namaKategori,
            $batchProduk,
            $tanggalProduksi,
            $secretKey
        );

        return hash_equals($hashDatabase, $hashBaru);
    }

    /**
     * Generate QR Code SVG — tidak butuh Imagick
     */
    public function generateQrCode(string $kodeProduk): string
    {
        $url      = url('/verify/' . $kodeProduk);
        $fileName = 'qrcodes/' . $kodeProduk . '.svg';

        $qrImage = QrCode::format('svg')
                         ->size(300)
                         ->margin(1)
                         ->errorCorrection('H')
                         ->generate($url);

        PublicFile::put($fileName, $qrImage);

        return $fileName;
    }

    /**
     * Generate URL QR data
     */
    public function generateQrData(string $kodeProduk, string $hash): string
    {
        return url('/verify/' . $kodeProduk);
    }

    /**
     * Tentukan status produk
     * Hash tidak cocok      → TIDAK_TERVERIFIKASI
     * Hash cocok, scan <= 3 → ASLI
     * Hash cocok, scan > 3  → MENCURIGAKAN
     */
    public function determineStatus(bool $hashValid, int $totalScan): string
    {
        if (!$hashValid) {
            return 'TIDAK_TERVERIFIKASI';
        }

        return $totalScan <= 3 ? 'ASLI' : 'MENCURIGAKAN';
    }
}