<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class WhatsAppChannelController extends Controller
{
    public function edit()
    {
        $channelUrl = (string) SiteSetting::getValue('whatsapp_channel_url', '');
        $isEnabled = SiteSetting::getValue('whatsapp_channel_enabled', '0') === '1';

        return view('admin.whatsapp_channel.edit', compact('channelUrl', 'isEnabled'));
    }

    public function update(Request $request)
    {
        $isEnabled = $request->boolean('is_enabled');

        $validated = $request->validate([
            'whatsapp_channel_url' => [
                $isEnabled ? 'required' : 'nullable',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    $trimmed = trim((string) $value);
                    if ($trimmed === '') {
                        return;
                    }
                    if (! SiteSetting::isValidWhatsAppChannelUrl($trimmed)) {
                        $fail('Please enter a valid WhatsApp Channel join URL (e.g. https://whatsapp.com/channel/...), not a direct chat link (wa.me).');
                    }
                },
            ],
        ]);

        $cleanUrl = trim((string) ($validated['whatsapp_channel_url'] ?? ''));

        SiteSetting::setValue('whatsapp_channel_url', $cleanUrl !== '' ? $cleanUrl : null);
        SiteSetting::setValue('whatsapp_channel_enabled', $isEnabled ? '1' : '0');

        return back()->with('success', 'WhatsApp Channel settings updated successfully.');
    }
}
