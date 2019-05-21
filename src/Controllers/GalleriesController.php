<?php

namespace Hosein\Galleries\Controllers;

use Hosein\Galleries\CategoryGallery;
use Hosein\Galleries\Gallery;
use Hosein\Products\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GalleriesController extends Controller
{
    public function index(){
        $data["categories"]=CategoryGallery::all();
        $data["galleries"]=Gallery::all();
        return view("GalleriesView::galleries",$data);
    }
    public function creatCategory(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'picture'=>'required'
        ]);
        if($validator->fails()){
            return redirect("galleries")
                ->withErrors($validator,"category")
                ->withInput();
        }
        if(!$this->checkExtention($request->file("picture")->getClientOriginalExtension(),"image")){
            return redirect("galleries")
                ->with("message","فایل به درستی انتخاب نشده است");
        }
        $path=public_path()."/upload/";

        if($filename=$this->uploadfile($path,$request->file("picture"))){
            $category=new CategoryGallery();
            $category->name=$request->all()["name"];
            $category->picture=$filename;
            $category->type=$request->all()["type"];
            $category->save();
            return redirect("galleries")->with("message","با موفقیت آپلود شد");
        }
        else{
            return redirect("galleries")->with("message","آپلود فایل با مشکل مواجه شده است");
        }
    }
    public function editCategory($id){
        $category=CategoryGallery::where("id",$id)->first();
        return redirect("galleries")->with("category",$category);
    }
    public function updateCategory(Request $request,$id)
    {
        $category=CategoryGallery::where("id",$id)->first();
        $destination=public_path()."/upload/";
        $file=$category->picture;
        if(!empty($request->file("picture"))){
            $oldfile=$file;
            $file=$this->uploadfile($destination,$request->file("picture"));
            if($file!=false){
                $this->deletefile($destination,$oldfile);
            }
        }
        $category->name=$request->all()["name"];
        $category->picture=$file;
        $category->type=$request->all()["type"];
        $category->save();
        return redirect("galleries");
    }
    public function deleteCategory($id){
        $category=CategoryGallery::where("id",$id)->first();
        $destination=public_path()."/upload/";
        if(file_exists(public_path()."/upload/".$category->picture))
            $this->deletefile($destination,$category->picture);
        $category->delete();
        return redirect("galleries");
    }

    public function getCategoryWithType($type){
        $category=CategoryGallery::where("type",$type)->get();
        echo json_encode($category);
    }
    public function creatGallery(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'picture'=>'required'
        ]);
        if($validator->fails()){
            return redirect("galleries")
                ->withErrors($validator,"gallery")
                ->withInput();
        }
        if(!$this->checkExtention($request->file("picture")->getClientOriginalExtension(),"image")){
            return redirect("galleries")
                ->with("message","فایل به درستی انتخاب نشده است");
        }
        $path=public_path()."/upload/";

        if($filename=$this->uploadfile($path,$request->file("picture"))){
            $gallery=new Gallery();
            $gallery->title=$request->all()["title"];
            $gallery->picture=$filename;
            $gallery->type=$request->all()["type"];
            $gallery->category_id=$request->all()["category_id"];
            $gallery->details=$request->all()["details"];
            $gallery->paths="";
            $gallery->like=0;
            $gallery->dislike=0;
            $gallery->visited=0;
            $gallery->save();
            return redirect("galleries")->with("message","با موفقیت آپلود شد");
        }
        else{
            return redirect("galleries")->with("message","آپلود فایل با مشکل مواجه شده است");
        }
    }
    public function editGallery($id){
        $gallery=Gallery::where("id",$id)->first();
        return redirect("galleries")->with("gallery",$gallery);
    }
    public function updateGallery(Request $request,$id)
    {
        $gallery=Gallery::where("id",$id)->first();
        $destination=public_path()."/upload/";
        $file=$gallery->picture;
        if(!empty($request->file("picture"))){
            $oldfile=$file;
            $file=$this->uploadfile($destination,$request->file("picture"));
            if($file!=false){
                $this->deletefile($destination,$oldfile);
            }
        }
        $gallery->title=$request->all()["title"];
        $gallery->picture=$file;
        $gallery->type=$request->all()["type"];
        $gallery->category_id=$request->all()["category_id"];
        $gallery->details=$request->all()["details"];
        $gallery->save();
        return redirect("galleries");
    }
    public function deleteGallery($id){
        $gallery=Gallery::where("id",$id)->first();
        $destination=public_path()."/upload/";
        if(strlen($gallery->paths)>0){
            $path=explode(",",$gallery->paths);
            foreach ($path as $value){
                if(file_exists(public_path()."/upload/".$value))
                    $this->deletefile($destination,$value);
            }
        }
        if(file_exists(public_path()."/upload/".$gallery->picture))
            $this->deletefile($destination,$gallery->picture);
        $gallery->delete();
        return redirect("galleries");
    }
    public function addToGallery(Request $request,$id){
        $gallery=Gallery::where("id",$id)->first();
        $destination=public_path()."/upload/";
        $paths=$gallery->paths;
        if(!empty($request->file("paths"))) {
            $paths .= ",".$this->uploadfile($destination, $request->file("paths"));
            $paths=trim($paths,",");
            $gallery->paths = $paths;
            $gallery->save();
        }
        return redirect("galleries");
    }
    public function getGallery($id){
        $gallery=Gallery::where("id",$id)->first();
        echo json_encode($gallery);
    }
    public function deleteImgGallery($id,$img){
        $gallery=Gallery::where("id",$id)->first();
        $paths="";
        $path=explode(",",$gallery->paths);
        for($i=0;$i<count($path);$i++){
            if($path[$i]!=$img){
                $paths.=",".$path[$i];
            }
        }
        $destination=public_path()."/upload/";
        $this->deletefile($destination,$img);
        $gallery->paths=trim($paths,",");
        $gallery->save();
        echo json_encode($gallery);
    }
    public function checkExtention($extention,$type){
        $list=[];
        if($type=="video") {
            $list = ['mp4'];
        }
        else if($type=="sound") {
            $list = ['mp3'];
        }
        else if($type=="image") {
            $list = ['jpg', 'png', 'jpeg'];
        }
        foreach ($list as $value){
            if($value==$extention){
                return 1;
            }
        }
        return 0;
    }
    public function uploadfile($destination,$file){
        $filename=$file->getClientOriginalName();
        $name=explode('.',$file->getClientOriginalName())[0];
        $extenstion=$file->getClientOriginalExtension();
        while(file_exists($destination.$filename)){
            $filename=$name."_".rand(1,10000000).".".$extenstion;
        }
        if($file->move($destination,$filename)){
            return $filename;
        }
        return false;
    }
    public function deletefile($destination,$filename){
        if(file_exists($destination."/".$filename)){
            unlink($destination."/".$filename);
            return 1;
        }
        return 0;
    }
}
