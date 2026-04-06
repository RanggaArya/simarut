<?php

namespace App\Support;

use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Perangkat;
use Illuminate\Support\Facades\DB;

class NomorInventarisGenerator
{
    public static function generate(int $jenisId, int $kategoriId, int $tahun): string
    {
        $jenis = Jenis::findOrFail($jenisId);
        $kat   = Kategori::findOrFail($kategoriId);

        $prefix    = $jenis->prefix ?: 'A';
        $kodeJenis = $jenis->kode_jenis ?: '01';
        $kodeKat   = str_pad(preg_replace('/\D+/', '', (string) $kat->kode_kategori), 3, '0', STR_PAD_LEFT);

        return self::generateFromCodes($jenisId, $prefix, $kodeJenis, $kategoriId, $kodeKat, $tahun);
    }

    public static function generateFromCodes(
        int $jenisId,
        string $prefix,
        string $kodeJenis,
        int $kategoriId,
        string $kodeKat,
        int $tahun
    ): string {
        $prefix    = strtoupper(trim($prefix ?: 'B'));
        $kodeJenis = trim($kodeJenis);
        $kodeKat   = str_pad(preg_replace('/\D+/', '', $kodeKat), 3, '0', STR_PAD_LEFT);
        $tahun     = (int) $tahun;

        $tableName = (new Perangkat())->getTable(); 

        $base = "{$prefix}.{$kodeJenis}.{$kodeKat}.";
        $likeAllYears = $base . '%';

        $maxUrut = (int) DB::table($tableName)
            ->where('nomor_inventaris', 'like', $likeAllYears)
            ->selectRaw("
            MAX(CAST(
                SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_inventaris, '.', -2), '.', 1)
            AS UNSIGNED)) AS max_urut
        ")
            ->value('max_urut');

        $next = max(1, $maxUrut + 1);

        do {
            $minWidth = 2;
            $width = max($minWidth, strlen((string) $next));
            $urutStr = str_pad((string) $next, $width, '0', STR_PAD_LEFT);

            $existsSameUrutAnyYear = DB::table($tableName)
                ->where('nomor_inventaris', 'like', $base . $urutStr . '.%')
                ->exists();

            if ($existsSameUrutAnyYear) {
                $next++;
                continue;
            }

            $candidate = "{$base}{$urutStr}.{$tahun}";

            $existsExact = DB::table($tableName)
                ->where('nomor_inventaris', $candidate)
                ->exists();

            if (!$existsExact) {
                return $candidate;
            }

            $next++;
        } while (true);
    }
}