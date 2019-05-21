<h1>Galleries</h1>
<hr>
<h3>category Galleries</h3>
<hr>
<form action="@if(session("category")){{url("galleries/updateCategory")}}/{{session("category")->id}}@else{{url("galleries/creatCategory")}}@endif" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="text" name="name" placeholder="Enter Name for Galleries"
           value="@if(session("category")){{session("category")->name}}@endif">
    <br><br>
    @if(session("category"))<img style="width:100px;height:100px;" src="{{asset("/upload/")}}/{{session("category")->picture}}" alt="">@endif
    <input type="file" name="picture">
    <br><br>
    <select name="type" id="">
        <option value="video" @if(session("category")){{(session("category")->type=="video")?"selected":""}}@endif>video</option>
        <option value="sound" @if(session("category")){{(session("category")->type=="sound")?"selected":""}}@endif>sound</option>
        <option value="image" @if(session("category")){{(session("category")->type=="image")?"selected":""}}@endif>image</option>
    </select>
    <br><br>
    <input type="submit" name="send" value="send">
</form>
<hr>
<table border="1">
    <tr>
        <th>id</th>
        <th>picture</th>
        <th>name</th>
        <th>type</th>
        <th>edit</th>
        <th>delete</th>
    </tr>
    @if(isset($categories))
        @foreach($categories as $key=>$value)
            <tr>
                <td>{{$value->id}}</td>
                <td><img style="width:100px;height:100px;" src="{{asset("/upload")}}/{{$value->picture}}" alt=""></td>
                <td>{{$value->name}}</td>
                <td>{{$value->type}}</td>
                <td><a href="{{url("galleries/editCategory")}}/{{$value->id}}">edit</a></td>
                <td><a href="{{url("galleries/deleteCategory")}}/{{$value->id}}">delete</a></td>
            </tr>
        @endforeach
    @endif
</table>
<hr>
<hr>
<h3>Gallery</h3>
<hr>
<form action="@if(session("gallery")){{url("galleries/updateGallery")}}/{{session("gallery")->id}}@else{{url("galleries/creatGallery")}}@endif" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="text" name="title" placeholder="Enter Gallery Title"
           value="@if(session("gallery")){{session("gallery")->title}}@endif">
    <br><br>
    @if(session("gallery"))<img style="width:100px;height:100px;" src="{{asset("/upload/")}}/{{session("gallery")->picture}}" alt="">@endif

    <input type="file" name="picture">
    <br><br>
    <select name="type" id="type">
        <option value="image" @if(session("gallery")){{(session("gallery")->type=="image")?"selected":""}}@else selected @endif>image</option>
        <option value="video" @if(session("gallery")){{(session("gallery")->type=="video")?"selected":""}}@endif>video</option>
        <option value="sound" @if(session("gallery")){{(session("gallery")->type=="sound")?"selected":""}}@endif>sound</option>
    </select>
    <br><br>
    <select name="category_id" id="category_id">
        <option value="">category name</option>
    </select>
    <br><br>
    <textarea name="details" id="" cols="30" rows="10"
    placeholder="Enter Details"
    >@if(session("gallery")){{session("gallery")->details}}@endif</textarea>
    <br><br>
    <input type="submit" name="send" value="send">
</form>
<hr>
<table border="1">
    <tr>
        <th>id</th>
        <th>picture</th>
        <th>title</th>
        <th>type</th>
        <th>category</th>
        <th>added</th>
        <th>edit</th>
        <th>delete</th>
    </tr>
    @if(isset($galleries))
        @foreach($galleries as $key=>$value)
            <tr>
                <td>{{$value->id}}</td>
                <td><img style="width:100px;height:100px;" src="{{asset("/upload")}}/{{$value->picture}}" alt=""></td>
                <td>{{$value->title}}</td>
                <td>{{$value->type}}</td>
                <td>{{getCategoryWithId($value->category_id)->name}}</td>
                <td><button data-id="{{$value->id}}" data-action="addToGallery">added</button></td>
                <td><a href="{{url("galleries/editGallery")}}/{{$value->id}}">edit</a></td>
                <td><a href="{{url("galleries/deleteGallery")}}/{{$value->id}}">delete</a></td>
            </tr>
        @endforeach
    @endif
</table>
<?php
    function getCategoryWithId($id){
        $category=\Hosein\Galleries\CategoryGallery::where("id",$id)->first();
        return $category;
    }
?>
<hr>
<hr>
<form id="addedform" action="" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="paths">
    <br><br>
    <input type="submit" name="send" value="send">

</form>
<div id="listimg"></div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $("#addedform").hide();
        $(document).on("click","button",function () {
            let action = $(this).attr("data-action");
            let id = $(this).attr("data-id");
            if(action=="addToGallery"){
                $("#addedform").show();
                getDataGallery(id);
                $("#addedform").attr("action","{{url("galleries/addToGallery")}}/"+id);
            }
            if(action=="deleteimg"){
                deleteImgGallery(id,$(this).attr("data-path"))
            }

        });
        type=$("#type option:selected").val();
        sendData(type);
        $("#type").change(function () {
            sendData($(this).val());
        });
    });
    function sendData(type) {
        $.ajax({
            "url":"{{url("galleries/getCategory")}}/"+type,
            "method":"get",
            beforeSend:function () {

            },
            success:function (data) {
                data=JSON.parse(data);
                str="";
                for(i=0;i<data.length;i++){
                    str+="<option value='"+data[i].id+"'";

                    if(data[i].id==parseInt("{{(session("gallery")?session("gallery")->category_id:"")}}")){
                        str+="selected='selected'";
                    }
                    str+=">"+data[i].name+"</option>";
                }
                $("#category_id").html(str);
            }
        })
    }
    function getDataGallery(id) {
        $.ajax({
            "url":"{{url("galleries/getGallery")}}/"+id,
            "method":"get",
            beforeSend:function () {

            },
            success:function (data) {
                data=JSON.parse(data);

                str="";
                if(data.paths.length>0) {
                    paths = data.paths.split(",");
                    for (i = 0; i < paths.length; i++) {
                        str += "<img style='width:75px;height:75px;' src='{{asset("/upload")}}/" + paths[i] + "' />" +
                            "<button data-id='" + data.id + "' data-path='" + paths[i] + "' data-action='deleteimg'>delete</button>";
                    }
                }
                $("#listimg").html(str);
            }
        })
    }
    function deleteImgGallery(id,img) {
        $.ajax({
            "url":"{{url("galleries/deleteImgGallery")}}/"+id+"/"+img,
            "method":"get",
            beforeSend:function () {

            },
            success:function (data) {

                data=JSON.parse(data);
                str="";
                paths=data.paths.split(",");
                for(i=0;i<paths.length;i++){
                    str+="<img style='width:75px;height:75px;' src='{{asset("/upload")}}/"+paths[i]+"' />" +
                        "<button data-id='"+data.id+"' data-path='"+paths[i]+"' data-action='deleteimg'>delete</button>";
                }
                $("#listimg").html(str);
            }
        })
    }
</script>