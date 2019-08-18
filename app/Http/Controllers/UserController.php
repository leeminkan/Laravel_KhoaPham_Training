<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;

class UserController extends Controller
{
    //
    public function getDanhSach()
    {
    	$user = User::all();
    	return view('admin.user.danhsach',['user' => $user]);
    }
    public function getThem()
    {
    	return view('admin.user.them');
    }

    public function postThem(Request $req)
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
    	$user->quyen = $req->quyen;
    	$user->save();
    	return redirect('admin/user/them')->with('thongbao','Thêm user thành công!');
    }
    public function getSua($id)
    {
    	$user = User::find($id);
    	return view('admin.user.sua',['user'=>$user]);
    }
    public function postSua(Request $req, $id)
    {
    	$this->validate($req,
    		[
    			'name' => 'required'
    		],
    		[
    			'name.required' => 'Bạn chưa nhập tên'
    		]);
    	$user = User::find($id);
    	$user->name = $req->name;
    	$user->quyen = $req->quyen;
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
    	return redirect('admin/user/sua/'.$id)->with('thongbao','Sửa user thành công!');
    }
    public function getXoa($id)
    {
    	$user = User::find($id);
    	$user->delete();
    	return redirect('admin/user/danhsach')->with('thongbao','Bạn đã xóa User thành công');
    }

    public function getDangnhapAdmin()
    {
        return view('admin.login');
    }
    public function postDangnhapAdmin(Request $req)
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
            return redirect('admin/theloai/danhsach')->with('thongbao', 'Đăng nhập thành công!');
        }
        else
        {
            return redirect('admin/dangnhap')->with('thongbao', 'Đăng nhập không thành công!');
        }
    }

    public function getDangxuatAdmin()
    {
        Auth::logout();
        return redirect('admin/dangnhap');
    }
}
