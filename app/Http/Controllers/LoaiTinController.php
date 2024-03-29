<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\LoaiTin;

class LoaiTinController extends Controller
{
    //
    public function getDanhSach()
    {
    	$loaitin = LoaiTin::all();
    	return view('admin.loaitin.danhsach',['loaitin' => $loaitin]);
    }
    public function getThem()
    {
    	$theloai = TheLoai::all();
    	return view('admin.loaitin.them', ['theloai' => $theloai]);
    }

    public function postThem(Request $req)
    {
    	$this->validate($req,
    		[
    			'Ten' => 'required|unique:LoaiTin,Ten|min:3|max:100',
    			'TheLoai' => 'required'
    		],
    		[
    			'Ten.required' => 'Bạn chưa nhập tên loại tin',
    			'Ten.unique' => 'Tên loại tin đã tồn tại',
    			'Ten.min' => 'Tên loại tin phải có độ dài từ 3 đến 100 ký tự',
    			'Ten.max' => 'Tên loại tin phải có độ dài từ 3 đến 100 ký tự',
    			'TheLoai.required' => 'Bạn chưa chọn tên thể loại'
    		]);
    	$loaitin = new LoaiTin;
    	$loaitin->Ten = $req->Ten;
    	$loaitin->TenKhongDau = changeTitle($req->Ten);
    	$loaitin->idTheLoai = $req->TheLoai;
    	$loaitin->save();
    	return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công!');
    }
    public function getSua($id)
    {
    	$loaitin = LoaiTin::find($id);
    	$theloai = TheLoai::all();
    	return view('admin.loaitin.sua',['loaitin'=>$loaitin, 'theloai' => $theloai]);
    }
    public function postSua(Request $req, $id)
    {
    	$this->validate($req,
    		[
    			'Ten' => 'required|unique:LoaiTin,Ten|min:3|max:100',
    			'TheLoai' => 'required'
    		],
    		[
    			'Ten.required' => 'Bạn chưa nhập tên loại tin',
    			'Ten.unique' => 'Tên loại tin đã tồn tại',
    			'Ten.min' => 'Tên loại tin phải có độ dài từ 3 đến 100 ký tự',
    			'Ten.max' => 'Tên loại tin phải có độ dài từ 3 đến 100 ký tự',
    			'TheLoai.required' => 'Bạn chưa chọn tên thể loại'
    		]);
    	$loaitin = LoaiTin::find($id);
    	$loaitin->Ten = $req->Ten;
    	$loaitin->TenKhongDau = changeTitle($req->Ten);
    	$loaitin->idTheLoai = $req->TheLoai;
    	$loaitin->save();
    	return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Sửa thành công!');
    }
    public function getXoa($id)
    {
    	$loaitin = LoaiTin::find($id);
    	$loaitin->delete();
    	return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
