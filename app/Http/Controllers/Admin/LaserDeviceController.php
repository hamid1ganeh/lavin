<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\LaserTubeHistory;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use App\Models\LaserDevice;
use Auth;
use Illuminate\Support\Facades\DB;

class LaserDeviceController extends Controller
{
    function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.index');

        $lasers = LaserDevice::orderBy('name','asc')
                                ->withTrashed()
                                ->get();

        $goods = WarehouseStock::with('good')
                ->where('warehouse_id',1)
                ->whereHas('good',function($q){
                    $q->where('status',Status::Active)->orderBy('title','asc');
                })->get()->pluck('good');


        return view('admin.warehousing.lasers.all',compact('lasers','goods'));
    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.create');

        return view('admin.warehousing.lasers.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.create');

        $request->validate(
            [
                "code"=>['required','max:255','unique:laser_devices'],
                "name"=>['required','max:255'],
                "brand"=>['required','max:255'],
                "model"=>['required','max:255'],
                "year"=>['required','min:4','max:4'],

            ],
            [
                "code.required"=>"کد دستگاه الزامی است",
                "code.max"=>"حداکثر طول مجاز برای کد دستگاه 255 کارکتر",
                "code.unique"=>"کد دستگاه قبلا ثبت شده است",
                "name.required"=>"نام دستگاه دستگاه الزامی است",
                "name.max"=>"حداکثر طول مجاز برای نام دستگاه 255 کارکتر",
                "brand.required"=>"برند دستگاه الزامی است",
                "brand.max"=>"حداکثر طول مجاز برای برند دستگاه 255 کارکتر",
                "model.required"=>"مدل دستگاه الزامی است",
                "model.max"=>"حداکثر طول مجاز برای مدل دستگاه 255 کارکتر",
                "year.required"=>"سال ساخت دستگاه الزامی است",
                "year.max"=>"حداکثر طول مجاز برای سال ساخت دستگاه 255 کارکتر",

            ]);

            LaserDevice::create([
                'code'=> $request->code,
                'name'=> $request->name,
                'brand'=> $request->brand,
                'model'=> $request->model,
                'year'=> $request->year
            ]);

        toast('دستگاه لیزر جدید ثبت شد.', 'success')->position('bottom-end');

        return redirect(route('admin.warehousing.lasers.index'));
    }


    public function edit(LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.edit');

        return view('admin.warehousing.lasers.edit',compact('laser'));
    }


    public function update(Request $request,LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.edit');

        $request->validate(
            [
                "code"=>['required','max:255','unique:laser_devices,code,'.$laser->id],
                "name"=>['required','max:255'],
                "brand"=>['required','max:255'],
                "model"=>['required','max:255'],
                "year"=>['required','min:4','max:4'],

            ],
            [
                "code.required"=>"کد دستگاه الزامی است",
                "code.max"=>"حداکثر طول مجاز برای کد دستگاه 255 کارکتر",
                "code.unique"=>"کد دستگاه قبلا ثبت شده است",
                "name.required"=>"نام دستگاه دستگاه الزامی است",
                "name.max"=>"حداکثر طول مجاز برای نام دستگاه 255 کارکتر",
                "brand.required"=>"برند دستگاه الزامی است",
                "brand.max"=>"حداکثر طول مجاز برای برند دستگاه 255 کارکتر",
                "model.required"=>"مدل دستگاه الزامی است",
                "model.max"=>"حداکثر طول مجاز برای مدل دستگاه 255 کارکتر",
                "year.required"=>"سال ساخت دستگاه الزامی است",
                "year.max"=>"حداکثر طول مجاز برای سال ساخت دستگاه 255 کارکتر",

            ]);

        $laser->update([
                'code'=> $request->code,
                'name'=> $request->name,
                'brand'=> $request->brand,
                'model'=> $request->model,
                'year'=> $request->year
            ]);

        toast('بروزررسانی انجام  شد.', 'success')->position('bottom-end');

        return redirect(route('admin.warehousing.lasers.index'));
    }

    public function destroy(LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.destroy');

        $laser->delete();
        toast('دستگاه مورد نظر حذف  شد.', 'error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.recycle');
        $laser = LaserDevice::onlyTrashed()->findOrFail($id);
        $laser->restore();
        toast('دستگاه مورد نظر بازیابی  شد.', 'error')->position('bottom-end');
        return back();
    }

    public function tube(LaserDevice $laser,Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.tube');

            $request->validate(
            [
                "good"=>['required','exists:goods,id'],
                "description"=>['nullable','max:255'],
            ],
            [
                "good.required"=>" انتخاب کالا الزامی است",
                "code.max"=>"حداکثر طول مجاز برای کد دستگاه 255 کارکتر",
                "code.unique"=>"کد دستگاه قبلا ثبت شده است",
                "description.max"=>"حداکثر طول مجاز برای  توضیحات 255 کارکتر",
            ]);

            $waste = 0;
            if(!is_null($laser->shot)){
                $waste = $laser->shot;
            }

            if($waste && is_null($request->description)){
                alert()->error('تویپ فعلی به اتمام نرسیده است. لطفا توضیحاتی برای آن ثبت کنید.');
                return back();
            }

            $good = Goods::find($request->good);

            $history = new LaserTubeHistory();
            $history->laser_device_id =  $laser->id;
            $history->goods_id =  $good->id;
            $history->good_title = $good->title;
            $history->good_brand = $good->brand;
            $history->changed_by = Auth::guard('admin')->id();
            $history->shot =  $good->value_per_count;
            $history->waste =  $waste;

             $laser->tube_id =  $good->id;
             $laser->shot =  $good->value_per_count;

            DB::transaction(function() use ($history,$laser) {
                $history->save();
                $laser->save();
            });

            toast('تویب لیزر تعوض شد.', 'error')->position('bottom-end');
            return back();
    }

    public function history(LaserDevice $laser)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.lasers.tube.history');

        $histories = LaserTubeHistory::where('laser_device_id',$laser->id)->orderBy('created_at','desc')->get();
        return view('admin.warehousing.lasers.history',compact('histories','laser'));
    }
}
