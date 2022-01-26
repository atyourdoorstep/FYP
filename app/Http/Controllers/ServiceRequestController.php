<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceRequestController extends Controller
{
    protected $parentDir = '1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6/';
    public function requestList()
    {
        return view('serviceRequest.index',
            [
                'check'=>true,
                'data'=>ServiceRequest::with('user')->where('isActive','=',1)->paginate(10),
            ]
    );
    }
    public function requestDetails($id)
    {
        $data=ServiceRequest::with('user')->where('id','=',$id)->get()->first();
        return view('serviceRequest.request',
            [
                'check'=>true,
                'data'=>$data,
                'description'=>$data->requestDescription(),
            ]
        );
    }
    public function replyUser($mail,$message)
    {
        Mail::to($mail)->send(new \App\Mail\AdminResponseMail());

        if(Mail::failures() != 0) {
            return "<p> Success! Your E-mail has been sent.</p>";
        }

        else {
            return "<p> Failed! Your E-mail has not sent.</p>";
        }
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
        $directories = \Storage::disk('google')->files('1uBRvJVYTEzvezHRucXfJm5Ux9llvGQA2/1n90Ddvi_ao3O1DS1Qc5tPiLqfPuiw4Y6');
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
                'request' => ServiceRequest::create(
                    [
                        'user_id' => $user->id,
//                'description'=>$msg,
                        'path' => $path
                    ]
                )
            ];
    }

    public function getRequest(Request $request)
    {
        $req = $request->all()['id'];
        $req = ServiceRequest::find($req);
//        $user=$req['user_id'];
        $cont = \Storage::disk('google')->get($req->path);
        return
            [
                'request' => $cont,
                'user' => $req->user,
            ];
    }

    public function userRequests(Request $request)
    {
//        $user = $request->all()['id'];
        $user = User::find( $request->all()['id']);
        if ($user->serviceRequests ?? '') {
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
