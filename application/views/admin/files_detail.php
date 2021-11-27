<div class="container container-medium">


        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
				<div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title text-center" style="padding-top: 7.5px;">FILES</h4>
                        <div class="panel-title-button pull-right">
                            <a href="#form2" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-filter"></span></a>
                            <a href="#" onclick="searchFilter(0)" class="btn"><span class="fas fa-redo-alt"></span></a>
                        </div>
                    </div>
					<div class="panel-body">


                        <input type="hidden" name="page" id="page" value="0"/>
						<div id='pagination0'></div>
                        <table id='postList' class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>IMAGE</th>
                                    <th class="text-center" width="150"><span class="glyphicon glyphicon-cog"></span></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
						</table>
						<div id='pagination'></div>

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
                                                    <option value="500">500</option>
                                                    <option value="1000">1000</option>
                                                    <option value="1500">1500</option>
                                                    <option value="2000">2000</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="<?php echo base_url(). "admin/camera/index"; ?>" class="btn btn-primary">Show All</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script src="https://releases.flowplayer.org/js/flowplayer-3.2.12.min.js"></script>
                        <script src="https://releases.flowplayer.org/audio/flowplayer.audio.min.js"></script>

                        <!--<link rel="stylesheet" href="https://releases.flowplayer.org/7.1.2/skin/skin.css">-->

                        <div id="sample-box" class="modal fade" role="dialog">
                            <div class="modal-dialog" role="document">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div id="showCamera"></div>
                                </div>
                            </div>
                        </div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">

		
		searchFilter(0);
		function searchFilter(page_num) {
			page_num = page_num?page_num:0;
			var sortBy = $('#sortBy').val();
			var limitBy = $('#limitBy').val();
			
			$('#page').val(page_num);
			
			
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/admin/files/ajaxPaginationData/'+page_num,
				data:'page='+page_num+'&sortBy='+sortBy+'&limitBy='+limitBy+'&pathBy=<?php echo $path;?>',
				dataType:'json',
				beforeSend: function () {
					$('#loading_ajax').show();
				},
                success: function (responseData) {
                    console.log(responseData);
                    $('#pagination').html(responseData.pagination);
                    $('#pagination0').html(responseData.pagination);
                    paginationData(responseData.empData);
                    $('#loading_ajax').fadeOut("slow");
				}
			});
		}
		
		function paginationData(data) {
			$('#postList tbody').empty();
			var nomor = 1;
			for(emp in data){

			    var thumb = "thumb.php";
			    if(data[emp].ext == "webp"){
                    thumb = "thumb2.php";
                }
				var empRow = '<tr>'+
							'<td class="text-center">'+nomor+'</td>'+
							'<td><a href="#sample-box" data-toggle="modal" onclick="submit(\'<?php echo base_url();?>thumb.php?src=./uploads/'+data[emp].path+'&size=100x100\',\'<?php echo base_url();?>uploads/'+data[emp].path+'\')">'+
							'<img src=\'<?php echo base_url();?>'+thumb+'?src=./uploads/'+data[emp].path+'&size=40x40\' /> '+data[emp].image+'</a><br/>'+data[emp].size+', '+data[emp].tanggal2+'</td>'+
							'<td class="text-center"><a href="<?php echo base_url();?>uploads/'+data[emp].path+'" target="_blank" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span></a> <a onclick="hapus(\''+data[emp].path+'\')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>'+
							+'</tr>';
				nomor++;
				$('#postList tbody').append(empRow);					
			}
		}



        function submit(x,y) {
            $('#showCamera').html(
                '<div class="modal-body"><img class="img-responsive" src="'+x+'" width="100%" /></div>'+
                '<div class="modal-footer">'+
                '<a href="javascript:void();" data-dismiss="modal" class="btn btn-default">Close</a>'+
                '<a href="'+y+'" class="btn btn-primary" target="_blank">Lihat Gambar Asli</a>'+
                '</div>'
            );
        }
        
        function hapus(x){
            //var tanya = confirm('Apakah yakin mau hapus data?');
            //if(tanya){
                $.ajax({
                    type:'POST',
                    data: 'path='+x,
                    url:'<?php echo base_url('index.php/admin/files/hapusdatabypath') ;?>',
    				beforeSend: function () {
    					$('#loading_ajax').show();
    				},
                    success: function(){
                        $('#loading_ajax').fadeOut("slow");
			            var page = $('#page').val();
                        searchFilter(page);
                    }
                });
            //}else{
            //}
        }
	
	</script>