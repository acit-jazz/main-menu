<?php

namespace AcitJazz\MainMenu\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  $this->id ? [
            'id'         => $this->id,
            'title'      => $this->title,
            'slug'       => $this->slug,
            'model'      =>  $this->model && $this->type ==  '_self'? ListModelResource::make($this->model::find($this->model_id) ,'App\Models\Page')->resolve() : $this->model,
            'model_id'   => $this->model_id,
            'style'      => $this->style,
            'url'        => $this->url,
            'type'       => $this->type ?? '_self',
            'location'   => $this->location,
            'parent_id'  => $this->parent_id,
            'order'      => (int) $this->order,
            'is_active'  => (bool) $this->is_active,

            // Optional: include nested children if loaded
            'children'   => $this->children ?  MenuResource::collection($this->whenLoaded('children'))->resolve() : [],
        ] : [];
    }
}
