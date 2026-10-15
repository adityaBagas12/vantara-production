import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Interactive Calendar Component for Alpine.js
Alpine.data('calendarWidget', (initialPackageId = null) => ({
    packageId: initialPackageId,
    searchQuery: '',
    currentYear: new Date().getFullYear(),
    currentMonth: new Date().getMonth() + 1, // 1 - 12
    monthNames: [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ],
    dayNames: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
    calendarDays: [],
    bookedDates: [],
    isLoading: false,
    selectedDate: '',
    rangeStart: '',
    rangeEnd: '',
    checkResult: null,
    isCheckingDate: false,

    matchesSearch(haystack) {
        if (!this.searchQuery) return true;
        const q = this.searchQuery.toLowerCase().trim();
        return haystack.toLowerCase().includes(q);
    },

    init() {
        if (this.rangeStart && this.rangeEnd && this.rangeEnd < this.rangeStart) {
            this.rangeEnd = '';
        }
        this.fetchMonthData();
    },

    setPackage(pkgId) {
        this.packageId = pkgId;
        this.fetchMonthData();
        if (this.selectedDate) {
            this.checkSpecificDate(this.selectedDate);
        }
    },

    prevMonth() {
        if (this.currentMonth === 1) {
            this.currentMonth = 12;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.fetchMonthData();
    },

    nextMonth() {
        if (this.currentMonth === 12) {
            this.currentMonth = 1;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.fetchMonthData();
    },

    async fetchMonthData() {
        this.isLoading = true;
        try {
            const params = new URLSearchParams({
                year: this.currentYear,
                month: this.currentMonth,
            });
            if (this.packageId) {
                params.append('package_id', this.packageId);
            }

            const response = await fetch(`/api/availability/calendar?${params.toString()}`);
            if (response.ok) {
                const data = await response.json();
                this.bookedDates = data.booked_dates || [];
            }
        } catch (error) {
            console.error('Failed to fetch calendar data:', error);
        } finally {
            this.isLoading = false;
            this.generateCalendarDays();
        }
    },

    generateCalendarDays() {
        const days = [];
        const jsDay = new Date(this.currentYear, this.currentMonth - 1, 1).getDay();
        const firstDayOffset = (jsDay + 6) % 7; // Monday = 0, ..., Sunday = 6
        const daysInMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Previous month padding
        const prevMonthDays = new Date(this.currentYear, this.currentMonth - 1, 0).getDate();
        for (let i = firstDayOffset - 1; i >= 0; i--) {
            days.push({
                day: prevMonthDays - i,
                dateString: '',
                isCurrentMonth: false,
                isPast: true,
                isBooked: false,
                bookingInfo: null,
            });
        }

        // Current month days
        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(this.currentYear, this.currentMonth - 1, d);
            const dateString = `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const isPast = dateObj < today;
            const booking = this.bookedDates.find(b => b.date === dateString);
            const isFullyBooked = Boolean(booking && (booking.is_fully_booked ?? true));
            const isPartiallyBooked = Boolean(booking && !booking.is_fully_booked);

            days.push({
                day: d,
                dateString: dateString,
                isCurrentMonth: true,
                isPast: isPast,
                isBooked: isFullyBooked,
                isPartiallyBooked: isPartiallyBooked,
                bookingInfo: booking || null,
            });
        }

        // Trailing days to fill 35 or 42 grid cells
        const totalSlots = Math.ceil(days.length / 7) * 7;
        let nextDay = 1;
        while (days.length < totalSlots) {
            days.push({
                day: nextDay++,
                dateString: '',
                isCurrentMonth: false,
                isPast: true,
                isBooked: false,
                isPartiallyBooked: false,
                bookingInfo: null,
            });
        }

        this.calendarDays = days;

        // Jika tanggal yang sedang dipilih ternyata sudah penuh (fully booked), batalkan pilihan
        if (this.rangeStart && this.rangeHasBookedDate()) {
            this.selectedDate = '';
            this.rangeStart = '';
            this.rangeEnd = '';
            this.checkResult = {
                available: false,
                message: 'Tanggal ini sudah terisi penuh dan tidak dapat dipilih.',
            };
        }
    },

    isDateBooked(dateStr) {
        if (!dateStr) return false;
        const booking = this.bookedDates.find(b => b.date === dateStr);
        return Boolean(booking && (booking.is_fully_booked ?? true));
    },

    isDatePartiallyBooked(dateStr) {
        if (!dateStr) return false;
        const booking = this.bookedDates.find(b => b.date === dateStr);
        return Boolean(booking && !booking.is_fully_booked);
    },

    isDateInRange(dateStr) {
        return Boolean(this.rangeStart && this.rangeEnd && dateStr > this.rangeStart && dateStr < this.rangeEnd);
    },

    isDateSelected(dateStr) {
        return Boolean(dateStr && (dateStr === this.rangeStart || dateStr === this.rangeEnd || this.isDateInRange(dateStr)));
    },

    rangeHasBookedDate() {
        if (!this.rangeStart) return false;
        const end = this.rangeEnd || this.rangeStart;
        return this.calendarDays.some(day => day.dateString && day.dateString >= this.rangeStart && day.dateString <= end && this.isDateBooked(day.dateString));
    },

    dateSummary() {
        if (!this.rangeStart) return 'Belum dipilih';
        const start = this.rangeStart.split('-').reverse().join(' / ');
        const end = (this.rangeEnd || this.rangeStart).split('-').reverse().join(' / ');
        return this.rangeEnd && this.rangeEnd !== this.rangeStart ? `${start} — ${end}` : start;
    },

    durationLabel() {
        if (!this.rangeStart) return '';
        const start = new Date(`${this.rangeStart}T00:00:00`);
        const end = new Date(`${this.rangeEnd || this.rangeStart}T00:00:00`);
        return `${Math.round((end - start) / 86400000) + 1} hari`;
    },

    resetRange() {
        this.selectedDate = '';
        this.rangeStart = '';
        this.rangeEnd = '';
        this.checkResult = null;
    },

    getBookingInfo(dateStr) {
        if (!dateStr) return null;
        return this.bookedDates.find(b => b.date === dateStr) || null;
    },

    onDayClick(dayObj) {
        // Jangan izinkan klik jika bukan bulan aktif, sudah lewat, atau SUDAH TERISI PENUH
        if (!dayObj.isCurrentMonth || dayObj.isPast || dayObj.isBooked) return;

        if (!this.rangeStart) {
            this.rangeStart = dayObj.dateString;
            this.rangeEnd = '';
        } else if (dayObj.dateString >= this.rangeStart) {
            // Tanggal mulai terkunci; klik berikutnya hanya mengatur tanggal selesai.
            this.rangeEnd = dayObj.dateString;
        } else {
            return;
        }
        this.selectedDate = this.rangeStart;
        this.checkSpecificDate(dayObj.dateString);
    },

    async checkSpecificDate(dateStr) {
        if (!dateStr) return;
        this.isCheckingDate = true;
        this.checkResult = null;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch('/api/availability/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    date: dateStr,
                    package_id: this.packageId || null,
                })
            });

            if (response.ok) {
                this.checkResult = await response.json();
            } else {
                const error = await response.json();
                this.checkResult = {
                    available: false,
                    message: error.message || 'Gagal memverifikasi tanggal.',
                };
            }
        } catch (err) {
            this.checkResult = {
                available: false,
                message: 'Terjadi kesalahan jaringan saat mengecek ketersediaan.',
            };
        } finally {
            this.isCheckingDate = false;
        }
    }
}));

Alpine.start();
