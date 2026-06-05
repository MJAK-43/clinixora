const PAGE_PARAMS = {
    countries: 'countries_page',
    cities: 'cities_page',
    districts: 'districts_page',
};

function queryFromObject(query) {
    const params = new URLSearchParams();
    Object.entries(query).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            params.set(key, String(value));
        }
    });

    return params;
}

export function registerGeographyPage() {
    window.Alpine.data('geographyPage', (config) => ({
        baseUrl: config.baseUrl,
        query: { ...config.initialQuery },
        loading: {
            countries: false,
            cities: false,
            districts: false,
        },

        applySearch(detail) {
            const { panel, param, value } = detail;
            if (value) {
                this.query[param] = value;
            } else {
                delete this.query[param];
            }

            const pageParam = PAGE_PARAMS[panel];
            if (pageParam) {
                delete this.query[pageParam];
            }

            this.refreshPanel(panel);
        },

        async refreshPanel(panel) {
            const target = document.getElementById(`geo-${panel}-body`);
            if (!target) {
                return;
            }

            this.loading[panel] = true;
            target.classList.add('opacity-50', 'pointer-events-none');

            try {
                const params = queryFromObject(this.query);
                const response = await fetch(`${this.baseUrl}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Geography-Panel': panel,
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
                console.error('Recherche géographie :', error);
            } finally {
                this.loading[panel] = false;
                target.classList.remove('opacity-50', 'pointer-events-none');
            }
        },

        onPanelClick(event, panel) {
            const link = event.target.closest('a[href]');
            if (!link) {
                return;
            }

            if (link.hasAttribute('data-geo-nav')) {
                return;
            }

            const pageParam = PAGE_PARAMS[panel];
            if (!pageParam || !link.href.includes(`${pageParam}=`)) {
                return;
            }

            event.preventDefault();
            const url = new URL(link.href);
            this.query = Object.fromEntries(url.searchParams.entries());
            this.refreshPanel(panel);
        },

        syncUrl() {
            const params = queryFromObject(this.query);
            const next = `${this.baseUrl}?${params.toString()}`;
            window.history.replaceState({}, '', next);
        },
    }));
}
