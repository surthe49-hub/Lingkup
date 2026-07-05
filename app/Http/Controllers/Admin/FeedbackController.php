<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * GET /admin/feedback
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');

        $query = Feedback::query();

        if ($statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $feedback = $query
            ->with(['user', 'pathway.target'])
            ->when($request->filled('rating'), function ($query) use ($request) {
                $query->where('rating', $request->input('rating'));
            })
            ->when($statusFilter === 'unread', fn ($query) => $query->unread())
            ->when($statusFilter === 'read', fn ($query) => $query->read())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.feedback.index', [
            'feedback' => $feedback,
            'filters' => $request->only(['rating', 'status']),
        ]);
    }

    /**
     * PATCH /admin/feedback/{feedback}/toggle-read
     */
    public function toggleRead(Feedback $feedback): RedirectResponse
    {
        if ($feedback->isRead()) {
            $feedback->markAsUnread();
            $message = 'Feedback ditandai belum dibaca.';
        } else {
            $feedback->markAsRead();
            $message = 'Feedback ditandai sudah dibaca.';
        }

        return back()->with('success', $message);
    }

    /**
     * DELETE /admin/feedback/{feedback}
     */
    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus.');
    }

    /**
     * PATCH /admin/feedback/{feedback}/restore
     *
     * Route menggunakan ->withTrashed() supaya route model binding
     * bisa menemukan feedback yang sudah dihapus.
     */
    public function restore(Feedback $feedback): RedirectResponse
    {
        $feedback->restore();

        return back()->with('success', 'Feedback berhasil direstore.');
    }
}