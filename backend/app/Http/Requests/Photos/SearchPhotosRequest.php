<?php

namespace App\Http\Requests\Photos;

/**
 * Class SearchPhotosRequest
 * @package App\Http\Requests\Photos
 */
class SearchPhotosRequest extends AllPhotosRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules() + [
            'tag' => 'required_without:query|string',
            'query' => 'required_without:tag|string',
        ];
    }
}
