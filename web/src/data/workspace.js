export const menus = {
  admin: [
    { key: 'dashboard', label: 'Dashboard', icon: 'LayoutDashboard' },
    { key: 'inventory', label: 'Inventaris', icon: 'Boxes' },
    { key: 'customers', label: 'Pelanggan', icon: 'Users' },
    { key: 'orders', label: 'Pesanan', icon: 'ClipboardList' },
    { key: 'projects', label: 'Proyek', icon: 'Building2' },
    { key: 'schedules', label: 'Jadwal & Mandor', icon: 'CalendarDays' },
    { key: 'documents', label: 'Dokumen', icon: 'FileText' },
    { key: 'payments', label: 'Pembayaran', icon: 'Wallet' },
    { key: 'users', label: 'Pengguna', icon: 'Users' },
    { key: 'reports', label: 'Laporan', icon: 'ChartNoAxesCombined' },
  ],
  owner: [
    { key: 'dashboard', label: 'Dashboard', icon: 'LayoutDashboard' },
    { key: 'inventory', label: 'Pantau Inventaris', icon: 'Boxes' },
    { key: 'payments', label: 'Pembayaran', icon: 'Wallet' },
    { key: 'reports', label: 'Laporan', icon: 'ChartNoAxesCombined' },
  ],
  customer: [
    { key: 'dashboard', label: 'Ringkasan', icon: 'LayoutDashboard' },
    { key: 'catalog', label: 'Katalog Barang', icon: 'PackageSearch' },
    { key: 'cart', label: 'Keranjang', icon: 'ShoppingCart' },
    { key: 'rentals', label: 'Pesanan Saya', icon: 'ClipboardList' },
    { key: 'invoices', label: 'Invoice & Pembayaran', icon: 'Receipt' },
    { key: 'schedules', label: 'Jadwal', icon: 'CalendarDays' },
    { key: 'profile', label: 'Profil', icon: 'UserRound' },
  ],
}

export const titles = {
  dashboard: 'Dashboard', inventory: 'Manajemen Inventaris', customers: 'Data Pelanggan',
  orders: 'Manajemen Pesanan', projects: 'Data Proyek', schedules: 'Jadwal & Penugasan Mandor',
  documents: 'Dokumen Operasional', reports: 'Laporan Bisnis', users: 'Manajemen Pengguna',
  payments: 'Pembayaran', catalog: 'Katalog Scaffolding', cart: 'Keranjang Sewa',
  rentals: 'Pesanan Saya', invoices: 'Invoice & Pembayaran', profile: 'Profil Saya',
}

export const initialData = {
  inventory: [
    { id: 'BRG-001', code: 'SCF-001', name: 'Scaffolding Frame 2.0 m', category: 'Scaffolding', total: 120, available: 85, rented: 30, damaged: 5, daily: 100000 },
    { id: 'BRG-002', code: 'JKB-001', name: 'Jack Base 1.2 m', category: 'Jackbase', total: 200, available: 150, rented: 45, damaged: 5, daily: 35000 },
    { id: 'BRG-003', code: 'CLF-001', name: 'Clamp Fix', category: 'Clamp', total: 300, available: 200, rented: 90, damaged: 10, daily: 12000 },
    { id: 'BRG-004', code: 'CLS-001', name: 'Clamp Swivel', category: 'Clamp', total: 250, available: 180, rented: 60, damaged: 10, daily: 15000 },
  ],
  customers: [
    { id: 'CUS-001', name: 'CV Maju Jaya', contact: 'Budi Santoso', email: 'budi@majujaya.co.id', phone: '0812-3456-7890', status: 'Aktif' },
    { id: 'CUS-002', name: 'PT Indah Karya', contact: 'Rina Putri', email: 'rina@indahkarya.co.id', phone: '0813-5555-1212', status: 'Aktif' },
    { id: 'CUS-003', name: 'CV Sinar Abadi', contact: 'Agus Pratama', email: 'agus@sinarabadi.co.id', phone: '0821-5555-9012', status: 'Aktif' },
  ],
  orders: [
    { id: 'PS-0021', customer: 'CV Maju Jaya', project: 'Proyek A — Jakarta', period: '01–30 Des 2026', total: 15000000, status: 'Menunggu persetujuan pelanggan' },
    { id: 'PS-0020', customer: 'PT Indah Karya', project: 'Proyek B — Bandung', period: '20 Nov–20 Des 2026', total: 20000000, status: 'Disetujui' },
    { id: 'PS-0019', customer: 'CV Sinar Abadi', project: 'Proyek C — Surabaya', period: '18 Nov–18 Des 2026', total: 8000000, status: 'Berjalan' },
  ],
  projects: [
    { id: 'PRJ-001', name: 'Proyek A', customer: 'CV Maju Jaya', address: 'Jakarta Pusat', status: 'Berjalan' },
    { id: 'PRJ-002', name: 'Proyek B', customer: 'PT Indah Karya', address: 'Bandung', status: 'Persiapan' },
    { id: 'PRJ-003', name: 'Proyek C', customer: 'CV Sinar Abadi', address: 'Surabaya', status: 'Berjalan' },
  ],
  schedules: [
    { id: 'SCH-001', order: 'PS-0020', type: 'Pengiriman', date: '20 Nov 2026', time: '08:00', mandor: 'Andi Wijaya', status: 'Dijadwalkan' },
    { id: 'SCH-002', order: 'PS-0019', type: 'Pengambilan', date: '22 Nov 2026', time: '10:00', mandor: 'Dedi Saputra', status: 'Menunggu' },
  ],
  documents: [
    { id: 'OFF-0012', type: 'Penawaran', customer: 'CV Maju Jaya', order: 'PS-0021', status: 'Menunggu persetujuan pelanggan' },
    { id: 'SJ-0011', type: 'Surat jalan', customer: 'PT Indah Karya', order: 'PS-0020', status: 'Diterbitkan' },
    { id: 'INV-0012', type: 'Invoice', customer: 'CV Maju Jaya', order: 'PS-0021', status: 'Menunggu verifikasi' },
  ],
  invoices: [
    { id: 'INV-0012', customer: 'CV Maju Jaya', due: '25 Nov 2026', amount: 15000000, paid: 5000000, status: 'Menunggu verifikasi' },
    { id: 'INV-0011', customer: 'PT Indah Karya', due: '22 Nov 2026', amount: 20000000, paid: 20000000, status: 'Lunas' },
    { id: 'INV-0010', customer: 'CV Sinar Abadi', due: '28 Nov 2026', amount: 8000000, paid: 0, status: 'Belum dibayar' },
  ],
  users: [
    { id: 'USR-001', name: 'Rina Administrator', email: 'admin@scaffoldsync.local', role: 'Admin', status: 'Aktif' },
    { id: 'USR-002', name: 'Ade Pemilik', email: 'owner@scaffoldsync.local', role: 'Pemilik', status: 'Aktif' },
    { id: 'USR-003', name: 'Andi Wijaya', email: 'mandor@scaffoldsync.local', role: 'Mandor', status: 'Aktif' },
  ],
  cart: [],
}
