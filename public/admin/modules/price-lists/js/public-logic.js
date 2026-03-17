const { createApp } = Vue;
createApp({
    data() {
        return {
            items: [],
            allItems: [], 
            history: {},
            categories: [],
            filterCategory: '',
            activeChart: false,
            activeItemName: '',
            chartInstance: null,
            dataTable: null
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData() {
            axios.get('/admin/modules/price-lists/api/public-data.php').then(res => {
                this.allItems = res.data.items;
                this.items = res.data.items;
                this.history = res.data.history;
                this.categories = [...new Set(this.items.map(i => i.category))].sort();
                this.$nextTick(() => {
                    this.initDataTable();
                });
            }).catch(err => {
                console.error('Error loading data:', err);
            });
        },
        initDataTable() {
            if (typeof $ === 'undefined' || !$.fn.DataTable) {
                console.error("jQuery or DataTables not loaded.");
                return;
            }
            if (this.dataTable) {
                this.dataTable.destroy();
                this.dataTable = null;
            }
            const vm = this;
            this.dataTable = $('#public_price_table').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']], 
                columnDefs: [
                    { orderable: true, targets: [0, 1, 2, 3] }, 
                    { orderable: false, targets: [4] }, 
                    { 
                        targets: 3, 
                        type: 'num',
                        render: function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                return parseFloat(data);
                            }
                            return vm.formatCurrency(data);
                        }
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    zeroRecords: "No matching records found"
                },
                drawCallback: function() {
                    vm.attachTrendHandlers();
                }
            });
            this.attachTrendHandlers();
        },
        attachTrendHandlers() {
            const vm = this;
            $('#public_price_table tbody').off('mouseenter', '.trend-btn');
            $('#public_price_table tbody').off('mouseleave', '.trend-btn');
            $('#public_price_table tbody').on('mouseenter', '.trend-btn', function(event) {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const item = vm.allItems.find(i => i.id == id);
                if (item) {
                    vm.showChart(item, event);
                }
            });
            $('#public_price_table tbody').on('mouseleave', '.trend-btn', function() {
                vm.hideChart();
            });
        },
        refreshTable() {
            if (!this.dataTable) return;
            if (this.filterCategory === '') {
                this.items = [...this.allItems];
            } else {
                this.items = this.allItems.filter(item => item.category === this.filterCategory);
            }
            this.$nextTick(() => {
                if (this.filterCategory === '') {
                    this.dataTable.column(2).search('').draw();
                } else {
                    this.dataTable.column(2).search('^' + this.filterCategory + '$', true, false).draw();
                }
            });
        },
        showChart(item, event) {
            const data = this.history[item.id] || [];
            if (data.length === 0) {
                console.warn("No trend data available for item:", item.name);
                return;
            }
            this.activeChart = true;
            this.activeItemName = item.name;
            const popover = document.getElementById('chart-popover');
            const yOffset = 180;
            const xOffset = 20;
            popover.style.top = (event.clientY - yOffset) + 'px';
            popover.style.left = (event.clientX + xOffset) + 'px';
            this.$nextTick(() => {
                const ctx = document.getElementById('trendChart');
                if (!ctx) return;
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }
                this.chartInstance = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.date),
                        datasets: [{
                            label: 'Price (INR)',
                            data: data.map(d => parseFloat(d.new_price)),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Price: ' + this.formatCurrency(context.parsed.y);
                                    }.bind(this)
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                beginAtZero: false,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString('en-IN');
                                    },
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            });
        },
        hideChart() {
            this.activeChart = false;
            if (this.chartInstance) {
                this.chartInstance.destroy();
                this.chartInstance = null;
            }
        },
        formatCurrency(val) {
            const num = parseFloat(val);
            if (isNaN(num)) return '₹0.00';
            return '₹' + num.toLocaleString('en-IN', { 
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }
}).mount('#public-price-app');