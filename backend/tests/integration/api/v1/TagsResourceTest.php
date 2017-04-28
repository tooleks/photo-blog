<?php

use Core\Models\Tag;

/**
 * Class TagsResourceTest.
 */
class TagsResourceTest extends IntegrationApiV1TestCase
{
    protected $resourceName = 'tags';

    protected $resourceStructure = [
        'value',
    ];

    protected function createTestTag(array $attributes = [])
    {
        $tag = factory(Tag::class)->create($attributes);

        return $tag;
    }

    public function testGetSuccess()
    {
        $tag = $this->createTestTag();

        $retrievedTags = $this
            ->json('GET', sprintf('/%s', $this->resourceName))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    $this->resourceStructure,
                ],
            ])
            ->getData(true);

        $this->assertEquals($retrievedTags['data'][0]['value'], $tag->value);
    }
}
