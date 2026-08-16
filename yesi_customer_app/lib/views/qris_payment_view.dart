import 'package:flutter/material.dart';
import 'dart:io';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'home_view.dart';
import '../services/api_service.dart';

class QrisPaymentView extends StatefulWidget {
  final String orderNumber;

  const QrisPaymentView({super.key, required this.orderNumber});

  @override
  State<QrisPaymentView> createState() => _QrisPaymentViewState();
}

class _QrisPaymentViewState extends State<QrisPaymentView> {
  File? _imageProof;
  bool _isUploading = false;
  final ImagePicker _picker = ImagePicker();

  Future<void> _pickImage() async {
    final XFile? image = await _picker.pickImage(source: ImageSource.gallery);
    if (image != null) {
      setState(() {
        _imageProof = File(image.path);
      });
    }
  }

  Future<void> _uploadProofAndFinish() async {
    if (_imageProof == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap unggah bukti transfer terlebih dahulu!')),
      );
      return;
    }

    setState(() {
      _isUploading = true;
    });

    try {
      await ApiService.uploadPaymentProof(widget.orderNumber, _imageProof!);
      
      if (!mounted) return;
      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (ctx) => AlertDialog(
          title: const Text('Menunggu Verifikasi'),
          content: const Text(
            'Terima kasih! Bukti pembayaran Anda berhasil diunggah. Silakan tunggu kasir memverifikasi pesanan Anda.',
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pushAndRemoveUntil(
                  MaterialPageRoute(builder: (ctx) => const HomeView()),
                  (route) => false,
                );
              },
              child: const Text('OK, KEMBALI KE MENU'),
            ),
          ],
        ),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      if (mounted) {
        setState(() {
          _isUploading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pembayaran QRIS'),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Silakan Scan QRIS Berikut',
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            const Text(
              'Gunakan aplikasi M-Banking atau E-Wallet (Ovo, Gopay, Dana, dll) untuk memindai kode di bawah ini.',
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 30),
            Center(
              child: Container(
                padding: const EdgeInsets.all(15),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(15),
                  boxShadow: const [BoxShadow(blurRadius: 10, color: Colors.black12)],
                ),
                child: Image.asset(
                  'assets/images/qris_anda.png',
                  width: 250,
                  height: 250,
                  fit: BoxFit.contain,
                  errorBuilder: (ctx, err, stack) => Container(
                    width: 250,
                    height: 250,
                    color: Colors.grey[200],
                    child: const Center(
                      child: Text('Gambar QRIS Belum Dimasukkan Admin', textAlign: TextAlign.center),
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 20),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Expanded(
                  child: Text(
                    'Nomor Pesanan Anda:\n${widget.orderNumber}',
                    textAlign: TextAlign.center,
                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.red),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.copy),
                  tooltip: 'Salin Nomor Pesanan',
                  onPressed: () {
                    Clipboard.setData(ClipboardData(text: widget.orderNumber));
                    ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Nomor pesanan disalin!')));
                  },
                ),
              ],
            ),
            const SizedBox(height: 30),
            
            // Kolom Upload Bukti
            Container(
              padding: const EdgeInsets.all(15),
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey.shade300),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Column(
                children: [
                  const Text('Unggah Bukti Transfer', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 10),
                  _imageProof != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.file(_imageProof!, height: 150, fit: BoxFit.cover),
                        )
                      : const Icon(Icons.receipt_long, size: 50, color: Colors.grey),
                  const SizedBox(height: 10),
                  OutlinedButton.icon(
                    onPressed: _pickImage,
                    icon: const Icon(Icons.image),
                    label: Text(_imageProof == null ? 'Pilih Gambar' : 'Ganti Gambar'),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 30),
            _isUploading 
              ? const Center(child: CircularProgressIndicator())
              : ElevatedButton(
                  onPressed: _uploadProofAndFinish,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 15),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                  child: const Text('SAYA SUDAH TRANSFER', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                ),
          ],
        ),
      ),
    );
  }
}
