<?php

namespace Tests\Integration\Api\V1;

/**
 * Class TagsResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class TagsResourceTest extends TestCase
{
    public function testPaginateSuccess(): void
    {
        $this->createTag();

        $this
            ->json('GET', $this->getResourceFullName())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->getResourceStructure(),
                ],
            ]);
    }

    protected function getResourceStructure(): array
    {
        return [
            'value',
        ];
    }

    protected function getResourceName(): string
    {
        return 'tags';
    }
}
