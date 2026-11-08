<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

/** Child: mahasiswa reguler membayar UKT penuh. */
final class MahasiswaReguler extends Mahasiswa
{
    private int $ukt;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama,
        int $ukt
    ) {
        // Data umum diteruskan ke parent; child hanya mengurus UKT.
        parent::__construct($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama);
        $this->ukt = self::validasiUkt($ukt);
    }

    public function getUkt(): int { return $this->ukt; }

    public function hitungBiaya(): int { return $this->ukt; }

    public function getJenis(): string { return 'Reguler'; }
}
