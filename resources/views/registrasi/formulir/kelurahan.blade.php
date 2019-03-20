<script type="text/javascript">
  $("#kelurahan").select2();
</script>

    <select class="form-control" id="kelurahan">
      <option>Pilih</option>
      @foreach($row as $row_kelurahan)
      <option value="{{$row_kelurahan->id_kel}}">{{$row_kelurahan->nama}}</option>
      @endforeach
    </select>
