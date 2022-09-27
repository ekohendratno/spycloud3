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



                    <ul class="nav nav-tabs" style="margin-bottom: 8px">
                        <li class="active"><a data-toggle="tab" href="#terbaru">Terbaru</a></li>
                        <li><a data-toggle="tab" href="#pantau">Pantau</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="terbaru" class="tab-pane fade in active">

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

                        </div>
                        <div id="pantau" class="tab-pane fade in">

                            <table id='postList2' class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>Folder</th>
                                    <th class="text-center" width="150"><span class="glyphicon glyphicon-cog"></span></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div id='pagination2'></div>

                        </div>
                    </div>



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
        $('#postList2 tbody').empty();
        var nomor = 1;
        for(emp in data){

            var menu_add_fav = "";
            if(!data[emp].fav){
                menu_add_fav = '<a onclick="addFav(\''+data[emp].folder+'\')" title="Fav" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></a> ';
            }

            var empRow = '<tr>'+
                '<td class="text-center">'+nomor+'</td>'+
                '<td><a href="'+data[emp].link+'" target="_blank">'+data[emp].folder+'</a></td>'+
                '<td class="text-right">'+menu_add_fav+'<a onclick="res8Bulk(\''+data[emp].folder+'\')" title="Bulk" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-check"></span></a> <a onclick="submitDelete(\''+data[emp].folder+'\')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>'+
                +'</tr>';

            $('#postList tbody').append(empRow);

            if(data[emp].fav){
                var menu_add_fav = '<a onclick="removeFav(\''+data[emp].folder+'\')" title="Fav" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-minus"></span></a> ';
                var empRow2 = '<tr>'+
                    '<td class="text-center">'+nomor+'</td>'+
                    '<td><a href="'+data[emp].link+'" target="_blank">'+data[emp].folder+'</a></td>'+
                    '<td class="text-right">'+menu_add_fav+'<a onclick="res8Bulk(\''+data[emp].folder+'\')" title="Bulk" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-check"></span></a> <a onclick="submitDelete(\''+data[emp].folder+'\')" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>'+
                    +'</tr>';

                $('#postList2 tbody').append(empRow2);
            }



            nomor++;

        }
    }



    function submit(x) {
        $('#showCamera').html(
            '<img class="img-responsive" src="'+x+'" width="100%" />'
        );
    }

    function res8Bulk(path) {
        console.log(path);

        var tanya = confirm('Apakah yakin mau bulk data?');
        if(tanya){
            window.open( '<?php echo base_url(); ?>index.php/Res8Bulk?path='+path );
        }else{
        }
    }


    function submitDelete(path) {
        console.log(path);

        var tanya = confirm('Apakah yakin mau hapus data?');
        if(tanya){
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url(); ?>index.php/admin/files/hapusdatabypath2/',
                data:'path='+path,
                dataType:'json',
                beforeSend: function () {
                    $('#loading_ajax').show();
                },
                success: function (response) {

                    searchFilter(0);
                    $('#loading_ajax').fadeOut("slow");
                }
            });
        }else{
        }
    }

    function addFav(path) {
        console.log(path);

        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>index.php/admin/files/addfav/',
            data:'path='+path,
            dataType:'json',
            beforeSend: function () {
                $('#loading_ajax').show();
            },
            success: function (response) {

                searchFilter(0);
                $('#loading_ajax').fadeOut("slow");
            }
        });
    }

    function removeFav(path) {
        console.log(path);

        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>index.php/admin/files/removefav/',
            data:'path='+path,
            dataType:'json',
            beforeSend: function () {
                $('#loading_ajax').show();
            },
            success: function (response) {

                searchFilter(0);
                $('#loading_ajax').fadeOut("slow");
            }
        });
    }
</script>