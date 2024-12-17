<div class="md:grid md:grid-cols-3 md:gap-6">
    <x-section-title>
        <x-slot name="title">{{ __('City information') }}</x-slot>
        <x-slot name="description">
            {{ __('Enter your city to track its weather') }}
            <div>
                @if (session()->has('cities_message'))
                    <div
                        class="p-4 mt-3 col-span-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                        role="alert">
                        <span class="font-medium">{{ session('cities_message') }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center text-end mt-4">
                <button type="button"
                        wire:click.prevent="resetCitiesForm"
                        class="{{ $this->isCitiesFormChanged ? '' : 'hidden' }} text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >{{ __('Undo changes') }}</button>
                <button type="button"
                        wire:click.prevent="saveCitiesForm"
                        class="ms-4 {{ $this->isCitiesFormChanged ? '' : 'hidden' }} text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >{{ __('Save') }}</button>
            </div>
        </x-slot>
    </x-section-title>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            <div class="grid grid-cols-6 gap-6">
                @include('livewire.partials.city-picker')
                @include('livewire.partials.user-cities-list')
            </div>
        </div>
        <div
            class="flex px-4 pb-5 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <button type="button"
                    wire:click.prevent="resetCitiesForm"
                    class="{{ $this->isCitiesFormChanged ? '' : 'hidden' }} text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >{{ __('Undo changes') }}</button>
            <button type="button"
                    wire:click.prevent="saveCitiesForm"
                    class="ms-4 {{ $this->isCitiesFormChanged ? '' : 'hidden' }} text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >{{ __('Save') }}</button>
        </div>
    </div>
</div>
