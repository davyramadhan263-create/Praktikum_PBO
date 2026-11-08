<?php
declare(strict_types=1);
// Kedua child di-require di sini supaya class sudah dikenal sebelum session_start() di index.php.
require_once __DIR__ . '/Mahasiswa.php';
require_once __DIR__ . '/MahasiswaReguler.php';
require_once __DIR__ . '/MahasiswaBeasiswa.php';

/** Repository sederhana; menyimpan object (Reguler maupun Beasiswa) di array berdasarkan NIM. */
final class DataMahasiswa
{
    /** @var array<string, Mahasiswa> */
    private array $data = [];

    /** @param array<string, Mahasiswa> $dataAwal */
    public function __construct(array $dataAwal = [])
    {
        foreach ($dataAwal as $item) {
            if ($item instanceof Mahasiswa) {
                $this->data[$item->getNim()] = $item;
            }
        }
    }

    /** @return array<string, Mahasiswa> */
    public function semua(): array { return $this->data; }

    public function cari(string $nim): ?Mahasiswa { return $this->data[$nim] ?? null; }

    /**
     * Baru di Pertemuan 6: pencarian berdasarkan kata kunci (NIM atau nama).
     * @return array<string, Mahasiswa>
     */
    public function cariKata(string $kata): array
    {
        $kata = mb_strtolower(trim($kata));
        if ($kata === '') {
            return $this->data;
        }
        return array_filter($this->data, static function (Mahasiswa $m) use ($kata): bool {
            return str_contains(mb_strtolower($m->getNim()), $kata)
                || str_contains(mb_strtolower($m->getNama()), $kata);
        });
    }

    // Parameter bertipe Mahasiswa (parent) menerima MahasiswaReguler maupun MahasiswaBeasiswa.
    public function tambah(Mahasiswa $mahasiswa): bool
    {
        $nim = $mahasiswa->getNim(); // Bukan $mahasiswa->nim (private).
        if (isset($this->data[$nim])) return false;
        $this->data[$nim] = $mahasiswa;
        return true;
    }

    // NIM harus sama; jenis boleh berbeda (object lama diganti object child baru).
    public function ubah(string $nim, Mahasiswa $mahasiswa): bool
    {
        if (!isset($this->data[$nim]) || $nim !== $mahasiswa->getNim()) return false;
        $this->data[$nim] = $mahasiswa;
        return true;
    }

    public function hapus(string $nim): bool
    {
        if (!isset($this->data[$nim])) return false;
        unset($this->data[$nim]);
        return true;
    }
}
