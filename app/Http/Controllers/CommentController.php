<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Comment;
use App\TinTuc;


class CommentController extends Controller
{
    //
    public function getXoa($id, $idTinTuc)
    {
    	$comment = Comment::find($id);
    	$comment->delete();
    	return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao','Bạn đã xóa comment thành công');
    }
    public function postComment(Request $req, $id)
    {
    	$idTinTuc = $id;
    	$comment = new Comment;
    	$comment->idTinTuc = $id;
    	$comment->idUser = Auth::user()->id;
    	$comment->NoiDung = $req->NoiDung;
    	$comment->save();
    	$tintuc = TinTuc::find($id);

    	return redirect("tintuc/$id/".$tintuc->TieuDeKhongDau.".html")->with('thongbao', "Viết bình luận thành công!");
    }
}
