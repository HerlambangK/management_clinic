@props([
    'name',
    'id' => null,
    'value' => '',
    'label' => null,
    'required' => false,
    'placeholder' => '0',
    'help' => null,
    'error' => null,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ?? $errors->has($name);
    $initialValue = old($name, $value);
@endphp

<div x-data="currencyInput('{{ $initialValue }}')" class="w-full">
    @if($label)
        <label for="{{ $inputId }}_display" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative flex">
        <span class="inline-flex items-center px-3.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-200 dark:border-gray-600 rounded-l-lg select-none">
            Rp
        </span>
        <input
            type="text"
            id="{{ $inputId }}_display"
            x-model="displayValue"
            @input="formatInput($event)"
            @blur="formatOnBlur()"
            @keydown="allowOnlyNumbers($event)"
            class="w-full px-4 py-2.5 max-sm:py-2 text-sm text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:ring-rose-500/30 dark:focus:border-rose-400 transition {{ $hasError ? 'border-red-400 dark:border-red-400' : '' }}"
            placeholder="{{ $placeholder }}"
            inputmode="numeric"
            autocomplete="off"
            {{ $required ? 'required' : '' }}
        >
        <input type="hidden" name="{{ $name }}" :value="rawValue">
    </div>

    @if($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>

@once
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('currencyInput', (initialValue = '') => ({
        rawValue: '',
        displayValue: '',

        init() {
            const initial = initialValue ? String(initialValue).replace(/\D/g, '') : '';
            this.rawValue = initial;
            this.displayValue = this.formatNumber(initial);
        },

        allowOnlyNumbers(event) {
            // Allow: backspace, delete, tab, escape, enter, arrows, home, end
            const allowed = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (allowed.includes(event.key)) {
                return;
            }
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if ((event.ctrlKey || event.metaKey) && ['a','c','v','x'].includes(event.key.toLowerCase())) {
                return;
            }
            // Block non-numeric
            if (!/^\d$/.test(event.key)) {
                event.preventDefault();
            }
        },

        formatInput(event) {
            const input = event.target;
            const cursorPos = input.selectionStart;
            const oldValue = input.value;
            const oldLength = oldValue.length;

            let value = oldValue.replace(/\D/g, '');

            // Remove leading zeros
            value = value.replace(/^0+/, '') || '';

            this.rawValue = value;
            this.displayValue = this.formatNumber(value);

            this.$nextTick(() => {
                const newLength = input.value.length;
                const diff = newLength - oldLength;
                const newPos = Math.max(0, cursorPos + diff);
                input.setSelectionRange(newPos, newPos);
            });
        },

        formatOnBlur() {
            this.displayValue = this.formatNumber(this.rawValue);
        },

        formatNumber(value) {
            if (!value || value === '0') {
                return '';
            }
            return new Intl.NumberFormat('id-ID').format(parseInt(value, 10));
        }
    }));
});
</script>
@endpush
@endonce
