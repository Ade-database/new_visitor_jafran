<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VisitorRegistrationController extends Controller
{
    // Layar 1: tamu diminta isi nomor HP
    public function phone()
    {
        return view('kunjungan.phone');
    }

    // Dipanggil pas tamu klik "Lanjut" di layar 1
    public function checkPhone(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'regex:/^[0-9]{10,15}$/'],
        ], [
            'phone_number.regex' => 'Nomor HP harus angka saja, minimal 10 digit.',
        ]);

        $phone = $request->phone_number;

        Session::put('reg.phone', $phone);

        $visitor = Visitor::byPhone($phone)->first();

        if (! $visitor) {
            return redirect()->route('kunjungan.new-visitor');
        }

        Session::put('reg.visitor_id', $visitor->id);

        // Masih ada kunjungan yang disetujui dan belum checkout -> arahkan ke checkout
        $approvedVisit = Visit::where('visitor_id', $visitor->id)
            ->where('status', 'approved')
            ->latest('approved_at')
            ->first();

        if ($approvedVisit) {
            Session::put('reg.visit_id', $approvedVisit->id);

            return redirect()->route('kunjungan.checkout-confirm');
        }

        // Masih menunggu persetujuan -> jangan buat kunjungan ganda
        $pendingVisit = Visit::where('visitor_id', $visitor->id)
            ->where('status', 'pending')
            ->latest('submitted_at')
            ->first();

        if ($pendingVisit) {
            Session::put('reg.last_visit_id', $pendingVisit->id);

            return redirect()->route('kunjungan.thanks');
        }

        return redirect()->route('kunjungan.confirm');
    }

    public function newVisitorForm()
    {
        if (! Session::has('reg.phone')) {
            return redirect()->route('kunjungan.phone');
        }

        $departments = Department::orderBy('name')->get();

        $employees = Employee::orderBy('name')->get();

        return view(
            'kunjungan.new-visitor',
            compact(
                'departments',
                'employees'
            )
        );
    }

    public function storeNewVisitor(Request $request)
    {
        if (Visitor::byPhone(Session::get('reg.phone'))->exists()) {
            return redirect()->route('kunjungan.phone')
                ->withErrors(['phone_number' => 'Nomor ini sudah terdaftar, silakan mulai dari awal.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'photo' => 'required|image|max:5120',
        ]);

        $photoPath = $request->file('photo')->store('visitor-photos', 'public');

        $visitor = Visitor::create([
            'name' => $data['name'],
            'phone_number' => Session::get('reg.phone'),
            'company' => $data['company'] ?? null,
            'category' => 'reguler',
            'photo_path' => $photoPath,
        ]);

        Session::put('reg.visitor_id', $visitor->id);
        Session::put('reg.purpose', $data['purpose']);
        Session::put('reg.employee_id', $data['employee_id'] ?? null);
        Session::put('reg.department_id', $data['department_id'] ?? null);

        return redirect()->route('kunjungan.video');
    }

    // ==============================
    // KONFIRMASI KUNJUNGAN
    // ==============================

    public function confirmForm()
    {
        if (! Session::has('reg.visitor_id')) {
            return redirect()->route('kunjungan.phone');
        }

        $visitor = Visitor::findOrFail(
            Session::get('reg.visitor_id')
        );

        $departments = Department::orderBy('name')->get();

        $employees = Employee::orderBy('name')->get();

        return view(
            'kunjungan.confirm',
            compact(
                'visitor',
                'departments',
                'employees'
            )
        );
    }

    public function storeConfirm(Request $request)
    {
        $data = $request->validate([
            'company' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $visitor = Visitor::findOrFail(
            Session::get('reg.visitor_id')
        );

        $visitor->update([
            'company' => $data['company'] ?? null,
        ]);

        Session::put('reg.purpose', $data['purpose']);
        Session::put('reg.employee_id', $data['employee_id'] ?? null);
        Session::put('reg.department_id', $data['department_id'] ?? null);

        return redirect()->route('kunjungan.video');
    }

    // ==============================
    // VIDEO
    // ==============================

    public function video()
    {
        if (! Session::has('reg.visitor_id')) {
            return redirect()->route('kunjungan.phone');
        }

        return view('kunjungan.video');
    }

    // ==============================
    // SUBMIT KUNJUNGAN
    // ==============================

    public function submit(Request $request)
    {
        if (! Session::has('reg.visitor_id')) {
            return redirect()->route('kunjungan.phone');
        }

        $visit = Visit::create([
            'visitor_id' => Session::get('reg.visitor_id'),
            'department_id' => Session::get('reg.department_id'),
            'employee_id' => Session::get('reg.employee_id'),
            'purpose' => Session::get('reg.purpose'),
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        Session::forget([
            'reg.phone',
            'reg.visitor_id',
            'reg.purpose',
            'reg.department_id',
            'reg.employee_id',
        ]);

        Session::put('reg.last_visit_id', $visit->id);

        return redirect()->route('kunjungan.thanks');
    }

    // ==============================
    // CHECKOUT
    // ==============================

    public function checkoutForm()
    {
        if (! Session::has('reg.visit_id')) {
            return redirect()->route('kunjungan.phone');
        }

        $visit = Visit::with([
            'visitor',
            'employee',
            'department',
        ])->findOrFail(
            Session::get('reg.visit_id')
        );

        return view(
            'kunjungan.checkout-confirm',
            compact('visit')
        );
    }

    public function storeCheckout()
    {
        if (! Session::has('reg.visit_id')) {
            return redirect()->route('kunjungan.phone');
        }

        $visit = Visit::findOrFail(
            Session::get('reg.visit_id')
        );

        $visit->checkOut();

        Session::put('reg.feedback_visit_id', $visit->id);

        Session::forget('reg.visit_id');

        return redirect()->route('kunjungan.feedback');
    }

    // ==============================
    // FEEDBACK
    // ==============================

    public function feedbackForm()
    {
        if (! Session::has('reg.feedback_visit_id')) {
            return redirect()->route('kunjungan.phone');
        }

        return view('kunjungan.feedback');
    }

    public function storeFeedback(Request $request)
    {
        if (! Session::has('reg.feedback_visit_id')) {
            return redirect()->route('kunjungan.phone');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        \App\Models\VisitFeedback::create([
            'visit_id' => Session::get('reg.feedback_visit_id'),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        Session::forget('reg.feedback_visit_id');

        return redirect()
            ->route('kunjungan.thanks')
            ->with('info', 'Terima kasih atas feedback Anda!');
    }

    public function skipFeedback()
    {
        Session::forget('reg.feedback_visit_id');

        return redirect()
            ->route('kunjungan.thanks')
            ->with('info', 'Terima kasih sudah berkunjung!');
    }

    // ==============================
    // THANKS
    // ==============================

    public function thanks()
    {
        $visit = null;

        if (Session::has('reg.last_visit_id')) {
            $visit = Visit::find(Session::get('reg.last_visit_id'));
        }

        return view(
            'kunjungan.thanks',
            compact('visit')
        );
    }

    // ==============================
    // STATUS (dipanggil otomatis oleh halaman thanks)
    // ==============================

    public function status()
    {
        if (! Session::has('reg.last_visit_id')) {
            return response()->json(['status' => null]);
        }

        $visit = Visit::find(Session::get('reg.last_visit_id'));

        if (! $visit) {
            return response()->json(['status' => null]);
        }

        return response()->json([
            'status' => $visit->status,
            'rejected_reason' => $visit->rejected_reason,
        ]);
    }
}