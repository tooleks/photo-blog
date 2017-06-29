<?php

namespace Api\V1\Http\Presenters\Response;

use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class UserPresenter.
 *
 * @property int id
 * @property string name
 * @property string email
 * @property string created_at
 * @property string updated_at
 * @property string role
 * @package Api\V1\Http\Presenters\Response
 */
class UserPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'created_at' => function (): ?string {
                return $this->getWrappedModelAttribute('created_at');
            },
            'updated_at' => function (): ?string {
                return $this->getWrappedModelAttribute('updated_at');
            },
            'role' => 'role.name',
        ];
    }
}
