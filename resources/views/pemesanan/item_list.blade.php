<hr/>

              <p class="card-description" style="font-weight:bold">
                Masukan Data Jamaah
              </p>
              <table class="table table-striped table-bordered" id="keluarga_ikut">
                <thead>
                  <tr>
                    <th>Kode Registrasi</th>
                    <th >Nama Jamaah</th>
                    <th>JK</th>
                    <th>HP</th>
                    <th>Add</th>
                  </tr>
                </thead>
                <tbody class="neworderbody">
                  <?php $no =1; ?>
              <?php if($count>=1){ ?>
              @foreach($item as $row_item)
                <tr>
                  <td><input type="text" class="form-control" id="kode_registrasi_{{$no}}" value="{{$row_item->kode_registrasi}}"></td>
                  <td><input type="text" class="form-control" id="nama_jamaah_{{$no}}" value="{{$row_item->jamaah->nama_lengkap}}"></td>
                  <td><input type="text" class="form-control" id="jk_{{$no}}" value="{{$row_item->jamaah->jk}}"/></td>
                  <td><input type="text" class="form-control" id="hp_jamaah_{{$no}}" value="{{$row_item->jamaah->hp}}"/></td>
                    <td>
                    <button class="btn btn-primary" disabled onclick="open_jamaah()"><i class="fa fa-search"></i></button>
                    <button class="btn btn-danger" onclick="delete_item('{{$row_item->nomor_pesanan}}','{{$row_item->item}}','{{$row_item->kode_registrasi}}')"><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                <?php $no++; ?>
                @endforeach
              <?php } ?>
               <?php for ($i=$count+1; $i<$rows; $i++){ ?>
                 <tr>
                   <td><input type="text" class="form-control" id="kode_registrasi_{{$i}}"></td>
                   <td><input type="text" class="form-control" id="nama_jamaah_{{$i}}"></td>
                   <td><input type="text" class="form-control" id="jk_{{$i}}"/></td>
                   <td><input type="text" class="form-control" id="hp_jamaah_{{$i}}"/></td>
                   <td>
                   <button class="btn btn-primary" onclick="open_jamaah()"><i class="fa fa-search"></i></button>
                   <button class="btn btn-danger" disabled ><i class="fa fa-close"></i></button>
                 </td>
                 </tr>

               <?php } ?>
              </tbody>
              </table>
              @if($count>=1)
              <br>
              <button type="button" class="btn btn-success mr-2" onclick="finish()"><i class="fa fa-check"></i> Selesai</button>
              @endif
