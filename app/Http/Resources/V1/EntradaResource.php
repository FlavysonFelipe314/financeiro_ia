<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntradaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "category"=> $this->category,
            "payment_method" => $this->payment_method,
            "amount" => $this->amount,
            "user" => [
                "id" => $this->user->id,
                "name"=> $this->user->name,
                "email"=> $this->user->email,
            ],
            "conta" => [
                "id" => $this->conta->id,
                "name"=> $this->conta->name,
                "amount"=> $this->conta->amount,
            ],
        ];
    }
}
