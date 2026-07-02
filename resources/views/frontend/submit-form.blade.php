<div>
    @if ($success)
        <div class="rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ __('Thank you! Your message has been sent.') }}
        </div>
    @else
        <form wire:submit="submit" class="space-y-4">
            @foreach ($form->getFieldList() as $field)
                @php
                    $key = $field['key'];
                    $type = $field['type'] ?? 'text';
                    $label = $field['label'] ?? ucfirst($key);
                    $required = $field['required'] ?? false;
                    $options = $field['options'] ?? [];
                @endphp

                <div>
                    <label for="field_{{ $key }}" class="block text-sm font-medium">
                        {{ $label }}
                        @if ($required) <span class="text-red-500">*</span> @endif
                    </label>

                    @if ($type === 'textarea')
                        <textarea
                            id="field_{{ $key }}"
                            wire:model="fieldValues.{{ $key }}"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                    @elseif ($type === 'select')
                        <select
                            id="field_{{ $key }}"
                            wire:model="fieldValues.{{ $key }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">{{ __('Please select') }}</option>
                            @foreach ($options as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif ($type === 'checkbox')
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model="fieldValues.{{ $key }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                >
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        </div>
                    @elseif ($type === 'radio')
                        <div class="mt-1 space-y-1">
                            @foreach ($options as $option)
                                <label class="inline-flex items-center">
                                    <input
                                        type="radio"
                                        wire:model="fieldValues.{{ $key }}"
                                        value="{{ $option }}"
                                        class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    >
                                    <span class="ml-2">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <input
                            type="{{ $type === 'email' ? 'email' : ($type === 'tel' ? 'tel' : 'text') }}"
                            id="field_{{ $key }}"
                            wire:model="fieldValues.{{ $key }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    @endif

                    @error("fieldValues.{$key}")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            @if ($form->isHoneypotEnabled())
                @php $hpField = 'hp_' . md5($form->id . '_' . ($form->name ?? '')); @endphp
                <div style="display: none">
                    <input type="text" wire:model="fieldValues.{{ $hpField }}" tabindex="-1" autocomplete="off">
                </div>
            @endif

            @if ($error)
                <div class="rounded-lg bg-red-50 p-3 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                    {{ $error }}
                </div>
            @endif

            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Send') }}
            </button>
        </form>
    @endif
</div>
