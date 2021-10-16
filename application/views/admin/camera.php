<div class="container container-medium">


        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-12">
				<div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title text-center" style="padding-top: 7.5px;">CAMERA</h4>
                        <div class="panel-title-button pull-right">
                            <a href="#formsearch" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-search"></span></a>
                            <a href="#form2" data-toggle="modal" class="btn" title="Filter"><span class="fas fa-filter"></span></a>
                            <a href="#" onclick="searchFilter(0)" class="btn"><span class="fas fa-redo-alt"></span></a>
                            <a href="<?php echo base_url(). "admin/commander/index"; ?>" class="btn" title="Tambah Perintah"><span class="fas fa-plus"></span></a>
                            <a style="display: none" href="javascript:void(0);" onClick="resetData()" class="btn" title="Reset"><span class="fas fa-refresh"></span></a>
                        </div>
                    </div>
					<div class="panel-body">


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
                                                <a href="<?php echo base_url(). "admin/camera/index"; ?>" class="btn btn-primary">Show All</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script src="http://releases.flowplayer.org/js/flowplayer-3.2.12.min.js"></script>
                        <script src="http://releases.flowplayer.org/audio/flowplayer.audio.min.js"></script>

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

		setInterval(function () {
            searchFilter(0);
        },30000);


        function show_samples(artist_id, language){
            $("#sample-box").load("index.php?rt=artist/show_vo_samples", {'artist_id':artist_id, 'language':language} , function(responseTxt, statusTxt, xhr){
                if(statusTxt == "success")
                    $('#sample-box').modal();
                if(statusTxt == "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            });
        }
		
		searchFilter(0);
		function searchFilter(page_num) {
			page_num = page_num?page_num:0;
			var sortBy = $('#sortBy').val();
			var limitBy = $('#limitBy').val();
            var phoneBy = $('#phoneBy').val();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/admin/camera/ajaxPaginationData/'+page_num,
				data:'page='+page_num+'&sortBy='+sortBy+'&limitBy='+limitBy+'&phoneBy='+phoneBy,
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

                var lihat = '';

                if( data[emp].image != "" ){

                    lihat+= '<a href="<?php echo base_url('download/?for=') ;?>'+data[emp].image+'" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-download-alt"></span></a> ';

                    lihat+= '<a href="#sample-box" data-toggle="modal" onclick="submit(\''+data[emp].image+'\',\''+data[emp].for+'\')" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-eye-open"></span></a> ';

                }
				
				var empRow = '<tr>'+
							'<td class="text-center">'+nomor+'</td>'+
							'<td><span class="label label-default">'+data[emp].phone_name+'</span> '+
                            '<span class="label label-success">'+data[emp].for+'</span>'+
                            '<br>'+data[emp].image+'<br/>'+
							'<span class="label label-danger">'+data[emp].camera_date+'</span></td>'+
							'<td class="text-center">'+lihat+'<a onclick="hapus('+data[emp].camera_id+')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>'+
							+'</tr>';
				nomor++;
				$('#postList tbody').append(empRow);					
			}
		}

		function submit(x,y) {
		    var link = "<?php echo base_url(); ?>uploads/" + x;
		    if(y == "foto"){
                $('#showCamera').html(
                    '<img class="img-responsive" src="'+link+'" width="100%" />'
                );
            }else if(y == "video"){

                $('#showCamera').html(
                    '<div class="embed-responsive embed-responsive-16by9 flowplayer" data-swf="http://releases.flowplayer.org/swf/flowplayer-3.2.18.swf" data-ratio="0.4167">' +
                    '<video class="embed-responsive-item" controls="controls">'+
                    '<source type="video/mp4" src="'+link+'">'+
                    '</video>'+
                    '</div>'
                );
            }else if(y == "audio"){
                $('#showCamera').html(
                    '<div class="embed-responsive embed-responsive-16by9 flowplayer" data-swf="http://releases.flowplayer.org/swf/flowplayer-3.2.18.swf" data-ratio="0.4167">' +
                    '<video class="embed-responsive-item" controls="controls">'+
                    '<source type="audio/mpeg" src="'+link+'">'+
                    '</video>'+
                    '</div>'
                );
		    }else{

            }
        }
	
		function hapus(x){
			$('#loading_ajax').show();	
			var tanya = confirm('Apakah yakin mau hapus data?');
			if(tanya){
				$.ajax({
				type:'POST',
				data: 'id='+x,
				url:'<?php echo base_url('index.php/admin/camera/hapusdatabyid') ;?>',
				success: function(){					
					searchFilter(0);
				}
			});
			}else{				
				$('#loading_ajax').fadeOut("slow");	
			}
		}

	
	</script>