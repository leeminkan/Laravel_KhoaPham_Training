<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\LoaiTin;
use App\TinTuc;
use App\Comment;

class TinTucController extends Controller
{
    //
    public function getDanhSach()
    {
    	$tintuc = TinTuc::orderBy('id', 'ASC')->get();
    	return view('admin.tintuc.danhsach',['tintuc' => $tintuc]);
    }
    public function getThem()
    {
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	return view('admin.tintuc.them', ['loaitin' => $loaitin,'theloai' => $theloai]);
    }

    public function postThem(Request $req)
    {
    	$this->validate($req,
    		[
    			'TieuDe' => 'required|unique:TinTuc,TieuDe|min:3|max:100',
    			'LoaiTin' => 'required',
    			'TomTat' => 'required',
    			'NoiDung' => 'required'
    		],
    		[
    			'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
    			'TieuDe.unique' => 'Tiêu đề đã tồn tại',
    			'TieuDe.min' => 'Tiêu đề phải có độ dài từ 3 đến 100 ký tự',
    			'TieuDe.max' => 'Tiêu đề phải có độ dài từ 3 đến 100 ký tự',
    			'LoaiTin.required' => 'Bạn chưa chọn loại tin',
    			'TomTat.required' => 'Bạn chưa nhập tóm tắt',
    			'NoiDung.required' => 'Bạn chưa nhập nội dung'
    		]);
    	$tintuc = new TinTuc;
    	$tintuc->TieuDe = $req->TieuDe;
    	$tintuc->TieuDeKhongDau = changeTitle($req->TieuDe);
    	$tintuc->idLoaiTin = $req->LoaiTin;
    	$tintuc->TomTat = $req->TomTat;
    	$tintuc->NoiDung = $req->NoiDung;
    	$tintuc->NoiBat = $req->NoiBat;
    	$tintuc->SoLuotXem = 0;
    	if($req->hasFile('Hinh'))
    	{
    		$file = $req->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != "jpg" && $duoi != "png" && $duoi != "jpeg")
    		{
    			return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file ảnh đuôi: jpg, png, jpeg.');
    		}
    		$name = $file->getClientOriginalName();
    		$Hinh = str_random(4)."_".$name;
    		while (file_exists("upload/tintuc/".$Hinh)){
    			$Hinh = str_random(4)."_".$name;
    		}
    		$file->move("upload/tintuc/",$Hinh);
    		$tintuc->Hinh = $Hinh;
    	}
    	else
    	{
    		$tintuc->Hinh = "";
    	}
    	$tintuc->save();
    	return redirect('admin/tintuc/them')->with('thongbao','Thêm thành công!');
    }
    public function getSua($id)
    {
    	$tintuc = TinTuc::find($id);
    	$loaitin = LoaiTin::all();
    	$theloai = TheLoai::all();
    	return view('admin.tintuc.sua',['tintuc'=>$tintuc, 'loaitin' => $loaitin, 'theloai' => $theloai]);
    }
    public function postSua(Request $req, $id)
    {
    	$this->validate($req,
    		[
    			'TieuDe' => 'required|min:3|max:100',
    			'LoaiTin' => 'required',
    			'TomTat' => 'required',
    			'NoiDung' => 'required'
    		],
    		[
    			'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
    			'TieuDe.min' => 'Tiêu đề phải có độ dài từ 3 đến 100 ký tự',
    			'TieuDe.max' => 'Tiêu đề phải có độ dài từ 3 đến 100 ký tự',
    			'LoaiTin.required' => 'Bạn chưa chọn loại tin',
    			'TomTat.required' => 'Bạn chưa nhập tóm tắt',
    			'NoiDung.required' => 'Bạn chưa nhập nội dung'
    		]);
    	$tintuc = TinTuc::find($id);
    	$tintuc->TieuDe = $req->TieuDe;
    	$tintuc->TieuDeKhongDau = changeTitle($req->TieuDe);
    	$tintuc->idLoaiTin = $req->LoaiTin;
    	$tintuc->TomTat = $req->TomTat;
    	$tintuc->NoiDung = $req->NoiDung;
    	$tintuc->NoiBat = $req->NoiBat;
    	$tintuc->SoLuotXem = 0;
    	if($req->hasFile('Hinh'))
    	{
    		$file = $req->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != "jpg" && $duoi != "png" && $duoi != "jpeg")
    		{
    			return redirect('admin/tintuc/sua')->with('loi','Bạn chỉ được chọn file ảnh đuôi: jpg, png, jpeg.');
    		}
    		$name = $file->getClientOriginalName();
    		$Hinh = str_random(4)."_".$name;
    		while (file_exists("upload/tintuc/".$Hinh)){
    			$Hinh = str_random(4)."_".$name;
    		}
    		$file->move("upload/tintuc/",$Hinh);
    		unlink("upload/tintuc/".$tintuc->Hinh);
    		$tintuc->Hinh = $Hinh;
    	}
    	$tintuc->save();
    	return redirect('admin/tintuc/sua/'.$id)->with('thongbao','Sửa thành công!');
    }
    public function getXoa($id)
    {
    	$tintuc = TinTuc::find($id);
    	$tintuc->delete();
    	return redirect('admin/tintuc/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
