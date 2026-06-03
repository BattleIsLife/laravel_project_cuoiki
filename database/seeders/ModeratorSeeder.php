<?php

namespace Database\Seeders;

use App\Models\Moderator;
use Database\Factories\ModeratorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('12345678');

        // Mảng chứa dữ liệu của 4 loại moderator cố định
        $moderators = [
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'permission' => 'admin',
            ],
            [
                'username' => 'user_moderator',
                'email' => 'user_mod@gmail.com',
                'permission' => 'user_moderator',
            ],
            [
                'username' => 'post_moderator',
                'email' => 'post_mod@gmail.com',
                'permission' => 'post_moderator',
            ],
            [
                'username' => 'none_moderator',
                'email' => 'none_mod@gmail.com',
                'permission' => 'none',
            ],
        ];

        // Vòng lặp duyệt qua từng tài khoản để nạp vào Database
        foreach ($moderators as $mod) {
            Moderator::updateOrCreate(
                // Điều kiện để kiểm tra xem tài khoản đã tồn tại chưa (Dựa vào email duy nhất)
                ['email' => $mod['email']], 
                // Dữ liệu sẽ được thêm mới hoặc cập nhật
                [
                    'username' => $mod['username'],
                    'permission' => $mod['permission'],
                    'password' => $defaultPassword,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Đã khởi tạo xong 4 tài khoản Moderator cố định thành công!');
    }
}
