<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Produk</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-5xl space-y-6">
    <!-- Header -->
    <header class="bg-white shadow rounded-xl p-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">📦 Manajemen Produk</h1>
      <div>
        <button id="btnLogin" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Login</button>
        <button id="btnLogout" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg hidden">Logout</button>
      </div>
    </header>

    <!-- Login Form -->
    <section id="auth" class="bg-white shadow rounded-xl p-6 hidden">
      <h2 class="text-lg font-semibold mb-4">🔑 Login</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <input id="email" type="email" placeholder="Email"
               class="p-3 border rounded-lg focus:ring focus:ring-blue-300">
        <input id="password" type="password" placeholder="Password"
               class="p-3 border rounded-lg focus:ring focus:ring-blue-300">
        <button id="btnSubmitLogin"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg">Masuk</button>
      </div>
      <p id="loginMsg" class="text-sm mt-3 text-red-500"></p>
    </section>

    <!-- Form Produk -->
    <section class="bg-white shadow rounded-xl p-6">
      <h2 class="text-lg font-semibold mb-4">📝 Form Produk</h2>
      <input id="idProduk" type="hidden" />
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <input id="nama" placeholder="Nama Produk" class="p-3 border rounded-lg focus:ring focus:ring-green-300"/>
        <input id="harga" placeholder="Harga" class="p-3 border rounded-lg focus:ring focus:ring-green-300"/>
        <input id="stok" placeholder="Stok" class="p-3 border rounded-lg focus:ring focus:ring-green-300"/>
      </div>
      <div class="mt-4 flex gap-3">
        <button id="btnSave" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Simpan</button>
        <button id="btnReset" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Reset</button>
      </div>
      <p id="formMsg" class="text-sm mt-3 text-green-600"></p>
    </section>

    <!-- Daftar Produk -->
    <section class="bg-white shadow rounded-xl p-6">
      <h2 class="text-lg font-semibold mb-4">📋 Daftar Produk</h2>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-200 text-gray-700">
              <th class="p-3 text-left">#</th>
              <th class="p-3 text-left">Nama</th>
              <th class="p-3 text-left">Harga</th>
              <th class="p-3 text-left">Stok</th>
              <th class="p-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody id="produkBody" class="divide-y divide-gray-200"></tbody>
        </table>
      </div>
    </section>
  </div>

<script>
const API_BASE = '/api';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function setTokenHeader() {
  const token = localStorage.getItem('jwt_token');
  if (token) axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
  else delete axios.defaults.headers.common['Authorization'];
}

// Toggle Login Form
document.getElementById('btnLogin').addEventListener('click', () => {
  document.getElementById('auth').classList.toggle('hidden');
});

// Login
document.getElementById('btnSubmitLogin').addEventListener('click', async () => {
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  try {
    const res = await axios.post(API_BASE + '/login', { email, password });
    const token = res.data.data.access_token;
    localStorage.setItem('jwt_token', token);
    setTokenHeader();

    // ambil data user
    const resMe = await axios.get(API_BASE + '/me');
    document.getElementById('loginMsg').innerText = 'Login sebagai ' + resMe.data.data.name;

    document.getElementById('btnLogout').classList.remove('hidden');
    document.getElementById('btnLogin').classList.add('hidden');
    document.getElementById('auth').classList.add('hidden');

    loadProduks();
  } catch (err) {
    localStorage.removeItem('jwt_token');
    document.getElementById('loginMsg').innerText = err.response?.data?.message || 'Login gagal';
  }
});

// Logout
document.getElementById('btnLogout').addEventListener('click', async () => {
  try { await axios.post(API_BASE + '/logout'); } catch(e) {}
  localStorage.removeItem('jwt_token');
  setTokenHeader();
  document.getElementById('btnLogout').classList.add('hidden');
  document.getElementById('btnLogin').classList.remove('hidden');
});

// Save Produk
document.getElementById('btnSave').addEventListener('click', async () => {
  const id = document.getElementById('idProduk').value;
  const nama = document.getElementById('nama').value;
  const harga = parseInt(document.getElementById('harga').value);
  const stok = parseInt(document.getElementById('stok').value);

  try {
    if (id) {
      await axios.put(API_BASE + '/produk/' + id, { nama, harga, stok });
      document.getElementById('formMsg').innerText = 'Produk berhasil diupdate ✅';
    } else {
      await axios.post(API_BASE + '/produk', { nama, harga, stok });
      document.getElementById('formMsg').innerText = 'Produk berhasil ditambahkan ✅';
    }
    resetForm();
    loadProduks();
  } catch (err) {
    document.getElementById('formMsg').innerText = err.response?.data?.message || 'Error';
  }
});

// Reset Form
document.getElementById('btnReset').addEventListener('click', resetForm);
function resetForm() {
  document.getElementById('idProduk').value = '';
  document.getElementById('nama').value = '';
  document.getElementById('harga').value = '';
  document.getElementById('stok').value = '';
  document.getElementById('formMsg').innerText = '';
}

// Load Produk
async function loadProduks() {
  setTokenHeader();
  try {
    const res = await axios.get(API_BASE + '/produk');
    const produks = res.data.data;
    const tbody = document.getElementById('produkBody');
    tbody.innerHTML = '';
    produks.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.className = "hover:bg-gray-50";
      tr.innerHTML = `
        <td class="p-3">${i+1}</td>
        <td class="p-3">${p.nama}</td>
        <td class="p-3">Rp ${p.harga.toLocaleString('id-ID')}</td>
        <td class="p-3">${p.stok}</td>
        <td class="p-3 space-x-2">
          <button onclick="editProduk(${p.id})" class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 rounded-lg">Edit</button>
          <button onclick="deleteProduk(${p.id})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (err) {
    if (err.response?.status === 401) {
      document.getElementById('loginMsg').innerText = '⚠️ Token invalid / perlu login';
    }
  }
}

window.editProduk = async function(id) {
  try {
    const res = await axios.get(API_BASE + '/produk/' + id);
    const p = res.data.data;
    document.getElementById('idProduk').value = p.id;
    document.getElementById('nama').value = p.nama;
    document.getElementById('harga').value = p.harga;
    document.getElementById('stok').value = p.stok;
  } catch (err) {
    console.error(err);
  }
}

window.deleteProduk = async function(id) {
  if (!confirm('Yakin hapus produk ini?')) return;
  try {
    await axios.delete(API_BASE + '/produk/' + id);
    loadProduks();
  } catch (err) {
    alert('❌ Gagal hapus produk');
  }
}

// init
setTokenHeader();
loadProduks();
</script>
</body>
</html>
