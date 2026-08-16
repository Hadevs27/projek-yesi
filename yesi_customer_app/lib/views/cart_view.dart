import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/cart_provider.dart';
import '../services/api_service.dart';
import 'qris_payment_view.dart';

class CartView extends StatefulWidget {
  const CartView({super.key});

  @override
  State<CartView> createState() => _CartViewState();
}

class _CartViewState extends State<CartView> {
  final _formKey = GlobalKey<FormState>();
  String _name = '';
  String _phone = '';
  String _address = '';
  String _paymentMethod = 'Tunai / Kasir';
  bool _isLoading = false;

  void _checkout() async {
    if (!_formKey.currentState!.validate()) return;
    _formKey.currentState!.save();
    
    final cart = Provider.of<CartProvider>(context, listen: false);
    if (cart.items.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Keranjang kosong')));
      return;
    }

    setState(() => _isLoading = true);

    try {
      final orderData = {
        'nama_pesanan': _name,
        'no_hp_pesanan': _phone,
        'alamat_pesanan': _address,
        'jenis_pembayaran': _paymentMethod,
        'id_meja': cart.tableId,
        'items': cart.items.values.map((e) => {
          'id_barang': e.product.id,
          'jumlah': e.quantity,
        }).toList(),
      };

      final orderResponse = await ApiService.createOrder(orderData);
      
      if (orderResponse['success'] == true) {
        String orderNumber = orderResponse['data']['order_number'];
        
        // Jika QRIS, arahkan ke halaman QRIS Statis
        if (_paymentMethod == 'QRIS') {
          cart.clear(); // Bersihkan keranjang
          
          Navigator.of(context).pushReplacement(MaterialPageRoute(
            builder: (ctx) => QrisPaymentView(orderNumber: orderNumber),
          ));
          return;
        }
        
        // Jika Tunai / Kasir
        cart.clear();
        showDialog(
          context: context,
          builder: (ctx) => AlertDialog(
            title: const Text('Pesanan Berhasil'),
            content: Text('Nomor Order Anda: $orderNumber\n\nSilakan langsung menuju kasir untuk melakukan pembayaran uang tunai.'),
            actions: [
              TextButton(
                onPressed: () => Navigator.of(context).popUntil((route) => route.isFirst),
                child: const Text('OK'),
              )
            ],
          )
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Keranjang Belanja'),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: cart.items.length,
              itemBuilder: (ctx, i) {
                final item = cart.items.values.toList()[i];
                return ListTile(
                  leading: Image.network(item.product.imageUrl, width: 50, fit: BoxFit.cover),
                  title: Text(item.product.name),
                  subtitle: Text('Rp ${item.product.price} x ${item.quantity}'),
                  trailing: IconButton(
                    icon: const Icon(Icons.delete, color: Colors.red),
                    onPressed: () => cart.removeItem(item.product.id),
                  ),
                );
              },
            ),
          ),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: const BoxDecoration(color: Colors.white, boxShadow: [BoxShadow(blurRadius: 5, color: Colors.black12)]),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                  Text('Total: Rp ${cart.totalAmount}', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 10),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Nama Lengkap'),
                    validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                    onSaved: (v) => _name = v!,
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'No HP / WA'),
                    validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                    onSaved: (v) => _phone = v!,
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Alamat Pengiriman (Ketik Nama Meja Jika Makan di Tempat)'),
                    validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                    onSaved: (v) => _address = v!,
                  ),
                  DropdownButtonFormField<String>(
                    value: _paymentMethod,
                    items: ['Tunai / Kasir', 'QRIS'].map((e) => DropdownMenuItem(value: e, child: Text(e))).toList(),
                    onChanged: (v) => setState(() => _paymentMethod = v!),
                    decoration: const InputDecoration(labelText: 'Metode Pembayaran'),
                  ),
                  const SizedBox(height: 20),
                  _isLoading
                      ? const CircularProgressIndicator()
                      : ElevatedButton(
                          onPressed: _checkout,
                          style: ElevatedButton.styleFrom(minimumSize: const Size(double.infinity, 50)),
                          child: const Text('CHECKOUT SEKARANG'),
                        )
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
