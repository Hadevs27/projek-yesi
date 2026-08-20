class Product {
  final int id;
  final String name;
  final double price;
  final int stock;
  final String imageUrl;
  final int categoryId;
  final String? label;

  Product({
    required this.id,
    required this.name,
    required this.price,
    required this.stock,
    required this.imageUrl,
    required this.categoryId,
    this.label,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id_barang'],
      name: json['nama_barang'],
      price: double.parse(json['harga_barang'].toString()),
      stock: json['stok_barang'],
      imageUrl: json['foto_url'],
      categoryId: json['id_kategori'],
      label: json['label'],
    );
  }
}

class CartItem {
  final Product product;
  int quantity;

  CartItem({required this.product, this.quantity = 1});
}
