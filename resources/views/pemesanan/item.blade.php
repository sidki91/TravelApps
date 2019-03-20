<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}

</style>

<hr/>

              <p class="card-description" style="font-weight:bold">
                Masukan Data Jamaah
              </p>

              <table class="table table-striped table-bordered" id="keluarga_ikut">
                <thead>
                  <tr>
                    <th style="width:150px">Kode Registrasi</th>
                    <th style="width:200px">Nama Jamaah</th>
                    <th>JK</th>
                    <th>HP</th>
                    <th>Add</th>
                  </tr>
                </thead>
                <tbody class="neworderbody">
                @for($x=1;$x<=$jumlah;$x++)
                <tr>
                  <td><input type="text" class="form-control" id="kode_registrasi_{{$x}}"></td>
                  <td><input type="text" class="form-control" id="nama_jamaah_{{$x}}"></td>
                  <td><input type="text" class="form-control" id="jk_{{$x}}"/></td>
                  <td><input type="text" class="form-control" id="hp_jamaah_{{$x}}"/></td>
                  <td>
                    <button class="btn btn-primary" onclick="open_jamaah()"><i class="fa fa-search"></i></button>
                    <button class="btn btn-danger" disabled><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                @endfor

              </tbody>
              </table>
            <hr/>
          
