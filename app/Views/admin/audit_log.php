<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Log Aktivitas - Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Log Aktivitas<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Pantau aktivitas sistem dan pengguna<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-end">
        <a href="/admin/audit-log/export?<?= http_build_query($filters) ?>" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </div>

<script>
    var logsData = <?= json_encode($logs) ?>;
</script>

<div x-data="auditLogManager(logsData)">

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Aksi</label>
                <select name="action" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm">
                    <option value="">Semua</option>
                    <?php foreach ($actions as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filters['action'] ?? '') === $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Entity</label>
                <select name="entity_type" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm">
                    <option value="">Semua</option>
                    <?php foreach ($entity_types as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filters['entity_type'] ?? '') === $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm" value="<?= $filters['start_date'] ?? '' ?>">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm" value="<?= $filters['end_date'] ?? '' ?>">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="/admin/audit-log" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Entity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="logs.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada log ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="log in logs" :key="log.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(log.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800" x-text="log.username || 'System'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold" 
                                      :class="getActionBadgeClass(log.action)" 
                                      x-text="log.action"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div x-show="log.entity_type" class="flex items-center gap-1">
                                    <span class="font-medium" x-text="log.entity_type"></span>
                                    <span x-show="log.entity_id" class="text-gray-400" x-text="'#' + log.entity_id"></span>
                                </div>
                                <span x-show="!log.entity_type">-</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" x-text="log.description || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500" x-text="log.ip_address || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button @click="openDetail(log)" class="text-blue-600 hover:text-blue-900 font-medium cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-500 order-2 md:order-1">
            Menampilkan hal <span class="font-medium"><?= $pager->getCurrentPage() ?></span> dari <span class="font-medium"><?= $pager->getPageCount() ?></span>
            (<span class="font-medium"><?= $pager->getTotal() ?></span> data)
        </div>
        <div class="order-1 md:order-2">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click.self="closeModal()" class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <template x-if="selectedLog">
                <div>
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between sticky top-0">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Log Detail</h3>
                            <span class="text-sm text-gray-500" x-text="formatDate(selectedLog.created_at)"></span>
                        </div>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- User Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-500">Pengguna</div>
                            <div class="md:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800" x-text="selectedLog.username || 'System'"></span>
                                <span x-show="selectedLog.user_id" class="text-xs text-gray-400 ml-2" x-text="'(ID: ' + selectedLog.user_id + ')'"></span>
                            </div>
                        </div>

                        <!-- Action Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-500">Aksi</div>
                            <div class="md:col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold" 
                                      :class="getActionBadgeClass(selectedLog.action)"
                                      x-text="selectedLog.action"></span>
                            </div>
                        </div>

                        <!-- Entity Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-500">Entitas</div>
                            <div class="md:col-span-2">
                                <template x-if="selectedLog.entity_type">
                                    <div>
                                        <div class="font-medium text-gray-900" x-text="selectedLog.entity_type"></div>
                                        <div x-show="selectedLog.entity_id" class="text-sm text-gray-500 mt-1" x-text="'ID: ' + selectedLog.entity_id"></div>
                                    </div>
                                </template>
                                <span x-show="!selectedLog.entity_type" class="text-gray-400">-</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-500">Deskripsi</div>
                            <div class="md:col-span-2 text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="selectedLog.description || '-'"></div>
                        </div>

                        <!-- Technical Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Info Teknis</div>
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-400 mb-1">IP Address</div>
                                        <div class="font-mono text-sm text-gray-600" x-text="selectedLog.ip_address || '-'"></div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400 mb-1">User Agent</div>
                                        <div class="font-mono text-sm text-gray-600" x-text="selectedLog.user_agent || '-'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function auditLogManager(logsData) {
    return {
        logs: logsData,
        showModal: false,
        selectedLog: null,
        
        init() {
            // Start polling every 5 seconds
            setInterval(() => this.checkUpdates(), 5000);
        },

        async checkUpdates() {
            if (this.logs.length === 0) return;
            
            // Get highest ID currently displayed
            const maxId = Math.max(...this.logs.map(l => parseInt(l.id)));
            
            try {
                const response = await fetch(`/admin/audit-log/updates?last_id=${maxId}`);
                const result = await response.json();
                
                if (result.success && result.logs.length > 0) {
                    // Prepend new logs (since table is DESC, we want new ones at top)
                    // But result.logs is ASC (oldest new -> newest new)
                    // So we reverse them to put newest at top
                    const newLogs = result.logs.reverse();
                    
                    this.logs = [...newLogs, ...this.logs];
                    
                    // Optional: trim list if too long
                    if (this.logs.length > 200) {
                        this.logs = this.logs.slice(0, 200);
                    }
                }
            } catch (e) {
                console.error("Polling error", e);
            }
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        },

        getActionBadgeClass(action) {
            const classes = {
                'LOGIN': 'bg-green-100 text-green-800',
                'LOGOUT': 'bg-gray-100 text-gray-800',
                'CREATE': 'bg-blue-100 text-blue-800',
                'UPDATE': 'bg-yellow-100 text-yellow-800',
                'DELETE': 'bg-red-100 text-red-800',
                'CALL': 'bg-indigo-100 text-indigo-800',
                'RECALL': 'bg-purple-100 text-purple-800',
                'COMPLETE': 'bg-green-100 text-green-800',
                'SKIP': 'bg-orange-100 text-orange-800',
                'RESET': 'bg-red-100 text-red-800',
            };
            return classes[action] || 'bg-gray-100 text-gray-800';
        },

        openDetail(log) {
            this.selectedLog = log;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selectedLog = null;
        }
    };
}
</script>
</div>
<?= $this->endSection() ?>
