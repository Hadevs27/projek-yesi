async function test() {
  const data = {
    nama_pesanan: 'Harry Test',
    no_hp_pesanan: '08123456789',
    alamat_pesanan: 'Meja 1',
    jenis_pembayaran: 'Tunai / Kasir',
    id_meja: null,
    items: [
      {
        id_barang: 1, // assume there's item 1
        jumlah: 1
      }
    ]
  };

  try {
    console.log("Sending request to Vercel...");
    const res = await fetch('https://yesi-project-3ppo.vercel.app/api/orders/create', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    const text = await res.text();
    console.log("STATUS:", res.status);
    console.log("RESPONSE:", text);
  } catch (e) {
    console.error("ERROR:", e);
  }
}

test();
