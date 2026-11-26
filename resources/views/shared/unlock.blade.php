<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sharingFile->title }} - Protected Sharing File</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-2xl p-7 shadow-2xl space-y-5">
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="Vedanta Placement Agency" class="h-11 mx-auto bg-white/95 rounded-lg p-1.5 shadow">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/15 text-amber-400 border border-amber-500/30 text-xs font-bold mt-2">
                <i class="fas fa-lock"></i> Password Protected Candidate File
            </div>
            <h1 class="text-xl font-bold text-white">{{ $sharingFile->title }}</h1>
            <p class="text-xs text-slate-400">Prepared exclusively for <strong class="text-slate-200">{{ $schoolName }}</strong></p>
        </div>

        <form action="{{ route('shared-file.unlock', $sharingFile->share_token) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="file_unlock_password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Enter File Password
                </label>
                <input type="password" id="file_unlock_password" name="password" required autofocus
                       placeholder="Enter password shared by Vedanta team..."
                       class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-amber-400 focus:outline-none">
                @error('password')
                    <p class="text-xs text-rose-400 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-sm transition-colors cursor-pointer">
                <i class="fas fa-unlock-alt mr-1.5"></i> Open Candidate Sharing File
            </button>
        </form>
    </div>
</body>
</html>
