@extends('layouts.portal')

@section('title', __('attendance.scan_title'))
@section('page-title', __('attendance.scan_title'))

@section('content')
@php
    $history = $attendanceHistory ?? collect();
    $hasActiveSession = (bool) ($activeAttendance ?? false);
    $attendanceIsOpen = $attendanceIsOpen ?? false;
@endphp
<div class="max-w-2xl mx-auto space-y-6" x-data="attendanceCheckin(@js($attendanceToken), @js($hasActiveSession), @js($scanMode), @js($activeAttendance?->checked_in_at?->toIso8601String()), @js($hasCompletedToday ?? false))">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.scan_title') }}</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.scan_desc') }}</p>

        @if(! $attendanceIsOpen)
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 text-sm p-4">
                {{ __('attendance.qr_closed') }}
            </div>
        @endif

        @if($lastAttendance)
            <div class="mt-4 p-4 rounded-xl {{ $tc->bgLight }} dark:bg-gray-700/50">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('attendance.last_checkin') }}:</span>
                    {{ $lastAttendance->checked_in_at?->format('d M Y H:i') }}
                </p>
                @if($lastAttendance->location_name)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('attendance.location_name_label') }}: {{ $lastAttendance->location_name }}
                    </p>
                @elseif($lastAttendance->latitude && $lastAttendance->longitude)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('attendance.location_name_label') }}: {{ $lastAttendance->latitude }}, {{ $lastAttendance->longitude }}
                    </p>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-600 dark:text-red-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-4">
            @if(! $hasActiveSession)
                <div class="lg:col-span-3 p-5 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gradient-to-br from-white via-white to-primary-50/40 dark:from-gray-800 dark:via-gray-800 dark:to-primary-900/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.scan_qr_title') }}</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('attendance.scan_qr_desc') }}</p>
                        </div>
                        <span class="text-[11px] font-semibold uppercase tracking-wide px-2 py-1 rounded-full {{ $tc->bgLight }} {{ $tc->badgeText ?? 'text-primary-700' }}">
                            {{ $attendanceIsOpen ? __('attendance.session_active') : __('attendance.session_inactive') }}
                        </span>
                    </div>

                    <div class="mt-4 relative rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700">
                        <video x-ref="video" class="w-full h-56 object-cover" playsinline></video>
                        <div class="absolute inset-0 pointer-events-none" x-show="scanning" x-cloak>
                            <div class="absolute inset-5 border-2 border-primary-300/70 rounded-2xl animate-pulse-soft"></div>
                            <div class="absolute left-8 right-8 top-6 h-0.5 bg-primary-400/80 animate-scan-line"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-primary-100/20"></div>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 text-[11px] text-white/90 bg-black/40 px-3 py-1.5 rounded-full" x-show="scanning" x-cloak>
                            {{ __('attendance.scan_qr_scanning') }}
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <button type="button" class="px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition" @click="startScan" :disabled="scanning">
                            <span x-show="!scanning">{{ __('attendance.scan_qr_button') }}</span>
                            <span x-show="scanning" x-cloak>{{ __('attendance.scan_qr_scanning') }}</span>
                        </button>
                        <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition" @click="stopScan" x-show="scanning" x-cloak>
                            {{ __('attendance.scan_qr_stop') }}
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-red-600 dark:text-red-400" x-show="scanError" x-text="scanError" x-cloak></p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="scanHint" x-text="scanHint" x-cloak></p>
                </div>
            @endif

            <div class="{{ $hasActiveSession ? 'lg:col-span-5' : 'lg:col-span-2' }} p-5 border border-gray-200 dark:border-gray-700 rounded-2xl">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.location_title') }}</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('attendance.location_desc') }}</p>

                @if($hasActiveSession)
                    <div class="mt-4 rounded-2xl border border-primary-100 bg-primary-50/60 dark:bg-primary-900/20 dark:border-primary-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-primary-600 dark:text-primary-300 font-semibold">
                            {{ __('attendance.session_timer_title') }}
                        </p>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" x-text="timerDisplay"></div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('attendance.session_timer_desc') }}
                        </p>
                    </div>
                @endif

                <div class="mt-4 rounded-xl border border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-900/30 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                    <div class="flex items-center justify-between">
                        <span>{{ __('attendance.location_name_label') }}</span>
                        <span x-text="locationLabel"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('attendance.location_accuracy') }}</span>
                        <span x-text="accuracyDisplay"></span>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <button type="button" class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-lg transition" @click="refreshLocation">
                        {{ __('attendance.location_refresh') }}
                    </button>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400" x-text="locationStatus"></span>
                </div>

                <form x-ref="form" method="POST" action="{{ route('portal.attendance.store') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="attendance_token" x-model="attendanceTokenInput">
                    <input type="hidden" name="scan_mode" x-model="scanMode">
                    <input type="hidden" name="latitude" x-model="latitude">
                    <input type="hidden" name="longitude" x-model="longitude">
                    <input type="hidden" name="accuracy" x-model="accuracy">
                    <input type="hidden" name="location_name" x-model="locationName">

                    <button
                        type="button"
                        class="w-full px-4 py-3 {{ $tc->button }} text-white text-sm font-medium rounded-xl transition disabled:opacity-60 disabled:cursor-not-allowed"
                        :disabled="loading"
                        @click="startLocationCheckIn"
                    >
                        <span x-show="!loading">{{ $hasActiveSession ? __('attendance.end_session_button') : __('attendance.checkin_button') }}</span>
                        <span x-show="loading" x-cloak>{{ __('attendance.checkin_loading') }}</span>
                    </button>
                </form>

                <p class="mt-3 text-sm text-red-600 dark:text-red-400" x-show="error" x-text="error" x-cloak></p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('attendance.history_title') }}</h3>
        <form method="GET" action="{{ route('portal.attendance.scan') }}" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="hidden" name="mode" value="{{ $scanMode }}">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('attendance.history_search') }}</label>
                <input
                    type="date"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('attendance.history_from') }}</label>
                <input
                    type="date"
                    name="date_from"
                    value="{{ $filters['date_from'] ?? '' }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('attendance.history_to') }}</label>
                <input
                    type="date"
                    name="date_to"
                    value="{{ $filters['date_to'] ?? '' }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('attendance.history_status') }}</label>
                <select
                    name="status"
                    class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                    <option value="">{{ __('attendance.history_all') }}</option>
                    <option value="checked_in" {{ ($filters['status'] ?? '') === 'checked_in' ? 'selected' : '' }}>{{ __('attendance.session_active') }}</option>
                    <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>{{ __('attendance.session_finished') }}</option>
                </select>
            </div>
            <div class="md:col-span-4 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 {{ $tc->button }} text-white text-sm font-medium rounded-lg transition">
                    {{ __('attendance.history_filter') }}
                </button>
                <a href="{{ route('portal.attendance.scan', ['mode' => $scanMode]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition">
                    {{ __('common.reset') }}
                </a>
            </div>
        </form>
        <div class="mt-4 space-y-3">
            @forelse($history as $attendance)
                @php
                    $durationLabel = $attendance->checked_out_at
                        ? $attendance->checked_out_at->diffInMinutes($attendance->checked_in_at)
                        : null;
                    $durationText = $durationLabel !== null
                        ? sprintf('%dh %02dm', intdiv($durationLabel, 60), $durationLabel % 60)
                        : __('attendance.duration_in_progress');
                @endphp
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $attendance->checked_in_at?->format('d M Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $attendance->checked_out_at ? __('attendance.session_finished') : __('attendance.session_active') }}
                        </p>
                        @if($attendance->location_name)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('attendance.location_name_label') }}: {{ $attendance->location_name }}
                            </p>
                        @elseif($attendance->latitude && $attendance->longitude)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('attendance.location_name_label') }}: {{ $attendance->latitude }}, {{ $attendance->longitude }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('attendance.duration_label') }}: {{ $durationText }}
                        </p>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $attendance->checked_out_at?->format('H:i') ?? '-' }}
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('attendance.no_data') }}</p>
            @endforelse
        </div>
        @if(method_exists($history, 'links'))
            <div class="mt-4">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function attendanceCheckin(expectedToken, hasActiveSession, scanMode, activeStartedAt, hasCompletedToday) {
        return {
            loading: false,
            scanning: false,
            error: null,
            scanError: null,
            scanHint: null,
            latitude: null,
            longitude: null,
            accuracy: null,
            locationName: '',
            locationStatus: '',
            attendanceTokenInput: '',
            expectedToken: expectedToken,
            hasActiveSession: hasActiveSession,
            scanMode: scanMode,
            activeStartedAt: activeStartedAt,
            hasCompletedToday: hasCompletedToday,
            timerInterval: null,
            timerTick: 0,
            stream: null,
            detector: null,
            init() {
                this.refreshLocation();
                this.setupTimer();
                if (this.hasActiveSession) {
                    this.stopScan();
                    return;
                }
                if (this.scanMode === 'qr') {
                    this.startScan();
                }
            },
            get latitudeDisplay() {
                return this.latitude === null ? '-' : this.latitude.toFixed(6);
            },
            get longitudeDisplay() {
                return this.longitude === null ? '-' : this.longitude.toFixed(6);
            },
            get accuracyDisplay() {
                return this.accuracy === null ? '-' : `${this.accuracy} m`;
            },
            get locationLabel() {
                if (this.locationName) {
                    return this.locationName;
                }
                if (this.latitude === null || this.longitude === null) {
                    return '-';
                }
                return `${this.latitude.toFixed(6)}, ${this.longitude.toFixed(6)}`;
            },
            get timerDisplay() {
                if (! this.activeStartedAt) {
                    return '00:00:00';
                }
                void this.timerTick;
                const started = new Date(this.activeStartedAt).getTime();
                const diffSeconds = Math.max(0, Math.floor((Date.now() - started) / 1000));
                const hours = String(Math.floor(diffSeconds / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((diffSeconds % 3600) / 60)).padStart(2, '0');
                const seconds = String(diffSeconds % 60).padStart(2, '0');
                return `${hours}:${minutes}:${seconds}`;
            },
            startLocationCheckIn() {
                if (! this.confirmRestart()) {
                    return;
                }
                this.scanMode = 'location';
                this.attendanceTokenInput = '';
                this.startCheckIn();
            },
            startCheckIn() {
                this.error = null;
                this.loading = true;
                this.fetchLocation()
                    .then(() => {
                        this.$refs.form.submit();
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
            refreshLocation() {
                this.fetchLocation().catch(() => {});
            },
            fetchLocation() {
                return new Promise((resolve, reject) => {
                    if (! navigator.geolocation) {
                        this.locationStatus = '{{ __('attendance.location_not_supported') }}';
                        this.error = this.locationStatus;
                        reject();
                        return;
                    }

                    this.locationStatus = '{{ __('attendance.location_fetching') }}';
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.accuracy = Math.round(position.coords.accuracy);
                            this.locationStatus = '{{ __('attendance.location_filled') }}';
                            this.resolveLocationName().finally(() => resolve());
                        },
                        (error) => {
                            if (error?.code === error.PERMISSION_DENIED) {
                                this.locationStatus = '{{ __('attendance.location_permission_denied') }}';
                            } else if (error?.code === error.POSITION_UNAVAILABLE) {
                                this.locationStatus = '{{ __('attendance.location_unavailable') }}';
                            } else if (error?.code === error.TIMEOUT) {
                                this.locationStatus = '{{ __('attendance.location_timeout') }}';
                            } else {
                                this.locationStatus = '{{ __('attendance.location_denied') }}';
                            }
                            this.error = this.locationStatus;
                            reject();
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                        }
                    );
                });
            },
            resolveLocationName() {
                if (this.latitude === null || this.longitude === null) {
                    return Promise.resolve();
                }
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${this.latitude}&lon=${this.longitude}`;
                return fetch(url, { headers: { 'Accept-Language': 'id' } })
                    .then((response) => response.ok ? response.json() : null)
                    .then((data) => {
                        if (! data?.address) {
                            return;
                        }
                        const address = data.address;
                        const village = address.village || address.suburb || address.neighbourhood || address.hamlet;
                        const city = address.city || address.town || address.county || address.city_district;
                        const state = address.state || address.region;
                        const parts = [village, city, state].filter(Boolean);
                        if (parts.length > 0) {
                            this.locationName = parts.join(', ');
                        }
                    })
                    .catch(() => {
                        // ignore reverse geocode errors
                    });
            },
            startScan() {
                if (! this.confirmRestart()) {
                    return;
                }
                this.scanMode = 'qr';
                this.scanError = null;
                this.scanHint = null;

                if (this.hasActiveSession) {
                    this.scanError = '{{ __('attendance.scan_disabled_active') }}';
                    return;
                }

                if (! this.expectedToken) {
                    this.scanError = '{{ __('attendance.qr_missing') }}';
                    return;
                }

                if (!('BarcodeDetector' in window)) {
                    this.scanError = '{{ __('attendance.qr_not_supported') }}';
                    return;
                }

                this.scanning = true;
                this.detector = new BarcodeDetector({ formats: ['qr_code'] });

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then((stream) => {
                        this.stream = stream;
                        this.$refs.video.srcObject = stream;
                        this.$refs.video.play();
                        this.scanHint = '{{ __('attendance.scan_qr_hint') }}';
                        this.detectLoop();
                    })
                    .catch(() => {
                        this.scanning = false;
                        this.scanError = '{{ __('attendance.camera_denied') }}';
                    });
            },
            stopScan() {
                this.scanning = false;
                this.scanHint = null;
                if (this.stream) {
                    this.stream.getTracks().forEach((track) => track.stop());
                }
                this.stream = null;
            },
            detectLoop() {
                if (! this.scanning || ! this.detector) {
                    return;
                }

                this.detector.detect(this.$refs.video)
                    .then((codes) => {
                        if (codes.length > 0) {
                            const raw = codes[0].rawValue || '';
                            const token = this.extractToken(raw);
                            this.stopScan();

                            if (! token) {
                                this.scanError = '{{ __('attendance.qr_invalid') }}';
                                return;
                            }

                            if (token !== this.expectedToken) {
                                this.scanError = '{{ __('attendance.qr_invalid') }}';
                                return;
                            }

                            this.attendanceTokenInput = token;
                            this.startCheckIn();
                            return;
                        }

                        requestAnimationFrame(() => this.detectLoop());
                    })
                    .catch(() => {
                        requestAnimationFrame(() => this.detectLoop());
                    });
            },
            confirmRestart() {
                if (! this.hasCompletedToday || this.hasActiveSession) {
                    return true;
                }
                return window.confirm('{{ __('attendance.restart_confirm') }}');
            },
            setupTimer() {
                if (! this.activeStartedAt) {
                    return;
                }
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                }
                this.timerInterval = setInterval(() => {
                    this.timerTick += 1;
                }, 1000);
            },
            extractToken(value) {
                if (! value) {
                    return null;
                }

                let raw = String(value).trim();

                if (raw.startsWith('ATTEND:')) {
                    raw = raw.replace('ATTEND:', '');
                }

                try {
                    const url = new URL(raw);
                    const token = url.searchParams.get('token');
                    if (token) {
                        raw = token;
                    }
                } catch (e) {
                    // ignore
                }

                if (raw.includes('|')) {
                    raw = raw.split('|')[0];
                }

                return raw || null;
            },
        };
    }
</script>
@endpush
