/**
 * Chat assistant Clinixora (mode MVP) — catalogue + messages compacts
 */
export function registerAssistantChat() {
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const postJson = async (url, body) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify(body),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const message = data.message ?? data.reply ?? 'Erreur de communication avec l’assistant.';
            throw new Error(message);
        }

        return data;
    };

    const getJson = async (url) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message ?? 'Impossible de charger le catalogue.');
        }

        return data;
    };

    window.clinixoraAssistantChat = (routes = {}) => ({
        messages: [],
        draft: '',
        loading: false,
        pending: null,
        catalog: [],
        catalogLoading: true,
        catalogError: null,
        explorerStep: 'modules',
        selectedModule: null,
        selectedAction: null,
        initialized: false,
        routes: {
            catalog: routes.catalog ?? '/api/agent/catalog',
            messages: routes.messages ?? '/api/agent/messages',
            confirm: routes.confirm ?? '/api/agent/confirm',
        },

        get breadcrumbLabel() {
            if (this.explorerStep === 'syntax' && this.selectedAction) {
                return this.selectedAction.label;
            }
            if (this.explorerStep === 'actions' && this.selectedModule) {
                return this.selectedModule.label;
            }

            return 'Modules';
        },

        init() {
            if (!this.initialized) {
                this.initialized = true;
            }
            this.loadCatalog();
        },

        formatTime() {
            return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        },

        scrollChat() {
            this.$nextTick(() => {
                const el = this.$refs.messagesScroll;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });
        },

        resetExplorerScroll() {
            this.$nextTick(() => {
                const el = this.$refs.explorerPanel;
                if (el) {
                    el.scrollTop = 0;
                }
            });
        },

        async loadCatalog() {
            this.catalogLoading = true;
            this.catalogError = null;

            try {
                const data = await getJson(this.routes.catalog);
                this.catalog = data.modules ?? [];
            } catch (e) {
                this.catalogError = e.message ?? 'Catalogue indisponible.';
                this.catalog = [];
            } finally {
                this.catalogLoading = false;
            }
        },

        openModule(module) {
            this.selectedModule = module;
            this.selectedAction = null;
            this.explorerStep = 'actions';
        },

        openAction(action) {
            this.selectedAction = action;
            this.explorerStep = 'syntax';
        },

        explorerBack() {
            if (this.explorerStep === 'syntax') {
                this.explorerStep = 'actions';
                this.selectedAction = null;

                return;
            }
            if (this.explorerStep === 'actions') {
                this.explorerStep = 'modules';
                this.selectedModule = null;
            }
        },

        resetExplorer() {
            this.explorerStep = 'modules';
            this.selectedModule = null;
            this.selectedAction = null;
        },

        afterActionComplete(data) {
            if (data.reset_explorer) {
                this.resetExplorer();
                this.resetExplorerScroll();
            }
            this.scrollChat();
        },

        pushAssistantMessage(data) {
            this.messages.push({
                role: 'assistant',
                content: data.reply,
                display: data.display ?? null,
                expanded: false,
                time: this.formatTime(),
            });
        },

        visibleListItems(msg) {
            if (!msg.display?.items) {
                return [];
            }
            const limit = msg.display.preview_limit ?? 6;
            if (msg.expanded) {
                return msg.display.items;
            }

            return msg.display.items.slice(0, limit);
        },

        hasMoreListItems(msg) {
            if (!msg.display?.items) {
                return false;
            }
            const limit = msg.display.preview_limit ?? 6;

            return msg.display.items.length > limit && !msg.expanded;
        },

        hiddenListCount(msg) {
            if (!msg.display?.items) {
                return 0;
            }
            const limit = msg.display.preview_limit ?? 6;

            return Math.max(0, msg.display.items.length - limit);
        },

        useSyntax() {
            if (!this.selectedAction?.syntax) {
                return;
            }
            this.draft = this.selectedAction.syntax;
            this.resetExplorer();
            this.$nextTick(() => this.$refs.messageInput?.focus());
        },

        async send() {
            const text = this.draft.trim();
            if (!text || this.loading) {
                return;
            }

            this.messages.push({ role: 'user', content: text, time: this.formatTime() });
            this.draft = '';
            this.loading = true;
            this.pending = null;
            this.scrollChat();

            try {
                const data = await postJson(this.routes.messages, { message: text });
                this.pushAssistantMessage(data);
                if (data.pending?.token) {
                    this.pending = data.pending;
                }
                this.afterActionComplete(data);
            } catch (e) {
                this.pushAssistantMessage({ reply: e.message ?? 'Une erreur est survenue.' });
                this.scrollChat();
            } finally {
                this.loading = false;
            }
        },

        async confirmPending() {
            if (!this.pending?.token || this.loading) {
                return;
            }

            this.loading = true;

            try {
                const data = await postJson(this.routes.confirm, { token: this.pending.token });
                this.pushAssistantMessage(data);
                this.pending = null;
                this.afterActionComplete(data);
            } catch (e) {
                this.pushAssistantMessage({ reply: e.message ?? 'Confirmation échouée.' });
                this.scrollChat();
            } finally {
                this.loading = false;
            }
        },

        cancelPending() {
            this.pending = null;
            this.pushAssistantMessage({ reply: 'Action annulée.' });
            this.resetExplorer();
            this.resetExplorerScroll();
        },
    });
}
