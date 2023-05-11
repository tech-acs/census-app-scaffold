<div class="shadow sm:rounded-md sm:overflow-hidden">
    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
        <div class="w-1/2">
            <x-label for="name" value="{{ __('Name') }} *" />
            <x-scaffold::multi-lang-input name="name" type="text" value="{{ old('name', $areaHierarchy->name ?? null) }}" autofocus />
            <x-input-error for="name" class="mt-2" />
        </div>
        <div>
            <x-label for="zero_pad_length" value="{{ __('Zero pad code to length') }} *" />
            <x-input name="zero_pad_length" class="mt-1 w-20" type="number" min="0" value="{{ old('zero_pad_length', $areaHierarchy->zero_pad_length ?? 0) }}" />
            <small>(Set 0 for no zero-padding)</small>
            <x-input-error for="zero_pad_length" class="mt-2" />
        </div>
        <div>
            <x-label for="simplification_tolerance" value="{{ __('Shape simplification tolerance') }} *" />
            <x-input name="simplification_tolerance" class="mt-1 w-20" type="number" step="any" min="0" value="{{ old('simplification_tolerance', $areaHierarchy->simplification_tolerance ?? 0) }}" />
            <small>(Set 0 for no simplification)</small>
            <x-input-error for="simplification_tolerance" class="mt-2" />
        </div>
    </div>
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <x-secondary-button class="mr-2"><a href="{{ route('developer.area-hierarchy.index') }}">{{ __('Cancel') }}</a></x-secondary-button>
        <x-button>
            {{ __('Submit') }}
        </x-button>
    </div>
</div>
