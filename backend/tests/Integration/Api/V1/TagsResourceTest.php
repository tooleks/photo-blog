<?php

namespace Tests\Integration\Api\V1;

/**
 * Class TagsResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class TagsResourceTest extends TestCase
{
    protected function getResourceName(): string
    {
        return 'tags';
    }

    protected function getResourceStructure(): array
    {
        return [
            'value',
        ];
    }

    public function testGetSuccess(): void
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
}
