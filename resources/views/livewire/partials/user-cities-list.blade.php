@if (!empty($draftCities))
    <div class="col-span-6">
        <x-label value="{{ __('Saved cities') }}"/>
        <ul class="mt-1 w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach ($draftCities as $city)
                <li class="w-full px-4 py-1 items-center justify-between flex first:rounded-t-lg last:rounded-b-lg border-b border-gray-200 dark:border-gray-600">
                    <div class="">
{{--                        {{$city['created_at']->format('Y-m-d H:i:s')}}--}}
                        <span class="font-semibold">{{ $city['name'] }}</span>
                        @php
                            $location = collect([ $city['country'] ?? null, $city['state'] ?? null  ])->filter()->join(', ');
                        @endphp
                        @if($location)
                            <span class="text-sm text-gray-300">({{ $location }})</span>
                        @endif
                    </div>
                    <div class="rounded-full p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" wire:click="deleteCity('{{ $city['id'] }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
