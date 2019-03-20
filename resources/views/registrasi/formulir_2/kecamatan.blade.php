<script type="text/javascript">
  $("#kecamatan").select2();
</script>

    <select class="form-control" id="kecamatan" onchange="change_kecamatan()">
      <option>Pilih</option>
      @foreach($row as $row_kecamatan)
      <option value="{{$row_kecamatan->id_kec}}">{{$row_kecamatan->nama}}</option>
      @endforeach
    </select>
