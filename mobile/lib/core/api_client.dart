import 'dart:convert';
import 'dart:typed_data';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';

/// Lightweight API boundary for the frontend.
/// Set --dart-define=API_BASE_URL=https://your-api.example/api/v1 at build/run time.
/// Endpoint paths and payloads must be agreed with the backend team before use.
class ApiClient {
  ApiClient._();
  static final ApiClient instance = ApiClient._();

  static const String baseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'http://10.0.2.2:8000/api/v1',
  );
  static const bool useMocks =
      bool.fromEnvironment('USE_MOCKS', defaultValue: true);

  Future<Map<String, dynamic>> readLocalConfig() async {
    return {
      'baseUrl': baseUrl,
      'useMocks': useMocks,
      'platform': defaultTargetPlatform.name,
      'jsonAvailable': jsonEncode({'ok': true}).isNotEmpty,
      'servicesReady': true,
      'note': 'Endpoint API menunggu kontrak resmi backend.',
    };
  }

  Future<void> saveDemoPreference(String key, String value) async {
    // Placeholder for preferences; use shared_preferences after the team approves it.
    await Future<void>.delayed(const Duration(milliseconds: 40));
    debugPrint('Demo preference: $key=$value');
  }

  Future<Uint8List> loadLogoBytes() async => rootBundle
      .load('assets/logo-scaffoldsync.png')
      .then((v) => v.buffer.asUint8List());
}
