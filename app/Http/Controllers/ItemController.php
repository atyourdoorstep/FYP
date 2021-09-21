<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Seller;
use App\Models\SellerFolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
//    public function index()
//    {
//        return view('item.index', ['data' => Category::with('children')->where('organization_id', Auth::user()->organization_id)->whereNull('category_id')->get()]);
//    }
//
//    public function edit($id)
//    {
//        //dd(Item::find($id));
//        return (view('item.index', ['data' => Category::with('children')->where('organization_id', Auth::user()->organization_id)->whereNull('category_id')->get(),
//            'info' => Item::find($id)]));
//    }
//
//    public function editList()
//    {
//        /*  $result = Category::select('id')->where('organization_id', Auth::user()->organization_id)->get();
//          $temp = array();
//          foreach ($result as $res) {
//              array_push($temp, $res->id);
//          }*/
//        // dd(getFeild('id', 'categories', 'organization_id =' . Auth::user()->organization_id));
//        //dd(Item::orderBy('created_at')->whereIn('category_id', getFeild('id', 'categories', 'organization_id =' . Auth::user()->organization_id)));
//        return ValidateUserSession(view('item.editList', ['data' => (Item::orderBy('created_at')->whereIn('category_id', getFeild('id', 'categories', 'organization_id =' . Auth::user()->organization_id))
//            ->paginate(10))]), 'canEdit', redirect(back()));
//    }
//
//    public function delete($id)
//    {
//        //$temp=Item::all()->count();
//        $name=Item::find($id)->name;
//        if(Item::find($id)->delete()) {
//            DB::select(DB::raw("ALTER TABLE items AUTO_INCREMENT =" . 0));
//            return redirect()->route('item.list')->with('success','item '.$name.' deleted successfully');
//        }
//        return redirect()->route('item.list')->withErrors('error','ERROR');
//        // return redirect(route('editCategory'));
//    }
//
//    public function update($id)
//    {
//        //dd($id);
//        // dd(request()->all());
//        $data = \request()->validate(
//            [
//                'name' => 'required',
//                'description' => 'nullable',
//                'category_id' => 'required|Numeric',
//                'item_code' => 'required',
//                //'image'=>'required',
//                'price' => 'required|Numeric',
////                'isBargainAble'=>'required'
//            ]
//        );
//        // dd($data);
//        if ($data['image'] ?? '') {
//            $imagePath = $data['image']->store('uploads', 'public');
//            $data['image'] = $imagePath;
//        }
//        // dd($data);
//        Item::where('id', $id)->update($data);
//        return redirect(route('item.list'));
//
//    }
//new
    public function create(Request $request)//regItem
    {
        $user=$request->all()['user'];
        $user=User::find($user->id);
        if(!Seller::where('user_id',$user->id)->count())
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'User is not registered as a Service Provider Register as a service provider to start selling',

                ]
                ,400
            );
        }
        $data =  Validator::make($request->all(),
            [
                'name' => ['required'],
                'description' => 'nullable',
                'category_id' => 'required',
                'image' => 'required',
                'price' => ['required','numeric','min:1'],
                'isBargainAble'=>'required'
            ]
        );
        //The name has already been taken.
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $data=$request->all();
        $seller=$user->seller;
//        if(Item::where('name',$data['name'])->where('seller_id',$seller->id)->get()->count())
        if($seller->items->where('name',$data['name'])->where('category_id',$data['category_id'])->count())
        {
            return response()->json(['success'=>false,'message'=>'You have already added item with same name('.$data['name'].') in '.Category::find($data['category_id'])->name.' category'],400);
        }
        $data['seller_id']=$seller->id;
        $imagePath = $data['image']->store($seller->sellerFolder['item'], 'google');
        $url=\Storage::disk('google')->url($imagePath);
        $data['image']=$url;
        return response()->json(
            [
                'success'=>true,
                'item'=>Item::create($data),
            ]
            ,200
        );
    }
    public function update(Request $request)//regItem
    {
        $user=$request->all()['user'];
        if(!Seller::where('user_id',$user->id)->count())
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'User is not registered as a Service Provider Register as a service provider to start selling',
                ]
                ,400
            );
        }
        $data =  Validator::make($request->all(),
            [
                'name' => [''],
                'description' => 'nullable',
                'category_id' => '',
                'image' => '',
                'id' => 'required',
                'price' => '',
                'isBargainAble'=>''
            ]
        );
        //The name has already been taken.
        if($data->fails())
            return response()->json(['success'=>false,'message'=>$data->messages()->all()],400);
        $item=Item::findOrFail($request->all()['id']);
        $data=$request->all();
        $seller=User::find($user->id)->seller;
        if($data['name']??'') {
            if (Item::where('name',$data['name'])->where('seller_id', '!=',$seller->id)->where('id',$item->id)->get()->count()) {
                return response()->json([ 'success' => false, 'message' => 'The name has already been taken.'], 400);
            }
        }
        if($data['image']??'') {
            $imagePath = $data['image']->store($seller->sellerFolder['item'], 'google');
            $url = \Storage::disk('google')->url($imagePath);
            $data['image'] = $url;
            parse_str(parse_url($item->image)['query'], $params);
            \Storage::disk('google')->delete($params['id']);
        }
        $item->update($data);
        return response()->json(
            [
                'success'=>true,
                'item'=>$item,
            ]
            ,200
        );
    }
}
