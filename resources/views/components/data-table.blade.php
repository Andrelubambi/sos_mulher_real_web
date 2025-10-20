@props(['title', 'data', 'columns', 'class' => ''])

<div class="{{ $class }}">
    <div class="card-box">
        <h5 class="h5 text-dark mb-20 pl-20 mt-4">{{ $title }}</h5>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email ?? 'N/A' }}</td>
                        <td>{{ $item->telefone ?? 'N/A' }}</td>
                        @if($columns[3] == 'Função')
                            <td>{{ ucfirst($item->role) }}</td>
                        @else
                            <td>{{ $item->id }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>