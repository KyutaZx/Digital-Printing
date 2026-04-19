<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DesignReview;
use App\Models\DesignRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Staff Review Design
    |--------------------------------------------------------------------------
    */

    public function review(Order $order)
    {
        $review = DesignReview::create([
            'order_id' => $order->id,
            'review_status' => 'pending',
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Design review started');
    }

    /*
    |--------------------------------------------------------------------------
    | Request Design Revision
    |--------------------------------------------------------------------------
    */

    public function requestRevision(Request $request, DesignReview $review)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $review->update([
            'review_status' => 'revision',
            'notes' => $request->notes,
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Revision requested');
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Upload Revision
    |--------------------------------------------------------------------------
    */

    public function uploadRevision(Request $request, DesignReview $review)
    {
        $request->validate([
            'file' => 'required|file|max:4096'
        ]);

        $filePath = $request->file('file')->store('design_revisions', 'public');

        $revisionNumber = DesignRevision::where('design_review_id', $review->id)->count() + 1;

        DesignRevision::create([
            'design_review_id' => $review->id,
            'revision_number' => $revisionNumber,
            'file_path' => $filePath,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Revision uploaded');
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Design
    |--------------------------------------------------------------------------
    */

    public function approve(DesignReview $review)
    {
        $review->update([
            'review_status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        $review->order->update([
            'status' => 'design_approved'
        ]);

        return redirect()->back()->with('success', 'Design approved');
    }
}