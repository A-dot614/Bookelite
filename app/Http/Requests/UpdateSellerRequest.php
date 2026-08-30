<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerRequest extends FormRequest
{
    /**
     * The store owner (or an admin) may update a seller profile.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        $seller = $this->route('seller');
        if (! $seller) {
            $seller = $user->seller;
        }

        if (! $seller instanceof Seller) {
            return false;
        }

        return $seller->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'store_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}