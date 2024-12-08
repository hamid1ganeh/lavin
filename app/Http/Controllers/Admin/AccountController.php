<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class AccountController extends Controller
{
    public function index()
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.list');

        $accounts = Account::orderBy('created_at','desc')->withTrashed()->get();
        return view('admin.accounting.accounts.all',compact('accounts'));
    }

    public function create()
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.create');

        return view('admin.accounting.accounts.create');
    }


    public function store(Request $request)
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.create');

        $request->validate(
            [
                'full_name' => ['required','max:255'],
                'bank_name' => ['required','max:255'],
                'account_type' => ['required','max:255'],
                'opened_at' => ['required'],
                'account_number' => ['required','max:255','unique:accounts'],
                'cart_number' => ['required','max:255','unique:accounts'],
                'shaba_number' => ['required','max:255','unique:accounts'],
            ],
            [
                'full_name.required' => 'نام و نام خانوادگی الزامی است.',
                'full_name.max' => 'حداکثر طول نام و نام خانوادگی 255 کارکتر.',
                'bank_name.required' => 'نام بانک الزامی است.',
                'bank_name.max' => 'حداکثر  طول نام بانک 255 کارکتر.',
                'account_type.required' => 'نوع حساب الزامی است.',
                'account_type.max' => 'حداکثر طول نوع حساب 255 کارکتر.',
                'opened_at.required' => 'تاریخ افتتاح حساب الزامی است.',
                'account_number.required' => 'شماره حساب الزامی است.',
                'account_number.unique' => 'این شماره حساب قبلا ثبت شده است.',
                'account_number.max' => 'حداکثر طول شماره حساب 255 کارکتر.',
                'cart_number.required' => 'شماره کارت الزامی است.',
                'cart_number.max' => 'حداکثر طول شماره کارت 255 کارکتر.',
                'cart_number.unique' => 'این شماره کارت قبلا ثبت شده است.',
                'shaba_number.required' => 'شماره شبا الزامی است.',
                'shaba_number.max' => 'حداکثر طول شماره شبا 255 کارکتر.',
                'shaba_number.unique' => 'این شماره شبا قبلا ثبت شده است.',
            ]);

        $opened_at =   faToEn($request->opened_at);
        $opened_at = Jalalian::fromFormat('Y/m/d', $opened_at)->toCarbon("Y-m-d");
        Account::create(['full_name'=> $request->full_name,
                        'bank_name'=> $request->bank_name,
                        'account_type'=> $request->account_type,
                        'opened_at'=> $opened_at,
                        'account_number'=> $request->account_number,
                        'cart_number'=> $request->cart_number,
                        'shaba_number'=> $request->shaba_number,
                        'status'=> $request->status]);

        toast('حساب مالی جدید ثبت شد.','success')->position('bottom-end');
        return redirect(route('admin.accounting.accounts.index'));
    }

    public function edit(Account $account)
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.edit');
        return view('admin.accounting.accounts.edit',compact('account'));
    }


    public function update(Account $account,Request $request)
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.edit');

        $request->validate(
            [
                'full_name' => ['required','max:255'],
                'bank_name' => ['required','max:255'],
                'account_type' => ['required','max:255'],
                'opened_at' => ['required'],
                'account_number' => ['required','max:255','unique:accounts,account_number,'.$account->id],
                'cart_number' => ['required','max:255','unique:accounts,cart_number,'.$account->id],
                'shaba_number' => ['required','max:255','unique:accounts,shaba_number,'.$account->id],
            ],
            [
                'full_name.required' => 'نام و نام خانوادگی الزامی است.',
                'full_name.max' => 'حداکثر طول نام و نام خانوادگی 255 کارکتر.',
                'bank_name.required' => 'نام بانک الزامی است.',
                'bank_name.max' => 'حداکثر  طول نام بانک 255 کارکتر.',
                'account_type.required' => 'نوع حساب الزامی است.',
                'account_type.max' => 'حداکثر طول نوع حساب 255 کارکتر.',
                'opened_at.required' => 'تاریخ افتتاح حساب الزامی است.',
                'account_number.required' => 'شماره حساب الزامی است.',
                'account_number.unique' => 'این شماره حساب قبلا ثبت شده است.',
                'account_number.max' => 'حداکثر طول شماره حساب 255 کارکتر.',
                'cart_number.required' => 'شماره کارت الزامی است.',
                'cart_number.max' => 'حداکثر طول شماره کارت 255 کارکتر.',
                'cart_number.unique' => 'این شماره کارت قبلا ثبت شده است.',
                'shaba_number.required' => 'شماره شبا الزامی است.',
                'shaba_number.max' => 'حداکثر طول شماره شبا 255 کارکتر.',
                'shaba_number.unique' => 'این شماره شبا قبلا ثبت شده است.',
            ]);

        $opened_at =   faToEn($request->opened_at);
        $opened_at = Jalalian::fromFormat('Y/m/d', $opened_at)->toCarbon("Y-m-d");
        $account->update(['full_name'=> $request->full_name,
                            'bank_name'=> $request->bank_name,
                            'account_type'=> $request->account_type,
                            'opened_at'=> $opened_at,
                            'account_number'=> $request->account_number,
                            'cart_number'=> $request->cart_number,
                            'shaba_number'=> $request->shaba_number,
                            'status'=> $request->status]);

        toast('حساب مالی جدید ثبت شد.','success')->position('bottom-end');
        return redirect(route('admin.accounting.accounts.index'));
    }

    public function destroy(Account $account)
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.delete');

        $account->delete();
        toast('حساب مالی حذف شد.','success')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('accounting.accounts.recycle');

        $account = Account::withTrashed()->find($id);
        $account->restore();
        toast('حساب مالی مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}
