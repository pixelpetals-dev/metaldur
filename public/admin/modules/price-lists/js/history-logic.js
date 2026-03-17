const { createApp } = Vue;

createApp({
    data() {
        return {
            logs: []
        }
    },
    mounted() {
        this.fetchHistory();
    },
    methods: {
        fetchHistory() {
            axios.get('/admin/modules/price-lists/api/history-data.php')
                .then(res => {
                    this.logs = res.data.logs;
                    this.initTable();
                });
        },
        initTable() {
            this.$nextTick(() => {
                $('#kt_history_table').DataTable({
                    "order": [[0, "desc"]],
                    "pageLength": 50
                });
            });
        },
        formatCurrency(val) {
            return parseFloat(val).toFixed(2);
        },
        calculateDiff(log) {
            const diff = log.new_price - log.old_price;
            return Math.abs(diff).toFixed(2);
        },
        getPriceClass(log) {
            return log.new_price > log.old_price ? 'text-success' : 'text-danger';
        },
        getBadgeClass(log) {
            return log.new_price > log.old_price ? 'badge badge-light-success' : 'badge badge-light-danger';
        },
        getDirectionIcon(log) {
            return log.new_price > log.old_price ? '↑' : '↓';
        }
    }
}).mount('#price-history-app');