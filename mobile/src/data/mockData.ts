export interface Product {
  id: string;
  code: string;
  name: string;
  category: 'Scaffolding' | 'Jackbase' | 'Clamp';
  dailyPrice: number;
  stock: number;
  description: string;
}

export const products: Product[] = [
  { id: 'p1', code: 'SCF-001', name: 'Scaffolding 2x1 m', category: 'Scaffolding', dailyPrice: 2500, stock: 85, description: 'Rangka scaffolding untuk kebutuhan akses dan pekerjaan konstruksi.' },
  { id: 'p2', code: 'SCF-002', name: 'Scaffolding 1.5x1 m', category: 'Scaffolding', dailyPrice: 2200, stock: 60, description: 'Ukuran ringkas untuk pekerjaan konstruksi dengan ruang terbatas.' },
  { id: 'p3', code: 'JKB-001', name: 'Jackbase 1.2 m', category: 'Jackbase', dailyPrice: 1800, stock: 150, description: 'Komponen pengatur ketinggian dasar scaffolding.' },
  { id: 'p4', code: 'CLF-001', name: 'Clamp Fix', category: 'Clamp', dailyPrice: 800, stock: 200, description: 'Pengikat sambungan pipa scaffolding tipe tetap.' },
  { id: 'p5', code: 'CLS-001', name: 'Clamp Swivel', category: 'Clamp', dailyPrice: 900, stock: 180, description: 'Pengikat sambungan yang mendukung sudut bervariasi.' },
];

export const taskItems = [
  { id: 'SJ-0012', type: 'Pengiriman', time: '08:00', project: 'Proyek A', client: 'CV Maju Jaya', address: 'Jl. Sudirman No. 123, Jakarta Pusat' },
  { id: 'SJ-0013', type: 'Pengambilan', time: '13:30', project: 'Proyek B', client: 'PT Indah Karya', address: 'Jl. Asia Afrika, Bandung' },
];

export const rentalTabs = ['Menunggu Penawaran', 'Bayar DP', 'Sewa Aktif', 'Selesai'];

export const formatRupiah = (amount: number): string =>
  `Rp ${new Intl.NumberFormat('id-ID').format(amount)}`;
