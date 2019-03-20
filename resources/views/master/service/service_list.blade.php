<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-striped">
  <thead>
    <th style="text-align:center">No</th>
    <th>Kode Service</th>
    <th>Tipe Service</th>
    <th>Keterangan</th>
    <th>Created By</th>
    <th>Created Date</th>
    <th>Action</th>
  </thead>
  <tbody>
    <?php
    $bates=$array['perpage'];
    $klik=$array['page'];
    $klik1=$klik-1;
    if ($klik=='1')
    {
    $no = 1;
    }
    else
    {
    $no = ($bates * $klik1)+1;
    }

    ?>
    @if($array['count']>=1)
    @foreach($data as $key => $row)


    <tr>
      <td style="text-align:center">{{$no}}</td>
      <td>{{$row->kode_service}}</td>
      <td>{{$row->tipe_service}}</td>
      <td>{{$row->keterangan}}</td>
      <td>{{$row->user->name}}</td>
      <td>{{$row->created_at}}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="edit('{{$row->kode_service}}')">
        <i class="fa fa-pencil"></i>
        Edit
        </button>
        <button class="btn btn-youtube btn-sm" onclick="delete_data('{{$row->kode_service}}')">
        <i class="fa  fa-trash-o"></i>
        Delete
        </button>
      </td>
    </tr>
<?php $no++; ?>
    @endforeach
    @else
    <tr>
      <td colspan="7">
        <div class="alert alert-danger alert-dismissable">
          <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        Data is not available in the database, please check again.
      </div>
    </td>
    </tr>
    @endif
  </tbody>
</table>
</div>
