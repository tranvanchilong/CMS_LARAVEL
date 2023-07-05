<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Term;
use App\Stock;
use App\Attribute;
use App\Attributeprice;
use App\Meta;
use App\Postcategory;
use Auth;
use Str;
use Session;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Price;
use App\Models\Termoption;
use App\Models\Termoptionvalue;
use Carbon\Carbon;
use DB;
use App\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$type=1)
    {
        $lang_id =  $request->language;
        $auth_id=Auth::id();
        if ($request->src) {
            $posts=Term::where('type','product')->with('preview')->where('status',$type)->where('user_id',$auth_id)->language($lang_id)->where($request->type,'LIKE','%'.$request->src.'%')->latest()->paginate(30);
        }
        else{
            $posts=Term::where('type','product')->with('preview')->withCount('order')->where('status',$type)->where('user_id',$auth_id)->language($lang_id)->latest()->paginate(30);
        }

        $src=$request->src ?? '';

        $actives=Term::where('type','product')->where('status',1)->where('user_id',$auth_id)->count();
        $drafts=Term::where('type','product')->where('status',2)->where('user_id',$auth_id)->count();
        $incomplete=Term::where('type','product')->where('status',3)->where('user_id',$auth_id)->count();
        $trash=Term::where('type','product')->where('status',0)->where('user_id',$auth_id)->count();
        return view('seller.products.index',compact('posts','src','type','actives','drafts','incomplete','trash','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $limit=user_limit();
        $posts_count=Term::where('user_id',Auth::id())->count();
        // if ($limit['product_limit'] <= $posts_count) {
        //  Session::flash('error', 'Maximum posts limit exceeded');
        //  return back();
        // }


        return view('seller.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
          'title' => 'required|max:100',
          'price' => 'required|max:50',
          'sku' =>'required|unique:stocks',
        ]);
        if ($request->affiliate) {
            $request->validate([
              'purchase_link' => 'required|max:100'
            ]);
        }
        $slug=Str::slug($request->title);



        if($request->special_price_start <=  Carbon::now()->format('Y-m-d') && $request->special_price != null){
         if($request->special_price != null){
            if($request->price_type == 1){
                $price=$request->price-$request->special_price;
            }
            else{
                $percent= $request->price * $request->special_price / 100;
                $price= $request->price-$percent;
                $price=str_replace(',','',number_format($price,2));
            }

        }
        else{
            $price=$request->price;
        }
       }
       else{
          $price=$request->price;
        }

        DB::beginTransaction();
        try {
        $term= new Term;
        $term->title=$request->title;
        if($request->lang_id){
            $term->lang_id = json_encode($request->lang_id);
        }
        $term->slug=$slug;
        $term->status=3;
        $term->type='product';
        $term->user_id=Auth::id();
        $term->save();



        $term_price=new Price;
        $term_price->term_id=$term->id;
        $term_price->price=$price;
        $term_price->regular_price=$request->price;
        $term_price->special_price=$request->special_price ?? null;
        $term_price->price_type=$request->price_type;
        $term_price->starting_date=$request->special_price_start ?? null;
        $term_price->ending_date=$request->special_price_end ?? null;
        $term_price->sku = $request->sku ?? null;
        $term_price->save();

        $stock=new Stock;
        $stock->term_id = $term->id;
        $stock->stock_manage = $request->stock_manage ?? 0;
        $stock->stock_status = 1;
        $stock->stock_qty = $request->stock_qty ?? 999;
        $stock->sku = $request->sku ?? null;
        $stock->save();


        $dta['content']=null;
        $dta['excerpt']=null;


        if ($request->affiliate) {
            $meta=new Meta;
            $meta->term_id = $term->id;
            $meta->key = 'affiliate';
            $meta->value = $request->purchase_link;
            $meta->save();
        }
        $meta=new Meta;
        $meta->term_id = $term->id;
        $meta->key = 'content';
        $meta->value = json_encode($dta);
        $meta->save();

        $meta= new Meta;
        $meta->term_id = $term->id;
        $seo['meta_title']=$request->title;
        $seo['meta_description']='';
        $seo['meta_keyword']='';
        $meta->key = 'seo';
        $meta->value = json_encode($seo);
        $meta->save();

        Session::flash("flash_notification", [
            "level"     => "success",
            "message"   => "Product Created Successfully"
        ]);




            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back();
        }
        return redirect()->route('seller.product.edit',$term->id);
    }

    public function store_group(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $term=Term::where('user_id',Auth::id())->findorFail($id);

        $group= new Termoption;
        $group->user_id=Auth::id();
        $group->term_id=$id;
        $group->name=$request->name;
        $group->type=1;
        $group->is_required=$request->is_required ?? 0;
        $group->select_type=$request->select_type ?? 0;
        $group->save();

        return response()->json('Option Created Successfully....!!!');
    }

    public function stock(Request $request,$id)
    {

        foreach($request->stocks as $key => $item){
            $data['stock_manage']=$item['stock_manage'];
            $data['stock_status']=$item['stock_status'];
            $data['stock_qty']=$item['stock_qty'];
            $data['sku']=$item['sku'];
            $validatedData = Validator::make(
                ['sku' => $data['sku']],
                ['sku' => [
                    'sku' => 'required',
                    Rule::unique('stocks')->ignore($key),
                ]]
            )->validate();

            Stock::find($key)->update($data);
            $stock = Stock::find($key);
            Price::where('term_id',$stock->term_id)->where('variation_id_code',json_encode($stock->variation_id_code))->update(['sku' => $data['sku']]);
        }
        return response()->json('Stock Updated Successfully....!!!');
    }

    public function stock_single(Request $request,$id)
    {
        $stock1 = Stock::where('term_id',$id)->where('variation_id_code',null)->first();
        // dd($stock1->term_id);
        $v = Validator::make($request->all(), [
            'sku' => 'required',
            ['sku' => [
                Rule::unique('stocks'),
            ]]
        ]);
        if ($v->fails())
        {
            return response()->json('The sku field is required');

        }
        $stock= Stock::updateOrCreate(
            [
                'term_id' => $id,
                'variation_id_code' => null,
            ],
            [
                'stock_manage' =>$request->stock_manage ?? 0,
                'stock_status' =>$request->stock_status ?? 0,
                'stock_qty' =>$request->stock_qty ?? 999,
                'sku' =>$request->sku,
            ]
        );
        $price = Price::updateOrCreate(
            [
                'term_id' => $id,
                'variation_id_code' => null,
            ],
            [
                'sku' =>$request->sku ?? null,
            ]
        );

        return response()->json('Stock Updated Successfully....!!!');
    }


    public function add_row(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $term=Termoption::where('user_id',Auth::id())->where('type',1)->findorFail($request->row_id);

        $group= new Termoption;
        $group->user_id=Auth::id();
        $group->term_id=$term->term_id;
        $group->name=$request->name;
        $group->amount=$request->price ?? 0.00;
        $group->amount_type=$request->amount_type;
        $group->type=0;
        $group->p_id=$request->row_id;
        $group->save();

        return response()->json('Row Created Successfully....!!!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$type="edit")
    {
        if ($type=='edit') {
             $info=Term::with('content','post_categories','affiliate')->where('user_id',Auth::id())->findorFail($id);

            $cats=[];

            foreach ($info->post_categories as $key => $value) {
               array_push($cats, $value->category_id);
            }
            $content=json_decode($info->content->value);
            return view('seller.products.edit.item',compact('info','cats','content'));
        }
        if ($type=='varient') {
            $user_id=Auth::id();
            $info=Term::with('attributes')->where('user_id',$user_id)->findorFail($id);
            $attribute=[];
            $variation=[];

            $variations = collect($info->attributes)->groupBy(function($q){
                return $q->category_id;
            });
            foreach ($variations as $key => $value) {
                array_push($variation,$key);
                foreach($value as $row){
                    array_push($attribute,$row->variation_id);
                }

            }


            $posts=\App\Category::where([
              ['user_id',$user_id],
              ['type','parent_attribute'],
            ])->whereHas('childrenCategories')->with('childrenCategories')->get();
            return view('seller.products.edit.variants',compact('info','posts','variations','attribute'));
        }
        if ($type=='price') {
            $info=Term::with('price')->where('user_id',Auth::id())->findorFail($id);
            $variations = collect($info->attributes)->groupBy(function($q){
                return $q->attribute->name;
            });
            $countVariation = Price::where('term_id', $id)->where('variation_id_code','!=',null)->count();
            return view('seller.products.edit.price',compact('info','variations','countVariation'));
        }

        if ($type=='image') {
            $info=Term::with('medias')->where('user_id',Auth::id())->findorFail($id);

            return view('seller.products.edit.images',compact('info'));
        }

         if ($type=='files') {
            $info=Term::with('attributes','files')->where('user_id',Auth::id())->findorFail($id);

            return view('seller.products.edit.files',compact('info'));
        }
         if ($type=='option') {
            $info=Term::where('user_id',Auth::id())->with('options')->findorFail($id);

            return view('seller.products.edit.option',compact('info'));
        }

        if ($type=='seo') {
            $info=Term::with('seo')->where('user_id',Auth::id())->findorFail($id);
            $json=json_decode($info->seo->value);
            return view('seller.products.edit.seo',compact('info','json'));
        }
        if ($type=='inventory') {
            $info=Term::with('stock')->where('user_id',Auth::id())->findorFail($id);
            $countVariation = Stock::where('term_id', $id)->where('variation_id_code','!=',null)->count();
            return view('seller.products.edit.stock',compact('info', 'countVariation'));
        }


        if($type == 'express-checkout'){
          $user_id=Auth::id();
            $info=Term::with('attributes','options')->where('user_id',$user_id)->findorFail($id);
            $variations = collect($info->attributes)->groupBy(function($q){
                return $q->attribute->name;
            });
            //return $request=Request()->all();
            return view('seller.products.edit.express',compact('info','variations'));
        }

        abort(404);

    }

    public function merge_varitions_array($ar1, $ar2){
        $new = [];
        foreach($ar1 as $key=>$value1){
            foreach($ar2 as $value2){
                $temp = $ar1[$key];
                $temp[] = $value2;
                $new[] = $temp;
           }
       }
       return $new;
    }

    public function variation(Request $request,$id){
        $database = [];

        if($request->child == null){
            Attribute::where('term_id',$id)->delete();
            Stock::where('term_id',$id)->where('variation_id_code','!=',null)->delete();
            Price::where('term_id',$id)->where('variation_id_code','!=',null)->delete();
            return response()->json('Attributes Updated');
        }

        $data = [];
        foreach($request->child ?? [] as $key=>$child){
            $data[] = $child;
            foreach($child as $c){
                $database[$c] = Category::where('type','child_attribute')->where('id',$c)->first();
            }
        }

        $variations = [];
        foreach($data[0] as $dt){
            $variations[] = [$dt];
        }

        if(count($request->child) > 1){
            $i=1;
            for($i;$i<count($request->child);$i++){
                $variations = $this->merge_varitions_array($variations, $data[$i]);
            }
        }


        $final = [];
        foreach($variations as $v){
            $vartion_id_code = [];
            foreach($v as $value){
                $vartion_id_code[] = $database[$value]->id;
            }
            $final[] = $vartion_id_code;
        }

        $stocks = Stock::where('term_id',$id)->where('variation_id_code','!=',null)->get();
        $stocks_single = Stock::where('term_id',$id)->where('variation_id_code','=',null)->first();
        $stocks_temp = [];
        foreach($stocks as $stock){
            array_push($stocks_temp, $stock->id);
        }

        $prices = Price::where('term_id',$id)->where('variation_id_code','!=',null)->get();
        $prices_single = Price::where('term_id',$id)->where('variation_id_code','=',null)->first();
        $prices_temp = [];
        foreach($prices as $price){
            array_push($prices_temp, $price->id);
        }
        $sttVariation = 0;
        foreach($final as $f){
            $sttVariation+= 1;
            $check_stock = Stock::where('term_id',$id)->where('variation_id_code', json_encode($f))->first();
            if($check_stock){
                $stocks_temp = array_diff($stocks_temp, [$check_stock->id]);
            }else{
                Stock::create([
                    'term_id' => $id,
                    'variation_id_code' => $f,
                    'stock_manage' => $stocks_single->stock_manage,
                    'stock_status' => $stocks_single->stock_status,
                    'stock_qty' => $stocks_single->stock_qty,
                    'sku' => $stocks_single->sku.'-'.$sttVariation,
                ]);
            }

            $check_price = Price::where('term_id',$id)->where('variation_id_code', json_encode($f))->first();
            if($check_price){
                $prices_temp = array_diff($prices_temp, [$check_price->id]);
            }else{
                Price::create([
                    'term_id' => $id,
                    'variation_id_code' => $f,
                    'price' => $prices_single->price,
                    'regular_price' => $prices_single->regular_price,
                    'special_price' => $prices_single->special_price,
                    'price_type' => $prices_single->price_type,
                    'starting_date' => $prices_single->starting_date,
                    'ending_date' => $prices_single->ending_date,
                    'sku' => $prices_single->sku.'-'.$sttVariation,
                ]);
            }
        }

        Stock::whereIn('id', $stocks_temp)->delete();
        Price::whereIn('id', $prices_temp)->delete();

        $data=[];
        foreach ($request->child ?? [] as $key => $value) {

            foreach ($value as $r => $child) {
                $dat['category_id']=$key;
                $dat['variation_id']=$child;
                $dat['term_id']=$id;
                array_push($data,$dat);
            }

        }
        Term::where('user_id',Auth::id())->findorFail($id);
        Attribute::where('term_id',$id)->delete();
        if(count($data) > 0){
            Attribute::insert($data);
        }

       return response()->json('Attributes Updated');

    }

    public function option_delete(Request $request)
    {

        Termoption::where('p_id',$request->id)->where('user_id',Auth::id())->delete();
        Termoption::where('user_id',Auth::id())->where('id',$request->id)->delete();

        return response()->json('Option Deleted Successfully....!!');
    }
    public function row_update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $option=Termoption::where('user_id',Auth::id())->findorFail($request->id);
        $option->name=$request->name;
        $option->is_required=$request->is_required ?? 0;
        $option->select_type=$request->select_type ?? 0;
        $option->save();
        return response()->json('Option Updated Successfully....!!');
    }

    public function option_update(Request $request,$id)
    {

        $user_id=Auth::id();
        foreach($request->options as $key => $option){
            foreach($option as $row){
                foreach($row as $k=> $item){
                    $data['name']=$item['label'];
                    $data['amount']=$item['price'];
                    $data['amount_type']=$item['price_type'];
                    Termoption::where('user_id',$user_id)->where('type',0)->where('p_id',$key)->where('id',$k)->update($data);

                }

            }
        }

        return response()->json(['Option Updated....!!!']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->affiliate) {
            $request->validate([
              'purchase_link' => 'required|max:100'
            ]);
        }

        DB::beginTransaction();
        try {
       $info=Term::where('user_id',Auth::id())->with('affiliate')->findorFail($id);
       $info->title=$request->title;
       $info->lang_id = $request->lang_id;
       $info->slug=$request->slug;
       $info->featured=$request->featured;
       $info->price_status=$request->price_status;
       $info->status=$request->status ?? 2;
       $info->save();

       $meta=Meta::where('key','content')->where('term_id',$id)->first();
       if (empty($meta)) {
           $meta=new Meta;
           $meta->term_id=$id;
           $meta->key='content';
       }
       $dta['content']=$request->content;
       $dta['excerpt']=$request->excerpt;

       $meta->value=json_encode($dta);
       $meta->save();

       $catsArr=[];
       foreach ($request->cats ?? [] as $key => $value) {
        if (!empty($value)) {
             $data['category_id']=$value;
             $data['term_id']=$id;

           array_push($catsArr, $data);
        }

       }

       if (!empty($request->brand)) {
           $data['category_id']=$request->brand;
           $data['term_id']=$id;
          array_push($catsArr, $data);
       }

       Postcategory::where('term_id',$id)->delete();
       if (count($catsArr) > 0) {
          Postcategory::insert($catsArr);
       }

        if ($request->affiliate) {
            $meta=Meta::where('key','affiliate')->where('term_id',$id)->first();
            if (empty($meta)) {
               $meta=new Meta;
               $meta->term_id=$id;
               $meta->key='affiliate';
            }
            $meta->value = $request->purchase_link;
            $meta->save();
        }
        else{
          if (!empty($info->affiliate)) {
           Meta::where('key','affiliate')->where('term_id',$id)->delete();
          }

        }
         DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return back();
        }

       return response()->json(['Item Updated']);

   }

    public function price(Request $request, $id){
        foreach($request->prices as $key => $item){
            if($item['special_price_start'] <= Carbon::now()->format('Y-m-d') && $item['special_price'] != null){
                if($item['special_price'] != null){
                    if($item['price_type'] == 1){
                        $price=$item['price'] - $item['special_price'];
                    }else{
                        $percent = $item['price'] * $item['special_price'] / 100;
                        $price = $item['price'] - $percent;
                        $price =str_replace(',','',number_format($price,2));
                    }
                }else{
                    $price = $item['price'];
                }
            }else{
                $price = $item['price'];
            }

            $data['price']=$price;
            $data['regular_price']=$item['price'];
            $data['special_price']=$item['special_price'];
            $data['price_type']=$item['price_type'];
            $data['starting_date']=$item['special_price_start'];
            $data['ending_date']=$item['special_price_end'];
            $data['created_at']=Carbon::now();

            Price::find($key)->update($data);
       }
       return response()->json(['Price Updated....!!']);
    }

    public function price_single(Request $request, $id){
        if($request->special_price_start <= Carbon::now()->format('Y-m-d') && $request->special_price != null){
           if($request->special_price != null){
            if($request->price_type == 1){
                $price=$request->price-$request->special_price;
            }
            else{
                $percent= $request->price * $request->special_price / 100;
                $price= $request->price-$percent;
                $price=str_replace(',','',number_format($price,2));
            }

          }
          else{
            $price=$request->price;
           }
        }
        else{
            $price=$request->price;
        }

        $price= Price::updateOrCreate(
            [
                'term_id' => $id,
                'variation_id_code' => null,
            ],
            [
                'price' =>$price,
                'regular_price' =>$request->price,
                'special_price' =>$request->special_price ?? null,
                'price_type' =>$request->price_type,
                'starting_date' =>$request->special_price_start ?? null,
                'ending_date' =>$request->special_price_end ?? null,
            ]
        );

        return response()->json('Price Updated Successfully....!!!');

    }

   public function seo(Request $request, $id)
   {
       $info=Term::where('user_id',Auth::id())->findorFail($id);

       $meta=Meta::where('key','seo')->where('term_id',$id)->first();
       if (empty($meta)) {
           $meta=new Meta;
           $meta->term_id=$id;
           $meta->key='seo';
       }
       $data['meta_title']=$request->meta_title;
       $data['meta_description']=$request->meta_description;
       $data['meta_keyword']=$request->meta_keyword;

       $meta->value=json_encode($data);
       $meta->save();

       return response()->json(['Seo Updated']);
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param   \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $auth_id=Auth::id();
        if ($request->method=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $id) {

                   $term= Term::with('medias')->where('user_id',$auth_id)->find($id);
                   if (!empty($term)) {
                       foreach ($term->medias as $key => $row) {
                           mediaRemove($row->id);
                       }

                       Term::destroy($id);
                   }
                }
            }
        }
        else{
         if ($request->ids) {
            foreach ($request->ids as $id) {

                $term= Term::where('user_id',$auth_id)->find($id);
                if (!empty($term)) {

                 $term->status=$request->method;
                 $term->save();
             }
           }

       }
     }
        return response()->json('Success');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls|max:500'
        ]);

        $limit=user_limit();
        $posts_count=Term::where('user_id',Auth::id())->count();
        if ($limit['product_limit'] <= $posts_count) {
         $error['errors']['error']='Maximum posts limit exceeded';
         return response()->json($error,401);
        }
        Excel::import(new ProductImport,  $request->file('file'));

        return response()->json(['Product Imported Successfully']);
    }
}
