<div id="temp_view_table"></div>
<div id="ajax_list_table">

  <script src="{{ URL::asset('public/assets/js/sweetalert.js')}}"></script>
@include('style')
<script>

function check_all()
 {
    $(".ios8-switch").prop('checked', true);
 }
 function un_check_all()
 {
    $(".ios8-switch").prop('checked', false);
 }
function update_level()
{
        var check_view = '';
        $('input:checkbox[name^="action_view"]:checked').each(function(){
            check_view += $(this).val()+ " ";
        });
        var check_create = '';
         $('input:checkbox[name^="action_create"]:checked').each(function(){
            check_create += $(this).val()+ " ";

        });
        var check_alter = '';
         $('input:checkbox[name^="action_alter"]:checked').each(function(){
            check_alter += $(this).val()+ " ";
        });
        var check_drop = '';
         $('input:checkbox[name^="action_drop"]:checked').each(function(){
            check_drop += $(this).val()+ " ";
        });

        var view     = check_view;
        var create   = check_create;
        var alter    = check_alter;
        var drop     = check_drop;
        var level    = $("#level").val();
        var id_level = $("#id_level").val();
        var data =
         {
            view:view,
            create:create,
            alter :alter,
            drop  :drop,
            level :level,
            _token: $('input[name=_token]').val(),
            id    :id_level
        };
         if(level=='')
         {
            alertMsg('Level names cannot be empty !','error');
            $("#level").focus();
         }
         else
         {
               swal("A wild Pikachu appeared! What do you want to do?", {
                buttons: {

                  catch: {
                    text: "Yes",
                    value: "catch",
                  },
                  cancel: "Cancel",
                },
                })
                .then((value) => {
                switch (value) {
                   case "catch":
                   $.ajax({
                    url       :"priveleges/update",
                    dataType  : "json",
                    type		  : "POST",
                    data		  : data,
                    success:function(result)
                    {
                      if(result.status=='error')
                      {
                          if (result.errors.level)
                          {
                                alertMsg(result.errors.level,'error');
                          }

                      }
                          if(result.status=='success')
                          {
                            swal("Info System", result.msg, "success");
                            list_data('save');
                          }
                          else
                          {
                            swal("Info System", result.msg, "error");
                          }
                    }
                      })
                    break;
                    default:
                    return false;
                }
                });
          }
 }

</script>

<script>
function alertMsg(message,clasStyle)
{
  $.notify({
      title: 'Info System',
      text: message,
      image: "<i class='fa fa-warning'></i>"
  }, {
      style: 'metro',
      className: clasStyle,
      globalPosition:'top right',
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHideDelay: 3000,
      autoHide: true,
      clickToHide: true
  });

}
</script>

<form class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="inputEmail3">Level Name</label>
        <div class="col-sm-4">
            <input id="level" class="form-control" type="text" value="{{$row->gname}}" >
            <input type="hidden" id='id_level' value="{{$row->group_id}}"/>
        </div>
    </div>
</form>
<button class="btn btn-outline btn-success btn-sm" type="button" id="select_all" onclick="check_all()"><i class="fa fa-check"></i> Check All</button>
<button class="btn btn-outline btn-danger btn-sm" type="button" id="un_select_all" onclick="un_check_all()"><i class="fa fa-close"></i> Un Check All</button>
<p></p>
<div class="table-responsive">
<table class="table table-hover table-bordered">
  <thead>
    <th>Module</th>
    <th>View</th>
    <th>Create</th>
    <th>Alter</th>
    <th>Drop</th>
  </thead>
  <tbody>
    <?php $no =1;


    ?>
    @foreach($module as $key => $val)
    <?php
    if( $val->enable == 1 || $val->r_create == 1 || $val->r_alter == 1 || $val->r_drop == 1)
    {
        $role_data_view   = '';
        $role_data_create = '';
        $role_data_alter  = '';
        $role_data_drop   = '';

            foreach( explode(',', $row->role_view) as $r_view )
  					{
  						if( $r_view == $val->modid )
  						{
  							$role_data_view = $r_view;
  						}
  					}


              foreach( explode(',', $row->role_create) as $r_create )
    					{
    						if( $r_create == $val->modid )
    						{
    							$role_data_create = $r_create;
    						}
    					}

    					foreach( explode(',', $row->role_alter) as $r_alter )
    					{
    						if( $r_alter == $val->modid )
    						{
    							$role_data_alter = $r_alter;
    						}
    					}

    					foreach( explode(',', $row->role_drop) as $r_drop )
    					{
    						if( $r_drop == $val->modid )
    						{
    							$role_data_drop = $r_drop;
    						}
    					}

            $checked_view = '';
						if( $role_data_view == $val->modid )
						{
							$checked_view = 'checked';
						}

            $checked_create = '';
						if( $role_data_create == $val->modid )
						{
							$checked_create = 'checked';
						}

            $checked_alter = '';
						if( $role_data_alter == $val->modid )
						{
							$checked_alter = 'checked';
						}

            $checked_drop = '';
						if( $role_data_drop == $val->modid )
						{
							$checked_drop = 'checked';
						}

        }

    ?>
    <tr>
      <td>{{$val->modules}}</td>
      <td>
        @if($val->enable==1)
        <input type='checkbox' class='ios8-switch' id='action_view_{{$val->modid}}' {{$checked_view}} name="action_view" value="{{$val->modid}}">
        <label for='action_view_{{$val->modid}}'>&nbsp;</label>
        @endif
      </td>
      <td>
        @if($val->r_create==1)
        <input type="checkbox" class="ios8-switch" id="action_create_{{$val->modid}}" {{$checked_create}} name="action_create" value="{{$val->modid}}"/>
        <label for="action_create_{{$val->modid}}"><span></span></label>
        @endif
      </td>
      <td>
        @if($val->r_alter==1)
        <input type="checkbox" class="ios8-switch" id="action_alter_{{$val->modid}}" {{$checked_alter}} name="action_alter" value="{{$val->modid}}"/>
        <label for="action_alter_{{$val->modid}}"><span></span></label>
        @endif
      </td>
      <td>
        @if($val->r_drop==1)
        <input type="checkbox" class="ios8-switch" id="action_drop_{{$val->modid}}" {{$checked_drop}} name="action_drop" value="{{$val->modid}}"/>
        <label for="action_drop_{{$val->modid}}"><span></span></label>
        @endif
      </td>
    </tr>
    <?php $no++; ?>
    @endforeach
  </tbody>
</table>
</div>
<p></p>
<button class="btn btn-primary btn-sm" type="button" onclick="update_level()">
<i class="fa fa-file-o"></i>
Save changes
</button>

  <a href="{{URL::to('priveleges')}}" class="btn btn-github btn-sm"><i class="icon-reply-1"></i></i> Back </a>
</div>
<p></p>
<div id="pagination" class="pagging">
   <div><a href="#" id="1"></a></div>
 </div>
