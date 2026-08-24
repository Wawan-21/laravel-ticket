<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Tiket</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2"></div>

    <!-- Main Navigation Header -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl shadow-lg shadow-indigo-500/20">
                    <i class="fa-solid fa-ticket text-xl text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight text-white">TicketDesk</h1>
                    <p class="text-xs text-slate-400">Sistem Pengaduan Pelanggan</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="fetchTickets()" class="flex items-center gap-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium rounded-lg border border-slate-700 transition">
                    <i class="fa-solid fa-arrows-rotate" id="refresh-icon"></i>
                    <span>Refresh Data</span>
                </button>
                <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Buat Tiket Baru</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Tiket</p>
                    <h3 id="stat-total" class="text-2xl font-bold text-white mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-slate-700/50 rounded-xl flex items-center justify-center text-slate-300">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
            </div>
            <!-- Pending -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-amber-400 uppercase tracking-wider">Pending</p>
                    <h3 id="stat-pending" class="text-2xl font-bold text-amber-400 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 border border-amber-500/20">
                    <i class="fa-regular fa-clock text-xl"></i>
                </div>
            </div>
            <!-- Process -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-blue-400 uppercase tracking-wider">Diproses</p>
                    <h3 id="stat-process" class="text-2xl font-bold text-blue-400 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 border border-blue-500/20">
                    <i class="fa-solid fa-spinner text-xl"></i>
                </div>
            </div>
            <!-- Completed -->
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-emerald-400 uppercase tracking-wider">Selesai</p>
                    <h3 id="stat-completed" class="text-2xl font-bold text-emerald-400 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-800/40 p-4 rounded-xl border border-slate-800">
            <!-- Search -->
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                <input type="text" id="search-input" oninput="filterTickets()" placeholder="Cari pelanggan / deskripsi..." 
                    class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition placeholder:text-slate-500">
            </div>

            <!-- Status Filter Tabs -->
            <div class="flex items-center gap-1 bg-slate-900 p-1 rounded-lg border border-slate-800 w-full sm:w-auto overflow-x-auto">
                <button onclick="setFilter('all')" id="filter-all" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-md transition text-slate-300 bg-slate-800">Semua</button>
                <button onclick="setFilter('pending')" id="filter-pending" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-md transition text-slate-400 hover:text-slate-200">Pending</button>
                <button onclick="setFilter('process')" id="filter-process" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-md transition text-slate-400 hover:text-slate-200">Process</button>
                <button onclick="setFilter('completed')" id="filter-completed" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-md transition text-slate-400 hover:text-slate-200">Selesai</button>
            </div>
        </div>

        <!-- Ticket Table Container -->
        <div class="bg-slate-800/40 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/80 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-700/60">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Telepon</th>
                            <th class="py-4 px-6 max-w-md">Deskripsi Masalah</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ticket-table-body" class="divide-y divide-slate-800 text-sm">
                        <!-- Table rows populated dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Loading Indicator -->
            <div id="loading-state" class="py-12 text-center text-slate-500 hidden">
                <i class="fa-solid fa-spinner fa-spin text-3xl mb-2 text-indigo-500"></i>
                <p>Memuat data tiket dari REST API...</p>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="py-16 text-center text-slate-500 hidden">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-600">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                </div>
                <h4 class="font-semibold text-slate-400">Tidak ada data tiket</h4>
                <p class="text-xs text-slate-600 mt-1">Belum ada tiket pengaduan yang terdaftar.</p>
            </div>
        </div>
    </main>

    <!-- Modal Form (Tambah & Edit Tiket) -->
    <div id="ticket-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl max-w-lg w-full p-6 shadow-2xl relative transition-all scale-95 opacity-0 transform" id="modal-card">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-700">
                <h3 id="modal-title" class="text-lg font-bold text-white">Buat Tiket Pengaduan Baru</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-700 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="ticket-form" onsubmit="handleFormSubmit(event)" class="mt-4 space-y-4">
                <input type="hidden" id="ticket-id">

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Pelanggan</label>
                    <input type="text" id="customer_name" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nomor Telepon</label>
                    <!-- [PERUBAHAN 1]: Hanya izinkan angka & maksimal 15 digit -->
                    <input type="tel" id="phone_number" required placeholder="Contoh: 081234567890" maxlength="15"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Status Tiket</label>
                    <select id="status" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                        <option value="pending">Pending</option>
                        <option value="process">Diproses (Process)</option>
                        <option value="completed">Selesai (Completed)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Deskripsi Masalah</label>
                    <textarea id="issue_description" rows="4" required placeholder="Tuliskan keluhan pelanggan secara detail..."
                        class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</button>
                    <button type="submit" id="submit-btn" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-indigo-600/30 transition">
                        Simpan Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript REST API Integration -->
    <script>
        const API_URL = 'http://localhost:8000/api/tickets';
        let allTickets = [];
        let currentFilter = 'all';

        // Load data on start
        document.addEventListener('DOMContentLoaded', () => {
            fetchTickets();
        });

        // Fetch Tickets from API
        async function fetchTickets() {
            showLoading(true);
            const refreshIcon = document.getElementById('refresh-icon');
            refreshIcon.classList.add('fa-spin');

            try {
                const response = await fetch(API_URL);
                const result = await response.json();

                if (result.status || Array.isArray(result.data)) {
                    allTickets = result.data || [];
                    updateStats();
                    filterTickets();
                } else {
                    showToast('Gagal memuat data dari server', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Tidak dapat terhubung ke Server Laravel (CORS / Server mati)', 'error');
            } finally {
                showLoading(false);
                setTimeout(() => refreshIcon.classList.remove('fa-spin'), 500);
            }
        }

        // Render Table Data
        function renderTable(tickets) {
            const tbody = document.getElementById('ticket-table-body');
            const emptyState = document.getElementById('empty-state');
            tbody.innerHTML = '';

            if (tickets.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
            }

            tickets.forEach(ticket => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-800/50 transition border-b border-slate-800/60';
                
                // [PERUBAHAN 2]: Format link & template pesan WhatsApp Direct
                let phone = (ticket.phone_number || '').replace(/^0/, '62');
                let waMessage = encodeURIComponent(`Halo ${ticket.customer_name}, mengenai pengaduan Anda (#${ticket.id}): "${ticket.issue_description}". Status tiket Anda saat ini: *${(ticket.status || 'PENDING').toUpperCase()}*. Terima kasih!`);
                let waUrl = `https://wa.me/${phone}?text=${waMessage}`;

                row.innerHTML = `
                    <td class="py-4 px-6 font-mono text-xs text-slate-400">#${ticket.id}</td>
                    <td class="py-4 px-6 font-semibold text-white">${escapeHtml(ticket.customer_name)}</td>
                    <td class="py-4 px-6 text-slate-300 font-mono text-xs">${escapeHtml(ticket.phone_number)}</td>
                    <td class="py-4 px-6 text-slate-300 max-w-md truncate" title="${escapeHtml(ticket.issue_description)}">${escapeHtml(ticket.issue_description)}</td>
                    <td class="py-4 px-6">${getStatusBadge(ticket.status)}</td>
                    <td class="py-4 px-6 text-right space-x-1">
                        <!-- Tombol WA Direct -->
                        <a href="${waUrl}" target="_blank" class="p-2 inline-block text-emerald-400 hover:text-emerald-300 hover:bg-slate-700/50 rounded-lg transition" title="Hubungi via WhatsApp">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </a>
                        <button onclick="openEditModal(${ticket.id})" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-slate-700/50 rounded-lg transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="deleteTicket(${ticket.id})" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-700/50 rounded-lg transition" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Status Badges Format
        function getStatusBadge(status) {
            const s = (status || 'pending').toLowerCase();
            if (s === 'pending') {
                return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Pending</span>`;
            } else if (s === 'process' || s === 'diproses') {
                return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Diproses</span>`;
            } else {
                return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Selesai</span>`;
            }
        }

        // Update Stat Cards
        function updateStats() {
            document.getElementById('stat-total').innerText = allTickets.length;
            document.getElementById('stat-pending').innerText = allTickets.filter(t => (t.status||'').toLowerCase() === 'pending').length;
            document.getElementById('stat-process').innerText = allTickets.filter(t => (t.status||'').toLowerCase() === 'process' || (t.status||'').toLowerCase() === 'diproses').length;
            document.getElementById('stat-completed').innerText = allTickets.filter(t => (t.status||'').toLowerCase() === 'completed' || (t.status||'').toLowerCase() === 'selesai').length;
        }

        // Filter and Search
        function filterTickets() {
            const search = document.getElementById('search-input').value.toLowerCase();
            let filtered = allTickets.filter(t => {
                const nameMatch = (t.customer_name || '').toLowerCase().includes(search);
                const descMatch = (t.issue_description || '').toLowerCase().includes(search);
                const phoneMatch = (t.phone_number || '').includes(search);
                return nameMatch || descMatch || phoneMatch;
            });

            if (currentFilter !== 'all') {
                filtered = filtered.filter(t => (t.status || '').toLowerCase() === currentFilter);
            }

            renderTable(filtered);
        }

        function setFilter(filter) {
            currentFilter = filter;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-slate-800', 'text-slate-200');
                btn.classList.add('text-slate-400');
            });
            const activeBtn = document.getElementById(`filter-${filter}`);
            activeBtn.classList.add('bg-slate-800', 'text-slate-200');
            filterTickets();
        }

        // Handle Create & Update
        async function handleFormSubmit(e) {
            e.preventDefault();
            const id = document.getElementById('ticket-id').value;
            const payload = {
                customer_name: document.getElementById('customer_name').value,
                phone_number: document.getElementById('phone_number').value,
                issue_description: document.getElementById('issue_description').value,
                status: document.getElementById('status').value
            };

            const isEdit = Boolean(id);
            const url = isEdit ? `${API_URL}/${id}` : API_URL;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    showToast(isEdit ? 'Tiket berhasil diperbarui!' : 'Tiket berhasil dibuat!', 'success');
                    closeModal();
                    fetchTickets();
                } else {
                    const data = await res.json();
                    showToast(data.message || 'Gagal menyimpan tiket', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan koneksi', 'error');
            }
        }

        // Delete Ticket
        async function deleteTicket(id) {
            if (!confirm(`Apakah Anda yakin ingin menghapus tiket #${id}?`)) return;

            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (res.ok) {
                    showToast(`Tiket #${id} berhasil dihapus`, 'success');
                    fetchTickets();
                } else {
                    showToast('Gagal menghapus tiket', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan koneksi', 'error');
            }
        }

        // Modal Helpers
        function openCreateModal() {
            document.getElementById('ticket-form').reset();
            document.getElementById('ticket-id').value = '';
            document.getElementById('modal-title').innerText = 'Buat Tiket Pengaduan Baru';
            document.getElementById('status').value = 'pending';
            showModal(true);
        }

        function openEditModal(id) {
            const ticket = allTickets.find(t => t.id === id);
            if (!ticket) return;

            document.getElementById('ticket-id').value = ticket.id;
            document.getElementById('customer_name').value = ticket.customer_name;
            document.getElementById('phone_number').value = ticket.phone_number;
            document.getElementById('issue_description').value = ticket.issue_description;
            document.getElementById('status').value = ticket.status || 'pending';
            
            document.getElementById('modal-title').innerText = `Edit Tiket #${ticket.id}`;
            showModal(true);
        }

        function showModal(show) {
            const modal = document.getElementById('ticket-modal');
            const card = document.getElementById('modal-card');
            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => modal.classList.add('hidden'), 200);
            }
        }

        function closeModal() {
            showModal(false);
        }

        function showLoading(show) {
            document.getElementById('loading-state').classList.toggle('hidden', !show);
        }

        // Toast Notifications
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600' : 'bg-rose-600';
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';

            toast.className = `${bgClass} text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 text-sm font-medium transition-all transform translate-y-2 opacity-0`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${escapeHtml(message)}</span>`;

            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function escapeHtml(str) {
            return String(str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
        }
    </script>
</body>
</html>