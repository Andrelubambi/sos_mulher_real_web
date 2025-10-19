@foreach ($medicos as $medico)
<tr>
    <td class="table-plus">
        <div class="name-avatar d-flex align-items-center">
            <div class="avatar mr-2 flex-shrink-0">
                <img src="{{ asset('vendors/images/photo4.jpg') }}" class="border-radius-100 shadow" width="40" height="40" alt="" />
            </div>
            <div class="txt">
                <div class="weight-600">{{ $medico->name }}</div>
            </div>
        </div>
    </td>
    <td>{{ $medico->telefone }}</td>
    <td>
        <div class="table-actions">
            <a href="#" data-color="#265ed7">     
                <i class="icon-copy dw dw-edit2"></i>
            </a>
            <a href="#" data-color="#e95959">
                <i class="icon-copy dw dw-delete-3"></i>
            </a>
        </div>
    </td>
</tr>
@endforeach
