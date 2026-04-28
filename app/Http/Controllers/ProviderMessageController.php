<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\ProviderMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderMessageController extends Controller
{
    public function facilityIndex()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $messages = ProviderMessage::with(['client', 'provider', 'sender'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->get();

        return view('facility.messages.index', compact('messages'));
    }

    public function facilityCreate()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $clients = Client::where('facility_id', $facilityId)->orderBy('name')->get();
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('facility.messages.create', compact('clients', 'providers'));
    }

    public function facilityStore(Request $request)
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id;

        $data = $request->validate([
            'provider_id' => ['required', 'exists:users,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['nullable', 'string', 'max:50'],
        ]);

        ProviderMessage::create([
            'facility_id' => $facilityId,
            'client_id' => $data['client_id'] ?? null,
            'sender_id' => auth()->id(),
            'provider_id' => $data['provider_id'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'priority' => $data['priority'] ?? 'normal',
        ]);

        return redirect()
            ->route('facility.messages.index')
            ->with('success', 'Message sent to provider successfully.');
    }

    public function index()
    {
        $messages = ProviderMessage::with(['facility', 'client', 'sender'])
            ->where('provider_id', auth()->id())
            ->latest()
            ->get();

        return view('provider.messages.index', compact('messages'));
    }

    public function create()
    {
        $facilities = Facility::query()->orderBy('name')->get();
        $clients = Client::query()->orderBy('name')->get();

        return view('provider.messages.create', compact('facilities', 'clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['nullable', 'string', 'max:50'],
        ]);

        ProviderMessage::create([
            'facility_id' => $data['facility_id'],
            'client_id' => $data['client_id'] ?? null,
            'provider_id' => auth()->id(),
            'sender_id' => auth()->id(),
            'subject' => $data['subject'] ?? 'Provider Message',
            'message' => $data['message'],
            'priority' => $data['priority'] ?? 'normal',
            'sender_type' => 'provider',
        ]);

        return redirect()
            ->route('provider.messages.index')
            ->with('success', 'Message sent successfully.');
    }

    public function show(ProviderMessage $message)
    {
        $user = auth()->user();

        abort_if(
            !$user || ((int) $message->provider_id !== (int) $user->id && $user->role !== 'super_admin'),
            403,
            'Unauthorized provider message access.'
        );

        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        $message->load(['facility', 'client', 'sender']);

        return view('provider.messages.show', compact('message'));
    }

    public function reply(Request $request, ProviderMessage $message)
    {
        $user = auth()->user();

        abort_if(
            !$user || ((int) $message->provider_id !== (int) $user->id && $user->role !== 'super_admin'),
            403
        );

        $data = $request->validate([
            'provider_reply' => ['required', 'string'],
        ]);

        $message->update([
            'provider_reply' => $data['provider_reply'],
            'replied_at' => now(),
        ]);

        return redirect()
            ->route('provider.messages.show', $message)
            ->with('success', 'Reply sent successfully.');
    }

    public function providerIndex() { return $this->index(); }
    public function providerCreate() { return $this->create(); }
    public function providerStore(Request $request) { return $this->store($request); }
    public function providerShow(ProviderMessage $message) { return $this->show($message); }
    public function providerReply(Request $request, ProviderMessage $message) { return $this->reply($request, $message); }
}
