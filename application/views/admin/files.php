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


                        <table id='postList' class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>Folder</th>
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
                                                    <option value="10">10</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="150">150</option>
                                                    <option value="200">200</option>
                                                    <option value="500">500</option>
                                                    <option value="1000">1000</option>
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
			$.ajax({
				type: 'GET',
				url: '<?php echo base_url(); ?>index.php/admin/files/ajaxPaginationDataDir/'+page_num,
				data:'page='+page_num+'&sortBy='+sortBy+'&limitBy='+limitBy,
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

				var empRow = '<tr>'+
							'<td class="text-center">'+nomor+'</td>'+
							'<td><a href="'+data[emp].link+'">'+data[emp].folder+'</a></td>'+
							'<td class="text-center"><a onclick="#" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>'+
							+'</tr>';
				nomor++;
				$('#postList tbody').append(empRow);					
			}
		}



        function submit(x) {
            $('#showCamera').html(
                '<img class="img-responsive" src="'+x+'" width="100%" />'
            );
        }
	
	</script>