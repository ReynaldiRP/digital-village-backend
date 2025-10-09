<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function get(): ?object
    {
        return Profile::first();
    }

    public function create(
        array $data
    ): object {
        DB::beginTransaction();

        try {
            $profile = new Profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            $profile->save();

            if (array_key_exists('profile_images', $data)) {
                foreach ($data['profile_images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profile_images', 'public'),
                        'profile_id' => $profile->id,
                    ]);
                }
            }

            DB::commit();

            return $profile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        array $data
    ): ?object {
        DB::beginTransaction();

        try {
            $profile = Profile::first();
            if (isset($data['thumbnail'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agricultural_area = $data['agricultural_area'];
            $profile->total_area = $data['total_area'];

            $profile->save();

            if (array_key_exists('profile_images', $data)) {
                foreach ($data['profile_images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profile_images', 'public'),
                        'profile_id' => $profile->id,
                    ]);
                }
            }

            DB::commit();
            return $profile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
