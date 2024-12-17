<div class="col-span-6">
    <x-label value="{{ __('City Name') }}"/>
    <div class="w-full flex rounded-md mt-1" role="group">
        <input type="text"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-s-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="{{__('City name')}}"
               wire:keydown.enter="searchCities"
               wire:model="search">
        <button type="button"
                wire:click="searchCities"
                class="disabled:cursor-not-allowed px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-l-0 border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:ring-2 focus:ring-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            {{ __('Find') }}
        </button>
    </div>
    @php
        $visibleCities = array_filter($foundCities, function ($item){
            return $item['hidden'] !== true;
        });
    @endphp
    @if (!empty($visibleCities))
        <ul class="mt-1 w-full max-h-64 overflow-y-auto text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach ($visibleCities as $city)
                @if (!isset($city['hidden']) || $city['hidden'] !== true)
                    <li class="w-full px-4 py-1 items-center justify-between flex first:rounded-t-lg last:rounded-b-lg border-b border-gray-200 dark:border-gray-600">
                        <div class="">
                            <span class="font-semibold">{{ $city['name'] }}</span>
                            @php
                                $location = collect([ $city['country'] ?? null, $city['state'] ?? null ])->filter()->join(', ');
                            @endphp
                            @if($location)
                                <span class="text-sm text-gray-300">({{ $location }})</span>
                            @endif
                        </div>
                        <div class="rounded-full p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                             wire:click="addCity('{{ $city['id'] }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    @else
        @if ($searched)
            <p class="text-red-500 text-sm font-medium mt-1 ">{{__('Empty list')}}</p>
        @endif
    @endif
</div>
