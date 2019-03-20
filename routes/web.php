<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function ()
{
    #return view('welcome');
    return redirect()->route('login');
});

Auth::routes();
Route::group( ['middleware' => 'auth' ], function()
{
    Route::get('/home', 'HomeController@index');
    Route::post('/home/chart','HomeController@chart');
    #UsersController
    Route::get('/users','UsersController@index');
    Route::post('/user/list_data','UsersController@list_data');
    Route::post('/user/add','UsersController@add');
    Route::post('/user','UsersController@store');
    Route::post('/user/edit','UsersController@edit');
    Route::post('/user/update','UsersController@update');
    Route::post('/user/delete','UsersController@delete');

    #PrivelegesController
    Route::get('priveleges','PrivelegesController@index');
    Route::post('/priveleges/list_data','PrivelegesController@list_data');
    Route::post('/priveleges/add','PrivelegesController@add');
    Route::post('/priveleges','PrivelegesController@store');
    Route::post('/priveleges/edit','PrivelegesController@edit');
    Route::post('/priveleges/update','PrivelegesController@update');
    Route::post('/priveleges/delete','PrivelegesController@delete');


    #ModuleController
    Route::get('module','ModuleController@index');
    Route::post('/module/list_data','ModuleController@list_data');
    Route::post('/module/add','ModuleController@add');
    Route::post('/module/store','ModuleController@store');
    Route::post('/module/edit','ModuleController@edit');
    Route::post('/module/update','ModuleController@update');
    Route::post('/module/delete','ModuleController@delete');

    #LogController
    Route::get('log','LogController@index');
    Route::post('/log/list_data','LogController@list_data');
    Route::post('/log/display_chart_log','LogController@display_chart_log');


    #PendidikanController
    Route::get('/pendidikan','PendidikanController@index');
    Route::post('/pendidikan/list_data','PendidikanController@list_data');
    Route::post('/pendidikan','PendidikanController@store');
    Route::post('/pendidikan/edit','PendidikanController@edit');
    Route::post('/pendidikan/update','PendidikanController@update');
    Route::post('/pendidikan/delete','PendidikanController@delete');

    #PekerjaanController
    Route::get('/pekerjaan','PekerjaanController@index');
    Route::post('/pekerjaan/list_data','PekerjaanController@list_data');
    Route::post('/pekerjaan','PekerjaanController@store');
    Route::post('/pekerjaan/edit','PekerjaanController@edit');
    Route::post('/pekerjaan/update','PekerjaanController@update');
    Route::post('/pekerjaan/delete','PekerjaanController@delete');

    #StatusController
    Route::get('/status','StatusController@index');
    Route::post('/status/list_data','StatusController@list_data');
    Route::post('/status','StatusController@store');
    Route::post('/status/edit','StatusController@edit');
    Route::post('/status/update','StatusController@update');
    Route::post('/status/delete','StatusController@delete');

    #HubunganController
    Route::get('/hubungan','HubunganController@index');
    Route::post('/hubungan/list_data','HubunganController@list_data');
    Route::post('/hubungan','HubunganController@store');
    Route::post('/hubungan/edit','HubunganController@edit');
    Route::post('/hubungan/update','HubunganController@update');
    Route::post('/hubungan/delete','HubunganController@delete');

    #NegaraController
    Route::get('/negara','NegaraController@index');
    Route::post('/negara/list_data','NegaraController@list_data');
    Route::post('/negara','NegaraController@store');
    Route::post('/negara/edit','NegaraController@edit');
    Route::post('/negara/update','NegaraController@update');
    Route::post('/negara/delete','NegaraController@delete');

    #KotaController
    Route::get('/kota','KotaController@index');
    Route::post('/kota/list_data','KotaController@list_data');
    Route::post('/kota/add','KotaController@add');
    Route::post('/kota','KotaController@store');
    Route::post('/kota/edit','KotaController@edit');
    Route::post('/kota/update','KotaController@update');
    Route::post('/kota/delete','KotaController@delete');

    #ServiceController
    Route::get('/service','ServiceController@index');
    Route::post('/service/list_data','ServiceController@list_data');
    Route::post('/service','ServiceController@store');
    Route::post('/service/edit','ServiceController@edit');
    Route::post('/service/update','ServiceController@update');
    Route::post('/service/delete','ServiceController@delete');

    #Hotelcontroller
    Route::get('/hotel','Hotelcontroller@index');
    Route::post('/hotel/list_data','HotelController@list_data');
    Route::post('/hotel/add','HotelController@add');
    Route::post('/hotel/pilih_kota','HotelController@pilih_kota');
    Route::post('/hotel','HotelController@store');
    Route::post('/hotel/edit','HotelController@edit');
    Route::post('/hotel/update','HotelController@update');
    Route::post('/hotel/delete','HotelController@delete');

    #RoomController
    Route::get('/room','RoomController@index');
    Route::post('/room/list_data','RoomController@list_data');
    Route::post('/room/add','RoomController@add');
    Route::post('/room/change_hotel','RoomController@change_hotel');
    Route::post('/room/change_info','RoomController@change_info');
    Route::post('/room','RoomController@store');
    Route::post('/room/edit','RoomController@edit');
    Route::post('/room/update','RoomController@update');
    Route::post('/room/delete','RoomController@delete');

    #AirlinesController
    Route::get('/airlines','AirlinesController@index');
    Route::post('/airlines/list_data','AirlinesController@list_data');
    Route::post('/airlines','AirlinesController@store');
    Route::post('/airlines/edit','AirlinesController@edit');
    Route::post('/airlines/update','AirlinesController@update');
    Route::post('/airlines/delete','AirlinesController@delete');

    #PesawatController
    Route::get('/pesawat','PesawatController@index');
    Route::post('/pesawat/list_data','PesawatController@list_data');
    Route::post('/pesawat/add','PesawatController@add');
    Route::post('/pesawat','PesawatController@store');
    Route::post('/pesawat/edit','PesawatController@edit');
    Route::post('/pesawat/update','PesawatController@update');
    Route::post('/pesawat/delete','PesawatController@delete');

    #KategoriPerjalananController
    Route::get('/kategori_perjalanan','KategoriPerjalananController@index');
    Route::post('/kategori_perjalanan/list_data','KategoriPerjalananController@list_data');
    Route::post('/kategori_perjalanan','KategoriPerjalananController@store');
    Route::post('/kategori_perjalanan/edit','KategoriPerjalananController@edit');
    Route::post('/kategori_perjalanan/update','KategoriPerjalananController@update');
    Route::post('/kategori_perjalanan/delete','KategoriPerjalananController@delete');

    #PaketPerjalananController
    Route::get('/paket_perjalanan','PaketPerjalananController@index');
    Route::post('/paket_perjalanan/list_data','PaketPerjalananController@list_data');
    Route::post('/paket_perjalanan/add','PaketPerjalananController@add');
    Route::post('/paket_perjalanan/open_kota','PaketPerjalananController@display_kota');
    Route::post('/paket_perjalanan/list_data_kota','PaketPerjalananController@list_data_kota');
    Route::post('/paket_perjalanan/ambil_data_kota','PaketPerjalananController@ambil_data_kota');
    Route::post('/paket_perjalanan/form_harga','PaketPerjalananController@form_harga');
    Route::post('/paket_perjalanan/save_harga_paket','PaketPerjalananController@save_harga_paket');
    Route::post('/paket_perjalanan','PaketPerjalananController@store');
    Route::post('/paket_perjalanan/edit','PaketPerjalananController@edit');
    Route::post('/paket_perjalanan/check_harga','PaketPerjalananController@check_harga');
    Route::post('/paket_perjalanan/update_harga_paket','PaketPerjalananController@update_harga_paket');
    Route::post('/paket_perjalanan/update','PaketPerjalananController@update');
    Route::post('/paket_perjalanan/delete','PaketPerjalananController@delete');



    #FormulirController
    Route::get('/formulir','FormulirController@index');
    Route::post('/formulir/list_data','FormulirController@list_data');
    Route::post('/formulir/add','FormulirController@add');
    Route::post('/formulir/pilih_paket','FormulirController@pilih_paket');
    Route::post('/formulir/pilih_sub_paket','FormulirController@pilih_sub_paket');
    Route::post('/formulir/pilih_harga_paket','FormulirController@pilih_harga_paket');
    Route::post('/formulir/edit','FormulirController@edit');
    Route::post('/formulir/delete','FormulirController@delete');
    Route::post('/formulir/pengalaman_haji','FormulirController@pengalaman_haji');
    Route::post('/formulir/pengalaman_umroh','FormulirController@pengalaman_umroh');
    Route::post('/formulir/keterangan_menikah','FormulirController@keterangan_menikah');
    Route::post('/formulir/change_provinsi','FormulirController@change_provinsi');
    Route::post('/formulir/change_kabupaten_kota','FormulirController@change_kabupaten_kota');
    Route::post('/formulir/change_kecamatan','FormulirController@change_kecamatan');
    Route::post('/formulir/hitung_umur','FormulirController@hitung_umur');
    Route::post('/formulir/save','FormulirController@save');
    Route::post('/formulir/update','FormulirController@update');
    Route::post('/formulir/info_tambahan','FormulirController@info_tambahan');
    Route::post('/formulir/add_item','FormulirController@add_item');
    Route::post('/formulir/list_info_tambahan','FormulirController@list_info_tambahan');
    Route::post('/formulir/open_form_kel_ikut','FormulirController@open_form_kel_ikut');
    Route::post('/formulir/info_tambahan_2','FormulirController@info_tambahan_2');
    Route::post('/formulir/add_item2','FormulirController@add_item2');
    Route::post('/formulir/list_info_tambahan2','FormulirController@list_info_tambahan2');
    Route::post('/formulir/save_keluarga_ikut','FormulirController@save_keluarga_ikut');
    Route::post('/formulir/update_keluarga_ikut','FormulirController@update_keluarga_ikut');
    Route::post('/formulir/delete_keluarga_ikut','FormulirController@delete_keluarga_ikut');
    Route::post('/formulir/save_keluarga_dihubungi','FormulirController@save_keluarga_dihubungi');
    Route::post('/formulir/update_keluarga_dihubungi','FormulirController@update_keluarga_dihubungi');
    Route::post('/formulir/delete_keluarga_dihubungi','FormulirController@delete_keluarga_dihubungi');
    Route::post('/formulir/export_pdf','FormulirController@export_pdf');
    Route::get('/formulir/export/{id}','FormulirController@export');
    Route::post('/formulir/form_search','FormulirController@form_search');
    Route::get('/formulir/export_jamaah_by_kode/{tgl_awal}/{tgl_akhir}/{kode}','FormulirController@export_jamaah_by_kode');
    Route::get('/formulir/export_jamaah_by_nama/{tgl_awal}/{tgl_akhir}/{filter}/{nama}','FormulirController@export_jamaah_by_nama');
    Route::get('/formulir/export_all/{tgl_awal}/{tgl_akhir}','FormulirController@export_all');

    #PemesananController
    Route::get('/pemesanan','PemesananController@index');
    Route::post('/pemesanan/list_data','PemesananController@list_data');
    Route::post('/pemesanan/add','PemesananController@add');
    Route::post('/pemesanan/pilih_paket','PemesananController@pilih_paket');
    Route::post('/pemesanan/pilih_sub_paket','PemesananController@pilih_sub_paket');
    Route::post('/pemesanan/pilih_harga_paket','PemesananController@pilih_harga_paket');
    Route::post('/pemesanan/generate','PemesananController@generate');
    Route::post('/pemesanan/get_tgl_kembali','PemesananController@get_tgl_kembali');
    Route::post('/pemesanan/open_jamaah','PemesananController@open_jamaah');
    Route::post('/pemesanan/list_data_jamaah','PemesananController@list_data_jamaah');
    Route::post('/pemesanan/ambil_data_jamaah','PemesananController@ambil_data_jamaah');
    Route::post('/pemesanan/list_data_item','PemesananController@list_data_item');
    Route::post('/pemesanan/delete_item','PemesananController@delete_item');
    Route::post('/pemesanan/edit','PemesananController@edit');
    Route::post('/pemesanan/update','PemesananController@update');
    Route::post('/pemesanan/delete','PemesananController@delete');
    Route::post('/pemesanan/pdf_pesanan','PemesananController@pdf_pesanan');
    Route::get('/pemesanan/export_pesanan/{id}','PemesananController@export_pesanan');
    Route::get('/pemesanan/export_jamaah/{id}','PemesananController@export_jamaah');
    Route::post('/pemesanan/form_search','PemesananController@form_search');
    Route::post('/pemesanan/pilih_paket_search','PemesananController@pilih_paket_search');
    Route::post('/pemesanan/pilih_sub_paket_search','PemesananController@pilih_sub_paket_search');
    Route::get('/pemesanan/export_1/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}/{paket}/{sub_paket}/{tgl_berangkat}/{tgl_kembali}','PemesananController@export_1');
    Route::get('/pemesanan/export_2/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}/{paket}/{sub_paket}/{tgl_berangkat}','PemesananController@export_2');
    Route::get('/pemesanan/export_3/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}/{paket}/{sub_paket}','PemesananController@export_3');
    Route::get('/pemesanan/export_4/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}/{paket}','PemesananController@export_4');
    Route::get('/pemesanan/export_5/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}','PemesananController@export_5');
    Route::get('/pemesanan/export_6/{tgl_awal}/{tgl_akhir}/{jenis}/{kategori}/{tgl_berangkat}/{tgl_kembali}','PemesananController@export_6');
    Route::get('/pemesanan/export_7/{tgl_awal}/{tgl_akhir}/{jenis}','PemesananController@export_7');
    Route::get('/pemesanan/export_8/{tgl_awal}/{tgl_akhir}/{category}/{kode_kategori}','PemesananController@export_8');
    Route::get('/pemesanan/export_9/{tgl_awal}/{tgl_akhir}/{nomor_pesanan}','PemesananController@export_9');
    Route::get('/pemesanan/export_10/{tgl_awal}/{tgl_akhir}/{status}','PemesananController@export_10');
    Route::get('/pemesanan/export_all/{tgl_awal}/{tgl_akhir}','PemesananController@export_all');

    #PembayaranController
    Route::get('/pembayaran','PembayaranController@index');
    Route::post('/pembayaran/list_data','PembayaranController@list_data');
    Route::post('/pembayaran/add','PembayaranController@add');
    Route::post('/pembayaran/open_pemesanan','PembayaranController@open_pemesanan');
    Route::post('/pembayaran/list_data_pemesanan','PembayaranController@list_data_pemesanan');
    Route::post('/pembayaran/add_pemesanan','PembayaranController@add_pemesanan');
    Route::post('/pembayaran/save','PembayaranController@save');
    Route::post('/pembayaran/delete','PembayaranController@delete');
    Route::post('/pembayaran/pdf_pembayaran','PembayaranController@pdf_pembayaran');
    Route::get('/pembayaran/export_pembayaran/{id}','PembayaranController@export_pembayaran');
    Route::post('/pembayaran/form_search','PembayaranController@form_search');
    Route::get('/pembayaran/export_1/{tgl_awal}/{tgl_akhir}/{no_pembayaran}','PembayaranController@export_1');
    Route::get('/pembayaran/export_2/{tgl_awal}/{tgl_akhir}/{jenis_pembayaran}','PembayaranController@export_2');
    Route::get('/pembayaran/export_all/{tgl_awal}/{tgl_akhir}','PembayaranController@export_all');

    #ReportController
    Route::get('/report_jamaah','ReportController@report_jamaah');

});
