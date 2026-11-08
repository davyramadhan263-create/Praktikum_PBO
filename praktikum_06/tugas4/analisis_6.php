<?php
declare(strict_types=1);

/* ===== Hubungan yang tepat ===== */

// Parent sederhana (versi ringkas dari class Mahasiswa Pertemuan 6).
class Mahasiswa
{
    private ?Alamat $alamat = null;                  // has-a (komposisi)
    private ?KartuPerpustakaan $kartu = null;        // has-a (komposisi)

    public function __construct(private string $nim, private string $nama) {}

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }

    public function setAlamat(Alamat $alamat): void { $this->alamat = $alamat; }
    public function getAlamat(): ?Alamat { return $this->alamat; }

    public function setKartu(KartuPerpustakaan $kartu): void { $this->kartu = $kartu; }
    public function getKartu(): ?KartuPerpustakaan { return $this->kartu; }
}

// is-a: MahasiswaReguler adalah Mahasiswa.
class MahasiswaReguler extends Mahasiswa
{
    private const UKT_DEFAULT = 1500000;

    public function getUkt(): int
    {
        return self::UKT_DEFAULT;
    }

    public function hitungBiaya(): int
    {
        return $this->getUkt();
    }

    public function getJenis(): string
    {
        return 'Mahasiswa Reguler';
    }
}

// is-a: MahasiswaBeasiswa adalah Mahasiswa.
class MahasiswaBeasiswa extends Mahasiswa
{
    private const UKT_DEFAULT = 1000000;

    public function __construct(string $nim, string $nama, private int $potongan)
    {
        parent::__construct($nim, $nama);
    }

    public function getPotongan(): int { return $this->potongan; }

    public function getUkt(): int
    {
        return self::UKT_DEFAULT;
    }

    public function hitungBiaya(): int
    {
        return max(0, $this->getUkt() - $this->potongan);
    }

    public function getJenis(): string
    {
        return 'Mahasiswa Beasiswa';
    }
}

// Alamat dan KartuPerpustakaan berdiri sendiri; dipakai oleh Mahasiswa lewat property.
class Alamat
{
    public function __construct(private string $jalan, private string $kota) {}
    public function teks(): string { return $this->jalan . ', ' . $this->kota; }
}

class KartuPerpustakaan
{
    public function __construct(private string $nomor) {}
    public function getNomor(): string { return $this->nomor; }
}

/* ===== Contoh desain yang keliru ===== */

// KELIRU: kartu perpustakaan BUKAN mahasiswa. Desain yang benar adalah
// KartuPerpustakaan berdiri sendiri; jika ada data mahasiswa, itu dipindahkan
// ke kelas Mahasiswa dan dihubungkan lewat komposisi, bukan pewarisan.
class KartuKeliru
{
    public function __construct(private string $nomor) {}

    public function getNomor(): string { return $this->nomor; }
}

/* ===== Demo ===== */
if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "== Desain benar ==\n";
$m = new MahasiswaBeasiswa('23002', 'Siti', 60);
$m->setAlamat(new Alamat('Jl. Merdeka No. 1', 'Sukabumi'));
$m->setKartu(new KartuPerpustakaan('LIB-0001'));

echo 'Mahasiswa     : ' . $m->getNama() . ' (' . $m->getNim() . ")\n";
echo 'Alamat        : ' . $m->getAlamat()?->teks() . "\n";
echo 'Kartu         : ' . $m->getKartu()?->getNomor() . "\n";
echo 'Beasiswa is-a Mahasiswa? ' . ($m instanceof Mahasiswa ? 'ya' : 'tidak') . "\n";

echo "\n== Desain keliru ==\n";
$k = new KartuKeliru('LIB-0001');
echo 'Kartu is-a Mahasiswa? ' . ($k instanceof Mahasiswa ? 'ya (padahal tidak masuk akal)' : 'tidak') . "\n";
echo 'Kartu punya nomor   : ' . $k->getNomor() . "\n";
