<?php
declare(strict_types=1);
require_once __DIR__ . '/PegawaiTetap.php';
require_once __DIR__ . '/PegawaiKontrak.php';

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

function rupiah(int $angka): string {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

$daftar = [
    new PegawaiTetap(1, 'Budi', 6000000),
    new PegawaiKontrak(2, 'Sinta', 50000, 80),
];

echo "=== Daftar Pegawai ===\n";
foreach ($daftar as $p) {
    // Satu pemanggilan, hasil berbeda sesuai jenis object (overriding).
    echo $p->getId() . ' | ' . $p->getNama() . ' | ' . $p->getJenis()
        . ' | Gaji: ' . rupiah($p->hitungGaji()) . "\n";
}

echo "\n=== Uji Validasi (angka negatif) ===\n";
try {
    new PegawaiTetap(3, 'Rina', -1000000);
} catch (InvalidArgumentException $e) {
    echo 'Ditolak: ' . $e->getMessage() . "\n";
}
try {
    new PegawaiKontrak(4, 'Dedi', 50000, -10);
} catch (InvalidArgumentException $e) {
    echo 'Ditolak: ' . $e->getMessage() . "\n";
}
