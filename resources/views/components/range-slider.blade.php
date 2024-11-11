@props(['min' => 1, 'max' => 100, 'step' => 1])

<div
    x-data="{
        min: {{ $min }},
        max: {{ $max }},
        minValue: @entangle($attributes->wire('model')->value)[0],
        maxValue: @entangle($attributes->wire('model')->value)[1],
        step: {{ $step }},
        updateMinMax() {
            if (this.minValue > this.maxValue) {
                [this.minValue, this.maxValue] = [this.maxValue, this.minValue];
            }
            $wire.set('{{ $attributes->wire('model')->value }}', [this.minValue, this.maxValue]);
        }
    }"
    class="relative w-full h-14"
>
    <div class="absolute w-full h-2 bg-gray-200 rounded-full top-4"></div>
    <div
        class="absolute h-2 bg-blue-500 rounded-full top-4"
        :style="`left: ${(minValue - min) / (max - min) * 100}%; right: ${100 - (maxValue - min) / (max - min) * 100}%;`"
    ></div>

    <input
        type="range"
        x-model="minValue"
        @input="updateMinMax()"
        :min="min"
        :max="max"
        :step="step"
        class="absolute w-full top-3 pointer-events-none appearance-none bg-transparent"
    >

    <input
        type="range"
        x-model="maxValue"
        @input="updateMinMax()"
        :min="min"
        :max="max"
        :step="step"
        class="absolute w-full top-3 pointer-events-none appearance-none bg-transparent"
    >

    <div class="absolute top-8 left-0 text-sm">
        Min: <span x-text="minValue"></span>
    </div>
    <div class="absolute top-8 right-0 text-sm">
        Max: <span x-text="maxValue"></span>
    </div>
</div>
