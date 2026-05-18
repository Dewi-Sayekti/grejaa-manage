<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div style="position:relative;">
                <x-text-input id="update_password_current_password"
                    name="current_password" type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    style="padding-right: 44px;" />
                <button type="button" onclick="togglePassword('update_password_current_password', 'eye-cur')"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                    <i id="eye-cur" class="fas fa-eye-slash" style="font-size:15px;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Password Baru --}}
        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div style="position:relative;">
                <x-text-input id="update_password_password"
                    name="password" type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    style="padding-right: 44px;" />
                <button type="button" onclick="togglePassword('update_password_password', 'eye-new')"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                    <i id="eye-new" class="fas fa-eye-slash" style="font-size:15px;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div style="position:relative;">
                <x-text-input id="update_password_password_confirmation"
                    name="password_confirmation" type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    style="padding-right: 44px;" />
                <button type="button" onclick="togglePassword('update_password_password_confirmation', 'eye-con')"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                    <i id="eye-con" class="fas fa-eye-slash" style="font-size:15px;"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
}
</script>