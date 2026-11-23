import 'package:flutter/material.dart';
import '../../core/app_theme.dart';
import '../auth/login_screen.dart';

class _Product {
  const _Product(this.id, this.name, this.code, this.category, this.image,
      this.price, this.stock);
  final String id, name, code, category, image;
  final int price, stock;
}

const _products = <_Product>[
  _Product('p1', 'Scaffolding Frame 2.0 m', 'SCF-001', 'Scaffolding',
      'assets/products/scaffolding-frame.png', 100000, 85),
  _Product('p2', 'Jack Base 1.2 m', 'JKB-001', 'Jackbase',
      'assets/products/jackbase.png', 35000, 150),
  _Product('p3', 'Clamp Fix', 'CLF-001', 'Clamp',
      'assets/products/clamp-fix.png', 12000, 200),
  _Product('p4', 'Clamp Swivel', 'CLS-001', 'Clamp',
      'assets/products/clamp-swivel.png', 15000, 180),
];

class RoleShell extends StatefulWidget {
  const RoleShell({super.key, required this.role, required this.email});
  final String role;
  final String email;
  @override
  State<RoleShell> createState() => _RoleShellState();
}

class _RoleShellState extends State<RoleShell> {
  int _index = 0;
  final Map<String, int> _cart = {};
  final List<Map<String, String>> _orders = [
    {
      'code': 'PS-0021',
      'project': 'Proyek A — Jakarta',
      'status': 'Menunggu penawaran',
      'date': '19 Nov 2026'
    },
    {
      'code': 'PS-0020',
      'project': 'Proyek B — Bandung',
      'status': 'Disetujui',
      'date': '18 Nov 2026'
    },
  ];
  final Set<String> _completedTasks = {};
  String _category = 'Semua';
  String _search = '';
  String get _roleName =>
      {
        'owner': 'Pemilik',
        'mandor': 'Mandor',
        'customer': 'Pelanggan'
      }[widget.role] ??
      'Pengguna';
  List<_NavItem> get _navItems {
    if (widget.role == 'owner')
      return const [
        _NavItem('Ringkasan', Icons.space_dashboard_outlined),
        _NavItem('Stok', Icons.inventory_2_outlined),
        _NavItem('Pembayaran', Icons.account_balance_wallet_outlined),
        _NavItem('Laporan', Icons.bar_chart_rounded),
        _NavItem('Profil', Icons.person_outline),
      ];
    if (widget.role == 'mandor')
      return const [
        _NavItem('Tugas', Icons.assignment_outlined),
        _NavItem('Riwayat', Icons.history_rounded),
        _NavItem('Profil', Icons.person_outline),
      ];
    return const [
      _NavItem('Katalog', Icons.grid_view_rounded),
      _NavItem('Keranjang', Icons.shopping_cart_outlined),
      _NavItem('Pesanan', Icons.receipt_long_outlined),
      _NavItem('Jadwal', Icons.calendar_month_outlined),
      _NavItem('Profil', Icons.person_outline),
    ];
  }

  int get _cartCount => _cart.values.fold(0, (sum, value) => sum + value);
  int get _cartSubtotal => _cart.entries.fold(
      0,
      (sum, entry) =>
          sum +
          (_products.firstWhere((p) => p.id == entry.key).price * entry.value));

