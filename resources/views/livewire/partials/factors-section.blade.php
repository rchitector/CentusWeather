<div class="md:grid md:grid-cols-3 md:gap-6 mt-5">
    <x-section-title>
        <x-slot name="title">{{ __('Notification limits') }}</x-slot>
        <x-slot name="description">
            {{ __('Set up the limits which you will be sent notifications') }}
            <div>
                @if (session()->has('settings_message'))
                    <div
                        class="p-4 mt-3 col-span-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                        role="alert">
                        <span class="font-medium">{{ session('settings_message') }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center text-end mt-4">
                <button type="button"
                        wire:click.prevent="resetSettingsForm"
                        class="{{ $this->isSettingsFormChanged ? '' : 'hidden' }}  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >{{ __('Undo changes') }}</button>
                <button type="submit"
                        wire:click.prevent="saveSettingsForm"
                        class="ms-4 {{ $this->isSettingsFormChanged ? '' : 'hidden' }}  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >{{ __('Save') }}</button>
            </div>
        </x-slot>
    </x-section-title>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            <div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{__('Pause for')}}</span>
                    <div class="flex items-center">
                        <button type="button" wire:click="decrementPause" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                            </svg>
                        </button>
                        <input id="delay-value" disabled readonly value="{{ $pause }}" class="text-center w-12 h-full bg-gray-50 border border-x-0 border-gray-300 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        <button type="button" wire:click="incrementPause" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                            </svg>
                        </button>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{__('hours')}}</span>
                    <button type="button"
                            wire:click.prevent="applyNotificationPause"
                            class=" px-4 py-2 text-sm font-medium rounded-lg dark:text-white text-gray-900 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 ">{{__('Apply')}}</button>
                </div>
                @if($this->getNextNotificationDateTime())
                    <p class="mt-3 font-medium text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Notifications will start arriving in :time', ['time' => $this->getNextNotificationDateTime()]) }}
                    </p>
                @else
                    <p class="mt-3 font-medium text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Notifications will arriving without delay') }}
                    </p>
                @endif
            </div>
            <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">
            <label class="inline-flex items-center cursor-pointer mb-4">
                <input type="checkbox" class="sr-only peer" wire:model.change="draftSettings.rain_enabled">
                <div
                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enabled</span>
            </label>
            <label for="rain" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{__('Rain')}}: <span
                    id="rain-value">{{ $draftSettings['rain_value'] }} mm</span></label>
            <input {{ $draftSettings['rain_enabled'] ? '' : 'disabled' }} type="range" id="rain"
                   wire:model.change="draftSettings.rain_value"
                   min="{{$rainRanges[0]['min']}}" max="{{end($rainRanges)['max']}}" step="0.1"
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
            <div
                class="mt-2 relative mb-6 w-full flex justify-between font-medium text-sm text-gray-700 dark:text-gray-300">
                @php $max = end($rainRanges)['max']; @endphp
                @foreach($rainRanges as $item)
                    @if ($loop->first)
                        <span class="absolute"
                              style="left: {{($item['min']/$max)*100}}%">{{$item['min']}}</span>
                    @elseif ($loop->last)
                        <span class="absolute end-0">{{$item['max']}}</span>
                    @else
                        <span class="absolute"
                              style="left: {{($item['max']/$max)*100}}%">{{$item['max']}}</span>
                    @endif
                @endforeach
            </div>
            <p id="rain-description"
               class="font-medium text-sm text-gray-700 dark:text-gray-300">{{$this->getRainDescription()}}</p>
            <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">
            <label class="inline-flex items-center cursor-pointer mb-4">
                <input type="checkbox" class="sr-only peer" wire:model.change="draftSettings.snow_enabled">
                <div
                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enabled</span>
            </label>
            <label for="snow" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{__('Snow')}}: <span
                    id="snow-value">{{ $draftSettings['snow_value'] }} mm/h</span></label>
            <input {{ $draftSettings['snow_enabled'] ? '' : 'disabled' }} type="range" id="snow"
                   wire:model.change="draftSettings.snow_value"
                   min="{{$snowRanges[0]['min']}}" max="{{end($snowRanges)['max']}}" step="0.1"
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
            <div
                class="mt-2 relative mb-6 w-full flex justify-between font-medium text-sm text-gray-700 dark:text-gray-300">
                @php $max = end($snowRanges)['max']; @endphp
                @foreach($snowRanges as $item)
                    @if ($loop->first)
                        <span class="absolute"
                              style="left: {{($item['min']/$max)*100}}%">{{$item['min']}}</span>
                    @elseif ($loop->last)
                        <span class="absolute end-0">{{$item['max']}}</span>
                    @else
                        <span class="absolute"
                              style="left: {{($item['max']/$max)*100}}%">{{$item['max']}}</span>
                    @endif
                @endforeach
            </div>
            <p id="snow-description"
               class="font-medium text-sm text-gray-700 dark:text-gray-300">{{$this->getSnowDescription()}}</p>
            <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">
            <label class="inline-flex items-center cursor-pointer mb-4">
                <input type="checkbox" class="sr-only peer" wire:model.change="draftSettings.uvi_enabled">
                <div
                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enabled</span>
            </label>
            <label for="uvi" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{__('UV Index')}}:
                <span
                    id="uvi-value">{{ $draftSettings['uvi_value'] }}</span></label>
            <input {{ $draftSettings['uvi_enabled'] ? '' : 'disabled' }} type="range" id="uvi"
                   wire:model.change="draftSettings.uvi_value"
                   min="{{$uviRanges[0]['min']}}" max="{{end($uviRanges)['max']}}" step="0.1"
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
            <div
                class="mt-2 relative mb-6 w-full flex justify-between font-medium text-sm text-gray-700 dark:text-gray-300">
                @php $max = end($uviRanges)['max']; @endphp
                @foreach($uviRanges as $item)
                    @if ($loop->first)
                        <span class="absolute"
                              style="left: {{($item['min']/$max)*100}}%">{{$item['min']}}</span>
                    @elseif ($loop->last)
                        <span class="absolute end-0">{{$item['max']}}</span>
                    @else
                        <span class="absolute"
                              style="left: {{($item['max']/$max)*100}}%">{{$item['max']}}</span>
                    @endif
                @endforeach
            </div>
            <p id="uvi-description"
               class="font-medium text-sm text-gray-700 dark:text-gray-300">{{$this->getUviDescription()}}</p>
        </div>
        <div
            class="flex px-4 pb-5 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <button type="button"
                    wire:click.prevent="resetSettingsForm"
                    class="{{ $this->isSettingsFormChanged ? '' : 'hidden' }}  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >{{ __('Undo changes') }}</button>
            <button type="submit"
                    wire:click.prevent="saveSettingsForm"
                    class="ms-4 {{ $this->isSettingsFormChanged ? '' : 'hidden' }}  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >{{ __('Save') }}</button>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:init', () => {
            const rainRanges = JSON.parse('@json($rainRanges)');
            const snowRanges = JSON.parse('@json($snowRanges)');
            const uviRanges = JSON.parse('@json($uviRanges)');

            function getRainDescription(value) {
                return rainRanges.find(range => value >= range.min && value <= range.max)?.description || 'No description available';
            }

            function getSnowDescription(value) {
                return snowRanges.find(range => value >= range.min && value <= range.max)?.description || 'No description available';
            }

            function getUviDescription(value) {
                return uviRanges.find(range => value >= range.min && value <= range.max)?.description || 'No description available';
            }

            document.getElementById('rain').addEventListener('input', function (event) {
                const value = parseFloat(event.target.value);
                document.getElementById('rain-value').textContent = `${value} mm`;
                document.getElementById('rain-description').textContent = getRainDescription(value);
            });

            document.getElementById('snow').addEventListener('input', function (event) {
                const value = parseFloat(event.target.value);
                document.getElementById('snow-value').textContent = `${value} mm/h`;
                document.getElementById('snow-description').textContent = getSnowDescription(value);
            });

            document.getElementById('uvi').addEventListener('input', function (event) {
                const value = parseFloat(event.target.value);
                document.getElementById('uvi-value').textContent = `${value}`;
                document.getElementById('uvi-description').textContent = getUviDescription(value);
            });
        });
    </script>
</div>
