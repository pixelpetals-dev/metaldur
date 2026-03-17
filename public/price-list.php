<?php 
require_once __DIR__ . '/admin/bootstrap.php';
include 'includes/header.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/vue@3.5.27/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.13.4/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<style>
[v-cloak] {
    display: none;
}
.chart-popover {
    position: fixed;
    z-index: 9999;
    width: 320px;
    pointer-events: none;
}
.dataTables_wrapper {
    margin-top: 20px;
    color: #374151;
}
.dataTables_length {
    margin-bottom: 15px;
}
.dataTables_filter {
    margin-bottom: 15px;
}
.dataTables_length label,
.dataTables_filter label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dataTables_info {
    margin-top: 12px;
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}
.dataTables_paginate {
    margin-top: 12px;
    display: flex;
    gap: 4px;
}
.dataTables_wrapper .paginate_button {
    display: inline-block;
    padding: 6px 12px;
    margin-left: 4px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #ffffff;
    color: #374151 !important;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
}
.dataTables_wrapper .paginate_button:hover {
    background: #eff6ff;
    border-color: #2563eb;
    color: #1d4ed8 !important;
}
.dataTables_wrapper .paginate_button.current {
    background: #2563eb !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
    font-weight: 600;
}
.dataTables_wrapper .paginate_button.disabled {
    opacity: 0.4;
    cursor: default;
    pointer-events: none;
}
.dataTables_wrapper .paginate_button.disabled:hover {
    background: #ffffff;
    border-color: #d1d5db;
    color: #374151 !important;
}
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 6px 32px 6px 10px;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px;
    color: #374151;
    font-size: 14px;
    outline: none;
    cursor: pointer;
    appearance: none;
    transition: all 0.2s ease;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 6px 12px;
    margin-left: 6px;
    background-color: #ffffff;
    color: #374151;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
    width: 250px;
}
.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
.dataTables_wrapper .dataTables_length select:hover,
.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #9ca3af;
}
table.dataTable thead th {
    position: relative;
    cursor: pointer;
}
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before {
    right: 8px;
    content: "↕";
    opacity: 0.3;
    font-size: 12px;
}
table.dataTable thead .sorting_asc:before {
    content: "↑";
    opacity: 1;
    color: #2563eb;
}
table.dataTable thead .sorting_desc:before {
    content: "↓";
    opacity: 1;
    color: #2563eb;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: inline-block;
}
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: block;
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        margin-left: 0;
        margin-top: 5px;
    }
}
</style>
<main class="relative z-10 bg-[#114470]">
    <section class="relative h-[40vh] flex items-center justify-center overflow-hidden">
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold text-white brand-font mb-4 uppercase tracking-wider">PRICE LIST</h1>
            <p class="text-blue-100 font-light">Real-time pricing and market trends for precision tooling.</p>
        </div>
    </section>
    <section class="py-5 px-4 bg-[#114470]" id="public-price-app" v-cloak>
        <div class="max-w-xxl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden p-6 md:p-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <h2 class="text-3xl font-bold text-gray-800 brand-font border-l-4 border-blue-500 pl-4 uppercase">INVENTORY & TRENDS</h2>
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filter Category:</span>
                    <select v-model="filterCategory" @change="refreshTable" 
                        class="bg-gray-100 border border-gray-300 text-gray-900 font-semibold rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer shadow-sm hover:border-gray-400">
                        <option value="" class="text-gray-900">All Categories</option>
                        <option v-for="cat in categories" :key="cat" :value="cat" class="text-gray-900">
                            {{ cat }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="public_price_table" class="w-full text-left border-collapse display">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter">Item</th>
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter">SKU</th>
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter">Category</th>
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter text-right">Price</th>
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter">Stock</th>
                            <th class="py-4 px-2 text-xs font-black text-gray-400 uppercase tracking-tighter text-center">Trends</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="item in items" :key="item.id" class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-5 px-2 font-bold text-gray-800">{{ item.name }}</td>
                            <td class="py-5 px-2">
                                <span class="bg-blue-50 text-[#1a6096] px-2 py-1 rounded text-xs font-mono border border-blue-100">
                                    {{ item.sku }}
                                </span>
                            </td>
                            <td class="py-5 px-2">
                                <span class="text-gray-500 text-sm uppercase font-medium">{{ item.category }}</span>
                            </td>
                            <td class="py-5 px-2 text-right" :data-order="item.price">
                                <span class="text-xl font-bold text-[#1a6096]">{{ formatCurrency(item.price) }}</span>
                            </td>
                            <td class="py-5 px-2" :data-order="item.stock">
                                <span class="text-sm fs-9 px-2 py-1 rounded-full" :class="item.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                    {{ item.stock > 0 ? 'In Stock' : 'No Stock' }}
                                </span>
                            </td>
                            <td class="py-5 px-2 text-center">
                                <button class="trend-btn text-blue-400 hover:text-blue-600 transition-colors" 
                                        :data-id="item.id" 
                                        :data-name="item.name"
                                        type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-show="activeChart" id="chart-popover" 
             class="chart-popover bg-white shadow-2xl rounded-2xl p-4 border border-blue-100 ring-1 ring-black/5">
            <h6 class="text-gray-800 text-xs font-black uppercase mb-3 pb-2 border-b border-gray-100 tracking-widest">
                {{ activeItemName }} Trend
            </h6>
            <canvas id="trendChart"></canvas>
        </div>
    </section>
    <?php include 'includes/prefooter.php'; ?>
</main>
<script src="/admin/modules/price-lists/js/public-logic.js?v=<?php echo time();?>"></script>
<?php include 'includes/footer.php'; ?>