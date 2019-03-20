<script type="text/javascript">
  $("#sub_paket").select2();


</script>
<select id="sub_paket" class="form-control" onchange="pilih_harga_paket()" >
  <option value="">Pilih</option>
  @foreach($row as $item)
  <option value="{{$item->kode_harga}}">{{$item->kapasitas->deskripsi}}</option>
  @endforeach
</select>
