<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFeedBackController extends Controller
{

    protected $parentDir = '1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/16ujng3zy4K17pV28uJDLzyKL8Y5t-tIo/';
    public function requestList()
    {
        return view('serviceRequest.index',
            [
                'data'=>UserFeedBack::with('user')->where('isActive','=',1)->paginate(10),
            ]
        );
    }
    public function requestDetails($id)
    {
        $data=UserFeedBack::with('user')->where('id','=',$id)->get()->first();
        return view('serviceRequest.request',
            [
                'check'=>false,
                'data'=>$data,
                'description'=>$data->requestDescription(),
            ]
        );
    }
    public function create(Request $request)
    {
        $user = $request->all()['user'];
        $data = Validator::make($request->all(),
            [
                'description' => 'required,max:500',
            ]
        );
        $msg = $request->all()['description'];
        \Storage::disk('google')->append($this->parentDir . $user->CNIC, $request->all()['description']);
        $directories = \Storage::disk('google')->files('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/16ujng3zy4K17pV28uJDLzyKL8Y5t-tIo');
        $path = '';
        foreach ($directories as $dir) {
            $meta = \Storage::disk('google')->getMetaData($dir);
            $cont = \Storage::disk('google')->get($dir);
            if ($meta['name'] === $user->CNIC && $cont == $msg) {
                $path = $dir;
            }
        }
        return
            [
                'success' => true,
                'request' => UserFeedBack::create(
                    [
                        'user_id' => $user->id,
                        'path' => $path
                    ]
                )
            ];
    }

    public function getFeedBack(Request $request)
    {
        $req = $request->all()['id'];
        $req = UserFeedBack::find($req);
        $cont = \Storage::disk('google')->get($req->path);
        return
            [
                'request' => $cont,
                'user' => $req->user,
            ];
    }

    public function userFeedBack(Request $request)
    {
        $user = User::find( $request->all()['id']);
        if ($user->feedBack ?? '') {
            return response()->json(
                [
                    'success' => true,
                    'requests'=>$user->serviceRequests
                ]
            );
        }
        return response()->json(
            [
                'success' => false,
                'message'=>'This user has no requests for new services'
            ]
            ,200
        );
    }
}
