@extends('pdf.layout')

@section('content')
    From module view
@foreach($data as $d)
<h4>{{ $d->name }}</h4>
<table >
    <tr>
        <th style="width: 70px">Item Code</th>
        <th style="width: 100px">Item Name</th>
        <th style="width: 70px">Running Hour</th>
        <th style="width: 300px">Job Description</th>
        <th style="width: 70px">Cycle</th>
        <th>Next Due</th>
        <th>Last Done</th>
    </tr>
    @foreach($d->mntItems as $mntItem)
        @foreach ($mntItem->mntJobs as $mntJob)
            @php
                $countLines = count($mntJob->mntJobLines);
            @endphp
            @foreach ($mntJob->mntJobLines as $key => $mntJobLine)
                @php
                    $unit = ($mntJobLine->cycle_unit == "Hours") ? "Hrs." : "";
                    $runningHourUnit = ($mntJob->present_run_hour > 0) ? "Hrs." : "";
                @endphp
                <tr>
                    @if ($key == 0)
                    <td rowspan="{{ $countLines }}">{{ $mntItem->item_code }}</td>
                    <td rowspan="{{ $countLines }}">{{ $mntItem->name }}</td>
                    <td rowspan="{{ $countLines }}">{{ $mntJob->present_run_hour }} {{ $runningHourUnit }}</td>
                    @endif
                    <td>{{ $mntJobLine->job_description }}</td>
                    <td>{{ $mntJobLine->cycle }} {{ $mntJobLine->cycle_unit }}</td>
                    <td>{{ $mntJobLine->next_due }} {{ $unit }}</td>
                    <td>{{ $mntJobLine->last_done }}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
</table>
@endforeach

@endsection