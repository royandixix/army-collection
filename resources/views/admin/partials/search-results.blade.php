@if($results->count())
    <ul class="list-group list-group-flush">
        @foreach($results as $user)
            <li class="list-group-item">
                <strong>{{ $user->name }}</strong> <br>
                <small>{{ $user->email }}</small>
            </li>
        @endforeach
    </ul>
@else
    <div class="p-2 text-muted">Tidak ditemukan</div>
@endif
