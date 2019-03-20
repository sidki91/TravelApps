<script type="text/javascript">
  $("#sub_paket_search").select2();


</script>
<select id="sub_paket_search" class="form-control" style="width:200px" >
  <option value="">Pilih</option>
  @foreach($row as $item)
  <option value="{{$item->kode_harga}}">{{$item->kapasitas->deskripsi}}</option>
  @endforeach
</select>
