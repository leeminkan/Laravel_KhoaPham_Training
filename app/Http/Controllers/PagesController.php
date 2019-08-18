<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    //
    function __construct()
    {
    	$theloai = TheLoai::all();
    	$slide = Slide::all();
    	view()->share(['theloai'=>$theloai]);
    	view()->share(['slide'=>$slide]);
    }
    function trangchu()
    {
    	return view('pages.trangchu');
    }
    function lienhe()
    {
    	return view('pages.lienhe');
    }
    function gioithieu()
    {
        return view('pages.gioithieu');
    }
    function loaitin($id)
    {
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
        return view('pages.loaitin', ['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id)
    {
        $tintuc = TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat', 1)->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin', $tintuc->idLoaiTin)->take(4)->get();
        DB::table('tintuc')->where('id', $id)->update(['SoLuotXem' => $tintuc->SoLuotXem+1]);
        return view('pages.tintuc', ['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }

    function getDangnhap()
    {
        return view('pages.dangnhap');
    }
    function postDangnhap(Request $req)
    {
        $this->validate($req,
            [
                'email' => 'required',
                'password' => 'required|min:4|max:32'
            ],
            [
                'email.required' => 'Bạn chưa nhập email',
                'password.required' => 'Bạn chưa nhập password',
                'password.min' => 'Password phải từ 4 đến 32 ký tự',
                'password.max' => 'Password phải từ 4 đến 32 ký tự'
            ]);
        $credentials = $req->only('email', 'password');
        if(Auth::attempt($credentials))
        {
            return redirect('trangchu');
        }
        else
        {
            return redirect('dangnhap')->with('loi', 'Đăng nhập không thành công!');
        }
    }
    public function getDangxuat()
    {
        Auth::logout();
        return redirect('dangnhap');
    }

    public function getNguoidung()
    {
        $user = Auth::user();
        return view('pages.nguoidung', ['nguoidung' => $user]);
    }

    public function postNguoidung(Request $req)
    {
        $this->validate($req,
            [
                'name' => 'required'
            ],
            [
                'name.required' => 'Bạn chưa nhập tên'
            ]);
        $user = Auth::user();
        $user->name = $req->name;
        if($req->changePassword == "on")
        {
            $this->validate($req,
            [
                'password' => 'required|min:4|max:32',
                'repassword' => 'required|same:password'
            ],
            [
                'password.required' => 'Bạn chưa nhập password',
                'password.min' => 'Password phải từ 4 đến 32 ký tự',
                'password.max' => 'Password phải từ 4 đến 32 ký tự',
                'repassword.required' => 'Bạn chưa nhập lại password',
                'repassword.same' => 'Mật khẩu nhập lại chưa khớp'
            ]);
            $user->password = bcrypt($req->password);
        }
        $user->save();
        return redirect('nguoidung')->with('thongbao','Sửa user thành công!');
    }
    public function getDangky()
    {
        return view('pages.dangky');
    }
    public function postDangky(Request $req)
    {
        $this->validate($req,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4|max:32',
                'password' => 'required|same:password'
            ],
            [
                'name.required' => 'Bạn chưa nhập tên',
                'email.required' => 'Bạn chưa nhập email',
                'email.email' => 'Bạn chưa nhập đúng định dạng email',
                'email.unique' => 'Email đã tồn tại',
                'password.required' => 'Bạn chưa nhập password',
                'password.min' => 'Password phải từ 4 đến 32 ký tự',
                'password.max' => 'Password phải từ 4 đến 32 ký tự',
                'repassword.required' => 'Bạn chưa nhập lại password',
                'repassword.same' => 'Mật khẩu nhập lại chưa khớp'
            ]);
        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->quyen = 0;
        $user->save();
        return redirect('dangnhap')->with('thongbao','Đăng ký thành công!');
    }
    public function getTimkiem(Request $req)
    {
        $tukhoa = $req->tukhoa;
        $tintuc = TinTuc::where('TieuDe', 'like', "%$tukhoa%")->orWhere('TomTat', 'like', "%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->take(30)->paginate(5);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}