  void _notify(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message), behavior: SnackBarBehavior.floating));
  }

  void _addToCart(_Product product) {
    setState(() => _cart[product.id] = (_cart[product.id] ?? 0) + 1);
    _notify('${product.name} ditambahkan ke keranjang.');
  }

  void _submitRental(String projectName, String deliveryAddress,
      String startDate, String endDate, String notes) {
    if (_cart.isEmpty) return;
    setState(() {
      _orders.insert(0, {
        'code':
            'PS-${DateTime.now().millisecondsSinceEpoch.toString().substring(8)}',
        'project': '$projectName — $deliveryAddress',
        'status': 'Menunggu proses Admin',
        'date': '19 Nov 2026',
        'period': '$startDate — $endDate',
        'notes': notes,
      });
      _cart.clear();
      _index = widget.role == 'customer' ? 2 : _index;
    });
    _notify(
        'Permintaan sewa demo berhasil dibuat. Admin perlu memeriksa stok dan menerbitkan penawaran.');
  }

  String _dateLabel(DateTime value) =>
      '${value.year.toString().padLeft(4, '0')}-${value.month.toString().padLeft(2, '0')}-${value.day.toString().padLeft(2, '0')}';

  @override
  Widget build(BuildContext context) {
    final nav = _navItems;
    final pages = widget.role == 'owner'
        ? [
            _ownerOverview(),
            _ownerStock(),
            _ownerPayments(),
            _ownerReports(),
            _profile()
          ]
        : widget.role == 'mandor'
            ? [_mandorTasks(), _mandorHistory(), _profile()]
            : [
                _customerCatalog(),
                _customerCart(),
                _customerOrders(),
                _customerSchedule(),
                _profile()
              ];
    return Scaffold(
      appBar: AppBar(
        title: Row(children: [
          Image.asset('assets/logo-scaffoldsync.png',
              width: 160, height: 48, fit: BoxFit.contain)
        ]),
        actions: [
          IconButton(
              onPressed: () => _notify('Belum ada notifikasi baru.'),
              icon: const Icon(Icons.notifications_none_rounded)),
          Padding(
              padding: const EdgeInsets.only(right: 14),
              child: CircleAvatar(
                  radius: 17,
                  backgroundColor: const Color(0xFFE7F0F8),
                  child: Text(_roleName.substring(0, 1),
                      style: const TextStyle(
                          color: AppTheme.navy, fontWeight: FontWeight.w800)))),
        ],
      ),
      body: SafeArea(
          top: false, child: IndexedStack(index: _index, children: pages)),
      bottomNavigationBar: NavigationBar(
        selectedIndex: _index,
        onDestinationSelected: (index) => setState(() => _index = index),
        destinations: [
          for (var i = 0; i < nav.length; i++)
            NavigationDestination(
                icon: _navIcon(nav[i].icon, i), label: nav[i].label)
        ],
      ),
    );
  }

  Widget _navIcon(IconData icon, int index) {
    if (widget.role == 'customer' && index == 1 && _cartCount > 0)
      return Badge(label: Text('$_cartCount'), child: Icon(icon));
    return Icon(icon);
  }

  Widget _page(
      {required String title,
      String? subtitle,
      required List<Widget> children,
      Widget? trailing}) {
    return ListView(
        padding: const EdgeInsets.fromLTRB(16, 18, 16, 26),
        children: [
          Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Expanded(
                child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                  Text(title,
                      style: const TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: AppTheme.ink)),
                  if (subtitle != null) ...[
                    const SizedBox(height: 5),
                    Text(subtitle,
                        style: const TextStyle(
                            color: AppTheme.muted, fontSize: 12))
                  ]
                ])),
            if (trailing != null) trailing
          ]),
          const SizedBox(height: 18),
          ...children,
        ]);
  }

  Widget _sectionTitle(String title, {String? trailing}) => Padding(
      padding: const EdgeInsets.only(bottom: 11, top: 18),
      child: Row(children: [
        Expanded(
            child: Text(title,
                style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                    color: AppTheme.ink))),
        if (trailing != null)
          Text(trailing,
              style: const TextStyle(
                  color: AppTheme.blue,
                  fontWeight: FontWeight.w700,
                  fontSize: 11))
      ]));

  Widget _statGrid(List<_StatData> stats) => GridView.count(
        crossAxisCount: 2,
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        childAspectRatio: 1.65,
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
        children: [
          for (final stat in stats)
            Card(
                child: Padding(
                    padding: const EdgeInsets.all(13),
                    child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Icon(stat.icon, size: 19, color: stat.color),
                          Text(stat.label,
                              style: const TextStyle(
                                  fontSize: 10, color: AppTheme.muted)),
                          Text(stat.value,
                              style: const TextStyle(
                                  fontWeight: FontWeight.w800,
                                  fontSize: 17,
                                  color: AppTheme.ink))
                        ])))
        ],
      );

  Widget _ownerOverview() => _page(
        title: 'Dashboard Pemilik',
        subtitle: 'Selamat datang kembali. Berikut kondisi usaha Anda.',
        children: [
          _statGrid(const [
            _StatData('Omzet bulan ini', 'Rp48,5 jt', Icons.trending_up,
                Color(0xFF23855E)),
            _StatData(
                'Proyek berjalan', '5 proyek', Icons.apartment, AppTheme.blue),
            _StatData('Pembayaran tertunda', '3 tagihan',
                Icons.payments_outlined, AppTheme.orange),
            _StatData('Barang rusak', '15 unit', Icons.warning_amber_rounded,
                Color(0xFFCB5555)),
          ]),
          _sectionTitle('Ringkasan stok', trailing: '1.248 unit'),
          Card(
              child: Padding(
                  padding: const EdgeInsets.all(15),
                  child: Column(children: [
                    _progressRow(
                        'Tersedia', 890, 1248, const Color(0xFF287DB7)),
                    const SizedBox(height: 13),
                    _progressRow('Disewa', 342, 1248, AppTheme.orange),
                    const SizedBox(height: 13),
                    _progressRow('Rusak', 16, 1248, const Color(0xFFCA5555)),
                  ]))),
          _sectionTitle('Aktivitas terbaru'),
          _activityTile('Pengajuan sewa baru', 'CV Maju Jaya · Proyek A',
              'Menunggu diproses', Icons.assignment_outlined),
          _activityTile('Pembayaran diterima', 'PT Indah Karya · INV-0011',
              'Hari ini, 10:15', Icons.receipt_long_outlined),
        ],
      );

  Widget _progressRow(String label, int value, int total, Color color) =>
      Row(children: [
        SizedBox(
            width: 76,
            child: Text(label,
                style: const TextStyle(fontSize: 11, color: AppTheme.muted))),
        Expanded(
            child: ClipRRect(
                borderRadius: BorderRadius.circular(20),
                child: LinearProgressIndicator(
                    value: value / total,
                    minHeight: 8,
                    backgroundColor: const Color(0xFFF0F3F7),
                    color: color))),
        const SizedBox(width: 10),
        SizedBox(
            width: 35,
            child: Text('$value',
                textAlign: TextAlign.right,
                style:
                    const TextStyle(fontSize: 11, fontWeight: FontWeight.w700)))
      ]);

  Widget _ownerStock() => _page(
          title: 'Pantau Inventaris',
          subtitle: 'Jumlah stok barang menurut status terkini.',
          children: [
            _statGrid(const [
              _StatData(
                  'Tersedia', '890', Icons.inventory_2_outlined, AppTheme.blue),
              _StatData('Disewa', '342', Icons.local_shipping_outlined,
                  AppTheme.orange)
            ]),
            _sectionTitle('Daftar barang'),
            for (final item in _products) _productInventoryTile(item),
          ]);

  Widget _productInventoryTile(_Product item) {
    return Card(
      margin: const EdgeInsets.only(bottom: 9),
      child: Padding(
        padding: const EdgeInsets.all(11),
        child: Row(
          children: [
            Container(
              width: 58,
              height: 58,
              decoration: BoxDecoration(
                color: const Color(0xFFF4F7FA),
                borderRadius: BorderRadius.circular(9),
              ),
              child: Image.asset(item.image, fit: BoxFit.contain),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(item.name,
                      style: const TextStyle(
                          fontSize: 12, fontWeight: FontWeight.w800)),
                  const SizedBox(height: 4),
                  Text('${item.code} · ${item.category}',
                      style:
                          const TextStyle(fontSize: 10, color: AppTheme.muted)),
                  const SizedBox(height: 6),
                  Text('Tersedia ${item.stock} unit',
                      style: const TextStyle(
                          color: AppTheme.blue,
                          fontWeight: FontWeight.w700,
                          fontSize: 10)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _ownerPayments() => _page(
          title: 'Pembayaran',
          subtitle: 'Ringkasan tagihan dan pembayaran pelanggan.',
          children: [
            _statGrid(const [
              _StatData('Total tagihan', 'Rp72 jt', Icons.receipt_outlined,
                  AppTheme.blue),
              _StatData('Terbayar', 'Rp56 jt', Icons.check_circle_outline,
                  Color(0xFF23855E))
            ]),
            _sectionTitle('Riwayat invoice'),
            _invoiceTile('INV-0012', 'CV Maju Jaya', 'Rp15.000.000',
                'Menunggu verifikasi'),
            _invoiceTile('INV-0011', 'PT Indah Karya', 'Rp20.000.000', 'Lunas'),
            _invoiceTile(
                'INV-0010', 'CV Sinar Abadi', 'Rp8.000.000', 'Belum dibayar'),
          ]);

  Widget _invoiceTile(String code, String name, String amount, String status) =>
      Card(
          margin: const EdgeInsets.only(bottom: 9),
          child: ListTile(
              leading: const CircleAvatar(
                  backgroundColor: Color(0xFFEAF2FA),
                  child: Icon(Icons.receipt_long, color: AppTheme.blue)),
              title: Text(code,
                  style: const TextStyle(
                      fontSize: 12, fontWeight: FontWeight.w800)),
              subtitle:
                  Text('$name · $status', style: const TextStyle(fontSize: 10)),
              trailing: Text(amount,
                  style: const TextStyle(
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      color: AppTheme.ink))));

  Widget _ownerReports() => _page(
          title: 'Laporan',
          subtitle: 'Ringkasan performa bisnis (data demonstrasi).',
          children: [
            _statGrid(const [
              _StatData('Omzet bulan ini', 'Rp48,5 jt', Icons.show_chart,
                  AppTheme.blue),
              _StatData('Barang disewa', '342 unit', Icons.handshake_outlined,
                  AppTheme.orange)
            ]),
            _sectionTitle('Tren omzet bulanan'),
            Card(
                child: Padding(
                    padding: const EdgeInsets.all(15),
                    child: SizedBox(
                        height: 160,
                        child: Row(
                            crossAxisAlignment: CrossAxisAlignment.end,
                            mainAxisAlignment: MainAxisAlignment.spaceAround,
                            children: [
                              for (int i = 0; i < 7; i++)
                                _bar(
                                    [
                                      'Mei',
                                      'Jun',
                                      'Jul',
                                      'Agu',
                                      'Sep',
                                      'Okt',
                                      'Nov'
                                    ][i],
                                    [45, 62, 53, 75, 68, 85, 96][i].toDouble())
                            ])))),
            _sectionTitle('Barang paling sering disewa'),
            _activityTile('Scaffolding Frame 2.0 m', '12 kali disewa',
                'Rp15.000.000', Icons.construction),
            _activityTile('Jack Base 1.2 m', '10 kali disewa', 'Rp8.000.000',
                Icons.build_outlined),
            _activityTile('Clamp Fix', '8 kali disewa', 'Rp6.000.000',
                Icons.build_outlined),
          ]);

  Widget _bar(String label, double height) =>
      Column(mainAxisAlignment: MainAxisAlignment.end, children: [
        Container(
            width: 20,
            height: height,
            decoration: BoxDecoration(
                color: AppTheme.blue,
                borderRadius:
                    const BorderRadius.vertical(top: Radius.circular(5)))),
        const SizedBox(height: 7),
        Text(label, style: const TextStyle(fontSize: 9, color: AppTheme.muted))
      ]);

  Widget _mandorTasks() => _page(
          title: 'Tugas Saya',
          subtitle: 'Daftar kegiatan pengiriman dan pengambilan.',
          children: [
            _sectionTitle('Hari ini',
                trailing: '${_completedTasks.length} selesai'),
            _taskCard(
                'TASK-0021',
                'Pengiriman Scaffolding 2.0 m',
                'CV Maju Jaya · Proyek A',
                'Jl. Sudirman No. 123, Jakarta Pusat',
                '08:00 – 10:00 WIB'),
            _taskCard(
                'TASK-0020',
                'Pengambilan Jack Base 1.2 m',
                'PT Indah Karya · Proyek B',
                'Jl. Asia Afrika No. 45, Bandung',
                '10:30 – 12:00 WIB'),
            _taskCard(
                'TASK-0019',
                'Pengiriman Clamp Fix',
                'CV Sinar Abadi · Proyek C',
                'Jl. Basuki Rahmat No. 67, Surabaya',
                '14:00 – 16:00 WIB'),
          ]);

  Widget _taskCard(
      String id, String title, String customer, String address, String time) {
    final done = _completedTasks.contains(id);
    return Card(
        margin: const EdgeInsets.only(bottom: 11),
        child: Padding(
            padding: const EdgeInsets.all(14),
            child:
                Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Row(children: [
                Expanded(
                    child: Text(title,
                        style: const TextStyle(
                            fontSize: 13, fontWeight: FontWeight.w800))),
                _statusBadge(done ? 'Selesai' : 'Pengiriman')
              ]),
              const SizedBox(height: 7),
              Text(customer,
                  style: const TextStyle(fontSize: 10, color: AppTheme.muted)),
              const SizedBox(height: 10),
              _iconText(Icons.location_on_outlined, address),
              const SizedBox(height: 6),
              _iconText(Icons.schedule, time),
              const SizedBox(height: 13),
              SizedBox(
                  width: double.infinity,
                  child: OutlinedButton.icon(
                      onPressed: () =>
                          _openTaskDetail(id, title, address, done),
                      icon: const Icon(Icons.open_in_new, size: 16),
                      label: Text(
                          done ? 'Lihat detail tugas' : 'Buka detail tugas'))),
            ])));
  }

  void _openTaskDetail(String id, String title, String address, bool done) {
    Navigator.of(context)
        .push(MaterialPageRoute(
            builder: (_) => TaskDetailScreen(
                id: id,
                title: title,
                address: address,
                alreadyDone: done,
                onComplete: () {
                  setState(() => _completedTasks.add(id));
                })))
        .then((_) => setState(() {}));
  }

  Widget _mandorHistory() => _page(
          title: 'Riwayat Tugas',
          subtitle: 'Pekerjaan lapangan yang telah diselesaikan.',
          children: [
            _activityTile(
                'Pengiriman Scaffolding 2.0 m',
                'CV Maju Jaya · Jakarta',
                '05 Nov 2026 · 10:42 WIB',
                Icons.local_shipping_outlined),
            _activityTile('Pengambilan Jack Base', 'PT Indah Karya · Bandung',
                '03 Nov 2026 · 15:20 WIB', Icons.inventory_2_outlined),
            _activityTile('Pengiriman Clamp Fix', 'CV Sinar Abadi · Surabaya',
                '01 Nov 2026 · 09:05 WIB', Icons.local_shipping_outlined),
          ]);

  Widget _customerCatalog() {
    final filtered = _products
        .where((p) =>
            (_category == 'Semua' || p.category == _category) &&
            ('${p.name} ${p.code}'
                .toLowerCase()
                .contains(_search.toLowerCase())))
        .toList();
    return _page(
        title: 'Katalog Barang',
        subtitle: 'Pilih perlengkapan scaffolding untuk proyek Anda.',
        children: [
          TextField(
              decoration: const InputDecoration(
                  prefixIcon: Icon(Icons.search),
                  hintText: 'Cari barang atau kode...'),
              onChanged: (value) => setState(() => _search = value)),
          const SizedBox(height: 12),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                for (final category in [
                  'Semua',
                  'Scaffolding',
                  'Jackbase',
                  'Clamp'
                ])
                  Padding(
                    padding: const EdgeInsets.only(right: 7),
                    child: ChoiceChip(
                      label: Text(category),
                      selected: _category == category,
                      onSelected: (_) => setState(() => _category = category),
                    ),
                  ),
              ],
            ),
          ),
          const SizedBox(height: 12),
          GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
              childAspectRatio: .72,
              children: [for (final item in filtered) _productCard(item)]),
        ]);
  }

  Widget _productCard(_Product item) => Card(
      clipBehavior: Clip.antiAlias,
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Expanded(
            child: Container(
                width: double.infinity,
                color: const Color(0xFFF3F6F9),
                padding: const EdgeInsets.all(10),
                child: Image.asset(item.image, fit: BoxFit.contain))),
        Padding(
            padding: const EdgeInsets.fromLTRB(10, 9, 10, 10),
            child:
                Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Text(item.category.toUpperCase(),
                  style: const TextStyle(
                      fontSize: 8,
                      fontWeight: FontWeight.w800,
                      letterSpacing: .6,
                      color: AppTheme.muted)),
              const SizedBox(height: 4),
              Text(item.name,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                      color: AppTheme.ink)),
              const SizedBox(height: 4),
              Text('${_rupiah(item.price)}/hari',
                  style: const TextStyle(
                      fontSize: 10,
                      color: AppTheme.blue,
                      fontWeight: FontWeight.w700)),
              const SizedBox(height: 3),
              Text('Stok tersedia: ${item.stock}',
                  style: const TextStyle(fontSize: 9, color: AppTheme.muted)),
              const SizedBox(height: 7),
              SizedBox(
                  width: double.infinity,
                  height: 34,
                  child: ElevatedButton(
                      onPressed: () => _addToCart(item),
                      child: const Text('+ Tambah',
                          style: TextStyle(fontSize: 10)))),
            ])),
      ]));

  Widget _customerCart() => _page(
          title: 'Keranjang Sewa',
          subtitle: 'Periksa barang sebelum mengajukan permintaan.',
          children: [
            if (_cart.isEmpty)
              Card(
                  child: Padding(
                      padding: const EdgeInsets.all(28),
                      child: Column(children: [
                        const Icon(Icons.shopping_cart_outlined,
                            size: 38, color: AppTheme.muted),
                        const SizedBox(height: 10),
                        const Text('Keranjang masih kosong',
                            style: TextStyle(fontWeight: FontWeight.w800)),
                        const SizedBox(height: 5),
                        const Text(
                            'Tambahkan barang dari katalog terlebih dahulu.',
                            style:
                                TextStyle(color: AppTheme.muted, fontSize: 11))
                      ])))
            else ...[
              for (final entry in _cart.entries)
                _cartRow(_products.firstWhere((p) => p.id == entry.key),
                    entry.value),
              Card(
                  child: Padding(
                      padding: const EdgeInsets.all(15),
                      child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Ringkasan sewa',
                                style: TextStyle(fontWeight: FontWeight.w800)),
                            const SizedBox(height: 12),
                            _summaryLine(
                                'Jumlah jenis barang', '${_cart.length} jenis'),
                            _summaryLine(
                                'Subtotal per hari', _rupiah(_cartSubtotal)),
                            const SizedBox(height: 8),
                            const Text(
                                'Total akhir dihitung berdasarkan durasi dan penawaran Admin.',
                                style: TextStyle(
                                    fontSize: 10, color: AppTheme.muted)),
                            const SizedBox(height: 12),
                            SizedBox(
                                width: double.infinity,
                                child: ElevatedButton(
                                    onPressed: _showRentalForm,
                                    child:
                                        const Text('Ajukan permintaan sewa')))
                          ]))),
            ],
          ]);

  Widget _cartRow(_Product item, int quantity) => Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: Padding(
          padding: const EdgeInsets.all(10),
          child: Row(children: [
            Image.asset(item.image, width: 58, height: 58, fit: BoxFit.contain),
            const SizedBox(width: 10),
            Expanded(
                child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                  Text(item.name,
                      style: const TextStyle(
                          fontSize: 11, fontWeight: FontWeight.w800)),
                  Text('${_rupiah(item.price)}/hari',
                      style:
                          const TextStyle(fontSize: 10, color: AppTheme.muted)),
                  Row(children: [
                    IconButton(
                        onPressed: () => setState(() {
                              if (quantity <= 1) {
                                _cart.remove(item.id);
                              } else {
                                _cart[item.id] = quantity - 1;
                              }
                            }),
                        icon:
                            const Icon(Icons.remove_circle_outline, size: 19)),
                    Text('$quantity',
                        style: const TextStyle(fontWeight: FontWeight.w800)),
                    IconButton(
                        onPressed: () =>
                            setState(() => _cart[item.id] = quantity + 1),
                        icon: const Icon(Icons.add_circle_outline, size: 19))
                  ])
                ])),
            IconButton(
                onPressed: () => setState(() => _cart.remove(item.id)),
                icon: const Icon(Icons.delete_outline,
                    color: Colors.redAccent, size: 19))
          ])));

  void _showRentalForm() {
    final project = TextEditingController();
    final address = TextEditingController();
    final notes = TextEditingController();
    DateTime? startDate;
    DateTime? endDate;
    final today = DateTime.now();

    showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      showDragHandle: true,
      builder: (sheetContext) => StatefulBuilder(
        builder: (sheetContext, setSheetState) => Padding(
          padding: EdgeInsets.fromLTRB(
              18, 8, 18, MediaQuery.of(sheetContext).viewInsets.bottom + 20),
          child: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Text('Ajukan Sewa',
                    style:
                        TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
                const SizedBox(height: 14),
                TextField(
                    controller: project,
                    decoration:
                        const InputDecoration(labelText: 'Nama proyek')),
                const SizedBox(height: 10),
                TextField(
                    controller: address,
                    decoration:
                        const InputDecoration(labelText: 'Alamat pengiriman')),
                const SizedBox(height: 10),
                OutlinedButton.icon(
                  onPressed: () async {
                    final picked = await showDatePicker(
                      context: sheetContext,
                      initialDate: startDate ?? today,
                      firstDate: today,
                      lastDate: DateTime(today.year + 3),
                    );
                    if (picked != null)
                      setSheetState(() {
                        startDate = picked;
                        if (endDate != null && endDate!.isBefore(picked))
                          endDate = null;
                      });
                  },
                  icon: const Icon(Icons.calendar_today_outlined),
                  label: Text(startDate == null
                      ? 'Pilih tanggal mulai'
                      : 'Mulai: ${_dateLabel(startDate!)}'),
                ),
                const SizedBox(height: 6),
                OutlinedButton.icon(
                  onPressed: () async {
                    final picked = await showDatePicker(
                      context: sheetContext,
                      initialDate: endDate ?? startDate ?? today,
                      firstDate: startDate ?? today,
                      lastDate: DateTime(today.year + 3),
                    );
                    if (picked != null) setSheetState(() => endDate = picked);
                  },
                  icon: const Icon(Icons.event_available_outlined),
                  label: Text(endDate == null
                      ? 'Pilih tanggal selesai'
                      : 'Selesai: ${_dateLabel(endDate!)}'),
                ),
                const SizedBox(height: 10),
                TextField(
                    controller: notes,
                    maxLines: 3,
                    decoration: const InputDecoration(
                        labelText: 'Catatan tambahan (opsional)',
                        hintText: 'Kebutuhan akses atau waktu pengiriman')),
                const SizedBox(height: 14),
                ElevatedButton(
                  onPressed: () {
                    if (project.text.trim().isEmpty ||
                        address.text.trim().isEmpty ||
                        startDate == null ||
                        endDate == null) {
                      _notify(
                          'Lengkapi proyek, alamat, dan rentang tanggal sewa.');
                      return;
                    }
                    if (endDate!.isBefore(startDate!)) {
                      _notify(
                          'Tanggal selesai tidak boleh sebelum tanggal mulai.');
                      return;
                    }
                    Navigator.pop(sheetContext);
                    _submitRental(
                        project.text.trim(),
                        address.text.trim(),
                        _dateLabel(startDate!),
                        _dateLabel(endDate!),
                        notes.text.trim());
                  },
                  child: const Text('Kirim pengajuan demo'),
                ),
                const SizedBox(height: 6),
                const Text(
                    'Ketersediaan dan harga akhir akan divalidasi oleh backend setelah integrasi.',
                    textAlign: TextAlign.center,
                    style: TextStyle(fontSize: 10, color: AppTheme.muted)),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _customerOrders() => _page(
          title: 'Pesanan Saya',
          subtitle: 'Lacak perkembangan pengajuan dan penyewaan.',
          children: [
            for (final order in _orders)
              Card(
                  margin: const EdgeInsets.only(bottom: 10),
                  child: Padding(
                      padding: const EdgeInsets.all(14),
                      child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(children: [
                              Expanded(
                                  child: Text(order['code']!,
                                      style: const TextStyle(
                                          fontWeight: FontWeight.w800,
                                          fontSize: 12))),
                              _statusBadge(order['status']!)
                            ]),
                            const SizedBox(height: 7),
                            Text(order['project']!,
                                style: const TextStyle(fontSize: 11)),
                            const SizedBox(height: 5),
                            Text('Diajukan ${order['date']}',
                                style: const TextStyle(
                                    fontSize: 10, color: AppTheme.muted)),
                            if (order['period'] != null) ...[
                              const SizedBox(height: 6),
                              _iconText(Icons.date_range_outlined,
                                  'Periode: ${order['period']}')
                            ],
                            if ((order['notes'] ?? '').isNotEmpty) ...[
                              const SizedBox(height: 5),
                              _iconText(Icons.notes_outlined,
                                  'Catatan: ${order['notes']}')
                            ],
                            const SizedBox(height: 12),
                            const Divider(height: 1),
                            const SizedBox(height: 10),
                            _iconText(Icons.timeline,
                                'Menunggu proses dan penerbitan penawaran'),
                            const SizedBox(height: 8),
                            _iconText(Icons.receipt_long_outlined,
                                'Invoice dan status pembayaran'),
                            const SizedBox(height: 9),
                            Align(
                                alignment: Alignment.centerRight,
                                child: TextButton.icon(
                                    onPressed: () =>
                                        _showInvoice(order['code']!),
                                    icon: const Icon(
                                        Icons.receipt_long_outlined,
                                        size: 16),
                                    label: const Text(
                                        'Lihat invoice / pembayaran',
                                        style: TextStyle(fontSize: 10))))
                          ]))),
            _sectionTitle('Tagihan & pembayaran'),
            _customerInvoiceCard('INV-0012', 'PS-0021 · Proyek A',
                'Rp15.000.000', 'Menunggu pembayaran'),
            _customerInvoiceCard('INV-0008', 'PS-0018 · Proyek sebelumnya',
                'Rp8.000.000', 'Lunas'),
            _sectionTitle('Butuh bantuan?'),
            Card(
                child: ListTile(
                    leading:
                        const Icon(Icons.support_agent, color: AppTheme.blue),
                    title: const Text('Hubungi Admin',
                        style: TextStyle(
                            fontSize: 12, fontWeight: FontWeight.w800)),
                    subtitle: const Text('Tanyakan status pengajuan Anda',
                        style: TextStyle(fontSize: 10)),
                    trailing: const Icon(Icons.chevron_right))),
          ]);

  Widget _customerInvoiceCard(
          String code, String description, String amount, String status) =>
      Card(
        margin: const EdgeInsets.only(bottom: 9),
        child: Padding(
          padding: const EdgeInsets.all(13),
          child:
              Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Row(children: [
              Expanded(
                  child: Text(code,
                      style: const TextStyle(
                          fontWeight: FontWeight.w800, fontSize: 12))),
              _statusBadge(status)
            ]),
            const SizedBox(height: 6),
            Text(description,
                style: const TextStyle(color: AppTheme.muted, fontSize: 10)),
            const SizedBox(height: 12),
            Row(children: [
              Expanded(
                  child: Text(amount,
                      style: const TextStyle(
                          fontWeight: FontWeight.w800,
                          color: AppTheme.ink,
                          fontSize: 15))),
              if (status != 'Lunas')
                TextButton.icon(
                    onPressed: () => _showInvoice(code),
                    icon: const Icon(Icons.upload_file, size: 15),
                    label: const Text('Bayar', style: TextStyle(fontSize: 11)))
              else
                const Icon(Icons.check_circle, color: Color(0xFF23855E)),
            ]),
            const Text('Nilai dan status pada layar ini adalah data demo.',
                style: TextStyle(fontSize: 9, color: AppTheme.muted)),
          ]),
        ),
      );

  void _showInvoice(String orderCode) {
    showModalBottomSheet<void>(
        context: context,
        showDragHandle: true,
        builder: (sheetContext) => Padding(
            padding: const EdgeInsets.fromLTRB(18, 8, 18, 24),
            child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const Text('Invoice & Pembayaran',
                      style:
                          TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
                  const SizedBox(height: 5),
                  Text('Pesanan $orderCode',
                      style:
                          const TextStyle(color: AppTheme.muted, fontSize: 11)),
                  const SizedBox(height: 16),
                  _summaryLine('Status invoice', 'Menunggu penawaran'),
                  _summaryLine('Status pembayaran', 'Belum diterbitkan'),
                  const SizedBox(height: 10),
                  ElevatedButton.icon(
                      onPressed: () {
                        Navigator.pop(sheetContext);
                        _notify(
                            'Aksi pembayaran aktif setelah invoice dan payment API tersedia.');
                      },
                      icon: const Icon(Icons.upload_file),
                      label: const Text('Unggah bukti pembayaran')),
                  const SizedBox(height: 5),
                  const Text(
                      'Invoice final, nominal tagihan, dan validasi pembayaran berasal dari backend.',
                      textAlign: TextAlign.center,
                      style: TextStyle(fontSize: 10, color: AppTheme.muted)),
                ])));
  }

  Widget _customerSchedule() => _page(
          title: 'Jadwal',
          subtitle: 'Jadwal pengiriman dan pengambilan barang.',
          children: [
            Card(
                child: Padding(
                    padding: const EdgeInsets.all(15),
                    child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Pengiriman berikutnya',
                              style: TextStyle(
                                  fontSize: 12,
                                  color: AppTheme.muted,
                                  fontWeight: FontWeight.w700)),
                          const SizedBox(height: 8),
                          const Text('20 November 2026',
                              style: TextStyle(
                                  fontSize: 19,
                                  color: AppTheme.ink,
                                  fontWeight: FontWeight.w800)),
                          const SizedBox(height: 4),
                          const Text('08:00 – 10:00 WIB · Proyek A — Jakarta',
                              style: TextStyle(fontSize: 11)),
                          const SizedBox(height: 12),
                          _statusBadge('Dijadwalkan')
                        ]))),
            _sectionTitle('Aktivitas lain'),
            _activityTile(
                'Pengambilan Jack Base',
                'Menunggu konfirmasi setelah masa sewa',
                'CV Maju Jaya · Proyek A',
                Icons.inventory_2_outlined),
            _activityTile('Pengiriman Clamp Fix', 'Bukti serah terima tersedia',
                'Selesai', Icons.local_shipping_outlined),
          ]);

  Widget _profile() => _page(
          title: 'Profil',
          subtitle: 'Kelola informasi dan pengaturan akun.',
          children: [
            Card(
                child: Padding(
                    padding: const EdgeInsets.all(18),
                    child: Column(children: [
                      CircleAvatar(
                          radius: 33,
                          backgroundColor: const Color(0xFFE7F0F8),
                          child: Text(_roleName.substring(0, 1),
                              style: const TextStyle(
                                  fontSize: 24,
                                  color: AppTheme.blue,
                                  fontWeight: FontWeight.w800))),
                      const SizedBox(height: 10),
                      Text(
                          widget.role == 'owner'
                              ? 'Ade — Pemilik'
                              : widget.role == 'mandor'
                                  ? 'Andi Wijaya'
                                  : 'CV Maju Jaya',
                          style: const TextStyle(
                              fontWeight: FontWeight.w800, fontSize: 15)),
                      const SizedBox(height: 4),
                      Text(widget.email,
                          style: const TextStyle(
                              color: AppTheme.muted, fontSize: 10)),
                      const SizedBox(height: 10),
                      _statusBadge(_roleName)
                    ]))),
            _sectionTitle('Akun'),
            _settingsTile(
                Icons.person_outline, 'Edit profil', 'Ubah informasi akun'),
            _settingsTile(
                Icons.lock_outline, 'Keamanan akun', 'Kata sandi dan keamanan'),
            _settingsTile(
                Icons.notifications_none, 'Notifikasi', 'Atur pemberitahuan'),
            _sectionTitle('Bantuan'),
            _settingsTile(Icons.support_agent, 'Chat dengan Admin',
                'Pusat bantuan ScaffoldSync'),
            const SizedBox(height: 14),
            OutlinedButton.icon(
                onPressed: () => Navigator.of(context).pushAndRemoveUntil(
                    MaterialPageRoute(
                        builder: (_) => const LoginScreenReturn()),
                    (route) => false),
                icon: const Icon(Icons.logout),
                label: const Text('Keluar dari akun'),
                style: OutlinedButton.styleFrom(
                    foregroundColor: Colors.redAccent,
                    side: const BorderSide(color: Color(0xFFF0CACA)))),
          ]);

  Widget _settingsTile(IconData icon, String title, String subtitle) => Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
          onTap: () => _notify(
              '$title akan terhubung ke API akun setelah backend tersedia.'),
          leading: Icon(icon, color: AppTheme.blue, size: 20),
          title: Text(title,
              style:
                  const TextStyle(fontSize: 12, fontWeight: FontWeight.w800)),
          subtitle: Text(subtitle, style: const TextStyle(fontSize: 10)),
          trailing: const Icon(Icons.chevron_right, size: 18)));
  Widget _activityTile(
          String title, String subtitle, String trailing, IconData icon) =>
      Card(
          margin: const EdgeInsets.only(bottom: 8),
          child: ListTile(
              leading: CircleAvatar(
                  backgroundColor: const Color(0xFFEAF2FA),
                  child: Icon(icon, color: AppTheme.blue, size: 18)),
              title: Text(title,
                  style: const TextStyle(
                      fontSize: 11, fontWeight: FontWeight.w800)),
              subtitle: Text(subtitle, style: const TextStyle(fontSize: 10)),
              trailing: Text(trailing,
                  style: const TextStyle(fontSize: 9, color: AppTheme.muted))));
  Widget _iconText(IconData icon, String text) => Row(children: [
        Icon(icon, size: 14, color: AppTheme.muted),
        const SizedBox(width: 6),
        Expanded(
            child: Text(text,
                style: const TextStyle(fontSize: 10, color: AppTheme.muted)))
      ]);
  Widget _summaryLine(String label, String value) => Padding(
      padding: const EdgeInsets.symmetric(vertical: 5),
      child: Row(children: [
        Expanded(
            child: Text(label,
                style: const TextStyle(fontSize: 11, color: AppTheme.muted))),
        Text(value,
            style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w800))
      ]));
  Widget _statusBadge(String status) => Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
      decoration: BoxDecoration(
          color: status == 'Selesai' || status == 'Lunas'
              ? const Color(0xFFE8F6EF)
              : const Color(0xFFFFF2E6),
          borderRadius: BorderRadius.circular(30)),
      child: Text(status,
          style: TextStyle(
              fontSize: 9,
              fontWeight: FontWeight.w800,
              color: status == 'Selesai' || status == 'Lunas'
                  ? const Color(0xFF21845D)
                  : const Color(0xFFB96B23))));
}

