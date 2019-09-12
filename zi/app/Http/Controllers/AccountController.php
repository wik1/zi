<?php

namespace App\Http\Controllers;

use App\Services\UniOrdersService;
use App\Services\FinanceService;
use Illuminate\Support\Facades\Auth;

class AccountController extends BaseViewController
{
    public function home()
    {
        return redirect('/account/finances');
    }

    public function orders()
    {
        $orders = UniOrdersService::getOrdersForUser(Auth::user()->ktrh);
        return view('orders', ['orders' => $orders]);
    }

    public function order($id)
    {
        $order = UniOrdersService::getOrder($id);
        return view('order', ['order' => $order['order'], 'positions' => $order['positions']]);
    }

    public function finances()
    {
        return view('finances', [
            'headline' => FinanceService::getHeadline(Auth::user()->ktrh),
            'overdueDocuments' => FinanceService::getOverdueDocuments(Auth::user()->ktrh)
        ]);
    }

    public function invoices()
    {
        return view('invoices', [
            'invoices' => FinanceService::getInvoices(Auth::user()->ktrh)
        ]);
    }

    public function invoice($id)
    {
        $invoiceData = FinanceService::getInvoice($id, Auth::user()->ktrh);
        return view('invoice', [
            'headline' => $invoiceData['headline'],
            'positions' => $invoiceData['positions']
        ]);
    }
}