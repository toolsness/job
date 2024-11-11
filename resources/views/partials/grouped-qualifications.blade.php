@php
    $groupedQualifications = $candidate->qualifications
        ->sortBy('qualificationCategory.name')
        ->groupBy('qualificationCategory.name');
@endphp

@foreach ($groupedQualifications as $categoryName => $qualifications)
    <span class="font-semibold">{{ $categoryName }}:</span><br>
    @foreach ($qualifications->sortBy('qualification_name') as $qualification)
        <span class="ml-4 text-sm">{{ $qualification->qualification_name }}</span><br>
    @endforeach
    <br>
@endforeach
