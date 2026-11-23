import 'package:flutter/material.dart';

import '../../core/app_theme.dart';
import '../shell/role_shell.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _email = TextEditingController(
    text: 'owner@scaffoldsync.local',
  );
  final TextEditingController _password = TextEditingController(
    text: 'demo123',
  );
  String _role = 'owner';
  bool _obscure = true;
  String? _error;

  @override
  void dispose() {
    _email.dispose();
    _password.dispose();
    super.dispose();
  }

  void _login() {
    if (_email.text.trim().isEmpty || _password.text.isEmpty) {
      setState(() => _error = 'Email dan kata sandi wajib diisi.');
      return;
    }
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(
        builder: (_) => RoleShell(role: _role, email: _email.text.trim()),
      ),
    );
  }

  void _showMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message), behavior: SnackBarBehavior.floating),
    );
  }

  void _showForgotPassword() {
    final emailController = TextEditingController(text: _email.text);
    showDialog<void>(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Reset kata sandi'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text(
              'Masukkan email akun. Pengiriman tautan reset akan aktif setelah layanan email dan API backend terhubung.',
              style: TextStyle(fontSize: 12, color: AppTheme.muted),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: emailController,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(labelText: 'Alamat email'),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(dialogContext);
              _showMessage(
                'Permintaan reset demo dicatat untuk ${emailController.text}.',
              );
            },
            child: const Text('Kirim permintaan'),
          ),
        ],
      ),
    );
  }

  void _showCustomerRegistration() {
    final name = TextEditingController();
    final company = TextEditingController();
    final phone = TextEditingController();
    final email = TextEditingController();
    final password = TextEditingController();

    showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      showDragHandle: true,
      builder: (sheetContext) => Padding(
        padding: EdgeInsets.fromLTRB(
          18,
          8,
          18,
          MediaQuery.of(sheetContext).viewInsets.bottom + 20,
        ),
        child: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Text(
                'Daftar akun pelanggan',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: name,
                decoration: const InputDecoration(
                  labelText: 'Nama penanggung jawab',
                ),
              ),
              const SizedBox(height: 9),
              TextField(
                controller: company,
                decoration: const InputDecoration(labelText: 'Nama perusahaan'),
              ),
              const SizedBox(height: 9),
              TextField(
                controller: phone,
                keyboardType: TextInputType.phone,
                decoration: const InputDecoration(labelText: 'Nomor telepon'),
              ),
              const SizedBox(height: 9),
              TextField(
                controller: email,
                keyboardType: TextInputType.emailAddress,
                decoration: const InputDecoration(labelText: 'Alamat email'),
              ),
              const SizedBox(height: 9),
              TextField(
                controller: password,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Kata sandi (minimal 6 karakter)',
                ),
              ),
              const SizedBox(height: 13),
              ElevatedButton(
                onPressed: () {
                  if (name.text.trim().isEmpty ||
                      company.text.trim().isEmpty ||
                      !email.text.contains('@') ||
                      password.text.length < 6) {
                    _showMessage(
                      'Lengkapi data dan gunakan kata sandi minimal 6 karakter.',
                    );
                    return;
                  }
                  Navigator.pop(sheetContext);
                  Navigator.of(context).pushReplacement(
                    MaterialPageRoute(
                      builder: (_) => RoleShell(
                        role: 'customer',
                        email: email.text.trim(),
                      ),
                    ),
                  );
                },
                child: const Text('Buat akun demo pelanggan'),
              ),
              const SizedBox(height: 5),
              const Text(
                'Registrasi ini hanya simulasi lokal. Penyimpanan akun permanen menunggu API backend.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 10, color: AppTheme.muted),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(22),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 440),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Center(
                    child: Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFFE5EAF1)),
                      ),
                      child: Image.asset(
                        'assets/logo-scaffoldsync.png',
                        width: 255,
                        height: 76,
                        fit: BoxFit.contain,
                      ),
                    ),
                  ),
                  const SizedBox(height: 38),
                  const Text(
                    'SELAMAT DATANG',
                    style: TextStyle(
                      color: AppTheme.muted,
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      letterSpacing: 1.5,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Masuk ke ScaffoldSync',
                    style: TextStyle(
                      color: AppTheme.ink,
                      fontSize: 25,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Pilih peran untuk membuka area kerja yang sesuai.',
                    style: TextStyle(color: AppTheme.muted, height: 1.5),
                  ),
                  const SizedBox(height: 24),
                  const Text(
                    'Masuk sebagai',
                    style: TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
                  ),
                  const SizedBox(height: 8),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      _RoleChip(
                        label: 'Pemilik',
                        value: 'owner',
                        selected: _role,
                        onTap: (value) => setState(() => _role = value),
                      ),
                      _RoleChip(
                        label: 'Mandor',
                        value: 'mandor',
                        selected: _role,
                        onTap: (value) => setState(() => _role = value),
                      ),
                      _RoleChip(
                        label: 'Pelanggan',
                        value: 'customer',
                        selected: _role,
                        onTap: (value) => setState(() => _role = value),
                      ),
                    ],
                  ),
                  const SizedBox(height: 18),
                  const Text(
                    'Alamat email',
                    style: TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
                  ),
                  const SizedBox(height: 7),
                  TextField(
                    controller: _email,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(
                      prefixIcon: Icon(Icons.mail_outline),
                      hintText: 'nama@email.com',
                    ),
                  ),
                  const SizedBox(height: 15),
                  const Text(
                    'Kata sandi',
                    style: TextStyle(fontWeight: FontWeight.w700, fontSize: 12),
                  ),
                  const SizedBox(height: 7),
                  TextField(
                    controller: _password,
                    obscureText: _obscure,
                    decoration: InputDecoration(
                      prefixIcon: const Icon(Icons.lock_outline),
                      hintText: 'Masukkan kata sandi',
                      suffixIcon: IconButton(
                        onPressed: () => setState(() => _obscure = !_obscure),
                        icon: Icon(
                          _obscure
                              ? Icons.visibility_outlined
                              : Icons.visibility_off_outlined,
                        ),
                      ),
                    ),
                  ),
                  if (_error != null) ...[
                    const SizedBox(height: 10),
                    Text(
                      _error!,
                      style: const TextStyle(color: Colors.red, fontSize: 12),
                    ),
                  ],
                  const SizedBox(height: 20),
                  ElevatedButton.icon(
                    onPressed: _login,
                    icon: const Icon(Icons.arrow_forward),
                    label: const Text('Masuk ke aplikasi'),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      TextButton(
                        onPressed: _showForgotPassword,
                        child: const Text(
                          'Lupa kata sandi?',
                          style: TextStyle(fontSize: 11),
                        ),
                      ),
                      TextButton(
                        onPressed: _role == 'customer'
                            ? _showCustomerRegistration
                            : null,
                        child: const Text(
                          'Daftar pelanggan',
                          style: TextStyle(fontSize: 11),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Mode prototype: autentikasi masih simulasi lokal. Akun demo belum memanggil API backend.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 10,
                      color: AppTheme.muted,
                      height: 1.5,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _RoleChip extends StatelessWidget {
  const _RoleChip({
    required this.label,
    required this.value,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final String value;
  final String selected;
  final ValueChanged<String> onTap;

  @override
  Widget build(BuildContext context) {
    final active = selected == value;
    return InkWell(
      onTap: () => onTap(value),
      borderRadius: BorderRadius.circular(24),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 10),
        decoration: BoxDecoration(
          color: active ? const Color(0xFFE9F2FB) : Colors.white,
          borderRadius: BorderRadius.circular(24),
          border: Border.all(
            color: active ? AppTheme.blue : const Color(0xFFE1E8EF),
          ),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 11,
            fontWeight: FontWeight.w700,
            color: active ? AppTheme.blue : AppTheme.muted,
          ),
        ),
      ),
    );
  }
}
