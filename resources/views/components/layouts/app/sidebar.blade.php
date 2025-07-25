<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="users"  :href="route('admin.employee.index')" :current="request()->routeIs('admin.employee.index')" wire:navigate>{{ __('Empleado') }}</flux:navlist.item>
                    <flux:navlist.group expandable heading="Habitación" class="hidden lg:grid">
                        <flux:navlist.item icon="building-storefront"  :href="route('admin.room.index')" :current="request()->routeIs('admin.room.index')" wire:navigate>{{ __('Habitación') }}</flux:navlist.item>
                        <flux:navlist.item icon="building-storefront"  :href="route('admin.room_service.index')" :current="request()->routeIs('admin.room_service.index')" wire:navigate>{{ __('Servicio de Habitación') }}</flux:navlist.item>
                    </flux:navlist.group>
                    <flux:navlist.item icon="user"  :href="route('admin.guest.index')" :current="request()->routeIs('admin.guest.index')" wire:navigate>{{ __('Huesped') }}</flux:navlist.item>
                    
                    <flux:navlist.group expandable heading="Reserva" class="hidden lg:grid">
                        <flux:navlist.item icon="building-office"  :href="route('admin.booking.index')" :current="request()->routeIs('admin.booking.index')" wire:navigate>{{ __('Reserva') }}</flux:navlist.item>
                        <flux:navlist.item icon="document-text"  :href="route('admin.booking_detail.index')" :current="request()->routeIs('admin.booking_detail.index')" wire:navigate>{{ __('Detalle de Reserva') }}</flux:navlist.item>
                    </flux:navlist.group>
                    <flux:navlist.group expandable heading="Servicio" class="hidden lg:grid">
                        <flux:navlist.item icon="wrench-screwdriver"  :href="route('admin.service.index')" :current="request()->routeIs('admin.service.index')" wire:navigate>{{ __('Servicio') }}</flux:navlist.item>
                        <flux:navlist.item icon="document-text"  :href="route('admin.service_detail.index')" :current="request()->routeIs('admin.service_detail.index')" wire:navigate>{{ __('Detalle de Servicio') }}</flux:navlist.item>
                    </flux:navlist.group>
                    <flux:navlist.item icon="user"  :href="route('admin.payment.index')" :current="request()->routeIs('admin.payment.index')" wire:navigate>{{ __('Pago') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Sección de Notificaciones -->
            @auth
                <div class="px-4 py-2">
                    <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mb-2">
                        {{ __('Notificaciones') }}
                    </h3>

                    @if (Auth::user()->unreadNotifications->isEmpty())
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">
                            {{ __('No hay notificaciones nuevas.') }}
                        </p>
                    @else
                        <ul class="space-y-2">
                            @foreach (Auth::user()->unreadNotifications->take(5) as $notification)
                                <li class="flex items-start justify-between gap-2 p-3 bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded text-xs">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" />
                                        </svg>
                                        <span class="text-zinc-800 dark:text-zinc-200">
                                            {{ $notification->data['message'] }}
                                        </span>
                                    </div>

                                    <!-- Botón para marcar como leída -->
                                    <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Marcar como leída">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Botón para marcar todas como leídas -->
                        <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST" class="mt-3 text-right">
                            @csrf
                            <button type="submit" class="text-xs text-blue-500 hover:underline">
                                {{ __('Marcar todas como leídas') }}
                            </button>
                        </form>
                    @endif
                </div>
            @endauth


            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
