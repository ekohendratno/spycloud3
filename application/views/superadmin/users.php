<div class="container">


        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<b>USERS TARGET</b>
					</div>
					<div class="panel-body">

						
						<div class="row">
									<div class="col-md-10">
										<div class="col-md-3" style="padding-left: 0px;">
										<input class="form-control" type="text" id="keywords" placeholder="Type keywords to filter posts" onkeyup="searchFilter()"/>
										</div>
										<div class="col-md-2">
										<select class="form-control"  id="sortBy" onchange="searchFilter()">
											<option value="">Sort By</option>
											<option value="asc">Ascending</option>
											<option value="desc">Descending</option>
										</select>
										</div>
										<div class="col-md-1">
										<select class="form-control"  id="limitBy" onchange="searchFilter()">
											<option value="10">10</option>
											<option value="50">50</option>
											<option value="100">100</option>
										</select>
										</div>
										<div class="col-md-2">
										<select class="form-control"  id="levelBy" onchange="searchFilter()">
											<option value="target">Target</option>
											<option value="admin">Admin</option>
										</select>
										</div>
										<div class="col-md-1">
										<a href="<?php echo base_url(). "index.php/admin/users/index"; ?>" class="btn btn-primary">Show All</a>
										</div>
									</div>
						</div>
						<br/>
						<table id='postList' class="table table-striped table-hover table-bordered">
									<thead>				
										<tr>
											<th class="text-center">NO</th>
											<th>NAMA</th>
											<th class="text-center">LEVEL</th>
											<th>PHONE</th>
											<th class="text-center" width="100"><span class="glyphicon glyphicon-cog"></span></th>
										</tr>
									</thead>
									<tbody></tbody>		
						</table>
						<div id='pagination'></div>
					
						
						<div class="modal fade" id="form" role="dialog">
							<div class="modal-dialog modal-sm">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row">
											
										<div class="col-md-12">
											<input type="hidden" value="" id="user_id" name="user_id"/>
											<label>Aksi</label><br/>
											<select class="form-control"  id="id" name="id">
											<?php foreach($commanders as $item ){?>
											<option value="<?php echo $item['id']?>"><?php echo $item['title']?></option>
											<?php }?>
											</select>
											
										</div>
											
										</div>
										<div class="clear"></div>
									</div>
									<div class="modal-footer">
									  <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
									  <a href="#form" data-toggle="modal" onclick="tambahdata()" class="btn btn-danger">Mulai</a> 
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
				source: "<?php echo base_url()?>index.php/admin/users/autocompleteData",
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
		
		searchFilter(0);
		function searchFilter(page_num) {
			page_num = page_num?page_num:0;
			var keywords = $('#keywords').val();
			var sortBy = $('#sortBy').val();
			var limitBy = $('#limitBy').val();
			var levelBy = $('#levelBy').val();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/admin/users/ajaxPaginationData/'+page_num,
				data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&limitBy='+limitBy+'&levelBy='+levelBy,
				dataType:'json',
				beforeSend: function () {
					$('#loading_ajax').show();
				},
				success: function (responseData) {
					console.log(responseData);
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
				
				var level  = data[emp].level;
				
				
				var phone = '';
				if(data[emp].imei != ''){
					phone = '<span class="label label-success">'+data[emp].imei+'</span><br/><span class="label label-success">'+data[emp].serial+'</span><br/><span class="label label-success">'+data[emp].phone_model+'</span>';				   
				}
				
				var empRow = '<tr>'+
							'<td class="text-center">'+nomor+'</td>'+
							'<td>'+data[emp].username+'<br/><span class="label label-default">'+data[emp].password+'</span></td>'+
							'<td class="text-center"><span class="label label-default">'+level.toUpperCase()+'</span></td>'+
							'<td>'+phone+'</td>'+
							'<td class="text-center"><div class="btn-group" role="group"><a href="#form" data-toggle="modal" onclick="submit('+data[emp].user_id+')" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-flash"></span></a> <a onclick="hapus('+data[emp].user_id+')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></div></td>'+
							+'</tr>';
				nomor++;
				$('#postList tbody').append(empRow);					
			}
		}
		
		function tambahdata(){
			$('#loading_ajax').show();			
			var id =  $("[name='id']").val();
			var user_id =  $("[name='user_id']").val();
			
			$.ajax({
				type:'POST',
				data: 'id='+id+'&user_id='+user_id,
				url:'<?php echo base_url('index.php/admin/commander/tambahdata') ;?>',
				dataType:'json',
				success: function(hasil){
					$('#loading_ajax').fadeOut("slow");
					$('.modal-status').show();
					$('.modal-status').html('<p class="bg-warning">'+hasil.pesan+'</p>');
					
					if(hasil.pesan == ''){
						$('#form').modal('hide');
						searchFilter(0);

						//bersihkan form
						$("[name='user_id']").val('');
						$("[name='id']").val('');
				
						window.location.assign("<?php echo base_url();?>index.php/admin/commander"); 
					}
				}
			});
		}
		
		function submit(x){
			//bersihkan form
			$("[name='user_id']").val('');
			
			$("#avatar").empty();
			$("#avatar").hide();
			$('.modal-status').hide();
			if(x == 'tambah'){
				$('#btn-tambah').show();
				$('#btn-ubah').hide();
			}else{
				$('#loading_ajax').show();	
				$('#btn-tambah').hide();
				$('#btn-ubah').show();
				
				$("[name='user_id']").val(x);
				$('#loading_ajax').fadeOut("slow");
				
				
			}
		}
		
		function simpandata(){
			$('#loading_ajax').show();	
			var user_id =  $("[name='user_id']").val();
			var id =  $("#id").val();
			
			$.ajax({
				type:'POST',
				data: 'id='+user_id+'&id='+id,
				url:'<?php echo base_url('index.php/admin/users/simpandatabyid') ;?>',
				dataType:'json',
				success: function(hasil){
					$('#loading_ajax').fadeOut("slow");
					$('.modal-status').show();
					$('.modal-status').html('<p class="bg-warning">'+hasil.pesan+'</p>');
					
					if(hasil.pesan == ''){
						$('#form').modal('hide');
						searchFilter();
					}
				}
			});
		}
	
		function hapus(x){
			$('#loading_ajax').show();	
			var tanya = confirm('Apakah yakin mau hapus data?');
			if(tanya){
				$.ajax({
				type:'POST',
				data: 'id='+x,
				url:'<?php echo base_url('index.php/admin/users/hapusdatabyid') ;?>',
				success: function(){					
					searchFilter(0);
				}
			});
			}else{				
				$('#loading_ajax').fadeOut("slow");	
			}
		}
	
	</script>