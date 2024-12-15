<div class="md:grid md:grid-cols-3 md:gap-6">
    <x-section-title>
        <x-slot name="title">{{ __('City information') }}</x-slot>
        <x-slot name="description">{{ __('Enter your city to track its weather') }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="save">
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <div class="grid grid-cols-6 gap-6">
                    @if (session()->has('message'))
                        <div class="col-span-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            <span class="font-medium">{{ session('message') }}</span>
                        </div>
                    @endif

                    <div class="col-span-6">
                        <x-label value="{{ __('City Name') }}"/>
                        <div class="w-full flex rounded-md shadow-sm mt-1" role="group">
                            @php
                                $location = collect([$this->city_country ?? null, $this->city_state ?? null])->filter()->join(', ');
                                $full_location = $this->city_name . ($location ? " ($location)" : '');
                            @endphp
                            <input type="text" readonly disabled value="{{$full_location}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-s-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <button type="button" wire:click="openCityModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-l-0 border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:ring-2 focus:ring-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                                {{ __('Change') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-span-3">
                        <x-label value="{{ __('Latitude') }}"/>
                        <input type="text" readonly disabled value="{{$this->city_lat}}"
                               class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>

                    <div class="col-span-3">
                        <x-label value="{{ __('Longitude') }}"/>
                        <input type="text" readonly disabled value="{{$this->city_lon}}"
                               class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <button type="submit" @disabled(!$this->changed)
                class="{{ !$this->changed ? 'text-white bg-gray-400 dark:bg-gray-500 cursor-not-allowed' : 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800' }}">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>

    @if ($isModalOpen)
        <div class="inset-0 bg-black bg-opacity-40 flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{__('Find city')}}</h3>
                        <button type="button" wire:click="closeCityModal"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <form wire:submit.prevent="searchCities">
                            <div class="flex space-x-2">
                                <input type="text" id="search-city-name-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{__('City name')}}" wire:model="search">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{__('Find')}}
                                </button>
                            </div>
                        </form>
                        @if ($cities)
                            <ul class="w-full max-h-64 overflow-y-auto text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($cities as $city)
                                    <li class="w-full px-4 py-2 first:rounded-t-lg last:rounded-b-lg border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        wire:click="selectCity({{ json_encode($city) }})">
                                        <span class="font-semibold">{{ $city['name'] }}</span>
                                        @php
                                            $location = collect([ $city['country'] ?? null, $city['state'] ?? null ])->filter()->join(', ');
                                        @endphp
                                        @if($location)
                                            <span class="text-sm text-gray-300">({{ $location }})</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            @if ($searched)
                                <p class="text-gray-500">{{__('Not found')}}</p>
                            @endif
                        @endif
                    </div>
                    <div class="flex w-full justify-end items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="button" wire:click="closeCityModal"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            {{__('Cancel')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('modalOpen', () => {
                setTimeout(() => {
                    document.getElementById('search-city-name-input')?.focus();
                }, 50);
            });
        });
    </script>
</div>
