<?php
declare(strict_types=1);

/** Parent class: menyimpan data umum pegawai. */
abstract class Pegawai
{
    public function __construct(
        private int $id,
        private string $nama
    ) {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID pegawai harus lebih dari 0.');
        }
        $nama = trim($nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama pegawai wajib diisi.');
        }
        $this->nama = $nama;
    }

    public function getId(): int { return $this->id; }
    public function getNama(): string { return $this->nama; }

    // Child wajib menentukan cara hitung gaji dan labelnya sendiri.
    abstract public function hitungGaji(): int;
    abstract public function getJenis(): string;

    // protected: bisa dipakai child, tetapi tidak bisa dipanggil dari luar class.
    protected static function pastikanTidakNegatif(int $nilai, string $label): int
    {
        if ($nilai < 0) {
            throw new InvalidArgumentException("{$label} tidak boleh negatif.");
        }
        return $nilai;
    }
}
