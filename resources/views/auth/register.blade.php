<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Heading -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-indigo-700">
                Join KassCare
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Secure Healthcare Operations Platform
            </p>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 leading-relaxed">

            <div class="flex items-start gap-3">
                <input
                    id="terms"
                    type="checkbox"
                    name="terms"
                    required
                    class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >

                <label for="terms">
                    I acknowledge and agree to the
                    <a
                        href="{{ url('/terms') }}"
                        target="_blank"
                        class="font-semibold text-indigo-700 underline"
                    >
                        Terms & Conditions
                    </a>
                    and Privacy Policies of KassCare and KASS MTV USA LLC.

                    I understand that KassCare is a healthcare operations platform and that users are responsible for lawful usage, safeguarding protected healthcare information, maintaining credential security, and complying with applicable healthcare and privacy regulations.

                    I further acknowledge that subscriptions, billing cycles, free trials, and platform access may be governed by separate service agreements and may be suspended or terminated for violations, abuse, fraudulent activity, or non-payment.
                </label>
            </div>

            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <!-- Login Link -->
        <div class="flex items-center justify-between pt-2">
            <a
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}"
            >
                Already registered?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
