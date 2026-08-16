import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;

class ApiService {
  // Gunakan IP Lokal (IPv4) komputer Anda jika menggunakan Physical Device, 
  // atau 10.0.2.2 jika menggunakan Android Emulator.
  static const String baseUrl = 'http://192.168.1.9:8000/api';

  static Future<dynamic> getCategories() async {
    final response = await http.get(Uri.parse('$baseUrl/categories'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    throw Exception('Gagal memuat kategori');
  }

  static Future<dynamic> getProducts() async {
    final response = await http.get(Uri.parse('$baseUrl/products'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    throw Exception('Gagal memuat produk');
  }

  static Future<dynamic> getBestSellers() async {
    final response = await http.get(Uri.parse('$baseUrl/products/best-sellers'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    throw Exception('Gagal memuat best sellers');
  }

  static Future<dynamic> validateTable(String tableCode) async {
    final response = await http.get(Uri.parse('$baseUrl/tables/$tableCode'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  static Future<dynamic> createOrder(Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl/orders'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode(data),
    );
    return jsonDecode(response.body);
  }

  // Upload Bukti Pembayaran
  static Future<Map<String, dynamic>> uploadPaymentProof(String orderNumber, File imageFile) async {
    try {
      var request = http.MultipartRequest('POST', Uri.parse('$baseUrl/orders/$orderNumber/upload-proof'));
      request.files.add(await http.MultipartFile.fromPath('bukti_pembayaran', imageFile.path));
      
      var streamedResponse = await request.send();
      var response = await http.Response.fromStream(streamedResponse);
      
      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      throw Exception('Gagal mengunggah bukti pembayaran');
    } catch (e) {
      throw Exception('Terjadi kesalahan jaringan: $e');
    }
  }

  static Future<dynamic> trackOrder(String orderNumber, String phone) async {
    final response = await http.post(
      Uri.parse('$baseUrl/orders/track'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'order_number': orderNumber, 'no_hp_pesanan': phone}),
    );
    return jsonDecode(response.body);
  }
}
