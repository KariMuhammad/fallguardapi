<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => sprintf("%s %s", $this->name, $this->family_name),
            "family_name" => $this->family_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "date_of_birth" => $this->date_of_birth,
            "address" => $this->address,
            "photo" => $this->photo,
        ];
    }
}
