<?php
declare(strict_types=1);
require_once __DIR__ . '/Pegawai.php';

/** Child: pegawai kontrak digaji per jam kerja. */
final class PegawaiKontrak extends Pegawai
{
    private int $upahPerJam;
    private int $jamKerja;

    public function __construct(int $id, string $nama, int $upahPerJam, int $jamKerja)
    {
        parent::__construct($id, $nama); // id dan nama diteruskan ke parent
        $this->upahPerJam = self::pastikanTidakNegatif($upahPerJam, 'Upah per jam');
        $this->jamKerja = self::pastikanTidakNegatif($jamKerja, 'Jam kerja');
    }

    public function getUpahPerJam(): int { return $this->upahPerJam; }
    public function getJamKerja(): int { return $this->jamKerja; }

    public function hitungGaji(): int { return $this->upahPerJam * $this->jamKerja; }

    public function getJenis(): string { return 'Pegawai Kontrak'; }
}
