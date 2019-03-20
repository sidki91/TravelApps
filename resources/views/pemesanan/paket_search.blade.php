<script type="text/javascript">
  $("#paket_search").select2();
</script>
<select id="paket_search" class="form-control" onchange="pilih_sub_paket_search()" style="width:200px">
  <option value="">All</option>
  @foreach($data as $item)
  <option value="{{$item->kode_paket}}">{{$item->nama_paket}}</option>
  @endforeach
</select>
