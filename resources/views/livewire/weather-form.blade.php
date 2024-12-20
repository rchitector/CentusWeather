<div class="md:grid md:grid-cols-3 md:gap-6">
    <x-section-title>
        <x-slot name="title">{{ __('Settings') }}</x-slot>
        <x-slot name="description"></x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            @foreach(Arr::only($settings->toArray(), [
                'rain_enabled',
                'snow_enabled',
                'uvi_enabled',
                'rain_value',
                'snow_value',
                'uvi_value'
            ]) as $key=>$setting)
                <div class="text-gray-900 dark:text-gray-300">{{$key}}: {{$setting}}</div>
            @endforeach
        </div>
    </div>

    <x-section-title>
        <x-slot name="title">{{ __('Cities weather') }}</x-slot>
        <x-slot name="description"></x-slot>
    </x-section-title>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            @foreach($cities as $city)
                <div class="text-gray-900 dark:text-gray-300">{{$city->name}}</div>
                @foreach($city->weather as $weather)
                    <span class="text-gray-900 dark:text-gray-300">{{$weather->temp}}</span>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
