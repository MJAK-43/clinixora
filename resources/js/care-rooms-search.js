function queryFromObject(query) {
    const params = new URLSearchParams();
    Object.entries(query).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            params.set(key, String(value));
        }
    });

    return params;
}

export function registerCareRoomsPage() {
    window.Alpine.data('careRoomsPage', (config) => ({
        baseUrl: config.baseUrl,
        query: { ...config.initialQuery },
        loading: false,

        applySearch(detail) {
            const { param, value } = detail;
            if (value) {
                this.query[param] = value;
            } else {
                delete this.query[param];
            }

            delete this.query.care_rooms_page;
            this.refreshTable();
        },

        async refreshTable() {
            const target = document.getElementById('care-rooms-table-body');
            if (!target) {
                return;
            }

            this.loading = true;
            target.classList.add('opacity-50', 'pointer-events-none');

            try {
                const params = queryFromObject(this.query);
                const response = await fetch(`${this.baseUrl}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CareRoom-Panel': 'table',
                        Accept: 'text/html',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                target.innerHTML = await response.text();
                this.syncUrl();
            } catch (error) {
                console.error('Recherche salles de soin :', error);
            } finally {
                this.loading = false;
                target.classList.remove('opacity-50', 'pointer-events-none');
            }
        },

        syncUrl() {
            const params = queryFromObject(this.query);
            const url = params.toString() ? `${this.baseUrl}?${params.toString()}` : this.baseUrl;
            window.history.replaceState({}, '', url);
        },
    }));
}
