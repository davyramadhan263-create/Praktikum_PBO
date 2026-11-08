<?php
declare(strict_types=1);

// Latihan 2, 3, 4, 5: class Buku (constructor, destructor, overloading).
class Buku {
    private array $atributTambahan = [];

    // CONSTRUCTOR: dipanggil otomatis saat object dibuat dengan "new Buku(...)".
    // Menyiapkan keadaan awal object (judul, penulis, tahun) dan memvalidasinya.
    public function __construct(
        public string $judul,
        public string $penulis,
        public int $tahun
    ) {
        if ($judul === '' || $penulis === '' || $tahun < 1) {
            throw new InvalidArgumentException('Judul, penulis, dan tahun harus valid.');
        }
        echo "  [__construct] Buku '{$this->judul}' dibuat\n";
    }

    public function tampilInfo(): string {
        return "{$this->judul} oleh {$this->penulis} ({$this->tahun})";
    }

    // Latihan 4: overloading property.
    public function __set(string $nama, mixed $nilai): void {
        echo "  [__set] '$nama' diisi dengan '" . (is_scalar($nilai) ? $nilai : gettype($nilai)) . "'\n";
        $this->atributTambahan[$nama] = $nilai;
    }
    public function __get(string $nama): mixed {
        echo "  [__get] membaca '$nama'\n";
        return $this->atributTambahan[$nama] ?? null;
    }
    public function __isset(string $nama): bool {
        echo "  [__isset] memeriksa '$nama'\n";
        return isset($this->atributTambahan[$nama]);
    }
    public function __unset(string $nama): void {
        echo "  [__unset] menghapus '$nama'\n";
        unset($this->atributTambahan[$nama]);
    }

    // Latihan 5: hanya satu nama method yang diizinkan.
    public function __call(string $nama, array $argumen): mixed {
        if ($nama === 'infoSingkat' && count($argumen) === 0) {
            return $this->judul . ' (' . $this->tahun . ')';
        }
        throw new BadMethodCallException('Method tidak tersedia: ' . $nama);
    }

    // DESTRUCTOR: dipanggil otomatis saat referensi terakhir ke object dilepas
    // (unset / variabel di-reassign) atau saat skrip selesai.
    public function __destruct() {
        echo "  [__destruct] Buku '{$this->judul}' dihancurkan\n";
    }
}