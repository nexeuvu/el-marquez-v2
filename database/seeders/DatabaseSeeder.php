<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Administrador']);

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Room
            'admin.room.index',
            'admin.room.store',
            'admin.room.update',
            'admin.room.destroy',
            'admin.room.export-pdf',
            'admin.room.export-excel',

            // Room Service
            'admin.room_service.index',
            'admin.room_service.store',
            'admin.room_service.update',
            'admin.room_service.destroy',
            'admin.room_service.export-pdf',
            'admin.room_service.export-excel',

            // Guest
            'admin.guest.index',
            'admin.guest.store',
            'admin.guest.update',
            'admin.guest.destroy',
            'admin.guest.export-pdf',
            'admin.guest.export-excel',
            'admin.guest.consultar-dni',

            // Booking
            'admin.booking.index',
            'admin.booking.store',
            'admin.booking.update',
            'admin.booking.destroy',
            'admin.booking.export-pdf',
            'admin.booking.export-excel',

            // Booking Detail
            'admin.booking_detail.index',
            'admin.booking_detail.store',
            'admin.booking_detail.update',
            'admin.booking_detail.destroy',
            'admin.booking_detail.export-pdf',
            'admin.booking_detail.export-excel',

            // Service
            'admin.service.index',
            'admin.service.store',
            'admin.service.update',
            'admin.service.destroy',
            'admin.service.export-pdf',
            'admin.service.export-excel',

            // Service Detail
            'admin.service_detail.index',
            'admin.service_detail.store',
            'admin.service_detail.update',
            'admin.service_detail.destroy',
            'admin.service_detail.show',
            'admin.service_detail.export-pdf',
            'admin.service_detail.export-excel',

            // Employee
            'admin.employee.index',
            'admin.employee.store',
            'admin.employee.update',
            'admin.employee.destroy',
            'admin.employee.export-pdf',
            'admin.employee.export-excel',
            'admin.employee.consultar-dni',

            // Payment
            'admin.payment.index',
            'admin.payment.store',
            'admin.payment.update',
            'admin.payment.destroy',
            'admin.payment.export-pdf',
            'admin.payment.export-excel',

            // Notifications
            'admin.notifications.markAsRead',
            'admin.notifications.markAllAsRead',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm])->syncRoles([$role]);
        }

        // Usuario administrador de ejemplo
        User::factory()->create([
            'name' => 'Yoel Antonio RAMOS GUTIERREZ',
            'email' => 'tiernoninio@gmail.com',
            'password' => bcrypt('123456789'),
        ])->assignRole($role);
    }
}
