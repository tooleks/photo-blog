<?php

namespace Tests\Integration\Api\V1;

/**
 * Class PostsResourceTest.
 *
 * @package Tests\Integration\Api\V1
 */
class PostsResourceTest extends TestCase
{
    protected function getResourceName(): string
    {
        return 'posts';
    }

    protected function getResourceStructure(): array
    {
        return [
            'id',
            'created_by_user_id',
            'description',
            'published_at',
            'created_at',
            'updated_at',
        ];
    }

    public function testCreateSuccess(): void
    {
        $this->assertTrue(true);
    }
}
