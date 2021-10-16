<div class="container container-medium">


        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
				<div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title text-center" style="padding-top: 7.5px;">COMMANDER</h4>
                        <div class="panel-title-button pull-right">
                            <a href="#formsearch" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-search"></span></a>
                            <a href="#form2" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-filter"></span></a>
                            <a href="#" onclick="searchFilter(0)" class="btn"><span class="fas fa-redo-alt"></span></a>
                            <a href="#form" onclick="clearCommand()" data-toggle="modal" class="btn" title="Tambah Perintah"><span class="fas fa-plus"></span></a>
                            <a style="display: none" href="javascript:void(0);" onClick="resetData()" class="btn" title="Reset"><span class="fas fa-refresh"></span></a>
                        </div>
                    </div>
					<div class="panel-body">


                        <table id='postList' class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>ACTION</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center" width="100"><span class="glyphicon glyphicon-cog"></span></th>
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
                                                    <option value="150">150</option>
                                                    <option value="200">200</option>
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
                                                <a href="<?php echo base_url(). "admin/commander/index"; ?>" class="btn btn-primary">Show All</a>
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
                                        <label>TARGET</label><br>
                                        <select class="form-control" name="phone_id">
                                            <option value="">Pilih Target</option>
                                            <?php foreach($phone as $item ){?>
                                                <option value="<?php echo $item['id']?>"><?php echo $item['title']?></option>
                                            <?php }?>
                                        </select>
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
		$(document).ready(function(){
			$(".selector").keyup(function(){ // Ketika user menekan tombol di keyboard
				if(event.keyCode == 13){ // Jika user menekan tombol ENTER
				   // Panggil function search
					$('#loading_ajax').show();
				}
			  });
			$(".selector").autocomplete({
				source: "<?php echo base_url()?>index.php/admin/commander/autocompleteData",
				minLength: 1,
				select: function(event, ui) {
					$(".selector").val(ui.item.value);
					$("#siswa_id").val(ui.item.id);
				}
			}).data("ui-autocomplete")._renderItem = function( ul, item ) {
			return $( "<li class='ui-autocomplete-row'></li>" )
				.data( "item.autocomplete", item )
				.append( item.label )
				.appendTo( ul );
			};
		});

		setInterval(function () {
            searchFilter(0);
        },5000);
		
		searchFilter(0);
		function searchFilter(page_num) {
			page_num = page_num?page_num:0;
			var sortBy = $('#sortBy').val();
			var limitBy = $('#limitBy').val();
            var phoneBy = $('#phoneBy').val();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/admin/commander/ajaxPaginationData/'+page_num,
				data:'page='+page_num+'&sortBy='+sortBy+'&limitBy='+limitBy+'&phoneBy='+phoneBy,
				dataType:'json',
				beforeSend: function () {
					$('#loading_ajax').show();
				},
				success: function (responseData) {
					//console.log(responseData);
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
				
				var status = '<span class="label label-warning">Tertunda</span>';
				if(data[emp].panding == '0'){
					status = '<span class="label label-success">Dieksekusi</span>';	
				}

				var lihat = "";
				if(data[emp].panding < 1){

				    var aksi = "commander";
				    if( data[emp].id == 7 || data[emp].id == 12 || data[emp].id == 11 ){
				        aksi = "camera";
                    }else if( data[emp].id == 1 ){
                    }else if( data[emp].id == 2 ){
                    }else if( data[emp].id == 3 ){
                        aksi = "gps";
                    }else if( data[emp].id == 4 ){
                        aksi = "call";
                    }else if( data[emp].id == 5 ){
                        aksi = "sms";
                    }else if( data[emp].id == 6 ){
                        aksi = "contact";
                    }else if( data[emp].id == 8 ){
                    }else if( data[emp].id == 9 ){
                        aksi = "calendar";
                    }else if( data[emp].id == 10 ){
                    }else if( data[emp].id == 13 ){
                    }else if( data[emp].id == 14 ){
                    }else if( data[emp].id == 15 ){
                    }else if( data[emp].id == 16 ){
                    }else if( data[emp].id == 17 ){
                    }else if( data[emp].id == 18 ){
                    }


                    lihat+= '<a href="<?php echo base_url('index.php/admin/') ;?>'+aksi+'" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-eye-open"></span></a> ';
                }
				
				var empRow = '<tr>'+
							'<td class="text-center">'+nomor+'</td>'+
							'<td><span class="label label-success">'+data[emp].commands_prompt_name+'</span><br>'+data[emp].phone_name+' - '+data[emp].phone_serial+' - '+data[emp].phone_model+'<br/>'+
							'<span class="label label-danger">'+data[emp].start+'</span> '+
							'<span class="label label-success">'+data[emp].end+'</span></td>'+
							'<td class="text-center">'+status+'</td>'+
							'<td class="text-center"><div class="btn-group" role="group">'+lihat+'<a onclick="hapus('+data[emp].commands_id+')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></div></td>'+
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
				url:'<?php echo base_url('index.php/admin/commander/hapusdatabyid') ;?>',
				success: function(){					
					searchFilter(0);
				}
			});
			}else{				
				$('#loading_ajax').fadeOut("slow");	
			}
		}

		function clearCommand() {
            $("[name='phone_id']").val('');
            $("[name='id']").val('');
            $("[name='param1']").val('');
            $("[name='param2']").val('');
            $("[name='param3']").val('');
            $("[name='param4']").val('');
        }
		
		function goCommander() {
            $('#loading_ajax').show();
            var phone_id =  $("[name='phone_id']").val();
            var id =  $("[name='id']").val();
            var param1 =  $("[name='param1']").val();
            var param2 =  $("[name='param2']").val();
            var param3 =  $("[name='param3']").val();
            var param4 =  $("[name='param4']").val();

            $.ajax({
                type:'POST',
                data: 'phone_id='+phone_id+'&id='+id+'&param1='+param1+'&param2='+param2+'&param3='+param3+'&param4='+param4,
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
	
	</script>