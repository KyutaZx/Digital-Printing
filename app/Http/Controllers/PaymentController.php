<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Payment Methods
    |--------------------------------------------------------------------------
    */

    public function methods()
    {
        $methods = PaymentMethod::all();

        return view('payments.methods', compact('methods'));
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Payment Proof
    |--------------------------------------------------------------------------
    */

    public function uploadProof(Request $request, Order $order)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required|image|max:2048'
        ]);

        $filePath = $request->file('payment_proof')->store('payments', 'public');

        PaymentTransaction::create([
            'order_id' => $order->id,
            'payment_method_id' => $request->payment_method_id,
            'amount' => $order->total_price,
            'payment_proof' => $filePath,
            'status' => 'pending'
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Payment proof uploaded successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Staff Verify Payment
    |--------------------------------------------------------------------------
    */

    public function verify(PaymentTransaction $payment)
    {
        $payment->update([
            'status' => 'verified'
        ]);

        $payment->order->update([
            'status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Payment verified');
    }
}