class TaskDetailScreen extends StatefulWidget {
  const TaskDetailScreen(
      {super.key,
      required this.id,
      required this.title,
      required this.address,
      required this.alreadyDone,
      required this.onComplete});
  final String id, title, address;
  final bool alreadyDone;
  final VoidCallback onComplete;
  @override
  State<TaskDetailScreen> createState() => _TaskDetailScreenState();
}

class _TaskDetailScreenState extends State<TaskDetailScreen> {
  final List<String> _items = [
    'Scaffolding Frame 2.0 m · 40 pcs',
    'Jack Base 1.2 m · 80 pcs',
    'Clamp Fix · 120 pcs',
    'Clamp Swivel · 60 pcs'
  ];
  late List<bool> _checked;
  late List<String> _conditions;
  @override
  void initState() {
    super.initState();
    _checked = List<bool>.filled(4, widget.alreadyDone);
    _conditions = List<String>.filled(4, 'Baik');
  }

  @override
  Widget build(BuildContext context) {
    final allChecked = _checked.every((value) => value);
    return Scaffold(
      appBar: AppBar(title: const Text('Detail Tugas')),
      body: ListView(padding: const EdgeInsets.all(16), children: [
        Text(widget.title,
            style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.w800,
                color: AppTheme.ink)),
        const SizedBox(height: 8),
        Text(widget.id,
            style: const TextStyle(color: AppTheme.muted, fontSize: 11)),
        const SizedBox(height: 16),
        Card(
            child: Padding(
                padding: const EdgeInsets.all(14),
                child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _detailInfo(
                          Icons.location_on_outlined, 'Alamat', widget.address),
                      const Divider(height: 22),
                      _detailInfo(Icons.schedule, 'Waktu',
                          '20 November 2026 · 08:00–10:00 WIB'),
                      const Divider(height: 22),
                      _detailInfo(Icons.business_outlined, 'Pelanggan',
                          'CV Maju Jaya · Proyek A')
                    ]))),
        const SizedBox(height: 16),
        const Text('Checklist barang',
            style: TextStyle(fontWeight: FontWeight.w800, fontSize: 14)),
        const SizedBox(height: 8),
        Card(
          child: Column(
            children: [
              for (var i = 0; i < _items.length; i++)
                Padding(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  child: Row(
                    children: [
                      Checkbox(
                        value: _checked[i],
                        onChanged: widget.alreadyDone
                            ? null
                            : (value) =>
                                setState(() => _checked[i] = value ?? false),
                      ),
                      Expanded(
                          child: Text(_items[i],
                              style: const TextStyle(fontSize: 10))),
                      const SizedBox(width: 5),
                      DropdownButton<String>(
                        value: _conditions[i],
                        underline: const SizedBox.shrink(),
                        isDense: true,
                        items: const [
                          DropdownMenuItem(
                              value: 'Baik',
                              child:
                                  Text('Baik', style: TextStyle(fontSize: 10))),
                          DropdownMenuItem(
                              value: 'Rusak',
                              child: Text('Rusak',
                                  style: TextStyle(fontSize: 10))),
                          DropdownMenuItem(
                              value: 'Hilang',
                              child: Text('Hilang',
                                  style: TextStyle(fontSize: 10))),
                        ],
                        onChanged: widget.alreadyDone
                            ? null
                            : (value) => setState(
                                () => _conditions[i] = value ?? 'Baik'),
                      ),
                    ],
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 14),
        OutlinedButton.icon(
            onPressed: () => _message(
                'Lampiran foto membutuhkan izin kamera/galeri dan endpoint unggah backend.'),
            icon: const Icon(Icons.add_a_photo_outlined),
            label: const Text('Ambil / unggah foto bukti')),
        const SizedBox(height: 8),
        OutlinedButton.icon(
            onPressed: () => _showSignature(),
            icon: const Icon(Icons.draw_outlined),
            label: const Text('Konfirmasi tanda tangan penerima')),
        const SizedBox(height: 8),
        OutlinedButton.icon(
            onPressed: () => _message(
                'Form laporan kendala demo dibuka setelah kontrak backend tersedia.'),
            icon: const Icon(Icons.report_problem_outlined),
            label: const Text('Laporkan kendala')),
        const SizedBox(height: 8),
        OutlinedButton.icon(
            onPressed: () => _showBast(),
            icon: const Icon(Icons.description_outlined),
            label: const Text('Pratinjau BAST digital')),
        const SizedBox(height: 16),
        ElevatedButton(
            onPressed: widget.alreadyDone || !allChecked
                ? null
                : () {
                    widget.onComplete();
                    Navigator.pop(context);
                  },
            child: Text(widget.alreadyDone
                ? 'Tugas sudah selesai'
                : 'Tandai tugas selesai')),
        if (!allChecked && !widget.alreadyDone)
          const Padding(
              padding: EdgeInsets.only(top: 8),
              child: Text('Centang seluruh barang sebelum menyelesaikan tugas.',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 10, color: AppTheme.muted))),
      ]),
    );
  }

