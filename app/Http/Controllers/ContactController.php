<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(): View|Application|Factory
    {
        $contacts = Contact::select('id', 'email', 'subject')->paginate(20);

        return view('admin.contacto.index', compact('contacts'));
    }

    public function show(int $id): View|Application|Factory
    {
        $contact = Contact::findOrFail($id);

        return view('admin.contacto.show', compact('contact'));
    }

    public function client(): View|Application|Factory
    {
        return view('client.contacto.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required|min:10',
            ],
            [
                'email.required' => __('general.email_required'),
                'email.email' => __('general.email_invalid'),
                'subject.required' => __('general.subject_required'),
                'message.required' => __('general.message_required'),
                'message.min' => __('general.message_min'),
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', __('general.form_error'))
                ->withErrors($validator);
        }

        try {
            Contact::create([
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            return redirect()
                ->back()
                ->with('success', __('general.success'));
        } catch (Exception $exception) {
            if (env('APP_ENV') === 'local') {
                Log::error($exception->getMessage());
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('general.error'));
        }
    }
}
