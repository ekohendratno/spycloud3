<div class="container container-medium">


        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
				<div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title text-center" style="padding-top: 7.5px;">TARGET PHONE</h4>
                        <div class="panel-title-button pull-right">
                            <a href="#formsearch" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-search"></span></a>
                            <a href="#form2" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-filter"></span></a>
                            <a href="#" onclick="searchFilter(0)" class="btn"><span class="fas fa-redo-alt"></span></a>
                            <a style="display: none" href="javascript:void(0);" onClick="resetData()" class="btn" title="Reset"><span class="fas fa-refresh"></span></a>
                            <a href="javascript:void(0);" onClick="uploadAllData()" class="btn" title="Up Data"><span class="fas fa-upload"></span></a>
                            <a href="javascript:void(0);" onClick="noUploadAllData()" class="btn" title="Up Data"><span class="fas fa-stopwatch"></span></a>
                        </div>
                    </div>
					<div class="panel-body">


                        <table id='postList' class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>PHONE</th>
                                    <th class="text-center" width="250"><span class="glyphicon glyphicon-cog"></span></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
						</table>
						<div id='pagination'></div>


                        <div class="modal fade" id="formsearch" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row">


                                            <div class="col-md-12">
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-addon"><i class="fas fa-search"></i></div>
                                                    <input type="text" class="form-control token" name="keywords" id="keywords" placeholder="Type keywords to filter posts" onkeyup="searchFilter()">
                                                </div>

                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="form2" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <select class="form-control"  id="sortBy" onchange="searchFilter()">
                                                    <option value="">Sort By</option>
                                                    <option value="desc">Descending</option>
                                                    <option value="asc">Ascending</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control"  id="limitBy" onchange="searchFilter()">
                                                    <option value="10">10</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="300">300</option>
                                                    <option value="500">500</option>
                                                    <option value="1000">1000</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control"  id="phoneBy" onchange="searchFilter()">
                                                    <option value="">Semua Target</option>
                                                    <?php foreach($phone as $item ){?>
                                                        <option value="<?php echo $item['id']?>"><?php echo $item['title']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="<?php echo base_url(). "admin/phone/index"; ?>" class="btn btn-primary">Show All</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="form" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-status"></div>
                                    <div class="modal-body">
                                        <input type="hidden" class="form-control" name="phone_id" id="phone_id">
                                        <input type="hidden" class="form-control" name="uid" id="uid">
                                        <label>COMMAND</label><br>
                                        <select class="form-control" name="id">
                                            <option value="">Pilih Perintah</option>
                                            <?php foreach($commands_prompt as $item ){?>
                                                <option value="<?php echo $item['id']?>"><?php echo $item['title']?></option>
                                            <?php }?>
                                        </select>
                                        <label>Param 1</label>
                                        <input type="text" name="param1" class="form-control" />
                                        <label>Param 2</label>
                                        <input type="text" name="param2" class="form-control" />
                                        <label>Param 3</label>
                                        <input type="text" name="param3" class="form-control" />
                                        <label>Param 4</label>
                                        <input type="text" name="param4" class="form-control" />
                                    </div>
                                    <div class="modal-footer">
                                        <button onclick="goCommander()" type="button" id="btn-tambah" class="btn btn-primary">Jalankan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">

		/**setInterval(function () {
            searchFilter(0);
        },30000);*/
		
		searchFilter(0);
		function searchFilter(page_num) {
			page_num = page_num?page_num:0;
			var sortBy = $('#sortBy').val();
			var limitBy = $('#limitBy').val();
            var phoneBy = $('#phoneBy').val();
            var keywords = $('#keywords').val();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/admin/phone/ajaxPaginationData/'+page_num,
				data:'page='+page_num+'&sortBy='+sortBy+'&limitBy='+limitBy+'&phoneBy='+phoneBy+'&keywords='+keywords,
				dataType:'json',
				beforeSend: function () {
					$('#loading_ajax').show();
				},
				success: function (responseData) {
					$('#pagination').html(responseData.pagination);
					paginationData(responseData.empData);
					$('#loading_ajax').fadeOut("slow");
				}
			});
		}


        function paginationData(data) {
            $('#postList tbody').empty();
            var nomor = 1;
            for(emp in data){

                var phone_name = data[emp].phone_model;
                if( data[emp].phone_name != "" ){
                    phone_name = data[emp].phone_name;
                }

                var lihat = '<a href="#form" onclick="clearCommand('+data[emp].phone_id+','+data[emp].uid+')" data-toggle="modal" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-dashboard"></span></a> ';
                lihat+= '<a href="#" onclick="submit('+data[emp].phone_id+')" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-eye-open"></span></a> ';


                var status = '<span class="label label-danger">offline</span>';
                if( data[emp].status == "online"){
                    status = '<span class="label label-success">online</span>';
                }

                var empRow = '<tr>'+
                    '<td class="text-center">'+nomor+'</td>'+
                    '<td>'+phone_name+'<br>'+
                    '<span class="label label-success">'+data[emp].versicode+'</span> '+
                    '<span class="label label-success">'+data[emp].versiname+'</span> '+
                    '<span class="label label-success">'+data[emp].versioscodename+'</span> '+
                    '<span class="label label-warning">'+data[emp].phone_serial+'</span> '+
                    '<span class="label label-warning">'+data[emp].phone_model+'</span><br>'+
                    '<span class="label label-default">'+data[emp].phone_last_active+'</span> '+
                    '<span class="label label-default">'+data[emp].ForceUpload+'</span> '+
                    ''+status+'</td>'+
                    '<td class="text-center">'+lihat+
                    '<a href="<?php echo base_url();?>admin/files/buka/'+phone_name+'_fotos" target="_blank" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span></a>'+
                    '<a onclick="uploadYes('+data[emp].phone_id+')" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-cloud-upload"></span></a>'+
                    '<a onclick="hapus('+data[emp].phone_id+')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a>'+
                    '</td>'+
                    +'</tr>';
                nomor++;
                $('#postList tbody').append(empRow);
            }
        }
	
		function hapus(x){
			$('#loading_ajax').show();	
			var tanya = confirm('Apakah yakin mau hapus data?');
			if(tanya){
				$.ajax({
				type:'POST',
				data: 'id='+x,
				url:'<?php echo base_url('index.php/admin/phone/hapusdatabyid') ;?>',
				success: function(){					
					searchFilter(0);
				}
			});
			}else{				
				$('#loading_ajax').fadeOut("slow");	
			}
		}

        function uploadYes(x){
            $.ajax({
                type:'POST',
                data: 'id='+x,
                url:'<?php echo base_url('index.php/admin/phone/republish') ;?>',
                success: function(){
                    searchFilter(0);
                }
            });
        }

        function uploadAllData(){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('index.php/admin/phone/republishall') ;?>',
                success: function(){
                    searchFilter(0);
                }
            });
        }

        function noUploadAllData(){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('index.php/admin/phone/unpublishall') ;?>',
                success: function(){
                    searchFilter(0);
                }
            });
        }


        function clearCommand(x,y) {
            $("[name='phone_id']").val('');
            $("[name='id']").val('');
            $("[name='param1']").val('');
            $("[name='param2']").val('');
            $("[name='param3']").val('');
            $("[name='param4']").val('');

            $("[name='phone_id']").val(x);
            $("[name='uid']").val(y);
        }

        function goCommander() {
            $('#loading_ajax').show();
            var phone_id =  $("[name='phone_id']").val();
            var uid =  $("[name='uid']").val();
            var id =  $("[name='id']").val();
            var param1 =  $("[name='param1']").val();
            var param2 =  $("[name='param2']").val();
            var param3 =  $("[name='param3']").val();
            var param4 =  $("[name='param4']").val();

            $.ajax({
                type:'POST',
                data: 'phone_id='+phone_id+'&uid='+uid+'&id='+id+'&param1='+param1+'&param2='+param2+'&param3='+param3+'&param4='+param4,
                url:'<?php echo base_url('index.php/admin/commander/tambahdata') ;?>',
                dataType:'json',
                success: function(hasil){
                    $('#loading_ajax').fadeOut("slow");
                    $('.modal-status').show();
                    $('.modal-status').html('<p class="bg-warning">'+hasil.pesan+'</p>');

                    if(hasil.pesan == ''){
                        $('#form').modal('hide');
                        searchFilter(0);
                    }
                }
            });
        }

        function submit(x) {

        }
	
	</script>