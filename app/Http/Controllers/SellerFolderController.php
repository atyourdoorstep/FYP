<?php

namespace App\Http\Controllers;

use App\Models\SellerFolder;
use Illuminate\Http\Request;

class SellerFolderController extends Controller
{


    public function createDir($name,$parent='')
    {
        if($parent!=''&&$parent!=null)
        {
            $parent=$parent.'/';
        }
        \Storage::disk('google')->makeDirectory($parent.$name, 0777, true);
        $directories=\Storage::disk('google')->directories($parent??'');
        $path='';
        foreach ($directories as $dir)
        {
            $meta=\Storage::disk('google')->getMetaData($dir);
            if($meta['name']===$name)
            {
                $path=$dir;
            }
        }
        return $path;
    }






    public function create($userName,$id)
    {
        $folder=$userName.$id;
        if(SellerFolder::where('seller_id',$id)->count())
        {
            return response()->json(['success'=>false,'message'=>'This user is already registered as a service provider'],400);

        }
        $data=array();
        $data['main']=$this->createDir($folder,'');
        $data['invoice']=$this->createDir('invoice',$data['main']);
        $data['item']=$this->createDir('item',$data['main']);
        $data['return_invoice']=$this->createDir('return_invoice',$data['main']);
        $data['seller_id']=$id;
        return response()->json(
            [
                'success'=>true,
                'folder'=>SellerFolder::create($data)
            ]
            ,200
        );

    }
}
