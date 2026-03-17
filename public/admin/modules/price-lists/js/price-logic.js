const { createApp, ref, onMounted, nextTick } = Vue;
createApp({
    setup() {
        const items = ref([]);
        const categories = ref([]);
        const isSyncing = ref(false);
        const filters = ref({ category: '' });
        const adjustment = ref({ value: '', type: 'fixed', direction: 'increase' });
        let dt;
        const loadItems = async () => {
            try {
                const response = await axios.get('/admin/modules/price-lists/api/get-items.php');
                items.value = response.data.items;
                categories.value = response.data.categories || [];
                await nextTick();
                initDatatable();
            } catch (error) {
                console.error("Error loading items:", error);
                Swal.fire('Error', 'Failed to load items', 'error');
            }
        };
        const syncWithZoho = async () => {
            isSyncing.value = true;
            try {
                const csrf = document.getElementById('global_csrf').value;
                const response = await axios.post('/admin/modules/price-lists/api/sync-zoho.php', {
                    _csrf: csrf
                });
                if (response.data.success) {
                    Swal.fire({
                        text: `Sync Complete! Processed ${response.data.count} items.`,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: { confirmButton: "btn btn-primary" }
                    });
                    loadItems();
                } else {
                    throw new Error(response.data.message || 'Unknown error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Sync Failed', error.message || 'Check console logs', 'error');
            } finally {
                isSyncing.value = false;
            }
        };
        const initDatatable = () => {
            if (dt) {
                dt.destroy();
            }
            dt = $('#kt_price_table').DataTable({
                info: true,
                order: [],
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: 0 }, 
                ]
            });
        };
        const formatCurrency = (value) => {
            return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(value);
        };
        const formatDate = (dateString) => {
            if(!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB'); 
        };
        const confirmUpdate = () => {
             Swal.fire({
                title: 'Are you sure?',
                text: `This will update prices for all items in ${filters.value.category}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update prices!'
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkUpdate();
                }
            });
        };
        const performBulkUpdate = async () => {
        };
        const handleFileUpload = (event) => {
        };
        onMounted(() => {
            loadItems();
        });
        return {
            items,
            categories,
            filters,
            adjustment,
            isSyncing,
            syncWithZoho,
            confirmUpdate,
            handleFileUpload,
            formatCurrency,
            formatDate
        };
    }
}).mount('#price-list-app');