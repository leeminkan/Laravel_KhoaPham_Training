<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;

class SlideController extends Controller
{
    //
    public function getDanhSach()
    {
    	$slide = Slide::all();
    	return view('admin.slide.danhsach',['slide' => $slide]);
    }
    public function getThem()
    {
    	return view('admin.slide.them');
    }

    public function postThem(Request $req)
    {
    	$this->validate($req,
    		[
    			'Ten' => 'required',
    			'NoiDung' => 'required'
    		],
    		[
    			'Ten.required' => 'Bạn chưa nhập tên',
    			'NoiDung.required' => 'Bạn chưa nhập nội dung'
    		]);
    	$slide = new Slide;
    	$slide->Ten = $req->Ten;
    	$slide->NoiDung = $req->NoiDung;
    	if ($req->has('link')) $slide->link = $req->link;
    	else $slide->link = '';
    	if($req->hasFile('Hinh'))
    	{
    		$file = $req->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != "jpg" && $duoi != "png" && $duoi != "jpeg")
    		{
    			return redirect('admin/slide/them')->with('loi','Bạn chỉ được chọn file ảnh đuôi: jpg, png, jpeg.');
    		}
    		$name = $file->getClientOriginalName();
    		$Hinh = str_random(4)."_".$name;
    		while (file_exists("upload/slide/".$Hinh)){
    			$Hinh = str_random(4)."_".$name;
    		}
    		$file->move("upload/slide/",$Hinh);
    		$slide->Hinh = $Hinh;
    	}
    	else
    	{
    		$slide->Hinh = "";
    	}
    	$slide->save();
    	return redirect('admin/slide/them')->with('thongbao','Thêm slide thành công!');
    }
    public function getSua($id)
    {
    	$slide = Slide::find($id);
    	return view('admin.slide.sua',['slide'=>$slide]);
    }
    public function postSua(Request $req, $id)
    {
    	$this->validate($req,
    		[
    			'Ten' => 'required',
    			'NoiDung' => 'required'
    		],
    		[
    			'Ten.required' => 'Bạn chưa nhập tên',
    			'NoiDung.required' => 'Bạn chưa nhập nội dung'
    		]);
    	$slide = Slide::find($id);
    	$slide->Ten = $req->Ten;
    	$slide->NoiDung = $req->NoiDung;
    	if ($req->has('link')) $slide->link = $req->link;
    	else $slide->link = '';
    	if($req->hasFile('Hinh'))
    	{
    		$file = $req->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != "jpg" && $duoi != "png" && $duoi != "jpeg")
    		{
    			return redirect('admin/slide/sua')->with('loi','Bạn chỉ được chọn file ảnh đuôi: jpg, png, jpeg.');
    		}
    		$name = $file->getClientOriginalName();
    		$Hinh = str_random(4)."_".$name;
    		while (file_exists("upload/slide/".$Hinh)){
    			$Hinh = str_random(4)."_".$name;
    		}
    		unlink("upload/slide/".$slide->Hinh);
    		$file->move("upload/slide/",$Hinh);
    		$slide->Hinh = $Hinh;
    	}
    	$slide->save();
    	return redirect('admin/slide/sua/'.$id)->with('thongbao','Sửa slide thành công!');
    }
    public function getXoa($id)
    {
    	$slide = Slide::find($id);
    	$slide->delete();
    	return redirect('admin/slide/danhsach')->with('thongbao','Bạn đã xóa Slide thành công');
    }
}
