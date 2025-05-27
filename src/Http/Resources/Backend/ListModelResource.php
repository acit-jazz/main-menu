<?php

namespace AcitJazz\MainMenu\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class ListModelResource extends JsonResource
{

    protected $model;

    public function __construct($resource, $model = null)
    {
        parent::__construct($resource);
        $this->model = $model;
    }


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'slug'       => $this->slug,
            'model'       => $this->model,
        ];
    }
}
