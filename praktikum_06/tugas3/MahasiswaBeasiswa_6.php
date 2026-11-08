<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

/** Child: mahasiswa beasiswa membayar UKT setelah dipotong persen beasiswa. */
final class MahasiswaBeasiswa extends Mahasiswa
{
    private int $ukt;
    private int $potonganPersen;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama,
        int $ukt, int $potonganPersen
    ) {
        parent::__construct($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama);
        $this->ukt = self::validasiUkt($ukt);
        if ($potonganPersen < 0 || $potonganPersen > 100) {
            throw new InvalidArgumentException('Potongan beasiswa harus antara 0 dan 100.');
        }
        $this->potonganPersen = $potonganPersen;
    }

    public function getUkt(): int { return $this->ukt; }

    // Override method milik parent (di parent selalu 0).
    public function getPotonganPersen(): int { return $this->potonganPersen; }

    public function hitungBiaya(): int
    {
        return intdiv($this->ukt * (100 - $this->potonganPersen), 100);
    }

    public function getJenis(): string { return 'Beasiswa'; }
}
