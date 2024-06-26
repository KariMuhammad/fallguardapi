<?php

namespace App\Http\Resources\Caregiver;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaregiverResource extends JsonResource
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
            "name" => $this->name,
            "email" => $this->email,
            "date_of_birth" => $this->date_of_birth,
            "phone" => $this->phone,
            "country" => $this->country,
            "address" => $this->address,
            "photo" => $this->photo,
            "gender" => $this->gender,
            "rating" => $this->rating,
            // when deep query exists, we load the patients and their contacts and falls
            "patients" => UserResource::collection($this->whenLoaded('patients')),
        ];
    }
}