  Widget _detailInfo(IconData icon, String label, String value) =>
      Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Icon(icon, size: 18, color: AppTheme.blue),
        const SizedBox(width: 10),
        Expanded(
            child:
                Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          Text(label,
              style: const TextStyle(fontSize: 10, color: AppTheme.muted)),
          const SizedBox(height: 3),
          Text(value,
              style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700))
        ]))
      ]);
  void _message(String message) => ScaffoldMessenger.of(context)
      .showSnackBar(SnackBar(content: Text(message)));
  void _showSignature() => showDialog<void>(
      context: context,
      builder: (context) => AlertDialog(
              title: const Text('Tanda tangan penerima'),
              content: const Text(
                  'Komponen kanvas tanda tangan akan diaktifkan setelah kebutuhan penyimpanan dan endpoint backend disepakati.'),
              actions: [
                TextButton(
                    onPressed: () => Navigator.pop(context),
                    child: const Text('Mengerti'))
              ]));
  void _showBast() {
    final inspection = List.generate(
            _items.length, (index) => '${_items[index]}: ${_conditions[index]}')
        .join('\n');
    showDialog<void>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Berita Acara Serah Terima'),
        content: SingleChildScrollView(
          child: Text(
              'Tugas: ${widget.id}\n${widget.title}\n\nHasil inspeksi:\n$inspection\n\nFoto bukti dan tanda tangan penerima akan dilampirkan setelah izin perangkat dan API backend disiapkan.'),
        ),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Tutup'))
        ],
      ),
    );
  }
}

class LoginScreenReturn extends StatelessWidget {
  const LoginScreenReturn({super.key});
  @override
  Widget build(BuildContext context) => const LoginScreen();
}

class _NavItem {
  const _NavItem(this.label, this.icon);
  final String label;
  final IconData icon;
}

class _StatData {
  const _StatData(this.label, this.value, this.icon, this.color);
  final String label, value;
  final IconData icon;
  final Color color;
}

String _rupiah(int value) =>
    'Rp${value.toString().replaceAllMapped(RegExp(r'\B(?=(\d{3})+(?!\d))'), (_) => '.')}';
