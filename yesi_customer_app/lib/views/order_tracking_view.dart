import 'package:flutter/material.dart';
import '../services/api_service.dart';
import 'package:intl/intl.dart';

class OrderTrackingView extends StatefulWidget {
  const OrderTrackingView({super.key});

  @override
  State<OrderTrackingView> createState() => _OrderTrackingViewState();
}

class _OrderTrackingViewState extends State<OrderTrackingView> {
  final _orderNumberController = TextEditingController();
  final _phoneController = TextEditingController();
  
  bool _isLoading = false;
  Map<String, dynamic>? _orderData;
  String? _errorMessage;

  Future<void> _trackOrder() async {
    final orderNum = _orderNumberController.text.trim();
    final phoneNum = _phoneController.text.trim();

    if (orderNum.isEmpty || phoneNum.isEmpty) {
      setState(() => _errorMessage = 'Mohon isi Nomor Pesanan dan Nomor HP');
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = null;
      _orderData = null;
    });

    try {
      final response = await ApiService.trackOrder(orderNum, phoneNum);
      if (response['success'] == true) {
        setState(() {
          _orderData = response['data'];
        });
      } else {
        setState(() {
          _errorMessage = response['message'] ?? 'Pesanan tidak ditemukan';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Terjadi kesalahan. Pastikan koneksi internet Anda stabil.';
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Widget _buildStatusBadge(String status) {
    Color badgeColor;
    switch (status.toLowerCase()) {
      case 'menunggu pembayaran':
        badgeColor = Colors.orange;
        break;
      case 'sedang diproses':
        badgeColor = Colors.blue;
        break;
      case 'selesai':
        badgeColor = Colors.green;
        break;
      case 'dibatalkan':
        badgeColor = Colors.red;
        break;
      default:
        badgeColor = Colors.grey;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: badgeColor.withOpacity(0.1),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: badgeColor, width: 1),
      ),
      child: Text(
        status,
        style: TextStyle(color: badgeColor, fontWeight: FontWeight.bold),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final formatCurrency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Lacak Pesanan'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Masukkan Detail Pesanan',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 15),
            TextField(
              controller: _orderNumberController,
              decoration: const InputDecoration(
                labelText: 'Nomor Pesanan (Cth: ORD-...)',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.receipt),
              ),
            ),
            const SizedBox(height: 15),
            TextField(
              controller: _phoneController,
              keyboardType: TextInputType.phone,
              decoration: const InputDecoration(
                labelText: 'Nomor HP',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.phone),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isLoading ? null : _trackOrder,
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 15),
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
              ),
              child: _isLoading 
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : const Text('Lacak Sekarang', style: TextStyle(fontSize: 16)),
            ),

            const SizedBox(height: 30),

            if (_errorMessage != null)
              Container(
                padding: const EdgeInsets.all(15),
                decoration: BoxDecoration(
                  color: Colors.red.shade50,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: Colors.red.shade200)
                ),
                child: Row(
                  children: [
                    const Icon(Icons.error_outline, color: Colors.red),
                    const SizedBox(width: 10),
                    Expanded(child: Text(_errorMessage!, style: const TextStyle(color: Colors.red))),
                  ],
                ),
              ),

            if (_orderData != null) ...[
              const Divider(thickness: 2),
              const SizedBox(height: 10),
              const Text('Hasil Pencarian', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 15),
              Card(
                elevation: 3,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                child: Padding(
                  padding: const EdgeInsets.all(15),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Status:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          _buildStatusBadge(_orderData!['status_pesanan'] ?? 'Unknown'),
                        ],
                      ),
                      const SizedBox(height: 15),
                      Text('No. Pesanan: ${_orderData!['id_pesanan']}'),
                      Text('Tanggal: ${DateFormat('dd MMM yyyy, HH:mm').format(DateTime.parse(_orderData!['tanggal_pesanan']))}'),
                      Text('Nama: ${_orderData!['nama_pesanan']}'),
                      Text('Tipe Bayar: ${_orderData!['jenis_pembayaran']}'),
                      const Divider(height: 30),
                      const Text('Daftar Menu:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 10),
                      
                      ...(_orderData!['detail_pesanan'] as List).map((item) {
                        final fotoUrl = item['barang']['foto_url'] ?? '';
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 12.0),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              ClipRRect(
                                borderRadius: BorderRadius.circular(8),
                                child: Image.network(
                                  fotoUrl,
                                  width: 50,
                                  height: 50,
                                  fit: BoxFit.cover,
                                  errorBuilder: (context, error, stackTrace) => const Icon(Icons.fastfood, size: 40, color: Colors.grey),
                                ),
                              ),
                              const SizedBox(width: 10),
                              Expanded(
                                child: Text('${item['jumlah_pesanan']}x ${item['barang']['nama_barang']}', style: const TextStyle(fontWeight: FontWeight.w500)),
                              ),
                              Text(formatCurrency.format(double.parse(item['subtotal_harga']))),
                            ],
                          ),
                        );
                      }),
                      
                      const Divider(height: 30),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Total Pembayaran:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          Text(
                            formatCurrency.format(double.parse(_orderData!['total_harga_pesanan'])),
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Colors.red),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              )
            ]
          ],
        ),
      ),
    );
  }
}
