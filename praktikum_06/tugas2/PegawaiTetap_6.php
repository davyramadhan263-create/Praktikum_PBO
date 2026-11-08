<?php
declare(strict_types=1);
require_once __DIR__ . '/Pegawai.php';

/** Child: pegawai tetap digaji bulanan sebesar gaji pokok. */
final class PegawaiTetap extends Pegawai
{
    private int $gajiPokok;

    public function __construct(int $id, string $nama, int $gajiPokok)
    {
        parent::__construct($id, $nama); // id dan nama diteruskan ke parent
        $this->gajiPokok = self::pastikanTidakNegatif($gajiPokok, 'Gaji pokok');
    }

    public function getGajiPokok(): int { return $this->gajiPokok; }

    public function hitungGaji(): int { return $this->gajiPokok; }

    public function getJenis(): string { return 'Pegawai Tetap'; }
}
