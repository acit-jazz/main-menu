<?php

namespace AcitJazz\MainMenu\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FeMenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $url = $this->url;
        try {
            if ($this->model_id && $this->model) {
            $model =   $this->model::find($this->model_id);
            if($model->getTable() == 'pages'){
                $url = $model->slug ? route('fe.page.show', ['slug' => $model->slug]) : null;
            }
        }
        } catch (\Throwable $th) {
         $url = $this->url;
        }
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'slug'       => $this->slug,
            'model'      => $this->model,
            'model_id'   => $this->model_id,
            'style'      => $this->style,
            'url'        => $url,
            'type'       => $this->type,
            'location'   => $this->location,
            'parent_id'  => $this->parent_id,
            'order'      => (int) $this->order,
            'is_active'  => (bool) $this->is_active,

            // Optional: include nested children if loaded
            'children'   => FeMenuResource::collection($this->whenLoaded('children')),
        ];
    }
}
