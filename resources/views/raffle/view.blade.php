<table class="table table-borderless">
    <tbody>
        <tr>
            <td>Title</td>
            <td>: {{ $model['title'] }}</td>
        </tr>
        <tr>
            <td>Created At</td>
            <td>: {{ \Carbon\Carbon::parse($model['created_at'])->format('j F Y H:i') }}</td>
        </tr>
        <tr>
            <td>End Date</td>
            <td>: {{ \Carbon\Carbon::parse($model['end_date'])->format('j F Y H:i') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:
                @if ($model['status'] == 0)
                    <span class="badge rounded-pill bg-secondary p-1">Draft</span>
                @elseif ($model['status'] == 1)
                    <span class="badge rounded-pill bg-info p-1">Published</span>
                @elseif ($model['status'] == 2)
                    <span class="badge rounded-pill bg-primary p-1">Completed</span>
                @elseif ($model['status'] == 3)
                    <span class="badge rounded-pill bg-success p-1">Closed</span>
                @elseif ($model['status'] == 4)
                    <span class="badge rounded-pill bg-danger p-1">Cancelled</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td class="text-break" style="white-space: pre-line; text-align: justify;">: {!! nl2br(e($model['description'])) !!}</td>
        </tr>
        <tr>
            <td>Image</td>
            <td class="text-break" style="white-space: pre-line; text-align: center;">
                @if ($model['image'])
                    <img src="https://api.phaserbeary.xyz/storage/{{ $model['image'] }}"
                        alt="Raffle Image"
                        class="img-fluid"
                        style="max-width: 200px; max-height: 200px;">
                @else
                    No image available
                @endif
            </td>
        </tr>
    </tbody>
</table>
