<script type="text/javascript">
  $("#paket").select2();
</script>
<select id="paket" class="form-control" onchange="pilih_sub_paket()">
  <option value="">Pilih</option>
  @foreach($data as $item)
  <option value="{{$item->kode_paket}}">{{$item->nama_paket}}</option>
  @endforeach
</select>
