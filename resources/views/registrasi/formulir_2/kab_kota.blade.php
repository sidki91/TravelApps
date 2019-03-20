<script type="text/javascript">
  $("#kab_kota").select2();
</script>

    <select class="form-control" id="kab_kota" onchange="change_kabupaten_kota()">
      <option>Pilih</option>
      @foreach($row as $row_kab_kota)
      <option value="{{$row_kab_kota->id_kab}}">{{$row_kab_kota->nama}}</option>
      @endforeach
    </select>
