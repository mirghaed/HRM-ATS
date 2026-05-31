<script>
    function careersPage(config) {
        return {
            dark: document.documentElement.classList.contains('dark'),
            drawer: false,
            scrolled: false,
            submitting: false,
            resumeFileName: '',
            resumeClientError: '',
            submitState: 'idle',
            submitMessage: '',
            submitErrors: [],
            filtersOpen: false,
            hasAnyJobs: !!config.hasAnyJobs,
            visibleJobsCount: 0,
            captchaSrc: '{{ route('careers.captcha.image') }}?v={{ time() }}',
            filters: {
                q: '',
                department: '',
                workMode: '',
                employmentType: '',
            },
            init() {
                this.onScroll();
                window.addEventListener('scroll', () => this.onScroll(), { passive: true });

                this.$watch('filters.q', () => this.recomputeVisibleJobs());
                this.$watch('filters.department', () => this.recomputeVisibleJobs());
                this.$watch('filters.workMode', () => this.recomputeVisibleJobs());
                this.$watch('filters.employmentType', () => this.recomputeVisibleJobs());

                this.$nextTick(() => this.recomputeVisibleJobs());

                this.$watch('drawer', (isOpen) => {
                    if (isOpen) {
                        document.body.style.overflow = 'hidden';
                        this.$nextTick(() => this.$refs.drawerFirstLink?.focus());
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            },
            refreshCaptcha() {
                this.captchaSrc = '{{ route('careers.captcha.image') }}?v=' + Date.now();
            },
            onScroll() {
                this.scrolled = window.scrollY > 10;
            },
            toggleTheme() {
                this.dark = !this.dark;
                document.documentElement.classList.toggle('dark', this.dark);
                localStorage.setItem('yadak-theme', this.dark ? 'dark' : 'light');
            },
            matchCard(el) {
                const title = (el.dataset.title || '').toLowerCase();
                const department = (el.dataset.department || '').toLowerCase();
                const workMode = (el.dataset.workMode || '').toLowerCase();
                const employmentType = (el.dataset.employmentType || '').toLowerCase();

                const q = this.filters.q.trim().toLowerCase();
                const matchesQuery = q === '' || title.includes(q) || department.includes(q);
                const matchesDepartment = this.filters.department === '' || department === this.filters.department.toLowerCase();
                const matchesWorkMode = this.filters.workMode === '' || workMode === this.filters.workMode.toLowerCase();
                const matchesEmploymentType = this.filters.employmentType === '' || employmentType === this.filters.employmentType.toLowerCase();

                return matchesQuery && matchesDepartment && matchesWorkMode && matchesEmploymentType;
            },
            recomputeVisibleJobs() {
                if (!this.$refs.jobsGrid) {
                    this.visibleJobsCount = this.hasAnyJobs ? 1 : 0;
                    return;
                }

                const cards = this.$refs.jobsGrid.querySelectorAll('[data-job-card]');
                let count = 0;
                cards.forEach((card) => {
                    if (this.matchCard(card)) {
                        count += 1;
                    }
                });
                this.visibleJobsCount = count;
            },
            resetFilters() {
                this.filters.q = '';
                this.filters.department = '';
                this.filters.workMode = '';
                this.filters.employmentType = '';
                this.recomputeVisibleJobs();
            },
            setResumeFileName(event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                this.resumeClientError = '';
                this.resumeFileName = file ? file.name : '';

                if (!file) {
                    return;
                }

                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                if (!isPdf) {
                    this.resumeClientError = 'فقط فایل PDF قابل بارگذاری است.';
                    event.target.value = '';
                    this.resumeFileName = '';
                    return;
                }

                const maxBytes = {{ ((int) ($hrmSettings['upload.max_resume_size_kb'] ?? 10240)) * 1024 }};
                if (file.size > maxBytes) {
                    this.resumeClientError = 'حجم فایل بیشتر از حد مجاز است.';
                    event.target.value = '';
                    this.resumeFileName = '';
                }
            },
            handleGeneralSubmit(event) {
                event.preventDefault();
                const form = event.target;
                this.resumeClientError = '';
                this.submitState = 'idle';
                this.submitMessage = '';
                this.submitErrors = [];

                if (this.submitting) {
                    return;
                }

                const resumeInput = form.querySelector('input[name="resume"]');
                if (resumeInput?.files?.length) {
                    const file = resumeInput.files[0];
                    const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                    const maxBytes = {{ ((int) ($hrmSettings['upload.max_resume_size_kb'] ?? 10240)) * 1024 }};

                    if (!isPdf || file.size > maxBytes) {
                        this.resumeClientError = !isPdf ? 'فقط فایل PDF قابل بارگذاری است.' : 'حجم فایل بیشتر از حد مجاز است.';
                        this.submitState = 'error';
                        this.submitMessage = this.resumeClientError;
                        return;
                    }
                }

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                this.submitting = true;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(async (response) => {
                        const isJson = (response.headers.get('content-type') || '').includes('application/json');
                        const payload = isJson ? await response.json() : {};

                        if (!response.ok) {
                            if (response.status === 422 && payload.errors) {
                                const allErrors = [];
                                Object.values(payload.errors).forEach((messages) => {
                                    (messages || []).forEach((message) => allErrors.push(message));
                                });

                                this.submitState = 'error';
                                this.submitMessage = payload.message || 'لطفاً خطاهای فرم را بررسی کنید.';
                                this.submitErrors = allErrors;

                                if (payload.errors.resume?.length) {
                                    this.resumeClientError = payload.errors.resume[0];
                                }

                                if (payload.errors.captcha?.length) {
                                    this.refreshCaptcha();
                                }

                                return;
                            }

                            this.submitState = 'error';
                            this.submitMessage = payload.message || 'خطایی در ارسال فرم رخ داد. لطفاً دوباره تلاش کنید.';
                            this.refreshCaptcha();
                            return;
                        }

                        if (!isJson || payload.status !== 'ok') {
                            this.submitState = 'error';
                            this.submitMessage = 'پاسخ نامعتبر از سرور دریافت شد. لطفاً دوباره تلاش کنید.';
                            this.refreshCaptcha();
                            return;
                        }

                        const trackingCode = payload.tracking_code ? ` کد رهگیری: ${payload.tracking_code}` : '';
                        this.submitState = 'success';
                        this.submitMessage = (payload.message || 'درخواست شما با موفقیت ثبت شد.') + trackingCode;
                        this.submitErrors = [];
                        this.resumeClientError = '';
                        this.resumeFileName = '';
                        form.reset();
                        this.refreshCaptcha();
                    })
                    .catch(() => {
                        this.submitState = 'error';
                        this.submitMessage = 'ارتباط با سرور برقرار نشد. لطفاً دوباره تلاش کنید.';
                        this.refreshCaptcha();
                    })
                    .finally(() => {
                        this.submitting = false;
                    });
            },
            filterLabel(type, value) {
                if (type === 'department') {
                    const option = this.$el.querySelector(`select[x-model="filters.department"] option[value="${value}"]`);
                    return option ? option.textContent.trim() : value;
                }

                if (type === 'workMode') {
                    const option = this.$el.querySelector(`select[x-model="filters.workMode"] option[value="${value}"]`);
                    return option ? option.textContent.trim() : value;
                }

                if (type === 'employmentType') {
                    const option = this.$el.querySelector(`select[x-model="filters.employmentType"] option[value="${value}"]`);
                    return option ? option.textContent.trim() : value;
                }

                return value;
            },
            handleDrawerTab(event) {
                if (!this.drawer || event.key !== 'Tab' || !this.$refs.drawerPanel) {
                    return;
                }

                const focusables = this.$refs.drawerPanel.querySelectorAll(
                    'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled])'
                );
                if (!focusables.length) {
                    return;
                }

                const first = focusables[0];
                const last = focusables[focusables.length - 1];
                const active = document.activeElement;

                if (event.shiftKey && active === first) {
                    event.preventDefault();
                    last.focus();
                    return;
                }

                if (!event.shiftKey && active === last) {
                    event.preventDefault();
                    first.focus();
                }
            },
            get hasActiveFilters() {
                return !!(this.filters.q || this.filters.department || this.filters.workMode || this.filters.employmentType);
            },
        };
    }
</script>
